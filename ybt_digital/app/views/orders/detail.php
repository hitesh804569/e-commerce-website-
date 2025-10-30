<?php $pageTitle = 'Order #' . sanitize($order['order_number']) . ' - ' . SITE_NAME; ?>
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="<?php echo BASE_URL; ?>/orders" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Orders
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold mb-2">Order #<?php echo sanitize($order['order_number']); ?></h1>
                <p class="text-gray-600">Placed on <?php echo formatDateTime($order['created_at']); ?></p>
            </div>
            <div class="text-right">
                <?php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'completed' => 'bg-green-100 text-green-800',
                    'failed' => 'bg-red-100 text-red-800',
                    'refunded' => 'bg-gray-100 text-gray-800'
                ];
                $statusClass = $statusColors[$order['payment_status']] ?? 'bg-gray-100 text-gray-800';
                ?>
                <span class="px-4 py-2 text-sm font-semibold rounded-full <?php echo $statusClass; ?>">
                    <?php echo ucfirst($order['payment_status']); ?>
                </span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Order Summary</h3>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span><?php echo formatPrice($order['subtotal']); ?></span>
                    </div>
                    <?php if ($order['discount'] > 0): ?>
                    <div class="flex justify-between text-green-600">
                        <span>Discount:</span>
                        <span>-<?php echo formatPrice($order['discount']); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="flex justify-between font-bold text-lg pt-2 border-t">
                        <span>Total:</span>
                        <span class="text-blue-600"><?php echo formatPrice($order['total']); ?></span>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Payment Details</h3>
                <div class="space-y-1 text-sm">
                    <p><span class="text-gray-600">Provider:</span> <?php echo ucfirst($order['payment_provider']); ?></p>
                    <?php if ($order['payment_provider_id']): ?>
                    <p><span class="text-gray-600">Transaction ID:</span> <?php echo sanitize($order['payment_provider_id']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Customer Info</h3>
                <div class="space-y-1 text-sm">
                    <p><?php echo sanitize($_SESSION['user_name']); ?></p>
                    <p><?php echo sanitize($_SESSION['user_email']); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold mb-4">Order Items & Downloads</h3>
        
        <?php foreach ($orderItems as $item): ?>
        <div class="border-b last:border-b-0 py-4">
            <div class="flex gap-4">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-purple-100 rounded flex items-center justify-center flex-shrink-0">
                    <?php if ($item['thumbnail']): ?>
                        <img src="<?php echo BASE_URL . '/uploads/' . $item['thumbnail']; ?>" alt="<?php echo sanitize($item['product_title']); ?>" class="w-full h-full object-cover rounded">
                    <?php else: ?>
                        <i class="fas fa-box text-3xl text-gray-300"></i>
                    <?php endif; ?>
                </div>
                
                <div class="flex-1">
                    <h4 class="font-bold text-lg mb-1"><?php echo sanitize($item['product_title']); ?></h4>
                    <p class="text-gray-600 mb-2">Quantity: <?php echo $item['quantity']; ?> × <?php echo formatPrice($item['price']); ?></p>
                    
                    <?php
                    // Find download token for this item
                    $downloadToken = null;
                    foreach ($downloads as $dl) {
                        if ($dl['product_id'] == $item['product_id']) {
                            $downloadToken = $dl['download_token'];
                            $downloadExpires = $dl['expires_at'];
                            $downloadUsed = $dl['used_count'];
                            $downloadMax = $dl['max_uses'];
                            break;
                        }
                    }
                    
                    if ($downloadToken && $order['payment_status'] === 'completed'):
                    ?>
                    <div class="mt-3">
                        <a href="<?php echo BASE_URL; ?>/download.php?token=<?php echo $downloadToken; ?>" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            <i class="fas fa-download mr-2"></i> Download
                        </a>
                        <p class="text-xs text-gray-500 mt-2">
                            Downloads: <?php echo $downloadUsed; ?> / <?php echo $downloadMax; ?> | 
                            Expires: <?php echo formatDateTime($downloadExpires); ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="text-right">
                    <p class="font-bold text-blue-600"><?php echo formatPrice($item['price'] * $item['quantity']); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
