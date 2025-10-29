<?php $pageTitle = 'Forgot Password - ' . SITE_NAME; ?>
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-bold text-center mb-4">Forgot Password</h2>
            <p class="text-gray-600 text-center mb-8">Enter your email to receive a password reset link</p>
            
            <form method="POST" action="<?php echo BASE_URL; ?>/forgot-password">
                <?php echo getCSRFInput(); ?>
                
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold">
                    Send Reset Link
                </button>
                
                <div class="mt-6 text-center text-gray-600">
                    Remember your password? 
                    <a href="<?php echo BASE_URL; ?>/login" class="text-blue-600 hover:underline font-semibold">Login</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
