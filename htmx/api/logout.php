<?php

// Start the session
session_start();

// Unset all session variables
$_SESSION = [];

// Delete the session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Also delete the user_name cookie
setcookie('user_name', '', time() - 42000, '/');
// Delete the user_location cookie
setcookie('user_location', '', time() - 42000, '/');
// Finally, destroy the session
session_destroy();

// Redirect back to the homepage
header('Location: ../index.php');
exit;
