<?php
/**
 * Admin Authentication Middleware
 * Blocks non-admin users from accessing admin pages
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || 
    !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    
    // Redirect to login page or home page based on login status
    if (isset($_SESSION['user_id'])) {
        // User is logged in but not an admin
        header('Location: ' . BASE_URL . '/index.php');
    } else {
        // User is not logged in
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . BASE_URL . '/auth/login.php');
    }
    exit;
}