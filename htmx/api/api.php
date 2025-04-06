<?php
/**
 * Vehicle API Endpoint
 * 
 * This file provides an API endpoint for vehicle data that can be accessed by a Node.js/HTMX application.
 * It includes CORS headers to allow requests from the Render domain.
 * 
 * This API is hosted at afriasv.rhody.dev.
 */

// Include the Vehicle class
require_once 'vehicle.php';

// Set CORS headers to allow requests from Render domain
header('Access-Control-Allow-Origin: https://brawaautoimport2.onrender.com'); // Updated with actual Render domain
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Function to create sample vehicles
function createSampleVehicles() {
    // Create vehicle 1
    $vehicle1 = new Vehicle(
        1,
        'Toyota',
        'Camry',
        2018,
        20000,
        [
            'https://www.motortrend.com/uploads/sites/5/2017/10/2018-Toyota-Camry-XSE-front-three-quarter-in-motion-07-e1510268418873.jpg?w=768&width=768&q=75&format=webp',
            'https://hips.hearstapps.com/hmg-prod/amv-prod-cad-assets/wp-content/uploads/2017/06/2018-Toyota-Camry-110.jpg'
        ],
        'The 2018 Toyota Camry offers a comfortable ride with a spacious interior, making it a reliable choice for daily commuting.',
        ['Bluetooth Connectivity', 'Rearview Camera', 'Adaptive Cruise Control'],
        'Sedan',
        35000,
        'Gasoline',
        'White',
        5,
        'Front Wheel Drive',
        'Automatic',
        4
    );

    // Create vehicle 2
    $vehicle2 = new Vehicle(
        2,
        'Honda',
        'Accord',
        2019,
        25000,
        [
            'https://mma.prnewswire.com/media/776336/2019_Honda_Accord_Goes_On_Sale.jpg?p=twitter',
            'https://automobiles.honda.com/-/media/Honda-Automobiles/Vehicles/2019/Accord-Sedan/Gallery-Thumbnails/2018-accord-gallery-thumbnail-int-touring-front-wide-view-interior-1400-1x.jpg'
        ],
        'The 2019 Honda Accord stands out with its turbocharged engine options and upscale interior, providing a sporty yet refined driving experience.',
        ['Lane Keeping Assist', 'Apple CarPlay', 'Heated Seats'],
        'Sedan',
        28000,
        'Gasoline',
        'Black',
        5,
        'Front Wheel Drive',
        'CVT',
        4
    );

    // Create vehicle 3
    $vehicle3 = new Vehicle(
        3,
        'Ford',
        'Fusion',
        2017,
        18000,
        [
            'https://images.hgmsites.net/med/2017-ford-fusion_100541973_m.jpg',
            'https://www.autolist.com/6tuem73u73an/5mGO8WeSALSZDU8zbwocxc/dd15193c75b78ff07f3a4b066197396c/2017-ford-fusion-image-1.jpg'
        ],
        'The 2017 Ford Fusion combines stylish design with agile handling, offering a blend of comfort and performance.',
        ['Sync 3 Infotainment System', 'Blind Spot Monitoring', 'All-Wheel Drive'],
        'Sedan',
        45000,
        'Gasoline',
        'White',
        5,
        'All Wheel Drive',
        'Automatic',
        4
    );

    // Create vehicle 4
    $vehicle4 = new Vehicle(
        4,
        'Chevrolet',
        'Malibu',
        2020,
        22500,
        [
            'https://hips.hearstapps.com/hmg-prod/images/2020-chevrolet-malibu-mmp-1-1568146222.jpg?crop=0.653xw:0.630xh;0.0684xw,0.309xh&resize=2048:*',
            'https://di-uploads-pod16.dealerinspire.com/lemanschevycity/uploads/2020/05/2020-Chevy-Malibu-Dash.jpg'
        ],
        'The 2020 Chevrolet Malibu offers a smooth ride with a spacious interior, making it a practical choice for families.',
        ['Wi-Fi Hotspot', 'Teen Driver Technology', 'Remote Start'],
        'Sedan',
        22000,
        'Gasoline',
        'Blue',
        5,
        'Front Wheel Drive',
        'CVT',
        4
    );

    // Create vehicle 5
    $vehicle5 = new Vehicle(
        5,
        'Nissan',
        'Altima',
        2019,
        19000,
        [
            'https://media.ed.edmunds-media.com/nissan/altima/2019/oem/2019_nissan_altima_sedan_vc-t-edition-one_fq_oem_1_1600.jpg',
            'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQpPV1vTrqyMN2KOs7JCj99wA52CXAZC6kF-w&s'
        ],
        'The 2019 Nissan Altima features a comfortable interior and advanced safety features, making it a solid choice for a midsize sedan.',
        ['ProPilot Assist', 'All-Wheel Drive', 'Android Auto'],
        'Sedan',
        30000,
        'Gasoline',
        'Red',
        5,
        'All Wheel Drive',
        'CVT',
        4
    );

    return [$vehicle1, $vehicle2, $vehicle3, $vehicle4, $vehicle5];
}

