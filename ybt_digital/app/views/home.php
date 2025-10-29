<?php $pageTitle = 'Home - ' . SITE_NAME; ?>
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl">
            <h1 class="text-5xl font-bold mb-6">Premium Digital Products</h1>
            <p class="text-xl mb-8">Discover thousands of high-quality digital products. Download instantly after purchase.</p>
            <div class="flex space-x-4">
                <a href="<?php echo BASE_URL; ?>/products" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100">
                    Browse Products
                </a>
                <?php if (!isLoggedIn()): ?>
                <a href="<?php echo BASE_URL; ?>/register" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600">
                    Get Started
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-download text-2xl text-blue-600"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Instant Download</h3>
                <p class="text-gray-600">Download immediately after purchase</p>
            </div>
            <div class="text-center">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-2xl text-green-600"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Secure Payment</h3>
                <p class="text-gray-600">100% secure payment gateway</p>
            </div>
            <div class="text-center">
                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-2xl text-purple-600"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">24/7 Support</h3>
                <p class="text-gray-600">Always here to help you</p>
            </div>
            <div class="text-center">
                <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-sync text-2xl text-yellow-600"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Easy Refunds</h3>
                <p class="text-gray-600">7-day money-back guarantee</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<?php if (!empty($featuredProducts)): ?>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Featured Products</h2>
            <p class="text-gray-600">Check out our top-selling digital products</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($featuredProducts as $product): ?>
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
                    <p class="text-gray-600 text-sm mb-4"><?php echo truncate($product['description'], 80); ?></p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-blue-600"><?php echo formatPrice($product['price']); ?></span>
                        <a href="<?php echo BASE_URL; ?>/product?id=<?php echo $product['id']; ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            View
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="<?php echo BASE_URL; ?>/products" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700">
                View All Products
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Categories Section -->
<?php if (!empty($categories)): ?>
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Browse by Category</h2>
            <p class="text-gray-600">Find the perfect digital product for your needs</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <?php foreach ($categories as $category): ?>
            <a href="<?php echo BASE_URL; ?>/products?category=<?php echo $category['id']; ?>" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition text-center">
                <i class="fas fa-folder text-4xl text-blue-600 mb-3"></i>
                <h3 class="font-semibold"><?php echo sanitize($category['name']); ?></h3>
                <p class="text-sm text-gray-500"><?php echo $category['product_count']; ?> products</p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
        <p class="text-xl mb-8">Join thousands of satisfied customers today!</p>
        <?php if (!isLoggedIn()): ?>
        <a href="<?php echo BASE_URL; ?>/register" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block">
            Create Free Account
        </a>
        <?php else: ?>
        <a href="<?php echo BASE_URL; ?>/products" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block">
            Browse Products
        </a>
        <?php endif; ?>
    </div>
</section>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
