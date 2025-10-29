<?php
require_once __DIR__ . '/config/config.php';

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($productId <= 0) {
    redirect(SITE_URL . '/products');
}

// Get product details
$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE p.id = ? AND p.status = 'active'";
$product = fetchOne($query, [$productId], 'i');

if (!$product) {
    setFlash('error', 'Product not found');
    redirect(SITE_URL . '/products');
}

// Update views
executeQuery("UPDATE products SET views = views + 1 WHERE id = ?", [$productId], 'i');

// Get screenshots
$screenshotsQuery = "SELECT * FROM product_screenshots WHERE product_id = ? ORDER BY display_order";
$screenshots = fetchAll($screenshotsQuery, [$productId], 'i');

// Get related products
$relatedQuery = "SELECT * FROM products 
                 WHERE category_id = ? AND id != ? AND status = 'active' 
                 ORDER BY RAND() LIMIT 4";
$relatedProducts = fetchAll($relatedQuery, [$product['category_id'], $productId], 'ii');

// Check if user already owns this product
$userOwns = false;
if (isLoggedIn()) {
    $userOwns = userOwnsProduct($_SESSION['user_id'], $productId);
}

$pageTitle = sanitize($product['title']) . ' - ' . SITE_NAME;

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isLoggedIn()) {
        setFlash('error', 'Please login to add items to cart');
        redirect(SITE_URL . '/auth/login.php');
    }
    
    if ($userOwns) {
        setFlash('info', 'You already own this product');
    } else {
        if (addToCart($productId)) {
            setFlash('success', 'Product added to cart');
        } else {
            setFlash('info', 'Product already in cart');
        }
    }
    redirect(SITE_URL . '/product.php?id=' . $productId);
}

include __DIR__ . '/includes/header.php';
?>

