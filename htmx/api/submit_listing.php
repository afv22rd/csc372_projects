<?php

session_start();
require_once __DIR__.'/../database/database_connection.php'; // Adjust path as needed

// --- Configuration ---
define('MAX_IMAGES', 30);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('UPLOAD_DIR_BASE', __DIR__.'/../public/uploads/'); // Base upload directory

// --- Helper Functions ---
function sanitizeInput($data)
{
    return is_array($data) ? array_map('sanitizeInput', $data) : htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function generateErrorMessage($message)
{
    return "<div class='alert alert-error'><svg xmlns='http://www.w3.org/2000/svg' class='stroke-current shrink-0 h-6 w-6' fill='none' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' /></svg><span>Error! {$message}</span></div>";
}

function generateSuccessMessage($vehicleId)
{
    $detailUrl = "vehicle-detail.php?id={$vehicleId}";

    return "
    <div class='alert alert-success'>
        <svg xmlns='http://www.w3.org/2000/svg' class='stroke-current shrink-0 h-6 w-6' fill='none' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' /></svg>
        <span>Your listing has been created successfully!</span>
    </div>
    <div class='mt-4 text-center'>
        <a href='{$detailUrl}' class='btn btn-primary' hx-boost='false'>View Your Listing</a>
    </div>";
    // Note: hx-boost='false' ensures a full page load to the detail page
}

// --- Main Logic ---

// 1. Check User Authentication
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in'] || !isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo generateErrorMessage('You must be logged in to create a listing.');
    exit;
}
$userId = $_SESSION['user_id'];

// 2. Validate Request Method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo generateErrorMessage('Invalid request method.');
    exit;
}

// 3. Sanitize and Validate Input Data
$requiredFields = ['make', 'model', 'year', 'price', 'description'];
$formData = [];
$errors = [];

foreach ($_POST as $key => $value) {
    $formData[$key] = sanitizeInput($value);
}

foreach ($requiredFields as $field) {
    if (empty($formData[$field])) {
        $errors[] = "Field '".ucfirst(str_replace('_', ' ', $field))."' is required.";
    }
}

// Basic type validation (add more as needed)
if (!empty($formData['year']) && !filter_var($formData['year'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1900, 'max_range' => date('Y') + 1]])) {
    $errors[] = 'Invalid Year.';
}
if (!empty($formData['price']) && !filter_var($formData['price'], FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0]])) {
    $errors[] = 'Invalid Price.';
}
if (!empty($formData['mileage']) && !filter_var($formData['mileage'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]])) {
    $errors[] = 'Invalid Mileage.';
}
// Add more validation for other fields (seating, cylinders, selects etc.)

if (!empty($errors)) {
    http_response_code(400); // Bad Request
    echo generateErrorMessage(implode('<br>', $errors));
    exit;
}

// 4. Handle Image Uploads
$uploadedImagePaths = [];
$userUploadDir = UPLOAD_DIR_BASE.'user_'.$userId.'/';

if (!is_dir($userUploadDir) && !mkdir($userUploadDir, 0755, true)) {
    http_response_code(500);
    echo generateErrorMessage('Could not create upload directory.');
    exit;
}

