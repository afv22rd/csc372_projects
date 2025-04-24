<?php

// Start session
session_start();

// Include database connection
require_once '../database/database_connection.php';

// Get the posted data
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

// Simple validation
$errors = [];

if (empty($email)) {
    $errors['email'] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address';
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

try {
    // First check if user exists in database
    $sql = 'SELECT user_id, email FROM users WHERE email = ?';
    $statement = $pdo->prepare($sql);
    $statement->execute([$email]);
    $user = $statement->fetch();

    if (!$user) {
        // User doesn't exist
        header('Content-Type: text/html');
        echo '<div class="alert alert-error mb-4">';
        echo '<ul class="list-disc pl-5">';
        echo '<li>No account found with this email address</li>';
        echo '</ul>';
        echo '</div>';
        exit;
    }

    // User exists, update the password
    // For testing purposes, store as plain text
    $update_sql = 'UPDATE users SET password = ? WHERE user_id = ?';
    $update_statement = $pdo->prepare($update_sql);
    $result = $update_statement->execute([$password, $user['user_id']]);

    if ($result) {
        // Password updated successfully
        header('Content-Type: text/html');
        echo '<div class="alert alert-success mb-4">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Your password has been updated successfully. Redirecting...</span>
            </div>
        </div>';

        // Add a script to redirect to login form after a short delay
        echo '<script>
            setTimeout(function() {
                document.querySelector("#auth-form-container").innerHTML = "";
                htmx.ajax("GET", "api/auth_forms.php?form=login", {target:"#auth-form-container", swap:"innerHTML"});
            }, 2000);
        </script>';
    } else {
        // Failed to update password
        header('Content-Type: text/html');
        echo '<div class="alert alert-error mb-4">';
        echo '<ul class="list-disc pl-5">';
        echo '<li>Failed to update password. Please try again.</li>';
        echo '</ul>';
        echo '</div>';
    }
} catch (PDOException $e) {
    // Log error and show generic error message
    error_log('Database error: '.$e->getMessage());

    header('Content-Type: text/html');
    echo '<div class="alert alert-error mb-4">';
    echo '<ul class="list-disc pl-5">';
    echo '<li>An error occurred during password reset. Please try again later.</li>';
    echo '</ul>';
    echo '</div>';
}
