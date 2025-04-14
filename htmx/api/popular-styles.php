<?php

// filepath: /opt/lampp/htdocs/csc372_projects/htmx/api/popular-styles.php

// Set the content type to HTML
header('Content-Type: text/html');

try {
    // Define the path to the XML file
    $xmlFilePath = __DIR__.'/../public/data/popular-styles.xml';

    // Check if the file exists
    if (!file_exists($xmlFilePath)) {
        http_response_code(404);
        echo '<div class="col-span-full text-center">Vehicle styles not found</div>';
        exit;
    }

    // Load the XML file content
    $xmlData = file_get_contents($xmlFilePath);

    // Use a regular expression to extract all <vehicle> elements
    preg_match_all('/<vehicle>[\s\S]*?<\/vehicle>/', $xmlData, $vehicleMatches);

    // Check if any vehicle elements were found
    if (empty($vehicleMatches[0])) {
        http_response_code(404);
        echo '<div class="col-span-full text-center">Vehicle styles not found</div>';
        exit;
    }

    // Transform each vehicle XML element into an HTML button
    $buttonsHtml = array_map(function ($vehicleXml) {
        // Extract the vehicle type
        preg_match('/<type>(.*?)<\/type>/', $vehicleXml, $typeMatch);
        $type = $typeMatch[1] ?? 'Unknown';

        // Extract the alt text for image accessibility
        preg_match('/<alt>(.*?)<\/alt>/', $vehicleXml, $altMatch);
        $alt = $altMatch[1] ?? '';

        // Check if this vehicle should use an SVG icon instead of an image
        preg_match('/<isSvg>(.*?)<\/isSvg>/', $vehicleXml, $isSvgMatch);
        $isSvg = isset($isSvgMatch[1]) && $isSvgMatch[1] === 'true';

        if ($isSvg) {
            // For "Shop All" option, use an arrow SVG icon
            return "<button class='border border-base-900 rounded-lg hover:bg-base-200 transition-colors w-full h-40 flex flex-col items-center justify-center gap-2 p-4'>
                        <span class='md:text-lg sm:text-md font-medium'>{$type}</span>
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='h-24 w-auto'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3'></path>
                        </svg>
                    </button>";
        } else {
            // For regular vehicle types, extract and use the image URL
            preg_match('/<image>(.*?)<\/image>/', $vehicleXml, $imageMatch);
            $image = $imageMatch[1] ?? '';

            return "<button class='border border-base-900 rounded-lg hover:bg-base-200 transition-colors w-full h-40 flex flex-col items-center justify-center gap-2 p-4'>
                        <span class='md:text-lg sm:text-md font-medium'>{$type}</span>
                        <img src='{$image}' alt='{$alt}' class='h-24 w-auto object-contain'>
                    </button>";
        }
    }, $vehicleMatches[0]);

    // Output the combined HTML
    echo implode('', $buttonsHtml);
} catch (Exception $e) {
    // Log any errors that occur
    error_log('Error processing popular styles: '.$e->getMessage());
    http_response_code(500);
    echo '<div class="col-span-full text-center">Error loading vehicle styles</div>';
}