// Function to get all vehicles
function getAllVehicles() {
    // Create and return sample vehicles
    return createSampleVehicles();
}

// Handle API requests
$action = $_GET['action'] ?? 'all';

switch ($action) {
    case 'all':
        // Return all vehicles
        $vehicles = getAllVehicles();
        $vehiclesArray = array_map(function($vehicle) {
            return $vehicle->toArray();
        }, $vehicles);
        
        echo json_encode([
            'status' => 'success',
            'count' => count($vehiclesArray),
            'vehicles' => $vehiclesArray
        ]);
        break;
        
    case 'search':
        // Search vehicles by make, model, or year
        $searchTerm = $_GET['term'] ?? '';
        
        if (empty($searchTerm)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Search term is required'
            ]);
            exit();
        }
        
        $vehicles = getAllVehicles();
        $searchResults = [];
        
        foreach ($vehicles as $vehicle) {
            if (
                stripos($vehicle->getMake(), $searchTerm) !== false ||
                stripos($vehicle->getModel(), $searchTerm) !== false ||
                stripos((string)$vehicle->getYear(), $searchTerm) !== false
            ) {
                $searchResults[] = $vehicle->toArray();
            }
        }
        
        echo json_encode([
            'status' => 'success',
            'count' => count($searchResults),
            'vehicles' => $searchResults
        ]);
        break;
        
    case 'filter':
        // Filter vehicles by various criteria
        $make = $_GET['make'] ?? '';
        $model = $_GET['model'] ?? '';
        $minYear = $_GET['min_year'] ?? 0;
        $maxYear = $_GET['max_year'] ?? 9999;
        $minPrice = $_GET['min_price'] ?? 0;
        $maxPrice = $_GET['max_price'] ?? PHP_FLOAT_MAX;
        $bodyType = $_GET['body_type'] ?? '';
        
        $vehicles = getAllVehicles();
        $filteredResults = [];
        
        foreach ($vehicles as $vehicle) {
            $matches = true;
            
            if (!empty($make) && $vehicle->getMake() !== $make) {
                $matches = false;
            }
            
            if (!empty($model) && $vehicle->getModel() !== $model) {
                $matches = false;
            }
            
            if ($vehicle->getYear() < $minYear || $vehicle->getYear() > $maxYear) {
                $matches = false;
            }
            
            if ($vehicle->getPrice() < $minPrice || $vehicle->getPrice() > $maxPrice) {
                $matches = false;
            }
            
            if (!empty($bodyType) && $vehicle->getBodyType() !== $bodyType) {
                $matches = false;
            }
            
            if ($matches) {
                $filteredResults[] = $vehicle->toArray();
            }
        }
        
        echo json_encode([
            'status' => 'success',
            'count' => count($filteredResults),
            'vehicles' => $filteredResults
        ]);
        break;
        
    default:
        // Invalid action
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid action'
        ]);
        break;
} 