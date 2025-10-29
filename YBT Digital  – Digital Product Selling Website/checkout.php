<?php
require_once __DIR__ . '/config/config.php';

requireLogin();

$pageTitle = 'Checkout - ' . SITE_NAME;

// Get items to checkout
$checkoutItems = [];
if (isset($_GET['product'])) {
    // Buy now - single product
    $productId = intval($_GET['product']);
    $query = "SELECT * FROM products WHERE id = ? AND status = 'active'";
    $product = fetchOne($query, [$productId], 'i');
    if ($product) {
        $checkoutItems = [$product];
    }
} else {
    // Checkout from cart
    $checkoutItems = getCartItems();
}

if (empty($checkoutItems)) {
    setFlash('error', 'No items to checkout');
    redirect(SITE_URL . '/cart');
}

// Calculate totals
$subtotal = 0;
foreach ($checkoutItems as $item) {
    $price = $item['discount_price'] ?? $item['price'];
    $subtotal += $price;
}

$discount = 0;
$couponCode = '';
$tax = calculateTax($subtotal - $discount);
$total = $subtotal - $discount + $tax;

// Handle coupon application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_coupon'])) {
    $couponCode = sanitize($_POST['coupon_code']);
    $couponResult = applyCoupon($couponCode, $subtotal);
    
    if ($couponResult['success']) {
        $discount = $couponResult['discount'];
        $tax = calculateTax($subtotal - $discount);
        $total = $subtotal - $discount + $tax;
        $_SESSION['applied_coupon'] = $couponCode;
        setFlash('success', 'Coupon applied successfully!');
    } else {
        setFlash('error', $couponResult['message']);
    }
    redirect(SITE_URL . '/checkout.php' . (isset($_GET['product']) ? '?product=' . $_GET['product'] : ''));
}

// Get applied coupon from session
if (isset($_SESSION['applied_coupon'])) {
    $couponCode = $_SESSION['applied_coupon'];
    $couponResult = applyCoupon($couponCode, $subtotal);
    if ($couponResult['success']) {
        $discount = $couponResult['discount'];
        $tax = calculateTax($subtotal - $discount);
        $total = $subtotal - $discount + $tax;
    } else {
        unset($_SESSION['applied_coupon']);
    }
}

// Get settings
$settings = getSettings();
$paymentGateway = $settings['payment_gateway'] ?? 'razorpay';

include __DIR__ . '/includes/header.php';
?>

<style>
    .checkout-container {
        padding: 3rem 0;
    }
    
    .checkout-section {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .section-title {
        font-weight: bold;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }
    
    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .order-item:last-child {
        border-bottom: none;
    }
    
    .payment-method {
        border: 2px solid var(--border-color);
        border-radius: 8px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 1rem;
    }
    
    .payment-method:hover,
    .payment-method.active {
        border-color: var(--primary-color);
        background: rgba(18, 102, 241, 0.05);
    }
    
    .payment-method input[type="radio"] {
        margin-right: 0.5rem;
    }
    
    .order-summary-box {
        background: var(--light-bg);
        border-radius: 8px;
        padding: 1.5rem;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
    }
    
    .summary-total {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-color);
        padding-top: 1rem;
        margin-top: 1rem;
        border-top: 2px solid var(--border-color);
    }
</style>

