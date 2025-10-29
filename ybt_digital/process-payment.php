<?php
require_once __DIR__ . '/config/config.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(SITE_URL . '/cart');
}

$userId = $_SESSION['user_id'];
$items = json_decode($_POST['items'] ?? '[]', true);
$couponCode = sanitize($_POST['coupon_code'] ?? '');
$subtotal = floatval($_POST['subtotal'] ?? 0);
$discount = floatval($_POST['discount'] ?? 0);
$tax = floatval($_POST['tax'] ?? 0);
$total = floatval($_POST['total'] ?? 0);

if (empty($items) || $total <= 0) {
    setFlash('error', 'Invalid order data');
    redirect(SITE_URL . '/cart');
}

// Get settings
$settings = getSettings();
$paymentGateway = $settings['payment_gateway'] ?? 'razorpay';

// Generate order number
$orderNumber = generateOrderNumber();

// Create order in database
$conn = getDBConnection();
$conn->begin_transaction();

try {
    // Insert order
    $orderQuery = "INSERT INTO orders (user_id, order_number, subtotal, discount, tax, total, coupon_code, payment_method, payment_status, status) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending')";
    $orderId = insertAndGetId($orderQuery, [
        $userId, $orderNumber, $subtotal, $discount, $tax, $total, $couponCode, $paymentGateway
    ], 'isddddss');
    
    if (!$orderId) {
        throw new Exception('Failed to create order');
    }
    
    // Insert order items
    $itemQuery = "INSERT INTO order_items (order_id, product_id, product_title, price, quantity) VALUES (?, ?, ?, ?, 1)";
    foreach ($items as $productId) {
        $product = fetchOne("SELECT * FROM products WHERE id = ?", [$productId], 'i');
        if ($product) {
            $itemPrice = $product['discount_price'] ?? $product['price'];
            executeQuery($itemQuery, [$orderId, $productId, $product['title'], $itemPrice], 'iiss');
        }
    }
    
    // Update coupon usage if applied
    if (!empty($couponCode)) {
        incrementCouponUsage($couponCode);
    }
    
    $conn->commit();
    
    // Store order ID in session
    $_SESSION['pending_order_id'] = $orderId;
    
    // Redirect to payment gateway
    if ($paymentGateway === 'razorpay') {
        redirect(SITE_URL . '/payment/razorpay.php?order_id=' . $orderId);
    } elseif ($paymentGateway === 'stripe') {
        redirect(SITE_URL . '/payment/stripe.php?order_id=' . $orderId);
    } elseif ($paymentGateway === 'paypal') {
        redirect(SITE_URL . '/payment/paypal.php?order_id=' . $orderId);
    } else {
        // Demo mode - auto complete
        redirect(SITE_URL . '/payment/demo.php?order_id=' . $orderId);
    }
    
} catch (Exception $e) {
    $conn->rollback();
    error_log($e->getMessage());
    setFlash('error', 'Failed to process order. Please try again.');
    redirect(SITE_URL . '/checkout.php');
}
?>
