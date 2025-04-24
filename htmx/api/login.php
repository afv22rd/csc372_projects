<?php

// Start session
session_start();

// Include database connection
require_once '../database/database_connection.php';

// Get the posted data
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$remember = isset($_POST['remember']) ? true : false;

// Simple validation
$errors = [];

if (empty($email)) {
    $errors['email'] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address';
}

if (empty($password)) {
    $errors['password'] = 'Password is required';
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

// Check if user exists in database
$sql = 'SELECT user_id, name, email, password FROM users WHERE email = ?';
try {
    $statement = pdo($pdo, $sql, [$email]);
    $user = $statement->fetch();

    if (!$user) {
        header('Content-Type: text/html');
        echo '<div class="alert alert-error mb-4">';
        echo '<ul class="list-disc pl-5">';
        echo '<li>Invalid email or password</li>';
        echo '</ul>';
        echo '</div>';
        exit;
    }

    // Check password separately using direct string comparison
    if ($password !== $user['password']) {
        header('Content-Type: text/html');
        echo '<div class="alert alert-error mb-4">';
        echo '<ul class="list-disc pl-5">';
        echo '<li>Invalid email or password</li>';
        echo '</ul>';
        echo '</div>';
        exit;
    }

    // User authenticated successfully
    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['name'];

    // Set cookie if remember me is checked
    if ($remember) {
        setcookie('user_name', $user['name'], time() + (86400 * 30), '/'); // 30 days
        setcookie('user_email', $user['email'], time() + (86400 * 30), '/'); // 30 days
    } else {
        setcookie('user_name', $user['name'], 0, '/'); // Session cookie
    }

    // Return success message
    header('Content-Type: text/html');
    echo '<div class="alert alert-success mb-4">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Login successful! Redirecting...</span>
        </div>
    </div>
    <script>
        // Redirect after successful login
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
    echo '<li>An error occurred during login. Please try again later.</li>';
    echo '</ul>';
    echo '</div>';
}
