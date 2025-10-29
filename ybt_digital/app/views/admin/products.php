<?php $pageTitle = 'Manage Products'; ?>
<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold">Products</h2>
    <a href="<?php echo BASE_URL; ?>/admin/product/add" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i> Add Product
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Featured</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($products as $product): ?>
            <tr>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="h-10 w-10 flex-shrink-0">
                            <?php if ($product['thumbnail']): ?>
                                <img src="<?php echo BASE_URL . '/uploads/' . $product['thumbnail']; ?>" alt="" class="h-10 w-10 rounded object-cover">
                            <?php else: ?>
                                <div class="h-10 w-10 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-box text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium text-gray-900"><?php echo sanitize($product['title']); ?></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    <?php echo $product['category_name'] ? sanitize($product['category_name']) : '-'; ?>
                </td>
                <td class="px-6 py-4 font-semibold">
                    <?php echo formatPrice($product['price']); ?>
                </td>
                <td class="px-6 py-4">
                    <?php if ($product['is_featured']): ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Featured
                        </span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    <?php echo formatDate($product['created_at']); ?>
                </td>
                <td class="px-6 py-4 text-sm">
                    <a href="<?php echo BASE_URL; ?>/admin/product/edit?id=<?php echo $product['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/product/delete" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
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

            </main>
        </div>
    </div>
</body>
</html>
