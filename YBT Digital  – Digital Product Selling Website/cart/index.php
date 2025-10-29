<?php
require_once __DIR__ . '/../config/config.php';

$pageTitle = 'Shopping Cart - ' . SITE_NAME;

// Handle remove from cart
if (isset($_GET['remove'])) {
    $removeId = intval($_GET['remove']);
    removeFromCart($removeId);
    setFlash('success', 'Product removed from cart');
    redirect(SITE_URL . '/cart');
}

// Get cart items
$cartItems = getCartItems();
$subtotal = getCartTotal();
$tax = calculateTax($subtotal);
$total = $subtotal + $tax;

include __DIR__ . '/../includes/header.php';
?>

<style>
    .cart-container {
        padding: 3rem 0;
        min-height: 60vh;
    }
    
    .cart-item {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        display: flex;
        gap: 1.5rem;
        align-items: center;
        transition: all 0.3s;
    }
    
    .cart-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .cart-item-image {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: rgba(0,0,0,0.1);
        flex-shrink: 0;
    }
    
    .cart-item-details {
        flex-grow: 1;
    }
    
    .cart-summary {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 2rem;
        position: sticky;
        top: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .summary-row:last-child {
        border-bottom: none;
        font-size: 1.25rem;
        font-weight: bold;
        color: var(--primary-color);
    }
    
    .empty-cart {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-cart-icon {
        font-size: 5rem;
        color: var(--text-secondary);
        opacity: 0.3;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .cart-item {
            flex-direction: column;
            text-align: center;
        }
        
        .cart-item-image {
            width: 80px;
            height: 80px;
        }
        
        .cart-summary {
            position: static;
            margin-top: 2rem;
        }
    }
</style>

<div class="container cart-container">
    <h1 class="fw-bold mb-4">
        <i class="fas fa-shopping-cart me-2"></i> Shopping Cart
    </h1>
    
    <?php if (empty($cartItems)): ?>
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3 class="fw-bold mb-3">Your Cart is Empty</h3>
            <p class="text-muted mb-4">Add some products to your cart to get started</p>
            <a href="<?php echo SITE_URL; ?>/products" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-2"></i> Browse Products
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8 mb-4">
                <?php foreach ($cartItems as $item): ?>
                    <?php
                    $itemPrice = $item['discount_price'] ?? $item['price'];
                    ?>
                    <div class="cart-item">
                        <div class="cart-item-image">
                            <i class="fas fa-box-open"></i>
                        </div>
                        
                        <div class="cart-item-details">
                            <h5 class="fw-bold mb-2">
                                <a href="<?php echo SITE_URL; ?>/product.php?id=<?php echo $item['id']; ?>" 
                                   class="text-decoration-none text-dark">
                                    <?php echo sanitize($item['title']); ?>
                                </a>
                            </h5>
                            <p class="text-muted mb-2">
                                <?php echo substr(sanitize($item['description']), 0, 100) . '...'; ?>
                            </p>
                            <div class="d-flex align-items-center gap-3">
                                <span class="fw-bold text-primary" style="font-size: 1.25rem;">
                                    <?php echo formatPrice($itemPrice); ?>
                                </span>
                                <?php if ($item['discount_price']): ?>
                                    <span class="text-muted text-decoration-line-through">
                                        <?php echo formatPrice($item['price']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div>
                            <a href="?remove=<?php echo $item['id']; ?>" 
                               class="btn btn-outline-danger"
                               onclick="return confirm('Remove this item from cart?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="mt-3">
                    <a href="<?php echo SITE_URL; ?>/products" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="cart-summary">
                    <h4 class="fw-bold mb-4">Order Summary</h4>
                    
                    <div class="summary-row">
                        <span>Subtotal (<?php echo count($cartItems); ?> items)</span>
                        <span><?php echo formatPrice($subtotal); ?></span>
                    </div>
                    
                    <?php if ($tax > 0): ?>
                    <div class="summary-row">
                        <span>Tax</span>
                        <span><?php echo formatPrice($tax); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="summary-row">
                        <span>Total</span>
                        <span><?php echo formatPrice($total); ?></span>
                    </div>
                    
                    <div class="mt-4">
                        <?php if (isLoggedIn()): ?>
                            <a href="<?php echo SITE_URL; ?>/checkout.php" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-lock me-2"></i> Proceed to Checkout
                            </a>
                        <?php else: ?>
                            <a href="<?php echo SITE_URL; ?>/auth/login.php" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i> Login to Checkout
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-shield-alt text-success me-2"></i> Safe & Secure
                        </h6>
                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 100% Secure Payment</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Instant Download</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 7-Day Money Back</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
