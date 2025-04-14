<?php

header('Content-Type: application/json');

// Validate input
$firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
$lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
$appointmentDate = filter_input(INPUT_POST, 'appointmentDate', FILTER_SANITIZE_STRING);
$appointmentTime = filter_input(INPUT_POST, 'appointmentTime', FILTER_SANITIZE_STRING);

// Validate all required fields are present
if (!$firstName || !$lastName || !$email || !$phone || !$appointmentDate || !$appointmentTime) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required',
    ]);
    exit;
}

// Validate date is not in the past
$appointmentDateTime = strtotime("$appointmentDate $appointmentTime");
if ($appointmentDateTime < strtotime('today')) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Appointment date cannot be in the past',
    ]);
    exit;
}

// Validate time is within business hours (9 AM - 5 PM)
$hour = date('G', $appointmentDateTime);
if ($hour < 9 || $hour >= 17) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Appointments must be scheduled between 9 AM and 5 PM',
    ]);
    exit;
}

// In a real application, we would:
// 1. Check if the time slot is available
// 2. Store the appointment in a database
// 3. Send confirmation emails
// For this demo, we'll just return success

echo json_encode([
    'success' => true,
    'message' => 'Appointment scheduled successfully',
    'data' => [
        'name' => "$firstName $lastName",
        'email' => $email,
        'phone' => $phone,
        'dateTime' => date('F j, Y g:i A', $appointmentDateTime),
    ],
]);
