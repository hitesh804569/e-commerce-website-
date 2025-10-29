<?php
require_once __DIR__ . '/../config/config.php';

requireLogin();

$pageTitle = 'Dashboard - ' . SITE_NAME;
$user = getCurrentUser();

// Get user statistics
$totalOrders = fetchOne("SELECT COUNT(*) as count FROM orders WHERE user_id = ? AND payment_status = 'completed'", 
    [$_SESSION['user_id']], 'i')['count'];

$totalSpent = fetchOne("SELECT COALESCE(SUM(total), 0) as total FROM orders WHERE user_id = ? AND payment_status = 'completed'", 
    [$_SESSION['user_id']], 'i')['total'];

$totalProducts = fetchOne("SELECT COUNT(DISTINCT product_id) as count FROM order_items oi 
                           JOIN orders o ON oi.order_id = o.id 
                           WHERE o.user_id = ? AND o.payment_status = 'completed'", 
    [$_SESSION['user_id']], 'i')['count'];

// Get recent orders
$recentOrders = fetchAll("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5", 
    [$_SESSION['user_id']], 'i');

include __DIR__ . '/../includes/header.php';
?>

<style>
    .dashboard-container {
        padding: 3rem 0;
    }
    
    .stat-card {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: transform 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .dashboard-section {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .section-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }
    
    .quick-action {
        background: var(--card-bg);
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s;
        text-decoration: none;
        display: block;
    }
    
    .quick-action:hover {
        border-color: var(--primary-color);
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .quick-action-icon {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .order-row {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .order-row:last-child {
        border-bottom: none;
    }
    
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 1rem;
        }
        
        .order-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>

<div class="container dashboard-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-2">Welcome back, <?php echo sanitize($user['name']); ?>!</h1>
            <p class="text-muted">Manage your account and orders</p>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-value"><?php echo $totalOrders; ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #00b74a, #39c0ed);">
                <div class="stat-icon">
                    <i class="fas fa-download"></i>
                </div>
                <div class="stat-value"><?php echo $totalProducts; ?></div>
                <div class="stat-label">Products Owned</div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffa900, #f93154);">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-value"><?php echo formatPrice($totalSpent); ?></div>
                <div class="stat-label">Total Spent</div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-6">
            <a href="<?php echo SITE_URL; ?>/products" class="quick-action">
                <div class="quick-action-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h6 class="fw-bold mb-0">Browse Products</h6>
            </a>
        </div>
        
        <div class="col-md-3 col-6">
            <a href="<?php echo SITE_URL; ?>/user/orders.php" class="quick-action">
                <div class="quick-action-icon">
                    <i class="fas fa-box"></i>
                </div>
                <h6 class="fw-bold mb-0">My Orders</h6>
            </a>
        </div>
        
        <div class="col-md-3 col-6">
            <a href="<?php echo SITE_URL; ?>/user/profile.php" class="quick-action">
                <div class="quick-action-icon">
                    <i class="fas fa-user-edit"></i>
                </div>
                <h6 class="fw-bold mb-0">Edit Profile</h6>
            </a>
        </div>
        
        <div class="col-md-3 col-6">
            <a href="<?php echo SITE_URL; ?>/pages/contact.php" class="quick-action">
                <div class="quick-action-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h6 class="fw-bold mb-0">Support</h6>
            </a>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="dashboard-section">
        <div class="section-header">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-history me-2"></i> Recent Orders
            </h4>
            <a href="<?php echo SITE_URL; ?>/user/orders.php" class="btn btn-sm btn-primary">View All</a>
        </div>
        
        <?php if (!empty($recentOrders)): ?>
            <?php foreach ($recentOrders as $order): ?>
                <div class="order-row">
                    <div>
                        <h6 class="fw-bold mb-1"><?php echo sanitize($order['order_number']); ?></h6>
                        <small class="text-muted">
                            <?php echo formatDate($order['created_at']); ?>
                        </small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-primary"><?php echo formatPrice($order['total']); ?></div>
                        <span class="badge bg-<?php echo $order['payment_status'] === 'completed' ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($order['payment_status']); ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">No orders yet</p>
                <a href="<?php echo SITE_URL; ?>/products" class="btn btn-primary">
                    Start Shopping
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
