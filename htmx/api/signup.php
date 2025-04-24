<?php

// Start session
session_start();

// Include database connection
require_once '../database/database_connection.php';

// Get the posted data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

// Simple validation
$errors = [];

if (empty($name)) {
    $errors['name'] = 'Name is required';
}

if (empty($email)) {
    $errors['email'] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address';
}

if (empty($phone)) {
    $errors['phone'] = 'Phone number is required';
}

if (empty($password)) {
    $errors['password'] = 'Password is required';
} elseif (strlen($password) < 6) {
    $errors['password'] = 'Password must be at least 6 characters';
}

if ($password !== $confirm_password) {
    $errors['confirm_password'] = 'Passwords do not match';
}

// If there are errors, return them
if (!empty($errors)) {
    header('Content-Type: text/html');
    echo '<div class="alert alert-error mb-4">';
    echo '<ul class="list-disc pl-5">';
    foreach ($errors as $error) {
        echo '<li>'.htmlspecialchars($error).'</li>';
    }
    echo '</ul>';
    echo '</div>';
    exit;
}

// Check if email already exists
$sql = 'SELECT COUNT(*) FROM users WHERE email = ?';
$statement = pdo($pdo, $sql, [$email]);
if ($statement->fetchColumn() > 0) {
    header('Content-Type: text/html');
    echo '<div class="alert alert-error mb-4">';
    echo '<ul class="list-disc pl-5">';
    echo '<li>Email address already in use</li>';
    echo '</ul>';
    echo '</div>';
    exit;
}

// Generate the next user_id in sequence
try {
    // Find the highest user_id in the users table
    $sql = 'SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1';
    $statement = pdo($pdo, $sql);
    $last_user = $statement->fetch();

    // Generate new user_id
    if ($last_user && !empty($last_user['user_id'])) {
        // Extract the number part from the last user_id
        $last_id = $last_user['user_id'];
        preg_match('/user-(\d+)/', $last_id, $matches);

        if (isset($matches[1])) {
            // Increment the number and format with leading zeros
            $next_number = intval($matches[1]) + 1;
            $user_id = 'user-'.str_pad($next_number, 3, '0', STR_PAD_LEFT);
        } else {
            // Fallback if the format doesn't match
            $user_id = 'user-001';
        }
    } else {
        // No users exist yet, start with 001
        $user_id = 'user-001';
    }

    // Store the password directly as plain text for the assignment
    // Note: In a real application, passwords should always be hashed

    // Insert the user into the database with the generated user_id
    $sql = 'INSERT INTO users (user_id, name, email, phone, password) VALUES (?, ?, ?, ?, ?)';
    pdo($pdo, $sql, [$user_id, $name, $email, $phone, $password]);

    // Set session variables
    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_name'] = $name;

    // Set cookie with the user's name
    setcookie('user_name', $name, time() + (86400 * 30), '/'); // 30 days

    // Return success message
    header('Content-Type: text/html');
    echo '<div class="alert alert-success mb-4">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Account created successfully! Redirecting...</span>
        </div>
    </div>
    <script>
        // Redirect after successful signup
        setTimeout(function() {
            window.location.reload();
        }, 1000);
    </script>';
} catch (PDOException $e) {
    // Log error and show generic error message
    error_log('Database error: '.$e->getMessage());

    header('Content-Type: text/html');
    echo '<div class="alert alert-error mb-4">';
    echo '<ul class="list-disc pl-5">';
    echo '<li>An error occurred while creating your account. Please try again later.</li>';
    echo '</ul>';
    echo '</div>';
}
