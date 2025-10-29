<?php $pageTitle = sanitize($product['title']) . ' - ' . SITE_NAME; ?>
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div>
            <div class="bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg p-8 mb-4">
                <?php if ($product['thumbnail']): ?>
                    <img src="<?php echo BASE_URL . '/uploads/' . $product['thumbnail']; ?>" alt="<?php echo sanitize($product['title']); ?>" class="w-full rounded-lg">
                <?php else: ?>
                    <div class="flex items-center justify-center h-96">
                        <i class="fas fa-box-open text-9xl text-gray-300"></i>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($screenshots)): ?>
            <div class="grid grid-cols-4 gap-2">
                <?php foreach ($screenshots as $screenshot): ?>
                <img src="<?php echo BASE_URL . '/uploads/' . $screenshot['image_path']; ?>" alt="Screenshot" class="w-full h-20 object-cover rounded">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Product Info -->
        <div>
            <?php if ($product['category_name']): ?>
            <span class="inline-block bg-blue-100 text-blue-600 px-3 py-1 rounded text-sm mb-3">
                <?php echo sanitize($product['category_name']); ?>
            </span>
            <?php endif; ?>
            
            <h1 class="text-4xl font-bold mb-4"><?php echo sanitize($product['title']); ?></h1>
            
            <div class="mb-6">
                <span class="text-4xl font-bold text-blue-600"><?php echo formatPrice($product['price']); ?></span>
            </div>
            
            <div class="mb-6">
                <h3 class="font-bold text-lg mb-2">Description</h3>
                <p class="text-gray-700 whitespace-pre-line"><?php echo sanitize($product['description']); ?></p>
            </div>
            
            <form method="POST" action="<?php echo BASE_URL; ?>/cart/add" class="mb-6">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-lg hover:bg-blue-700 font-bold text-lg">
                    <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                </button>
            </form>
            
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="font-bold text-lg mb-4">Product Features</h3>
                <ul class="space-y-2">
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <span>Instant download after purchase</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <span>Lifetime access</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <span>24/7 customer support</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <span>7-day money-back guarantee</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
    <div class="mt-16">
        <h2 class="text-2xl font-bold mb-6">Related Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($relatedProducts as $related): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                <div class="h-48 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                    <?php if ($related['thumbnail']): ?>
                        <img src="<?php echo BASE_URL . '/uploads/' . $related['thumbnail']; ?>" alt="<?php echo sanitize($related['title']); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i class="fas fa-box-open text-6xl text-gray-300"></i>
                    <?php endif; ?>
                </div>
                <div class="p-4">
                    <h3 class="font-bold mb-2">
                        <a href="<?php echo BASE_URL; ?>/product?id=<?php echo $related['id']; ?>" class="hover:text-blue-600">
                            <?php echo sanitize($related['title']); ?>
                        </a>
                    </h3>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-blue-600"><?php echo formatPrice($related['price']); ?></span>
                        <a href="<?php echo BASE_URL; ?>/product?id=<?php echo $related['id']; ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            View
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
