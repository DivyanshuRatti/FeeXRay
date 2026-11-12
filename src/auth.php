<?php

// Call this at the top of any page that needs a logged-in user
function requireLogin()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header('Location: /login.php');
        exit;
    }
}