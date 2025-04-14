<?php

header('Content-Type: text/html; charset=utf-8');

// Function to load favorites data from JSON file
function loadFavorites()
{
    try {
        $jsonPath = __DIR__.'/../public/data/favorites.json';
        $jsonContent = file_get_contents($jsonPath);

        return json_decode($jsonContent, true) ?: ['favorites' => []];
    } catch (Exception $e) {
        error_log('Error loading favorites: '.$e->getMessage());

        return ['favorites' => []];
    }
}

// Load favorites data
$favoritesData = loadFavorites();
$favorites = $favoritesData['favorites'] ?? [];

// Count total favorites
$totalCount = 0;
foreach ($favorites as $category => $vehicles) {
    $totalCount += count($vehicles);
}

// Check what type of content is requested
$contentType = $_GET['content'] ?? '';

if ($contentType === 'count') {
    // Return just the count
    echo $totalCount;
} else {
    // Generate HTML for the full favorites list
    $html = <<<HTML
    <span class="text-lg font-bold fav-items">{$totalCount} Items</span>
    <span class="text-info">
        <ul class="fav-list">
HTML;

    // Add each category and its vehicles
    foreach ($favorites as $category => $vehicles) {
        $vehicleCount = count($vehicles);
        $html .= "<li class=\"font-semibold\">{$category}: {$vehicleCount}</li>";
        $html .= '<ul class="ml-4">';

        // Add each vehicle in this category
        foreach ($vehicles as $vehicle) {
            $html .= "<li>{$vehicle['make']} {$vehicle['model']}</li>";
        }

        $html .= '</ul>';
    }

    $html .= <<<HTML
        </ul>
    </span>
HTML;

    echo $html;
}
