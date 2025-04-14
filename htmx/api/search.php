<?php

// Ensure errors are displayed for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define base path for includes/requires relative to this script's location
define('BASE_PATH', dirname(__DIR__));

// Start output buffering to capture and discard any automatic JSON output
ob_start();

// Include api.php which already has getAllVehicles()
require_once __DIR__.'/api.php';

// Clear the output buffer - this discards any JSON that might have been echoed
ob_end_clean();

// Paths to template files are relative to the BASE_PATH (project root)
$popular_searches_path = BASE_PATH.'/public/data/popular-searches.html';
$search_results_template_path = BASE_PATH.'/public/data/search-results.html';

// Get the search term from the query parameters
// Check for both GET and POST methods
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
if (empty($searchTerm) && isset($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
}

// --- Popular Searches Logic ---
if (empty($searchTerm)) {
    if (file_exists($popular_searches_path)) {
        header('Content-Type: text/html; charset=utf-8');
        echo file_get_contents($popular_searches_path);
    } else {
        http_response_code(500);
        echo '<div class="p-2 text-red-500">Error: Popular searches data not found</div>';
    }
    exit;
}

// --- Vehicle Search Logic ---
try {
    // Get all vehicles and filter them
    // We're now using the getAllVehicles() function already defined in api.php
    $vehicles = getAllVehicles();
    $searchResults = [];

    // Filter vehicles that match the search term
    foreach ($vehicles as $vehicle) {
        if (
            stripos($vehicle->getMake(), $searchTerm) !== false
            || stripos($vehicle->getModel(), $searchTerm) !== false
            || stripos((string) $vehicle->getYear(), $searchTerm) !== false
        ) {
            $searchResults[] = $vehicle->toArray();
        }
    }

    // If no vehicles match the search term, show a message
    if (empty($searchResults)) {
        header('Content-Type: text/html; charset=utf-8');
        echo '<div class="p-4 text-center text-gray-500">No vehicles found matching "'.htmlspecialchars($searchTerm).'"</div>';
        exit;
    }

    // Read the template for individual search results
    if (!file_exists($search_results_template_path)) {
        throw new Exception('Search result template not found');
    }
    $template = file_get_contents($search_results_template_path);

    // Generate HTML for each matching vehicle
    $responseHTML = '';
    foreach ($searchResults as $vehicle) {
        $make = htmlspecialchars($vehicle['make'] ?? 'N/A');
        $model = htmlspecialchars($vehicle['model'] ?? 'N/A');
        $year = htmlspecialchars($vehicle['year'] ?? 'N/A');

        // Replace placeholders in the template with vehicle data
        $entry = str_replace(
            ['{{make}}', '{{model}}', '{{year}}'],
            [$make, $model, $year],
            $template
        );
        $responseHTML .= $entry;
    }

    // Set the content type to HTML and output only the HTML content
    header('Content-Type: text/html; charset=utf-8');
    echo $responseHTML;
} catch (Exception $e) {
    http_response_code(500);
    echo '<div class="p-2 alert alert-error">Error: '.htmlspecialchars($e->getMessage()).'</div>';
    error_log('Search Error: '.$e->getMessage());
}
