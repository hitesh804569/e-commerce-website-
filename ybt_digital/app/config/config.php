<?php
// YBT Digital - Configuration File

// Environment
define('APP_ENV', getenv('APP_ENV') ?: 'development');
define('APP_DEBUG', APP_ENV === 'development');

// Base paths
define('APP_ROOT', dirname(__DIR__, 2));
define('APP_PATH', APP_ROOT . '/app');
define('PUBLIC_PATH', APP_ROOT . '/public');
define('STORAGE_SECURE_PATH', APP_ROOT . '/storage_secure');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// Base URL (auto-detect)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
// HTTP_HOST already includes port if non-standard, so just use it directly
define('BASE_URL', getenv('BASE_URL') ?: $protocol . '://' . $host . '/ybt_digital/public');

// Database Configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'ybt_digital');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// Session Configuration
define('SESSION_LIFETIME', 7200);
define('SESSION_NAME', 'ybt_digital_session');

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_MIN_LENGTH', 8);

// File Upload Settings
define('MAX_UPLOAD_SIZE', 100 * 1024 * 1024);
define('ALLOWED_PRODUCT_MIMES', [
    'application/zip',
    'application/x-zip-compressed',
    'application/pdf',
    'application/octet-stream'
]);
define('ALLOWED_IMAGE_MIMES', [
    'image/jpeg',
    'image/jpg',
    'image/png',
    'image/webp'
]);

// Download Token Settings
define('DOWNLOAD_TOKEN_LENGTH', 64);
define('DOWNLOAD_TOKEN_EXPIRY_DAYS', 30);
define('DOWNLOAD_MAX_USES', 5);

// Payment Configuration - Stripe (Test Mode)
define('PAYMENT_PROVIDER', getenv('PAYMENT_PROVIDER') ?: 'stripe');
define('STRIPE_PUBLIC_KEY', getenv('STRIPE_PUBLIC_KEY') ?: 'pk_test_XXXXXXXXXXXXXXXXXXXX');
define('STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY') ?: 'sk_test_XXXXXXXXXXXXXXXXXXXX');

// Payment Configuration - Razorpay (Test Mode)
define('RAZORPAY_KEY_ID', getenv('RAZORPAY_KEY_ID') ?: 'rzp_test_XXXXXXXXXXXXXXXXXXXX');
define('RAZORPAY_KEY_SECRET', getenv('RAZORPAY_KEY_SECRET') ?: 'XXXXXXXXXXXXXXXXXXXX');

// Email Configuration (Placeholder)
define('MAIL_FROM', getenv('MAIL_FROM') ?: 'noreply@ybtdigital.com');
define('MAIL_FROM_NAME', getenv('MAIL_FROM_NAME') ?: 'YBT Digital');

// Pagination
define('PRODUCTS_PER_PAGE', 12);
define('ORDERS_PER_PAGE', 10);
define('USERS_PER_PAGE', 20);

// Site Settings
define('SITE_NAME', 'YBT Digital');
define('SITE_TAGLINE', 'Premium Digital Products Marketplace');

// Error Reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('UTC');
