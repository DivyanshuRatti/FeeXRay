<?php
session_start();

// Empty the session data
$_SESSION = [];

// Delete the session cookie in the browser
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

// Delete the session on the server
session_destroy();

// Show the "you've been logged out" page
require __DIR__ . '/../views/auth/logout.php';