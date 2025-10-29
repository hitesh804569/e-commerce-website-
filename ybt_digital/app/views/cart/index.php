<?php $pageTitle = 'Shopping Cart - ' . SITE_NAME; ?>
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>
    
    <?php if (empty($cartItems)): ?>
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Your cart is empty</h3>
            <p class="text-gray-500 mb-6">Start shopping to add items to your cart</p>
            <a href="<?php echo BASE_URL; ?>/products" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 inline-block">
                Browse Products
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <?php foreach ($cartItems as $item): ?>
                    <div class="p-6 border-b last:border-b-0">
                        <div class="flex gap-4">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded flex items-center justify-center flex-shrink-0">
                                <?php if ($item['thumbnail']): ?>
                                    <img src="<?php echo BASE_URL . '/uploads/' . $item['thumbnail']; ?>" alt="<?php echo sanitize($item['title']); ?>" class="w-full h-full object-cover rounded">
                                <?php else: ?>
                                    <i class="fas fa-box text-3xl text-gray-300"></i>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex-1">
                                <h3 class="font-bold text-lg mb-2">
                                    <a href="<?php echo BASE_URL; ?>/product?id=<?php echo $item['id']; ?>" class="hover:text-blue-600">
                                        <?php echo sanitize($item['title']); ?>
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-2"><?php echo truncate($item['description'], 100); ?></p>
                                <p class="text-2xl font-bold text-blue-600"><?php echo formatPrice($item['price']); ?></p>
                            </div>
                            
                            <div class="flex flex-col items-end justify-between">
                                <form method="POST" action="<?php echo BASE_URL; ?>/cart/remove">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="<?php echo BASE_URL; ?>/cart/update" class="flex items-center gap-2">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="w-16 px-2 py-1 border rounded text-center">
                                        <button type="submit" class="text-blue-600 hover:text-blue-700 text-sm">
                                            Update
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div>
                <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                    <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold"><?php echo formatPrice($subtotal); ?></span>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span class="text-blue-600"><?php echo formatPrice($subtotal); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <a href="<?php echo BASE_URL; ?>/checkout" class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 font-bold mb-3">
                        Proceed to Checkout
                    </a>
                    
                    <a href="<?php echo BASE_URL; ?>/products" class="block w-full bg-gray-200 text-gray-700 text-center py-3 rounded-lg hover:bg-gray-300">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
