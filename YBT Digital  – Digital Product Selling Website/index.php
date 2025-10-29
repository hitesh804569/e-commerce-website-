<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'Home - ' . SITE_NAME;

// Get featured products
$featuredQuery = "SELECT p.*, c.name as category_name 
                  FROM products p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  WHERE p.status = 'active' AND p.featured = 1 
                  ORDER BY p.created_at DESC LIMIT 8";
$featuredProducts = fetchAll($featuredQuery);

// Get categories
$categoriesQuery = "SELECT * FROM categories WHERE status = 'active' ORDER BY name";
$categories = fetchAll($categoriesQuery);

// Get testimonials (sample data - can be moved to database)
$testimonials = [
    ['name' => 'John Doe', 'rating' => 5, 'comment' => 'Amazing quality products! Fast download and great support.'],
    ['name' => 'Sarah Smith', 'rating' => 5, 'comment' => 'Best digital marketplace I\'ve used. Highly recommended!'],
    ['name' => 'Mike Johnson', 'rating' => 4, 'comment' => 'Great selection of products. Easy checkout process.']
];

include __DIR__ . '/includes/header.php';
?>

<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 100px 0;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-size: cover;
        background-position: bottom;
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
    }
    
    .feature-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin-bottom: 1rem;
    }
    
    .product-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
        height: 100%;
    }
    
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }
    
    .product-image {
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: rgba(0,0,0,0.1);
    }
    
    .price-tag {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-color);
    }
    
    .old-price {
        text-decoration: line-through;
        color: var(--text-secondary);
        font-size: 1rem;
    }
    
    .category-card {
        padding: 2rem;
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        background: var(--card-bg);
        border: 2px solid var(--border-color);
    }
    
    .category-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
    }
    
    .category-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: var(--primary-color);
    }
    
    .testimonial-card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 2rem;
        height: 100%;
    }
    
    .rating {
        color: #ffc107;
    }
    
    @media (max-width: 768px) {
        .hero-section {
            padding: 60px 0;
        }
        
        .product-card {
            margin-bottom: 1rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">Premium Digital Products</h1>
                <p class="lead mb-4">
                    Discover thousands of high-quality digital products. Download instantly after purchase.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="<?php echo SITE_URL; ?>/products" class="btn btn-light btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i> Explore Products
                    </a>
                    <a href="<?php echo SITE_URL; ?>/auth/signup.php" class="btn btn-outline-light btn-lg">
                        Get Started
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-laptop-code" style="font-size: 15rem; opacity: 0.2;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-download"></i>
                    </div>
                    <h5 class="fw-bold">Instant Download</h5>
                    <p class="text-muted small">Download immediately after purchase</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="fw-bold">Secure Payment</h5>
                    <p class="text-muted small">100% secure payment gateway</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5 class="fw-bold">24/7 Support</h5>
                    <p class="text-muted small">Always here to help you</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-sync"></i>
                    </div>
                    <h5 class="fw-bold">Easy Refunds</h5>
                    <p class="text-muted small">7-day money-back guarantee</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<?php if (!empty($categories)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Browse Categories</h2>
            <p class="text-muted">Find products by category</p>
        </div>
        
        <div class="row g-4">
            <?php foreach (array_slice($categories, 0, 6) as $category): ?>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="<?php echo SITE_URL; ?>/products?category=<?php echo $category['id']; ?>" class="text-decoration-none">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        <h6 class="fw-bold mb-0"><?php echo sanitize($category['name']); ?></h6>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Products Section -->
<?php if (!empty($featuredProducts)): ?>
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Featured Products</h2>
            <p class="text-muted">Check out our top-selling digital products</p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($featuredProducts as $product): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card product-card">
                    <div class="product-image">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="card-body">
                        <?php if ($product['category_name']): ?>
                        <span class="badge bg-primary mb-2"><?php echo sanitize($product['category_name']); ?></span>
                        <?php endif; ?>
                        
                        <h5 class="card-title fw-bold">
                            <a href="<?php echo SITE_URL; ?>/product.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                <?php echo sanitize($product['title']); ?>
                            </a>
                        </h5>
                        
                        <p class="card-text text-muted small">
                            <?php echo substr(sanitize($product['description']), 0, 80) . '...'; ?>
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <?php if ($product['discount_price']): ?>
                                    <span class="price-tag"><?php echo formatPrice($product['discount_price']); ?></span>
                                    <span class="old-price d-block"><?php echo formatPrice($product['price']); ?></span>
                                <?php else: ?>
                                    <span class="price-tag"><?php echo formatPrice($product['price']); ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo SITE_URL; ?>/product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?php echo SITE_URL; ?>/products" class="btn btn-primary btn-lg">
                View All Products <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Testimonials Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">What Our Customers Say</h2>
            <p class="text-muted">Trusted by thousands of satisfied customers</p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($testimonials as $testimonial): ?>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="rating mb-3">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <i class="fas fa-star<?php echo $i < $testimonial['rating'] ? '' : '-o'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="mb-3">"<?php echo sanitize($testimonial['comment']); ?>"</p>
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <?php echo strtoupper(substr($testimonial['name'], 0, 1)); ?>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold"><?php echo sanitize($testimonial['name']); ?></h6>
                            <small class="text-muted">Verified Customer</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container text-center text-white">
        <h2 class="fw-bold mb-3">Ready to Get Started?</h2>
        <p class="lead mb-4">Join thousands of satisfied customers today!</p>
        <a href="<?php echo SITE_URL; ?>/auth/signup.php" class="btn btn-light btn-lg">
            Create Free Account <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
