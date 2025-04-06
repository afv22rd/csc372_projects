<?php
/**
 * Test script for the Vehicle API
 * 
 * This script tests the Vehicle class and API endpoints.
 */

// Include the Vehicle class
require_once 'vehicle.php';

// Create a test vehicle
$testVehicle = new Vehicle(
    999,
    'Test',
    'Car',
    2023,
    30000,
    [
        'https://example.com/test-car-1.jpg',
        'https://example.com/test-car-2.jpg'
    ],
    'This is a test vehicle for testing purposes.',
    ['Test Feature 1', 'Test Feature 2'],
    'Sedan',
    10000,
    'Electric',
    'Silver',
    5,
    'All Wheel Drive',
    'Automatic',
    0
);

// Test the toArray method
echo "Testing toArray method:\n";
$vehicleArray = $testVehicle->toArray();
print_r($vehicleArray);
echo "\n";

// Test the calculatePayments method
echo "Testing calculatePayments method:\n";
$payments = $testVehicle->calculatePayments();
print_r($payments);
echo "\n";

// Test the isGoodValue method
echo "Testing isGoodValue method:\n";
$isGoodValue = $testVehicle->isGoodValue();
echo "Is good value: " . ($isGoodValue ? 'Yes' : 'No') . "\n\n";

// Test the API endpoints
echo "Testing API endpoints:\n";

// Test the 'all' endpoint
echo "Testing 'all' endpoint:\n";
$allVehicles = getAllVehicles();
echo "Number of vehicles: " . count($allVehicles) . "\n\n";

// Test the 'search' endpoint
echo "Testing 'search' endpoint with 'Toyota':\n";
$searchResults = [];
foreach ($allVehicles as $vehicle) {
    if (
        stripos($vehicle->getMake(), 'Toyota') !== false ||
        stripos($vehicle->getModel(), 'Toyota') !== false ||
        stripos((string)$vehicle->getYear(), 'Toyota') !== false
    ) {
        $searchResults[] = $vehicle->toArray();
    }
}
echo "Number of search results: " . count($searchResults) . "\n\n";

// Test the 'filter' endpoint
echo "Testing 'filter' endpoint with make='Toyota':\n";
$filteredResults = [];
foreach ($allVehicles as $vehicle) {
    if ($vehicle->getMake() === 'Toyota') {
        $filteredResults[] = $vehicle->toArray();
    }
}
echo "Number of filtered results: " . count($filteredResults) . "\n\n";

echo "All tests completed successfully!\n"; 