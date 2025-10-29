<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Site Configuration
define('SITE_URL', 'http://localhost/YBT%20Digital%20%20%E2%80%93%20Digital%20Product%20Selling%20Website');
define('SITE_NAME', 'YBT Digital');
define('ADMIN_EMAIL', 'admin@ybtdigital.com');

// Directory paths
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('PRODUCTS_PATH', UPLOAD_PATH . '/products');
define('SCREENSHOTS_PATH', UPLOAD_PATH . '/screenshots');
define('TEMP_PATH', ROOT_PATH . '/temp');

// Create directories if they don't exist
$directories = [UPLOAD_PATH, PRODUCTS_PATH, SCREENSHOTS_PATH, TEMP_PATH];
foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Security settings
define('PASSWORD_MIN_LENGTH', 6);
define('SESSION_TIMEOUT', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 minutes

// Download settings
define('MAX_DOWNLOADS', 5);
define('DOWNLOAD_EXPIRY_DAYS', 365);

// Pagination
define('PRODUCTS_PER_PAGE', 12);
define('ORDERS_PER_PAGE', 20);

// File upload settings
define('MAX_FILE_SIZE', 100 * 1024 * 1024); // 100MB
define('ALLOWED_FILE_TYPES', ['zip', 'rar', 'pdf', 'doc', 'docx', 'psd', 'ai']);
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Include database configuration
require_once __DIR__ . '/database.php';

// Autoload helper functions
require_once ROOT_PATH . '/includes/functions.php';

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('UTC');
?>
