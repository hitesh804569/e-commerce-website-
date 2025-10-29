<?php
require_once __DIR__ . '/../config/config.php';

requireLogin();

$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($orderId <= 0) {
    redirect(SITE_URL . '/cart');
}

// Get order
$order = fetchOne("SELECT * FROM orders WHERE id = ? AND user_id = ?", [$orderId, $_SESSION['user_id']], 'ii');

if (!$order) {
    setFlash('error', 'Order not found');
    redirect(SITE_URL . '/cart');
}

// Demo payment - auto complete
$transactionId = 'DEMO-' . strtoupper(uniqid());

// Update order
executeQuery("UPDATE orders SET payment_status = 'completed', transaction_id = ?, status = 'completed' WHERE id = ?", 
    [$transactionId, $orderId], 'si');

// Create download links
$items = fetchAll("SELECT * FROM order_items WHERE order_id = ?", [$orderId], 'i');
foreach ($items as $item) {
    $token = generateToken();
    $expiryDate = date('Y-m-d H:i:s', strtotime('+' . DOWNLOAD_EXPIRY_DAYS . ' days'));
    
    $downloadQuery = "INSERT INTO downloads (user_id, order_id, product_id, download_token, max_downloads, expiry_date) 
                      VALUES (?, ?, ?, ?, ?, ?)";
    executeQuery($downloadQuery, [
        $_SESSION['user_id'], $orderId, $item['product_id'], $token, MAX_DOWNLOADS, $expiryDate
    ], 'iiisis');
    
    // Update product sales count
    executeQuery("UPDATE products SET sales = sales + 1 WHERE id = ?", [$item['product_id']], 'i');
}

// Clear cart
clearCart();
unset($_SESSION['applied_coupon']);

setFlash('success', 'Payment successful! You can now download your products.');
redirect(SITE_URL . '/user/orders.php');
?>
