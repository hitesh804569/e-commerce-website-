<?php
require_once __DIR__ . '/../config/config.php';

// Clear admin session
unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_role']);

setFlash('success', 'You have been logged out successfully');
redirect(SITE_URL . '/admin/login.php');
?>
