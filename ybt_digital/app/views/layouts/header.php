<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-8">
                    <a href="<?php echo BASE_URL; ?>" class="text-2xl font-bold text-blue-600">
                        <i class="fas fa-shopping-bag"></i> <?php echo SITE_NAME; ?>
                    </a>
                    <div class="hidden md:flex space-x-6">
                        <a href="<?php echo BASE_URL; ?>" class="text-gray-700 hover:text-blue-600">Home</a>
                        <a href="<?php echo BASE_URL; ?>/products" class="text-gray-700 hover:text-blue-600">Products</a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="<?php echo BASE_URL; ?>/cart" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <span class="bg-blue-600 text-white rounded-full px-2 py-1 text-xs"><?php echo count($_SESSION['cart']); ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="text-gray-700 hover:text-blue-600">
                                <i class="fas fa-tachometer-alt"></i> Admin
                            </a>
                        <?php endif; ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
                                <i class="fas fa-user-circle"></i>
                                <span><?php echo sanitize($_SESSION['user_name']); ?></span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="hidden group-hover:block absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2">
                                <a href="<?php echo BASE_URL; ?>/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                                <a href="<?php echo BASE_URL; ?>/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Orders</a>
                                <a href="<?php echo BASE_URL; ?>/logout" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/login" class="text-gray-700 hover:text-blue-600">Login</a>
                        <a href="<?php echo BASE_URL; ?>/register" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php
    $flash = getFlash();
    if ($flash):
    ?>
    <div class="container mx-auto px-4 mt-4">
        <div class="<?php echo $flash['type'] === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700'; ?> border-l-4 p-4 rounded">
            <?php echo sanitize($flash['message']); ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main>
