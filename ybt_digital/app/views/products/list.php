<?php $pageTitle = 'Products - ' . SITE_NAME; ?>
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar -->
        <aside class="w-full md:w-64">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="font-bold text-lg mb-4">Search</h3>
                <form method="GET" action="<?php echo BASE_URL; ?>/products">
                    <input type="text" name="search" value="<?php echo sanitize($search); ?>" placeholder="Search products..." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <button type="submit" class="w-full mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Search
                    </button>
                </form>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4">Categories</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="<?php echo BASE_URL; ?>/products" class="<?php echo !$categoryId ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600'; ?>">
                            All Products
                        </a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/products?category=<?php echo $cat['id']; ?>" class="<?php echo $categoryId == $cat['id'] ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600'; ?>">
                            <?php echo sanitize($cat['name']); ?> (<?php echo $cat['product_count']; ?>)
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>
        
        <!-- Products Grid -->
        <main class="flex-1">
            <div class="mb-6">
                <h1 class="text-3xl font-bold mb-2">
                    <?php echo $selectedCategory ? sanitize($selectedCategory['name']) : 'All Products'; ?>
                </h1>
                <p class="text-gray-600"><?php echo $totalProducts; ?> products found</p>
            </div>
            
            <?php if (empty($products)): ?>
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">No products found</h3>
                    <p class="text-gray-500">Try adjusting your search or filters</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($products as $product): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                        <div class="h-48 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                            <?php if ($product['thumbnail']): ?>
                                <img src="<?php echo BASE_URL . '/uploads/' . $product['thumbnail']; ?>" alt="<?php echo sanitize($product['title']); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <i class="fas fa-box-open text-6xl text-gray-300"></i>
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <?php if ($product['category_name']): ?>
                            <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded"><?php echo sanitize($product['category_name']); ?></span>
                            <?php endif; ?>
                            <h3 class="font-bold text-lg mt-2 mb-2">
                                <a href="<?php echo BASE_URL; ?>/product?id=<?php echo $product['id']; ?>" class="hover:text-blue-600">
                                    <?php echo sanitize($product['title']); ?>
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-4"><?php echo truncate($product['description'], 100); ?></p>
                            <div class="flex justify-between items-center">
                                <span class="text-2xl font-bold text-blue-600"><?php echo formatPrice($product['price']); ?></span>
                                <a href="<?php echo BASE_URL; ?>/product?id=<?php echo $product['id']; ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="mt-8 flex justify-center">
                    <div class="flex space-x-2">
                        <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?><?php echo $categoryId ? '&category=' . $categoryId : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="px-4 py-2 bg-white border rounded hover:bg-gray-50">
                            Previous
                        </a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?><?php echo $categoryId ? '&category=' . $categoryId : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="px-4 py-2 <?php echo $i == $page ? 'bg-blue-600 text-white' : 'bg-white border hover:bg-gray-50'; ?> rounded">
                            <?php echo $i; ?>
                        </a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?><?php echo $categoryId ? '&category=' . $categoryId : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="px-4 py-2 bg-white border rounded hover:bg-gray-50">
                            Next
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
