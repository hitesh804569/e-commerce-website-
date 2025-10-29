<?php $pageTitle = 'Manage Orders'; ?>
<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="mb-6">
    <h2 class="text-2xl font-bold">Orders</h2>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Provider ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($orders as $order): ?>
            <tr>
                <td class="px-6 py-4 font-mono text-sm">
                    <?php echo sanitize($order['order_number']); ?>
                </td>
                <td class="px-6 py-4">
                    <div class="font-medium"><?php echo sanitize($order['user_name']); ?></div>
                    <div class="text-sm text-gray-500"><?php echo sanitize($order['user_email']); ?></div>
                </td>
                <td class="px-6 py-4 font-bold">
                    <?php echo formatPrice($order['total']); ?>
                    <?php if ($order['is_refunded']): ?>
                    <span class="block text-xs text-red-600">Refunded</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4">
                    <?php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'failed' => 'bg-red-100 text-red-800',
                        'refunded' => 'bg-gray-100 text-gray-800'
                    ];
                    $statusClass = $statusColors[$order['payment_status']] ?? 'bg-gray-100 text-gray-800';
                    ?>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $statusClass; ?>">
                        <?php echo ucfirst($order['payment_status']); ?>
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    <?php echo $order['payment_provider_id'] ? sanitize($order['payment_provider_id']) : '-'; ?>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    <?php echo formatDate($order['created_at']); ?>
                </td>
                <td class="px-6 py-4 text-sm">
                    <a href="<?php echo BASE_URL; ?>/order?id=<?php echo $order['id']; ?>" class="text-blue-600 hover:text-blue-900">
                        <i class="fas fa-eye"></i> View
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPages > 1): ?>
<div class="mt-6 flex justify-center">
    <div class="flex space-x-2">
        <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>" class="px-4 py-2 bg-white border rounded hover:bg-gray-50">
            Previous
        </a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?php echo $i; ?>" class="px-4 py-2 <?php echo $i == $page ? 'bg-blue-600 text-white' : 'bg-white border hover:bg-gray-50'; ?> rounded">
            <?php echo $i; ?>
        </a>
        <?php endfor; ?>
        
        <?php if ($page < $totalPages): ?>
        <a href="?page=<?php echo $page + 1; ?>" class="px-4 py-2 bg-white border rounded hover:bg-gray-50">
            Next
        </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

            </main>
        </div>
    </div>
</body>
</html>
