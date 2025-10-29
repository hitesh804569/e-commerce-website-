<?php
require_once __DIR__ . '/../config/config.php';

requireLogin();

$pageTitle = 'My Orders - ' . SITE_NAME;

// Get user orders with items
$ordersQuery = "SELECT o.*, 
                (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
                FROM orders o 
                WHERE o.user_id = ? 
                ORDER BY o.created_at DESC";
$orders = fetchAll($ordersQuery, [$_SESSION['user_id']], 'i');

include __DIR__ . '/../includes/header.php';
?>

<style>
    .orders-container {
        padding: 3rem 0;
        min-height: 60vh;
    }
    
    .order-card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s;
    }
    
    .order-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }
    
    .order-number {
        font-size: 1.25rem;
        font-weight: bold;
        color: var(--primary-color);
    }
    
    .order-items {
        margin: 1rem 0;
    }
    
    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .order-item:last-child {
        border-bottom: none;
    }
    
    .download-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .empty-orders {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-icon {
        font-size: 5rem;
        color: var(--text-secondary);
        opacity: 0.3;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .order-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>

<div class="container orders-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">
            <i class="fas fa-box me-2"></i> My Orders
        </h1>
        <a href="<?php echo SITE_URL; ?>/user/dashboard.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>
    
    <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
            <?php
            // Get order items
            $items = fetchAll("SELECT oi.*, p.id as product_id, p.title, p.file_path 
                              FROM order_items oi 
                              LEFT JOIN products p ON oi.product_id = p.id 
                              WHERE oi.order_id = ?", [$order['id']], 'i');
            ?>
            
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <div class="order-number"><?php echo sanitize($order['order_number']); ?></div>
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            <?php echo formatDate($order['created_at'], 'M d, Y g:i A'); ?>
                        </small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-primary mb-2" style="font-size: 1.5rem;">
                            <?php echo formatPrice($order['total']); ?>
                        </div>
                        <span class="badge bg-<?php echo $order['payment_status'] === 'completed' ? 'success' : ($order['payment_status'] === 'failed' ? 'danger' : 'warning'); ?>">
                            <?php echo ucfirst($order['payment_status']); ?>
                        </span>
                    </div>
                </div>
                
                <div class="order-items">
                    <h6 class="fw-bold mb-3">Order Items (<?php echo count($items); ?>)</h6>
                    
                    <?php foreach ($items as $item): ?>
                        <?php
                        // Get download link if payment completed
                        $download = null;
                        if ($order['payment_status'] === 'completed') {
                            $download = fetchOne("SELECT * FROM downloads WHERE order_id = ? AND product_id = ? AND user_id = ?", 
                                [$order['id'], $item['product_id'], $_SESSION['user_id']], 'iii');
                        }
                        ?>
                        
                        <div class="order-item">
                            <div>
                                <h6 class="fw-bold mb-1"><?php echo sanitize($item['product_title']); ?></h6>
                                <small class="text-muted">Digital Download</small>
                                <?php if ($download): ?>
                                    <div class="mt-1">
                                        <small class="text-muted">
                                            Downloads: <?php echo $download['download_count']; ?> / <?php echo $download['max_downloads']; ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold mb-2"><?php echo formatPrice($item['price']); ?></div>
                                <?php if ($download): ?>
                                    <?php if ($download['download_count'] < $download['max_downloads']): ?>
                                        <a href="<?php echo SITE_URL; ?>/download.php?token=<?php echo $download['download_token']; ?>" 
                                           class="btn btn-success btn-sm download-btn">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Download Limit Reached</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="mt-3 pt-3 border-top">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-credit-card me-1"></i>
                                Payment Method: <?php echo ucfirst($order['payment_method']); ?>
                            </small>
                            <?php if ($order['transaction_id']): ?>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-receipt me-1"></i>
                                    Transaction ID: <?php echo sanitize($order['transaction_id']); ?>
                                </small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 text-md-end mt-2 mt-md-0">
                            <?php if ($order['payment_status'] === 'completed'): ?>
                                <a href="<?php echo SITE_URL; ?>/user/invoice.php?order=<?php echo $order['id']; ?>" 
                                   class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="fas fa-file-invoice me-1"></i> View Invoice
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-orders">
            <div class="empty-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h3 class="fw-bold mb-3">No Orders Yet</h3>
            <p class="text-muted mb-4">Start shopping to see your orders here</p>
            <a href="<?php echo SITE_URL; ?>/products" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-2"></i> Browse Products
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
