<?php $pageTitle = 'Checkout - ' . SITE_NAME; ?>
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-xl font-bold mb-4">Payment Information</h3>
                <div class="bg-blue-50 border border-blue-200 rounded p-4 mb-4">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        This is a test environment. Payment integration is ready for <?php echo PAYMENT_PROVIDER === 'stripe' ? 'Stripe' : 'Razorpay'; ?>.
                    </p>
                </div>
                
                <form method="POST" action="<?php echo BASE_URL; ?>/checkout/process">
                    <?php echo getCSRFInput(); ?>
                    
                    <div class="mb-6">
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="<?php echo PAYMENT_PROVIDER; ?>" checked class="mr-3">
                            <i class="fab fa-<?php echo PAYMENT_PROVIDER === 'stripe' ? 'cc-stripe' : 'cc-visa'; ?> text-2xl mr-3"></i>
                            <span class="font-semibold"><?php echo PAYMENT_PROVIDER === 'stripe' ? 'Stripe' : 'Razorpay'; ?> Payment</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-lg hover:bg-blue-700 font-bold text-lg">
                        <i class="fas fa-lock mr-2"></i> Complete Order - <?php echo formatPrice($total); ?>
                    </button>
                </form>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-4">Order Items</h3>
                <?php foreach ($cartItems as $item): ?>
                <div class="flex gap-4 mb-4 pb-4 border-b last:border-b-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 rounded flex items-center justify-center flex-shrink-0">
                        <?php if ($item['thumbnail']): ?>
                            <img src="<?php echo BASE_URL . '/uploads/' . $item['thumbnail']; ?>" alt="<?php echo sanitize($item['title']); ?>" class="w-full h-full object-cover rounded">
                        <?php else: ?>
                            <i class="fas fa-box text-2xl text-gray-300"></i>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold"><?php echo sanitize($item['title']); ?></h4>
                        <p class="text-sm text-gray-600">Qty: <?php echo $item['quantity']; ?></p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-blue-600"><?php echo formatPrice($item['line_total']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div>
            <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-semibold"><?php echo formatPrice($subtotal); ?></span>
                    </div>
                    <?php if ($discount > 0): ?>
                    <div class="flex justify-between text-green-600">
                        <span>Discount (<?php echo sanitize($couponCode); ?>):</span>
                        <span class="font-semibold">-<?php echo formatPrice($discount); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="border-t pt-3">
                        <div class="flex justify-between text-xl font-bold">
                            <span>Total:</span>
                            <span class="text-blue-600"><?php echo formatPrice($total); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded p-4">
                    <h4 class="font-semibold mb-2">Security & Trust</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Secure checkout</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Instant download</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>7-day refund</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
