<?php
/**
 * Vehicle API Endpoint
 * 
 * This file provides an API endpoint for vehicle data that can be accessed by a Node.js/HTMX application.
 * It includes CORS headers to allow requests from the Render domain.
 * 
 * This API is hosted at afriasv.rhody.dev.
 */

// Include the Vehicle class - This loads the Vehicle.php file which contains the Vehicle class definition
require_once 'vehicle.php';

// Set CORS headers to allow requests from Render domain
// CORS (Cross-Origin Resource Sharing) lets websites request resources from different domains
header('Access-Control-Allow-Origin: https://brawaautoimport.onrender.com'); // This specifies which website can access this API
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // These are the HTTP methods allowed to access the API
header('Access-Control-Allow-Headers: Content-Type'); // This allows the Content-Type header in requests
header('Content-Type: application/json'); // This tells browsers that the response will be in JSON format

// Handle preflight OPTIONS request
// Browsers send OPTIONS requests before certain cross-origin requests to check if the actual request is safe to send
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Returns a 200 (OK) status code to approve the preflight request
    exit(); // Stops the script execution after handling the OPTIONS request
}

// Function to create sample vehicles
// This function creates and returns an array of Vehicle objects with sample data
function createSampleVehicles() {
    // Create vehicle 1 - Toyota Camry
    // Each vehicle is created with these parameters: id, make, model, year, price, images array, description,
    // features array, body type, mileage, fuel type, color, seating capacity, drivetrain, transmission, cylinders
    $vehicle1 = new Vehicle(
        1, // Unique identifier for the vehicle
        'Toyota', // Make (manufacturer) of the vehicle
        'Camry', // Model name
        2018, // Year of manufacture
        20000, // Price in dollars
        [
            // Array of image URLs showing the vehicle
            'https://www.motortrend.com/uploads/sites/5/2017/10/2018-Toyota-Camry-XSE-front-three-quarter-in-motion-07-e1510268418873.jpg?w=768&width=768&q=75&format=webp',
            'https://hips.hearstapps.com/hmg-prod/amv-prod-cad-assets/wp-content/uploads/2017/06/2018-Toyota-Camry-110.jpg'
        ],
        'The 2018 Toyota Camry offers a comfortable ride with a spacious interior, making it a reliable choice for daily commuting.', // Description
        ['Bluetooth Connectivity', 'Rearview Camera', 'Adaptive Cruise Control'], // Array of features
        'Sedan', // Body type (e.g., Sedan, SUV, Truck)
        35000, // Mileage in miles
        'Gasoline', // Type of fuel used
        'White', // Exterior color
        5, // Number of seats
        'Front Wheel Drive', // Drivetrain type
        'Automatic', // Transmission type
        4 // Number of cylinders in the engine
    );

    // Create vehicle 2 - Honda Accord
    // Same parameter structure as vehicle 1 but with different values
    $vehicle2 = new Vehicle(
        2, // Unique identifier
        'Honda', // Make
        'Accord', // Model
        2019, // Year
        25000, // Price
        [
            // Image URLs
            'https://mma.prnewswire.com/media/776336/2019_Honda_Accord_Goes_On_Sale.jpg?p=twitter',
            'https://automobiles.honda.com/-/media/Honda-Automobiles/Vehicles/2019/Accord-Sedan/Gallery-Thumbnails/2018-accord-gallery-thumbnail-int-touring-front-wide-view-interior-1400-1x.jpg'
        ],
        'The 2019 Honda Accord stands out with its turbocharged engine options and upscale interior, providing a sporty yet refined driving experience.', // Description
        ['Lane Keeping Assist', 'Apple CarPlay', 'Heated Seats'], // Features
        'Sedan', // Body type
        28000, // Mileage
        'Gasoline', // Fuel type
        'Black', // Color
        5, // Seating
        'Front Wheel Drive', // Drivetrain
        'CVT', // Transmission (Continuously Variable Transmission)
        4 // Cylinders
    );

    // Create vehicle 3 - Ford Fusion
    // Same parameter structure as previous vehicles
    $vehicle3 = new Vehicle(
        3, // ID
        'Ford', // Make
        'Fusion', // Model
        2017, // Year
        18000, // Price
        [
            // Images
            'https://images.hgmsites.net/med/2017-ford-fusion_100541973_m.jpg',
            'https://www.autolist.com/6tuem73u73an/5mGO8WeSALSZDU8zbwocxc/dd15193c75b78ff07f3a4b066197396c/2017-ford-fusion-image-1.jpg'
        ],
        'The 2017 Ford Fusion combines stylish design with agile handling, offering a blend of comfort and performance.', // Description
        ['Sync 3 Infotainment System', 'Blind Spot Monitoring', 'All-Wheel Drive'], // Features
        'Sedan', // Body type
        45000, // Mileage
        'Gasoline', // Fuel type
        'White', // Color
        5, // Seating
        'All Wheel Drive', // Drivetrain
        'Automatic', // Transmission
        4 // Cylinders
    );

    // Create vehicle 4 - Chevrolet Malibu
    // Same parameter structure as previous vehicles
    $vehicle4 = new Vehicle(
        4, // ID
        'Chevrolet', // Make
        'Malibu', // Model
        2020, // Year
        22500, // Price
        [
            // Images
            'https://hips.hearstapps.com/hmg-prod/images/2020-chevrolet-malibu-mmp-1-1568146222.jpg?crop=0.653xw:0.630xh;0.0684xw,0.309xh&resize=2048:*',
            'https://di-uploads-pod16.dealerinspire.com/lemanschevycity/uploads/2020/05/2020-Chevy-Malibu-Dash.jpg'
        ],
        'The 2020 Chevrolet Malibu offers a smooth ride with a spacious interior, making it a practical choice for families.', // Description
        ['Wi-Fi Hotspot', 'Teen Driver Technology', 'Remote Start'], // Features
        'Sedan', // Body type
        22000, // Mileage
        'Gasoline', // Fuel type
        'Blue', // Color
        5, // Seating
        'Front Wheel Drive', // Drivetrain
        'CVT', // Transmission
        4 // Cylinders
    );

    // Create vehicle 5 - Nissan Altima
    // Same parameter structure as previous vehicles
    $vehicle5 = new Vehicle(
        5, // ID
        'Nissan', // Make
        'Altima', // Model
        2019, // Year
        19000, // Price
        [
            // Images
            'https://media.ed.edmunds-media.com/nissan/altima/2019/oem/2019_nissan_altima_sedan_vc-t-edition-one_fq_oem_1_1600.jpg',
            'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQpPV1vTrqyMN2KOs7JCj99wA52CXAZC6kF-w&s'
        ],
        'The 2019 Nissan Altima features a comfortable interior and advanced safety features, making it a solid choice for a midsize sedan.', // Description
        ['ProPilot Assist', 'All-Wheel Drive', 'Android Auto'], // Features
        'Sedan', // Body type
        30000, // Mileage
        'Gasoline', // Fuel type
        'Red', // Color
        5, // Seating
        'All Wheel Drive', // Drivetrain
        'CVT', // Transmission
        4 // Cylinders
    );

    // Return all vehicles as an array
    // This creates an array containing all 5 vehicle objects
    return [$vehicle1, $vehicle2, $vehicle3, $vehicle4, $vehicle5];
}

