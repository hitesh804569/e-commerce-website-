<?php 
$pageTitle = 'Register - ' . SITE_NAME; 
// Debug: Check if BASE_URL is defined
if (APP_DEBUG && !defined('BASE_URL')) {
    die('ERROR: BASE_URL is not defined!');
}
?>
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<!-- Debug info (only in development) -->
<?php if (APP_DEBUG): ?>
<!-- Form will submit to: <?php echo BASE_URL . '/register'; ?> -->
<?php endif; ?>

<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-bold text-center mb-8">Create Account</h2>
            
            <form id="registerForm" method="POST" action="<?php echo BASE_URL . '/register'; ?>" onsubmit="return validateForm(event)">
                <?php echo getCSRFInput(); ?>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="name" name="name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required minlength="<?php echo PASSWORD_MIN_LENGTH; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Minimum <?php echo PASSWORD_MIN_LENGTH; ?> characters</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <p id="password-error" class="text-sm text-red-600 mt-1 hidden">Passwords do not match</p>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold">
                    Create Account
                </button>
                
                <div class="mt-6 text-center text-gray-600">
                    Already have an account? 
                    <a href="<?php echo BASE_URL; ?>/login" class="text-blue-600 hover:underline font-semibold">Login</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function validateForm(event) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const errorElement = document.getElementById('password-error');
    const confirmInput = document.getElementById('confirm_password');
    
    if (password !== confirmPassword) {
        event.preventDefault();
        errorElement.classList.remove('hidden');
        confirmInput.classList.add('border-red-500');
        confirmInput.classList.add('focus:border-red-500');
        confirmInput.focus();
        
        // Show alert for better UX
        alert('Passwords do not match. Please make sure both passwords are identical.');
        return false;
    }
    
    errorElement.classList.add('hidden');
    confirmInput.classList.remove('border-red-500');
    confirmInput.classList.remove('focus:border-red-500');
    return true;
}

// Real-time password match checking
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const errorElement = document.getElementById('password-error');
    const form = document.getElementById('registerForm');
    
    // Debug: Log form action
    console.log('Form action URL:', form.action);
    
    confirmPassword.addEventListener('input', function() {
        if (confirmPassword.value && password.value !== confirmPassword.value) {
            errorElement.classList.remove('hidden');
            confirmPassword.classList.add('border-red-500');
        } else {
            errorElement.classList.add('hidden');
            confirmPassword.classList.remove('border-red-500');
        }
    });
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