<div class="container checkout-container">
    <h1 class="fw-bold mb-4">
        <i class="fas fa-lock me-2"></i> Secure Checkout
    </h1>
    
    <div class="row">
        <div class="col-lg-8 mb-4">
            <!-- Order Items -->
            <div class="checkout-section">
                <h4 class="section-title">
                    <i class="fas fa-shopping-bag me-2"></i> Order Items
                </h4>
                
                <?php foreach ($checkoutItems as $item): ?>
                    <?php $itemPrice = $item['discount_price'] ?? $item['price']; ?>
                    <div class="order-item">
                        <div>
                            <h6 class="fw-bold mb-1"><?php echo sanitize($item['title']); ?></h6>
                            <small class="text-muted">Digital Download</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary"><?php echo formatPrice($itemPrice); ?></div>
                            <?php if ($item['discount_price']): ?>
                                <small class="text-muted text-decoration-line-through">
                                    <?php echo formatPrice($item['price']); ?>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Coupon Code -->
            <div class="checkout-section">
                <h4 class="section-title">
                    <i class="fas fa-tag me-2"></i> Discount Code
                </h4>
                
                <form method="POST" action="">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <input type="text" name="coupon_code" class="form-control" 
                                   placeholder="Enter coupon code" 
                                   value="<?php echo htmlspecialchars($couponCode); ?>"
                                   <?php echo $discount > 0 ? 'readonly' : ''; ?>>
                        </div>
                        <div class="col-md-4">
                            <?php if ($discount > 0): ?>
                                <a href="<?php unset($_SESSION['applied_coupon']); echo SITE_URL . '/checkout.php' . (isset($_GET['product']) ? '?product=' . $_GET['product'] : ''); ?>" 
                                   class="btn btn-outline-danger w-100">Remove</a>
                            <?php else: ?>
                                <button type="submit" name="apply_coupon" class="btn btn-primary w-100">
                                    Apply
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($discount > 0): ?>
                        <div class="alert alert-success mt-3 mb-0">
                            <i class="fas fa-check-circle me-2"></i> Coupon applied! You saved <?php echo formatPrice($discount); ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
            
            <!-- Payment Method -->
            <div class="checkout-section">
                <h4 class="section-title">
                    <i class="fas fa-credit-card me-2"></i> Payment Method
                </h4>
                
                <div class="payment-method active">
                    <label class="d-flex align-items-center mb-0">
                        <input type="radio" name="payment_method" value="<?php echo $paymentGateway; ?>" checked>
                        <div class="ms-2">
                            <div class="fw-bold">
                                <?php echo ucfirst($paymentGateway); ?> Payment Gateway
                            </div>
                            <small class="text-muted">Pay securely with credit/debit card, UPI, or net banking</small>
                        </div>
                    </label>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> 
                    Your payment information is secure and encrypted. We never store your card details.
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="checkout-section">
                <h4 class="section-title">Order Summary</h4>
                
                <div class="order-summary-box">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span><?php echo formatPrice($subtotal); ?></span>
                    </div>
                    
                    <?php if ($discount > 0): ?>
                    <div class="summary-row text-success">
                        <span>Discount</span>
                        <span>-<?php echo formatPrice($discount); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($tax > 0): ?>
                    <div class="summary-row">
                        <span>Tax</span>
                        <span><?php echo formatPrice($tax); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <span><?php echo formatPrice($total); ?></span>
                    </div>
                </div>
                
                <form action="<?php echo SITE_URL; ?>/process-payment.php" method="POST" id="checkoutForm">
                    <input type="hidden" name="items" value="<?php echo htmlspecialchars(json_encode(array_column($checkoutItems, 'id'))); ?>">
                    <input type="hidden" name="coupon_code" value="<?php echo htmlspecialchars($couponCode); ?>">
                    <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
                    <input type="hidden" name="discount" value="<?php echo $discount; ?>">
                    <input type="hidden" name="tax" value="<?php echo $tax; ?>">
                    <input type="hidden" name="total" value="<?php echo $total; ?>">
                    
                    <button type="submit" class="btn btn-success w-100 btn-lg mt-4">
                        <i class="fas fa-lock me-2"></i> Pay <?php echo formatPrice($total); ?>
                    </button>
                </form>
                
                <div class="mt-4 pt-4 border-top text-center">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-shield-alt text-success me-2"></i> 100% Secure Payment
                    </h6>
                    <div class="d-flex justify-content-center gap-2">
                        <i class="fab fa-cc-visa fa-2x text-primary"></i>
                        <i class="fab fa-cc-mastercard fa-2x text-warning"></i>
                        <i class="fab fa-cc-paypal fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Payment method selection
    document.querySelectorAll('.payment-method').forEach(method => {
        method.addEventListener('click', function() {
            document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
            this.classList.add('active');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
