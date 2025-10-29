<?php $pageTitle = 'Reports'; ?>
<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="mb-6">
    <h2 class="text-2xl font-bold">Sales Reports</h2>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="<?php echo BASE_URL; ?>/admin/reports" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Start Date</label>
            <input type="date" name="start_date" value="<?php echo $startDate; ?>" class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-2">End Date</label>
            <input type="date" name="end_date" value="<?php echo $endDate; ?>" class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
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
            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                <i class="fas fa-chart-bar text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Daily Average</p>
                <p class="text-2xl font-bold text-gray-900">
                    <?php
                    $days = max(1, (strtotime($endDate) - strtotime($startDate)) / 86400);
                    echo formatPrice($totalRevenue / $days);
                    ?>
                </p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                <i class="fas fa-trophy text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Top Products</p>
                <p class="text-2xl font-bold text-gray-900"><?php echo count($topProducts); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold">Revenue by Date</h3>
        </div>
        <div class="p-6">
            <?php if (empty($revenueByDate)): ?>
                <p class="text-gray-500 text-center py-8">No data available for selected period</p>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($revenueByDate as $data): ?>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600"><?php echo formatDate($data['date']); ?></span>
                        <span class="font-bold text-blue-600"><?php echo formatPrice($data['revenue']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold">Best Selling Products</h3>
        </div>
        <div class="p-6">
            <?php if (empty($topProducts)): ?>
                <p class="text-gray-500 text-center py-8">No products found</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($topProducts as $product): ?>
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <p class="font-semibold"><?php echo sanitize($product['title']); ?></p>
                            <p class="text-sm text-gray-600"><?php echo formatPrice($product['price']); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-blue-600"><?php echo $product['total_sales'] ?? 0; ?> sales</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

            </main>
        </div>
    </div>
</body>
</html>
