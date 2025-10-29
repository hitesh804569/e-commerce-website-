<?php
require_once __DIR__ . '/config/config.php';

requireLogin();

$token = isset($_GET['token']) ? sanitize($_GET['token']) : '';

if (empty($token)) {
    setFlash('error', 'Invalid download link');
    redirect(SITE_URL . '/user/orders.php');
}

// Get download record
$download = fetchOne("SELECT d.*, p.title, p.file_path, p.file_size 
                      FROM downloads d 
                      JOIN products p ON d.product_id = p.id 
                      WHERE d.download_token = ? AND d.user_id = ?", 
    [$token, $_SESSION['user_id']], 'si');

if (!$download) {
    setFlash('error', 'Download not found or access denied');
    redirect(SITE_URL . '/user/orders.php');
}

// Check download limit
if ($download['download_count'] >= $download['max_downloads']) {
    setFlash('error', 'Download limit reached for this product');
    redirect(SITE_URL . '/user/orders.php');
}

// Check expiry
if ($download['expiry_date'] && strtotime($download['expiry_date']) < time()) {
    setFlash('error', 'Download link has expired');
    redirect(SITE_URL . '/user/orders.php');
}

// Check if file exists
$filePath = PRODUCTS_PATH . '/' . $download['file_path'];

if (!file_exists($filePath)) {
    setFlash('error', 'File not found. Please contact support.');
    redirect(SITE_URL . '/user/orders.php');
}

// Update download count
executeQuery("UPDATE downloads SET download_count = download_count + 1 WHERE id = ?", 
    [$download['id']], 'i');

// Force download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($download['file_path']) . '"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: public');

// Clear output buffer
ob_clean();
flush();

// Read and output file
readfile($filePath);
exit();
?>
