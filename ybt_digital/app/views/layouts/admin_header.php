<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin - ' . SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-bold"><?php echo SITE_NAME; ?></h2>
                <p class="text-sm text-gray-400">Admin Panel</p>
            </div>
            <nav class="mt-4">
                <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="block px-4 py-3 hover:bg-gray-700 <?php echo strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/products.php" class="block px-4 py-3 hover:bg-gray-700 <?php echo strpos($_SERVER['REQUEST_URI'], 'products') !== false ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-box mr-2"></i> Products
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/orders.php" class="block px-4 py-3 hover:bg-gray-700 <?php echo strpos($_SERVER['REQUEST_URI'], 'orders') !== false ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-shopping-cart mr-2"></i> Orders
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/users.php" class="block px-4 py-3 hover:bg-gray-700 <?php echo strpos($_SERVER['REQUEST_URI'], 'users') !== false ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-users mr-2"></i> Users
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/coupons.php" class="block px-4 py-3 hover:bg-gray-700 <?php echo strpos($_SERVER['REQUEST_URI'], 'coupons') !== false ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-tags mr-2"></i> Coupons
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/reports.php" class="block px-4 py-3 hover:bg-gray-700 <?php echo strpos($_SERVER['REQUEST_URI'], 'reports') !== false ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-chart-bar mr-2"></i> Reports
                </a>
                <a href="<?php echo BASE_URL; ?>/" class="block px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-home mr-2"></i> Back to Site
                </a>
                <a href="<?php echo BASE_URL; ?>/auth/logout.php" class="block px-4 py-3 hover:bg-gray-700 text-red-400">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-md">
                <div class="flex items-center justify-between px-6 py-4">
                    <h1 class="text-2xl font-semibold text-gray-800"><?php echo $pageTitle ?? 'Dashboard'; ?></h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                    </div>
                </div>
            </header>
            
            <!-- Flash Messages -->
            <?php
            $flash = getFlash();
            if ($flash):
            ?>
            <div class="px-6 mt-4">
                <div class="<?php echo $flash['type'] === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700'; ?> border-l-4 p-4 rounded">
                    <?php echo sanitize($flash['message']); ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
