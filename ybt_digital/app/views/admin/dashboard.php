<?php $pageTitle = 'Admin Dashboard'; ?>
<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                <i class="fas fa-dollar-sign text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900"><?php echo formatPrice($totalRevenue); ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                <i class="fas fa-chart-line text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">This Month</p>
                <p class="text-2xl font-bold text-gray-900"><?php echo formatPrice($monthlyRevenue); ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                <i class="fas fa-shopping-cart text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Total Orders</p>
                <p class="text-2xl font-bold text-gray-900"><?php echo count($recentOrders); ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                <i class="fas fa-box text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Products</p>
                <p class="text-2xl font-bold text-gray-900"><?php echo count($topProducts); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold">Recent Orders</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <?php foreach (array_slice($recentOrders, 0, 5) as $order): ?>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-semibold">#<?php echo sanitize($order['order_number']); ?></p>
                        <p class="text-sm text-gray-600"><?php echo sanitize($order['user_name']); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-blue-600"><?php echo formatPrice($order['total']); ?></p>
                        <p class="text-xs text-gray-500"><?php echo formatDate($order['created_at']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <a href="<?php echo BASE_URL; ?>/admin/orders" class="block mt-4 text-center text-blue-600 hover:text-blue-800">
                View All Orders →
            </a>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold">Top Selling Products</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <?php foreach ($topProducts as $product): ?>
                <div class="flex justify-between items-center">
                    <div class="flex-1">
                        <p class="font-semibold"><?php echo sanitize($product['title']); ?></p>
                        <p class="text-sm text-gray-600"><?php echo formatPrice($product['price']); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold"><?php echo $product['total_sales'] ?? 0; ?> sales</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <a href="<?php echo BASE_URL; ?>/admin/products" class="block mt-4 text-center text-blue-600 hover:text-blue-800">
                View All Products →
            </a>
        </div>
    </div>
</div>

            </main>
        </div>
    </div>
</body>
</html>
