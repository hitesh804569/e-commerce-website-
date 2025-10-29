<?php $pageTitle = 'My Profile - ' . SITE_NAME; ?>
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">My Profile</h1>
    
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="<?php echo BASE_URL; ?>/profile">
                <?php echo getCSRFInput(); ?>
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Full Name</label>
                    <input type="text" name="name" value="<?php echo sanitize($user['name']); ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Email Address</label>
                    <input type="email" value="<?php echo sanitize($user['email']); ?>" disabled class="w-full px-4 py-2 border rounded-lg bg-gray-100 cursor-not-allowed">
                    <p class="text-sm text-gray-500 mt-1">Email cannot be changed</p>
                </div>
                
                <hr class="my-6">
                
                <h3 class="text-lg font-bold mb-4">Change Password</h3>
                <p class="text-sm text-gray-600 mb-4">Leave blank if you don't want to change your password</p>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Current Password</label>
                    <input type="password" name="current_password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">New Password</label>
                    <input type="password" name="new_password" minlength="<?php echo PASSWORD_MIN_LENGTH; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Minimum <?php echo PASSWORD_MIN_LENGTH; ?> characters</p>
                </div>
                
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                    Update Profile
                </button>
            </form>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h3 class="text-lg font-bold mb-4">Account Information</h3>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-600">Member since:</span> <?php echo formatDate($user['created_at']); ?></p>
                <p><span class="text-gray-600">Account Status:</span> 
                    <span class="<?php echo $user['is_blocked'] ? 'text-red-600' : 'text-green-600'; ?> font-semibold">
                        <?php echo $user['is_blocked'] ? 'Blocked' : 'Active'; ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
