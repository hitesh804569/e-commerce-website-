<?php $pageTitle = 'View User'; ?>
<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="mb-6">
    <a href="<?php echo BASE_URL; ?>/admin/users" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i> Back to Users
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center mb-6">
                <div class="w-24 h-24 bg-blue-600 rounded-full mx-auto flex items-center justify-center text-white text-4xl font-bold mb-4">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <h2 class="text-2xl font-bold"><?php echo sanitize($user['name']); ?></h2>
                <p class="text-gray-600"><?php echo sanitize($user['email']); ?></p>
            </div>
            
            <div class="space-y-3 mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Role:</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'; ?>">
                        <?php echo ucfirst($user['role']); ?>
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Status:</span>
                    <?php if ($user['is_blocked']): ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Blocked</span>
                    <?php else: ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    <?php endif; ?>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Member Since:</span>
                    <span class="font-semibold"><?php echo formatDate($user['created_at']); ?></span>
                </div>
            </div>
            
            <div class="space-y-2">
                <form method="POST" action="<?php echo BASE_URL; ?>/admin/user/role" class="mb-2">
                    <?php echo getCSRFInput(); ?>
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <select name="role" class="w-full px-4 py-2 border rounded-lg mb-2">
                        <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-user-cog mr-2"></i> Change Role
                    </button>
                </form>
                
                <form method="POST" action="<?php echo BASE_URL; ?>/admin/user/block">
                    <?php echo getCSRFInput(); ?>
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">
                        <i class="fas fa-<?php echo $user['is_blocked'] ? 'unlock' : 'ban'; ?> mr-2"></i>
                        <?php echo $user['is_blocked'] ? 'Unblock User' : 'Block User'; ?>
                    </button>
                </form>
                
                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                <form method="POST" action="<?php echo BASE_URL; ?>/admin/user/delete" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    <?php echo getCSRFInput(); ?>
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        <i class="fas fa-trash mr-2"></i> Delete User
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-4">Purchase History</h3>
            
            <?php if (empty($orders)): ?>
                <p class="text-gray-500 text-center py-8">No orders found</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($orders as $order): ?>
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-semibold">Order #<?php echo sanitize($order['order_number']); ?></p>
                                <p class="text-sm text-gray-600"><?php echo formatDateTime($order['created_at']); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-600"><?php echo formatPrice($order['total']); ?></p>
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
                            </div>
                        </div>
                        <p class="text-sm text-gray-600"><?php echo $order['item_count']; ?> item(s)</p>
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
