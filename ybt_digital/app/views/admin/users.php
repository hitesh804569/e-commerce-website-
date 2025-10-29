<?php $pageTitle = 'Manage Users'; ?>
<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="mb-6">
    <h2 class="text-2xl font-bold">Users</h2>
</div>

<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="<?php echo BASE_URL; ?>/admin/users" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <input type="text" name="search" value="<?php echo sanitize($search); ?>" placeholder="Search by name or email..." class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div>
            <select name="role" class="w-full px-4 py-2 border rounded-lg">
                <option value="">All Roles</option>
                <option value="user" <?php echo $role === 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>
        <div>
            <select name="blocked" class="w-full px-4 py-2 border rounded-lg">
                <option value="">All Status</option>
                <option value="0" <?php echo $blocked === '0' ? 'selected' : ''; ?>>Active</option>
                <option value="1" <?php echo $blocked === '1' ? 'selected' : ''; ?>>Blocked</option>
            </select>
        </div>
        <div>
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-search mr-2"></i> Search
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($users as $user): ?>
            <tr>
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900"><?php echo sanitize($user['name']); ?></div>
                    <div class="text-sm text-gray-500"><?php echo sanitize($user['email']); ?></div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'; ?>">
                        <?php echo ucfirst($user['role']); ?>
                    </span>
                </td>
                <td class="px-6 py-4">
                    <?php if ($user['is_blocked']): ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Blocked</span>
                    <?php else: ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    <?php echo formatDate($user['created_at']); ?>
                </td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex space-x-2">
                        <a href="<?php echo BASE_URL; ?>/admin/user/view?id=<?php echo $user['id']; ?>" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        <form method="POST" action="<?php echo BASE_URL; ?>/admin/user/block" class="inline">
                            <?php echo getCSRFInput(); ?>
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="<?php echo $user['is_blocked'] ? 'text-green-600 hover:text-green-900' : 'text-yellow-600 hover:text-yellow-900'; ?>" title="<?php echo $user['is_blocked'] ? 'Unblock' : 'Block'; ?>">
                                <i class="fas fa-<?php echo $user['is_blocked'] ? 'unlock' : 'ban'; ?>"></i>
                            </button>
                        </form>
                        
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <form method="POST" action="<?php echo BASE_URL; ?>/admin/user/delete" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <?php echo getCSRFInput(); ?>
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
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
        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo $role; ?>&blocked=<?php echo $blocked; ?>" class="px-4 py-2 bg-white border rounded hover:bg-gray-50">
            Previous
        </a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo $role; ?>&blocked=<?php echo $blocked; ?>" class="px-4 py-2 <?php echo $i == $page ? 'bg-blue-600 text-white' : 'bg-white border hover:bg-gray-50'; ?> rounded">
            <?php echo $i; ?>
        </a>
        <?php endfor; ?>
        
        <?php if ($page < $totalPages): ?>
        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo $role; ?>&blocked=<?php echo $blocked; ?>" class="px-4 py-2 bg-white border rounded hover:bg-gray-50">
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