// Function to get all vehicles
// This is a wrapper function that calls createSampleVehicles
function getAllVehicles() {
    // Create and return sample vehicles
    // In a real application, this would likely fetch data from a database instead
    return createSampleVehicles();
}

// Handle API requests
// Get the 'action' parameter from the URL (?action=value)
// If no action parameter is provided, default to 'all'
$action = $_GET['action'] ?? 'all'; // The ?? is the null coalescing operator - it returns 'all' if $_GET['action'] is not set

// Process different actions using a switch statement
switch ($action) {
    case 'all':
        // Return all vehicles
        // This case handles requests for all vehicles (e.g., /api.php?action=all)
        $vehicles = getAllVehicles(); // Get all vehicle objects
        $vehiclesArray = array_map(function($vehicle) {
            return $vehicle->toArray(); // Convert each Vehicle object to an array
        }, $vehicles);
        
        // Output a JSON response with status, count, and the vehicles data
        echo json_encode([
            'status' => 'success', // Indicates the request was successful
            'count' => count($vehiclesArray), // Number of vehicles returned
            'vehicles' => $vehiclesArray // The array of vehicle data
        ]);
        break;
        
    case 'search':
        // Search vehicles by make, model, or year
        // This case handles search requests (e.g., /api.php?action=search&term=Toyota)
        $searchTerm = $_GET['term'] ?? ''; // Get the search term from the URL parameter, default to empty string
        
        // If no search term is provided, return an error
        if (empty($searchTerm)) {
            echo json_encode([
                'status' => 'error', // Indicates an error occurred
                'message' => 'Search term is required' // Error message explaining the issue
            ]);
            exit(); // Stop execution of the script
        }
        
        $vehicles = getAllVehicles(); // Get all vehicle objects
        $searchResults = []; // Initialize empty array for search results
        
        // Loop through each vehicle to check if it matches the search term
        foreach ($vehicles as $vehicle) {
            if (
                stripos($vehicle->getMake(), $searchTerm) !== false || // Check if make contains search term (case-insensitive)
                stripos($vehicle->getModel(), $searchTerm) !== false || // Check if model contains search term
                stripos((string)$vehicle->getYear(), $searchTerm) !== false // Check if year contains search term (convert year to string first)
            ) {
                $searchResults[] = $vehicle->toArray(); // Add matching vehicle to results (as array)
            }
        }
        
        // Output a JSON response with the search results
        echo json_encode([
            'status' => 'success', // Indicates the search was successful
            'count' => count($searchResults), // Number of vehicles found
            'vehicles' => $searchResults // The matching vehicles data
        ]);
        break;
        
    case 'filter':
        // Filter vehicles by various criteria
        // This case handles filter requests (e.g., /api.php?action=filter&min_price=20000&max_price=30000)
        $make = $_GET['make'] ?? ''; // Get make filter parameter, default to empty string
        $model = $_GET['model'] ?? ''; // Get model filter parameter
        $minYear = $_GET['min_year'] ?? 0; // Get minimum year filter, default to 0
        $maxYear = $_GET['max_year'] ?? 9999; // Get maximum year filter, default to 9999
        $minPrice = $_GET['min_price'] ?? 0; // Get minimum price filter
        $maxPrice = $_GET['max_price'] ?? PHP_FLOAT_MAX; // Get maximum price filter, default to maximum possible float value
        $bodyType = $_GET['body_type'] ?? ''; // Get body type filter
        
        $vehicles = getAllVehicles(); // Get all vehicle objects
        $filteredResults = []; // Initialize empty array for filtered results
        
        // Loop through each vehicle to check if it matches all filter criteria
        foreach ($vehicles as $vehicle) {
            $matches = true; // Assume vehicle matches until proven otherwise
            
            // Check each filter criterion. If any fail, set matches to false
            
            // Check make filter if it's not empty
            if (!empty($make) && $vehicle->getMake() !== $make) {
                $matches = false; // Vehicle doesn't match make filter
            }
            
            // Check model filter if it's not empty
            if (!empty($model) && $vehicle->getModel() !== $model) {
                $matches = false; // Vehicle doesn't match model filter
            }
            
            // Check year range filter
            if ($vehicle->getYear() < $minYear || $vehicle->getYear() > $maxYear) {
                $matches = false; // Vehicle year is outside the specified range
            }
            
            // Check price range filter
            if ($vehicle->getPrice() < $minPrice || $vehicle->getPrice() > $maxPrice) {
                $matches = false; // Vehicle price is outside the specified range
            }
            
            // Check body type filter if it's not empty
            if (!empty($bodyType) && $vehicle->getBodyType() !== $bodyType) {
                $matches = false; // Vehicle doesn't match body type filter
            }
            
            // If vehicle passed all filter checks, add it to results
            if ($matches) {
                $filteredResults[] = $vehicle->toArray(); // Add matching vehicle to results (as array)
            }
        }
        
        // Output a JSON response with the filtered results
        echo json_encode([
            'status' => 'success', // Indicates the filtering was successful
            'count' => count($filteredResults), // Number of vehicles that match the filters
            'vehicles' => $filteredResults // The matching vehicles data
        ]);
        break;
        
    default:
        // Invalid action
        // This handles the case when an unsupported action is requested
        echo json_encode([
            'status' => 'error', // Indicates an error occurred
            'message' => 'Invalid action' // Error message explaining the issue
        ]);
        break;
}