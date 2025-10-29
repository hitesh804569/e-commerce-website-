<?php
require_once __DIR__ . '/../config/config.php';

// Clear session
session_unset();
session_destroy();

// Clear cookies
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, '/');
}

setFlash('success', 'You have been logged out successfully');
redirect(SITE_URL);
?>
