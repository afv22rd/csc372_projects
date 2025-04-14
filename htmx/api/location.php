<?php

if (isset($_GET['latitude']) && isset($_GET['longitude'])) {
    try {
        $latitude = $_GET['latitude'];
        $longitude = $_GET['longitude'];

        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=10";
        $opts = [
            'http' => [
                'header' => 'User-Agent: BrawaAutoImport/1.0',
            ],
        ];
        $context = stream_context_create($opts);
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            throw new Exception('Failed to fetch location data');
        }

        $data = json_decode($response, true);

        if (isset($data['address'])) {
            $province = isset($data['address']['state']) ? $data['address']['state'] : '';
            $city = '';

            // Try to get city from either city or town field
            if (isset($data['address']['city'])) {
                $city = $data['address']['city'];
            } elseif (isset($data['address']['town'])) {
                $city = $data['address']['town'];
            }

            if (!empty($city)) {
                $location = "{$city}, {$province}";
                // Set a cookie with the location data that expires in 7 days
                setcookie("user_location", $location, time() + (86400 * 7), "/");
                echo $location;
            } else {
                echo 'Update location';
            }
        } else {
            echo 'Update location';
        }
    } catch (Exception $e) {
        error_log('Location error: '.$e->getMessage());
        echo 'Update location';
    }
} else {
    // Check if location is stored in cookie
    if (isset($_COOKIE['user_location'])) {
        echo $_COOKIE['user_location'];
    } else {
        echo 'Update location';
    }
}
