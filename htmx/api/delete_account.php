<?php

// Start session
session_start();

// Include database connection
require_once '../database/database_connection.php';

// Get the posted data
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

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

// Check if user exists in database and credentials are correct
$sql = 'SELECT user_id, name, email, password FROM users WHERE email = ?';
try {
    $statement = $pdo->prepare($sql);
    $statement->execute([$email]);
    $user = $statement->fetch();

    if (!$user) {
        header('Content-Type: text/html');
        echo '<div class="alert alert-error mb-4">';
        echo '<ul class="list-disc pl-5">';
        echo '<li>No account found with that email address</li>';
        echo '</ul>';
        echo '</div>';
        exit;
    }

    // Check password separately using direct string comparison (as it's stored in plain text)
    if ($password !== $user['password']) {
        header('Content-Type: text/html');
        echo '<div class="alert alert-error mb-4">';
        echo '<ul class="list-disc pl-5">';
        echo '<li>Invalid password</li>';
        echo '</ul>';
        echo '</div>';
        exit;
    }

    // User is authenticated - delete the account
    try {
        $deleteStmt = $pdo->prepare('DELETE FROM users WHERE user_id = ?');
        $success = $deleteStmt->execute([$user['user_id']]);

        if ($success && $deleteStmt->rowCount() > 0) {
            // Account deleted successfully - log the user out
            session_destroy();
            setcookie('user_name', '', time() - 3600, '/');
            setcookie('user_email', '', time() - 3600, '/');

            // Return success message and redirect to homepage
            header('Content-Type: text/html');
            echo '<div class="alert alert-success mb-4">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Your account has been deleted successfully. You will be redirected in a moment.</span>
                </div>
            </div>';

            // Add JavaScript to redirect
            echo '<script>
                // Hide the form after successful deletion
                const form = document.querySelector("form");
                if (form) form.style.display = "none";
                
                // Close the modal and redirect after a delay
                setTimeout(function() {
                    document.getElementById("delete-account-modal").close();
                    window.location.href = "/csc372_projects/htmx/index.php";
                }, 2000);
            </script>';
        } else {
            error_log('Delete account failed: No rows affected. User ID: '.$user['user_id']);
            header('Content-Type: text/html');
            echo '<div class="alert alert-error mb-4">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>An error occurred while deleting your account. Please try again later.</span>
                </div>
            </div>';
        }
    } catch (PDOException $e) {
        // Log error with detailed information
        error_log('Database error during account deletion: '.$e->getMessage().' - User ID: '.$user['user_id']);

        header('Content-Type: text/html');
        echo '<div class="alert alert-error mb-4">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Database error: '.htmlspecialchars($e->getMessage()).'</span>
            </div>
        </div>';
    }
} catch (PDOException $e) {
    // Log error and show generic error message
    error_log('Database error: '.$e->getMessage());

    header('Content-Type: text/html');
    echo '<div class="alert alert-error mb-4">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>An error occurred. Please try again later.</span>
        </div>
    </div>';
}