<style>
    .product-detail {
        padding: 3rem 0;
    }
    
    .product-image-main {
        height: 400px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        color: rgba(0,0,0,0.1);
        margin-bottom: 1rem;
    }
    
    .product-thumbnails {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
    }
    
    .thumbnail {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        background: var(--light-bg);
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .thumbnail:hover {
        transform: scale(1.05);
    }
    
    .price-section {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        position: sticky;
        top: 20px;
    }
    
    .price-large {
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--primary-color);
    }
    
    .old-price-large {
        font-size: 1.5rem;
        text-decoration: line-through;
        color: var(--text-secondary);
    }
    
    .product-meta {
        display: flex;
        gap: 2rem;
        margin: 1.5rem 0;
        flex-wrap: wrap;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
    }
    
    .feature-list {
        list-style: none;
        padding: 0;
    }
    
    .feature-list li {
        padding: 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .feature-list li i {
        color: var(--success-color);
    }
    
    @media (max-width: 768px) {
        .product-image-main {
            height: 250px;
            font-size: 3rem;
        }
        
        .price-section {
            position: static;
            margin-top: 2rem;
        }
    }
</style>

<div class="container product-detail">
    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-7 mb-4">
            <div class="product-image-main">
                <i class="fas fa-box-open"></i>
            </div>
            
            <?php if (!empty($screenshots)): ?>
            <div class="product-thumbnails">
                <?php foreach ($screenshots as $screenshot): ?>
                <div class="thumbnail">
                    <img src="<?php echo SITE_URL . '/uploads/screenshots/' . $screenshot['image_path']; ?>" 
                         alt="Screenshot" class="w-100 h-100 object-fit-cover">
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <!-- Product Description -->
            <div class="mt-4">
                <h2 class="fw-bold mb-3">Product Description</h2>
                <div class="text-muted">
                    <?php echo nl2br(sanitize($product['description'])); ?>
                </div>
            </div>
            
            <!-- Product Features -->
            <div class="mt-4">
                <h3 class="fw-bold mb-3">What's Included</h3>
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> Instant download after purchase</li>
                    <li><i class="fas fa-check-circle"></i> Lifetime access to downloads</li>
                    <li><i class="fas fa-check-circle"></i> Regular updates included</li>
                    <li><i class="fas fa-check-circle"></i> 24/7 customer support</li>
                    <li><i class="fas fa-check-circle"></i> Money-back guarantee</li>
                </ul>
            </div>
        </div>
        
        <!-- Product Info & Purchase -->
        <div class="col-lg-5">
            <div class="price-section">
                <?php if ($product['category_name']): ?>
                <span class="badge bg-primary mb-3"><?php echo sanitize($product['category_name']); ?></span>
                <?php endif; ?>
                
                <h1 class="fw-bold mb-3"><?php echo sanitize($product['title']); ?></h1>
                
                <div class="product-meta">
                    <div class="meta-item">
                        <i class="fas fa-eye"></i>
                        <span><?php echo number_format($product['views']); ?> views</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-shopping-cart"></i>
                        <span><?php echo number_format($product['sales']); ?> sales</span>
                    </div>
                    <?php if ($product['file_size']): ?>
                    <div class="meta-item">
                        <i class="fas fa-file"></i>
                        <span><?php echo sanitize($product['file_size']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <hr>
                
                <div class="mb-4">
                    <?php if ($product['discount_price']): ?>
                        <div class="price-large"><?php echo formatPrice($product['discount_price']); ?></div>
                        <div class="old-price-large"><?php echo formatPrice($product['price']); ?></div>
                        <?php 
                        $discount = round((($product['price'] - $product['discount_price']) / $product['price']) * 100);
                        ?>
                        <span class="badge bg-danger mt-2">Save <?php echo $discount; ?>%</span>
                    <?php else: ?>
                        <div class="price-large"><?php echo formatPrice($product['price']); ?></div>
                    <?php endif; ?>
                </div>
                
                <?php if ($userOwns): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> You already own this product
                    </div>
                    <a href="<?php echo SITE_URL; ?>/user/orders.php" class="btn btn-success w-100 btn-lg mb-3">
                        <i class="fas fa-download me-2"></i> Go to Downloads
                    </a>
                <?php else: ?>
                    <form method="POST" action="">
                        <button type="submit" name="add_to_cart" class="btn btn-primary w-100 btn-lg mb-3">
                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                        </button>
                    </form>
                    <a href="<?php echo SITE_URL; ?>/checkout.php?product=<?php echo $productId; ?>" 
                       class="btn btn-success w-100 btn-lg">
                        <i class="fas fa-bolt me-2"></i> Buy Now
                    </a>
                <?php endif; ?>
                
                <?php if ($product['demo_url']): ?>
                <a href="<?php echo sanitize($product['demo_url']); ?>" target="_blank" 
                   class="btn btn-outline-primary w-100 mt-3">
                    <i class="fas fa-external-link-alt me-2"></i> View Demo
                </a>
                <?php endif; ?>
                
                <div class="mt-4 pt-4 border-top">
                    <h6 class="fw-bold mb-3">Secure Payment</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <i class="fab fa-cc-visa fa-2x text-primary"></i>
                        <i class="fab fa-cc-mastercard fa-2x text-warning"></i>
                        <i class="fab fa-cc-paypal fa-2x text-info"></i>
                        <i class="fab fa-cc-stripe fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
    <div class="mt-5">
        <h2 class="fw-bold mb-4">Related Products</h2>
        <div class="row g-4">
            <?php foreach ($relatedProducts as $related): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card product-card">
                    <div class="product-image" style="height: 180px; font-size: 2rem;">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title fw-bold mb-2">
                            <a href="<?php echo SITE_URL; ?>/product.php?id=<?php echo $related['id']; ?>" 
                               class="text-decoration-none text-dark">
                                <?php echo sanitize($related['title']); ?>
                            </a>
                        </h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="price-tag" style="font-size: 1.2rem;">
                                <?php echo formatPrice($related['discount_price'] ?? $related['price']); ?>
                            </div>
                            <a href="<?php echo SITE_URL; ?>/product.php?id=<?php echo $related['id']; ?>" 
                               class="btn btn-primary btn-sm">View</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
