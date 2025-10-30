<?php $pageTitle = 'Manage Coupons'; ?>
<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold">Coupons</h2>
    <button onclick="document.getElementById('addCouponModal').classList.remove('hidden')" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i> Add Coupon
    </button>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Value</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usage</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expires</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($coupons as $coupon): ?>
            <tr>
                <td class="px-6 py-4 font-mono font-bold">
                    <?php echo sanitize($coupon['code']); ?>
                </td>
                <td class="px-6 py-4 capitalize">
                    <?php echo sanitize($coupon['discount_type']); ?>
                </td>
                <td class="px-6 py-4 font-semibold">
                    <?php if ($coupon['discount_type'] === 'flat'): ?>
                        <?php echo formatPrice($coupon['discount_value']); ?>
                    <?php else: ?>
                        <?php echo $coupon['discount_value']; ?>%
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-sm">
                    <?php echo $coupon['used_count']; ?> / <?php echo $coupon['usage_limit'] ?: '∞'; ?>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    <?php echo $coupon['expires_at'] ? formatDate($coupon['expires_at']) : 'Never'; ?>
                </td>
                <td class="px-6 py-4">
                    <?php if ($coupon['is_active']): ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    <?php else: ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-sm">
                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/coupons" class="inline" onsubmit="return confirm('Are you sure you want to delete this coupon?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $coupon['id']; ?>">
                        <button type="submit" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Coupon Modal -->
<div id="addCouponModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold">Add New Coupon</h3>
            <button onclick="document.getElementById('addCouponModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form method="POST" action="<?php echo BASE_URL; ?>/admin/coupons">
            <?php echo getCSRFInput(); ?>
            <input type="hidden" name="action" value="create">
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Coupon Code *</label>
                <input type="text" name="code" required class="w-full px-4 py-2 border rounded-lg uppercase">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Discount Type *</label>
                <select name="discount_type" required class="w-full px-4 py-2 border rounded-lg">
                    <option value="flat">Flat Amount</option>
                    <option value="percentage">Percentage</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Discount Value *</label>
                <input type="number" name="discount_value" step="0.01" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Minimum Purchase</label>
                <input type="number" name="min_purchase" step="0.01" value="0" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Usage Limit</label>
                <input type="number" name="usage_limit" class="w-full px-4 py-2 border rounded-lg" placeholder="Leave blank for unlimited">
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Expires At</label>
                <input type="datetime-local" name="expires_at" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold">
                Create Coupon
            </button>
        </form>
    </div>
</div>

            </main>
        </div>
    </div>
</body>
</html>