if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
    $imageCount = count($_FILES['images']['name']);
    if ($imageCount > MAX_IMAGES) {
        http_response_code(400);
        echo generateErrorMessage('You can upload a maximum of '.MAX_IMAGES.' images.');
        exit;
    }

    for ($i = 0; $i < $imageCount; ++$i) {
        if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['images']['tmp_name'][$i];
            $fileName = basename($_FILES['images']['name'][$i]);
            $fileSize = $_FILES['images']['size'][$i];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Validate file size and extension
            if ($fileSize > MAX_FILE_SIZE) {
                $errors[] = "Image '{$fileName}' exceeds the maximum size limit of ".(MAX_FILE_SIZE / 1024 / 1024).'MB.';
                continue;
            }
            if (!in_array($fileExt, ALLOWED_EXTENSIONS)) {
                $errors[] = "Image '{$fileName}' has an invalid file type. Allowed types: ".implode(', ', ALLOWED_EXTENSIONS).'.';
                continue;
            }

            // Generate unique filename and move
            $newFileName = uniqid('img_', true).'.'.$fileExt;
            $destination = $userUploadDir.$newFileName;
            $relativePath = str_replace(__DIR__.'/../', '', $destination); // Store relative path from web root

            if (move_uploaded_file($tmpName, $destination)) {
                $uploadedImagePaths[] = $relativePath;
            } else {
                $errors[] = "Failed to upload image '{$fileName}'.";
            }
        } elseif ($_FILES['images']['error'][$i] !== UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Error uploading image: '.$_FILES['images']['error'][$i];
        }
    }
}

if (!empty($errors)) {
    // Attempt to clean up already uploaded files if errors occurred mid-upload
    foreach ($uploadedImagePaths as $path) {
        $fullPath = __DIR__.'/../'.$path;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
    http_response_code(400);
    echo generateErrorMessage('Image upload errors:<br>'.implode('<br>', $errors));
    exit;
}

// 5. Prepare Data for Database
$dbData = [
    'user_id' => $userId,
    'status' => 'active', // Default status
    'make' => $formData['make'] ?? null,
    'model' => $formData['model'] ?? null,
    'year' => $formData['year'] ?? null,
    'price' => $formData['price'] ?? null,
    'images' => json_encode($uploadedImagePaths), // Store as JSON array
    'description' => $formData['description'] ?? null,
    'features' => json_encode(array_map('trim', explode(',', $formData['features'] ?? ''))), // Store as JSON array
    'body_type' => $formData['body_type'] ?? null,
    'mileage' => $formData['mileage'] ?? null,
    'fuel_type' => $formData['fuel_type'] ?? null,
    'color' => $formData['color'] ?? null,
    'seating' => $formData['seating'] ?? null,
    'drivetrain' => $formData['drivetrain'] ?? null,
    'transmission' => $formData['transmission'] ?? null,
    'cylinders' => $formData['cylinders'] ?? null,
    'condition' => $formData['condition'] ?? null,
    'location' => $formData['location'] ?? null,
];

// Filter out null values if columns don't have defaults or aren't nullable (adjust as per your DB schema)
// $dbData = array_filter($dbData, fn($value) => $value !== null);

// 6. Insert into Database
try {
    $sql = 'INSERT INTO vehicles (user_id, status, make, model, year, price, images, description, features, body_type, mileage, fuel_type, color, seating, drivetrain, transmission, cylinders, `condition`, location)
            VALUES (:user_id, :status, :make, :model, :year, :price, :images, :description, :features, :body_type, :mileage, :fuel_type, :color, :seating, :drivetrain, :transmission, :cylinders, :condition, :location)';

    $statement = $pdo->prepare($sql);
    $statement->execute($dbData);
    $newVehicleId = $pdo->lastInsertId();

    // 7. Return Success Response
    http_response_code(200); // OK
    // Optionally trigger a client-side event
    // header('HX-Trigger: listingCreated');
    echo generateSuccessMessage($newVehicleId);
} catch (PDOException $e) {
    // Log the detailed error
    error_log('Database Error: '.$e->getMessage()); // Keep logging

    // Attempt to clean up uploaded files on DB error
    foreach ($uploadedImagePaths as $path) {
        $fullPath = __DIR__.'/../'.$path;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    http_response_code(500); // Internal Server Error
    // --- DEBUGGING: Output specific PDO error ---
    echo generateErrorMessage('Database Error: '.htmlspecialchars($e->getMessage()));
    // --- REMOVE OR COMMENT OUT ABOVE LINE IN PRODUCTION ---
    // echo generateErrorMessage("Failed to save listing to the database. Please try again later."); // Original line for production
}

exit;
