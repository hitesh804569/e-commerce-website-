<?php
require_once __DIR__ . '/../config/config.php';

$pageTitle = 'Dashboard - Admin Panel';

// Get statistics
$totalProducts = fetchOne("SELECT COUNT(*) as count FROM products")['count'];
$activeProducts = fetchOne("SELECT COUNT(*) as count FROM products WHERE status = 'active'")['count'];
$totalOrders = fetchOne("SELECT COUNT(*) as count FROM orders")['count'];
$completedOrders = fetchOne("SELECT COUNT(*) as count FROM orders WHERE payment_status = 'completed'")['count'];
$totalUsers = fetchOne("SELECT COUNT(*) as count FROM users")['count'];
$totalRevenue = fetchOne("SELECT COALESCE(SUM(total), 0) as total FROM orders WHERE payment_status = 'completed'")['total'];

// Get recent orders
$recentOrders = fetchAll("SELECT o.*, u.name as user_name, u.email as user_email 
                          FROM orders o 
                          JOIN users u ON o.user_id = u.id 
                          ORDER BY o.created_at DESC LIMIT 10");

// Get top products
$topProducts = fetchAll("SELECT p.*, COUNT(oi.id) as order_count 
                         FROM products p 
                         LEFT JOIN order_items oi ON p.id = oi.product_id 
                         LEFT JOIN orders o ON oi.order_id = o.id AND o.payment_status = 'completed'
                         GROUP BY p.id 
                         ORDER BY order_count DESC LIMIT 5");

include __DIR__ . '/includes/header.php';
?>

<h1 class="fw-bold mb-4">Dashboard Overview</h1>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Total Products</p>
                    <h3 class="fw-bold mb-0"><?php echo number_format($totalProducts); ?></h3>
                    <small class="text-success"><?php echo $activeProducts; ?> active</small>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Total Orders</p>
                    <h3 class="fw-bold mb-0"><?php echo number_format($totalOrders); ?></h3>
                    <small class="text-success"><?php echo $completedOrders; ?> completed</small>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #00b74a, #39c0ed);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Total Users</p>
                    <h3 class="fw-bold mb-0"><?php echo number_format($totalUsers); ?></h3>
                    <small class="text-muted">Registered</small>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #ffa900, #f93154);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Total Revenue</p>
                    <h3 class="fw-bold mb-0"><?php echo formatPrice($totalRevenue); ?></h3>
                    <small class="text-success">All time</small>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #1266f1, #b23cfd);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Orders -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-shopping-cart me-2"></i> Recent Orders
                    </h5>
                    <a href="<?php echo SITE_URL; ?>/admin/orders.php" class="btn btn-sm btn-primary">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentOrders)): ?>
                                <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo sanitize($order['order_number']); ?></td>
                                    <td>
                                        <?php echo sanitize($order['user_name']); ?><br>
                                        <small class="text-muted"><?php echo sanitize($order['user_email']); ?></small>
                                    </td>
                                    <td class="fw-bold text-primary"><?php echo formatPrice($order['total']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $order['payment_status'] === 'completed' ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($order['payment_status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo formatDate($order['created_at'], 'M d, Y'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No orders yet</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Products -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-star me-2"></i> Top Products
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($topProducts)): ?>
                    <?php foreach ($topProducts as $product): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="mb-1 fw-bold"><?php echo sanitize($product['title']); ?></h6>
                            <small class="text-muted"><?php echo $product['order_count']; ?> sales</small>
                        </div>
                        <span class="badge bg-primary"><?php echo formatPrice($product['price']); ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-muted mb-0">No sales data yet</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
