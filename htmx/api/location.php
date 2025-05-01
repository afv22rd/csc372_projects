<?php

if (isset($_GET['latitude']) && isset($_GET['longitude'])) {
    try {
        $latitude = $_GET['latitude'];
        $longitude = $_GET['longitude'];

        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=10";

        // Use cURL for the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Set a User-Agent header (required by Nominatim)
        curl_setopt($ch, CURLOPT_USERAGENT, 'BrawaAutoImport/1.0 (compatible; PHP cURL)');
        // Follow redirects if any
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // Set timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        // Verify SSL certificate
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($response === false || $httpcode !== 200) {
            throw new Exception('Failed to fetch location data. HTTP Code: '.$httpcode.' cURL Error: '.$curl_error);
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Failed to decode JSON response. Error: '.json_last_error_msg());
        }

        if (isset($data['address'])) {
            $province = isset($data['address']['state']) ? $data['address']['state'] : '';
            $city = '';
            $county = isset($data['address']['county']) ? $data['address']['county'] : ''; // Get county

            // Try to get city from either city or town field
            if (isset($data['address']['city'])) {
                $city = $data['address']['city'];
            } elseif (isset($data['address']['town'])) {
                $city = $data['address']['town'];
            } elseif (isset($data['address']['village'])) { // Add village as another fallback
                $city = $data['address']['village'];
            }

            $location = '';
            if (!empty($city) && !empty($province)) {
                $location = "{$city}, {$province}";
            } elseif (!empty($county) && !empty($province)) { // Fallback to county if city is missing
                $location = "{$county}, {$province}";
            } elseif (!empty($province)) { // Fallback to just province if city/county missing
                $location = $province;
            } // Add more fallbacks if needed (e.g., country)

            if (!empty($location)) {
                // Output the formatted location
                echo htmlspecialchars($location); // Sanitize output
            } else {
                error_log("Location API: Could not determine usable location parts for {$latitude},{$longitude}. Address: ".print_r($data['address'], true));
                echo 'Update location';
            }
        } else {
            error_log("Location API: 'address' key missing in response for {$latitude},{$longitude}. Response: ".$response);
            echo 'Update location';
        }
    } catch (Exception $e) {
        error_log('Location error: '.$e->getMessage());
        echo 'Update location';
    }
} else {
    // No coordinates provided - attempt IP-based lookup (or return default)
    // For now, just return the default message if no coords are given
    // A proper IP lookup would require another API or database.
    echo 'Update location';
}
