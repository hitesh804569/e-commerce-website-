<?php
// Secure Download Handler

session_start();

// Load configuration
require_once __DIR__ . '/../app/config/config.php';

// Load core files
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/Helpers.php';

// Load models
require_once APP_PATH . '/models/Download.php';

// Get token from URL
$token = $_GET['token'] ?? null;

if (!$token) {
    http_response_code(400);
    die('Download token is required');
}

// Initialize download model
$downloadModel = new Download();

// Find download by token
$download = $downloadModel->findByToken($token);

if (!$download) {
    http_response_code(404);
    die('Download not found or token is invalid');
}

// Validate download token
if (!$downloadModel->isValid($download)) {
    http_response_code(403);
    die('Download link has expired or usage limit exceeded');
}

// Verify user ownership (optional but recommended)
// If user is logged in, ensure the download belongs to them
if (isLoggedIn() && $download['user_id'] != $_SESSION['user_id']) {
    http_response_code(403);
    die('Unauthorized: This download does not belong to you');
}

// Build secure file path (never expose real paths to user)
$filepath = STORAGE_SECURE_PATH . '/product_files/' . $download['filename'];

// Check if file exists
if (!file_exists($filepath)) {
    http_response_code(404);
    die('File not found on server. Please contact support.');
}

// Atomically increment usage count
$downloadModel->incrementUsedCount($download['id']);

// Log the download event
$downloadModel->logDownload(
    $download['id'],
    $download['user_id'],
    $download['product_id']
);

// Get file information
$fileSize = filesize($filepath);
$mimeType = $download['mime_type'] ?? 'application/octet-stream';

// Clear any output buffers
if (ob_get_level()) {
    ob_end_clean();
}

// Set headers for secure file download
header('Content-Type: ' . $mimeType);
header('Content-Disposition: attachment; filename="' . $download['original_filename'] . '"');
header('Content-Length: ' . $fileSize);
header('Content-Transfer-Encoding: binary');
header('Cache-Control: private, no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Prevent any buffering
header('X-Accel-Buffering: no');

// Stream file to user
readfile($filepath);

// Exit to prevent any additional output
exit;
