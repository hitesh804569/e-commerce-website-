<?php

class CheckoutController {
    private $productModel;
    private $orderModel;
    private $orderItemModel;
    private $couponModel;
    private $downloadModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->orderItemModel = new OrderItem();
        $this->couponModel = new Coupon();
        $this->downloadModel = new Download();
    }
    
    public function index() {
        requireLogin();
        
        $cart = $_SESSION['cart'] ?? [];
        
        if (empty($cart)) {
            setFlash('error', 'Your cart is empty');
            redirect('/products');
        }
        
        $cartItems = [];
        $subtotal = 0;
        
        foreach ($cart as $productId => $quantity) {
            $product = $this->productModel->findById($productId);
            if ($product) {
                $product['quantity'] = $quantity;
                $product['line_total'] = $product['price'] * $quantity;
                $cartItems[] = $product;
                $subtotal += $product['line_total'];
            }
        }
        
        $discount = 0;
        $couponCode = '';
        
        if (isset($_SESSION['coupon'])) {
            $couponCode = $_SESSION['coupon']['code'];
            $discount = $_SESSION['coupon']['discount'];
        }
        
        $total = $subtotal - $discount;
        
        require_once APP_PATH . '/views/checkout/index.php';
    }
    
    public function process() {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/checkout');
        }
        
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Invalid CSRF token');
            redirect('/checkout');
        }
        
        $cart = $_SESSION['cart'] ?? [];
        
        if (empty($cart)) {
            setFlash('error', 'Your cart is empty');
            redirect('/products');
        }
        
        // Calculate totals
        $subtotal = 0;
        $orderItems = [];
        
        foreach ($cart as $productId => $quantity) {
            $product = $this->productModel->findById($productId);
            if ($product) {
                $lineTotal = $product['price'] * $quantity;
                $subtotal += $lineTotal;
                $orderItems[] = [
                    'product_id' => $productId,
                    'product_title' => $product['title'],
                    'price' => $product['price'],
                    'quantity' => $quantity
                ];
            }
        }
        
        $discount = 0;
        $couponCode = null;
        
        if (isset($_SESSION['coupon'])) {
            $couponCode = $_SESSION['coupon']['code'];
            $discount = $_SESSION['coupon']['discount'];
        }
        
        $total = $subtotal - $discount;
        
        // Create order
        $db = Database::getInstance();
        $db->beginTransaction();
        
        try {
            $orderId = $this->orderModel->create([
                'user_id' => $_SESSION['user_id'],
                'order_number' => generateOrderNumber(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'coupon_code' => $couponCode,
                'payment_provider' => PAYMENT_PROVIDER,
                'payment_provider_id' => 'test_' . uniqid(), // Placeholder for actual payment
                'payment_status' => 'completed', // In production, set to pending until payment confirmed
                'order_status' => 'completed'
            ]);
            
            // Create order items and download tokens
            foreach ($orderItems as $item) {
                $orderItemId = $this->orderItemModel->create([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'product_title' => $item['product_title'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity']
                ]);
                
                // Generate download token for each product
                $downloadToken = generateToken(DOWNLOAD_TOKEN_LENGTH);
                $expiresAt = date('Y-m-d H:i:s', strtotime('+' . DOWNLOAD_TOKEN_EXPIRY_DAYS . ' days'));
                
                $this->downloadModel->create([
                    'order_item_id' => $orderItemId,
                    'user_id' => $_SESSION['user_id'],
                    'product_id' => $item['product_id'],
                    'download_token' => $downloadToken,
                    'expires_at' => $expiresAt,
                    'max_uses' => DOWNLOAD_MAX_USES,
                    'used_count' => 0
                ]);
            }
            
            // Update coupon usage
            if ($couponCode) {
                $coupon = $this->couponModel->findByCode($couponCode);
                if ($coupon) {
                    $this->couponModel->incrementUsage($coupon['id']);
                }
            }
            
            $db->commit();
            
            // Clear cart and coupon
            unset($_SESSION['cart']);
            unset($_SESSION['coupon']);
            
            setFlash('success', 'Order placed successfully!');
            redirect('/order?id=' . $orderId);
            
        } catch (Exception $e) {
            $db->rollBack();
            setFlash('error', 'Order processing failed. Please try again.');
            redirect('/checkout');
        }
    }
}
