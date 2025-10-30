<?php $pageTitle = isset($product) ? 'Edit Product' : 'Add Product'; ?>
<?php require_once APP_PATH . '/views/layouts/admin_header.php'; ?>

<div class="mb-6">
    <a href="<?php echo BASE_URL; ?>/admin/products" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i> Back to Products
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6"><?php echo isset($product) ? 'Edit Product' : 'Add New Product'; ?></h2>
    
    <form method="POST" enctype="multipart/form-data" action="<?php echo isset($product) ? BASE_URL . '/admin/product/edit?id=' . $product['id'] : BASE_URL . '/admin/product/add'; ?>">
        <?php echo getCSRFInput(); ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Product Title *</label>
                <input type="text" name="title" value="<?php echo isset($product) ? sanitize($product['title']) : ''; ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Category</label>
                <select name="category_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">No Category</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo (isset($product) && $product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo sanitize($cat['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Price *</label>
                <input type="number" name="price" step="0.01" value="<?php echo isset($product) ? $product['price'] : ''; ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Description</label>
                <textarea name="description" rows="6" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"><?php echo isset($product) ? sanitize($product['description']) : ''; ?></textarea>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Thumbnail Image</label>
                <?php if (isset($product) && $product['thumbnail']): ?>
                <div class="mb-2">
                    <img src="<?php echo BASE_URL . '/uploads/' . $product['thumbnail']; ?>" alt="Current thumbnail" class="h-32 w-32 object-cover rounded">
                </div>
                <?php endif; ?>
                <input type="file" name="thumbnail" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
                <p class="text-sm text-gray-500 mt-1">JPG, PNG, WebP (Max 5MB)</p>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Product File (Digital Download)</label>
                <input type="file" name="product_file" accept=".zip,.pdf" class="w-full px-4 py-2 border rounded-lg">
                <p class="text-sm text-gray-500 mt-1">ZIP, PDF (Max 100MB)</p>
                <?php if (isset($product)): ?>
                <p class="text-sm text-green-600 mt-1">
                    <i class="fas fa-check-circle"></i> File already uploaded
                </p>
                <?php endif; ?>
            </div>
            
            <div class="md:col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" <?php echo (isset($product) && $product['is_featured']) ? 'checked' : ''; ?> class="mr-2">
                    <span class="font-semibold">Featured Product</span>
                </label>
            </div>
        </div>
        
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                <?php echo isset($product) ? 'Update Product' : 'Create Product'; ?>
            </button>
            <a href="<?php echo BASE_URL; ?>/admin/products" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-300 font-semibold">
                Cancel
            </a>
        </div>
    </form>
</div>

            </main>
        </div>
    </div>
</body>
</html>
