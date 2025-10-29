<?php
if (!defined('ROOT_PATH')) {
    require_once __DIR__ . '/../../config/config.php';
}

requireAdminLogin();

$currentAdmin = getCurrentAdmin();
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin Panel - ' . SITE_NAME; ?></title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 60px;
        }
        
        body {
            background: #f5f7fa;
        }
        
        .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #dee2e6;
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 2rem;
        }
        
        .admin-sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: #2c3e50;
            color: white;
            overflow-y: auto;
            z-index: 999;
        }
        
        .admin-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
        }
        
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-menu li a i {
            width: 24px;
            margin-right: 0.75rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .admin-sidebar.show {
                transform: translateX(0);
            }
            
            .admin-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="admin-header">
        <button class="btn btn-link d-md-none" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <h5 class="mb-0 fw-bold ms-3">
            <i class="fas fa-shield-alt text-primary"></i> Admin Panel
        </h5>
        <div class="ms-auto d-flex align-items-center gap-3">
            <a href="<?php echo SITE_URL; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-external-link-alt me-1"></i> View Site
            </a>
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" type="button" data-mdb-toggle="dropdown">
                    <i class="fas fa-user-circle"></i> <?php echo sanitize($currentAdmin['name']); ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/admin/settings.php">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/admin/logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="admin-sidebar" id="adminSidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?php echo SITE_URL; ?>/admin/products.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'products') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-box"></i> Products
                </a>
            </li>
            <li>
                <a href="<?php echo SITE_URL; ?>/admin/categories.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'categories') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-folder"></i> Categories
                </a>
            </li>
            <li>
                <a href="<?php echo SITE_URL; ?>/admin/orders.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'orders') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart"></i> Orders
                </a>
            </li>
            <li>
                <a href="<?php echo SITE_URL; ?>/admin/users.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'users') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li>
                <a href="<?php echo SITE_URL; ?>/admin/coupons.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'coupons') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-tag"></i> Coupons
                </a>
            </li>
            <li>
                <a href="<?php echo SITE_URL; ?>/admin/support.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'support') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-headset"></i> Support Tickets
                </a>
            </li>
            <li>
                <a href="<?php echo SITE_URL; ?>/admin/reports.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'reports') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </li>
            <li>
                <a href="<?php echo SITE_URL; ?>/admin/settings.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'settings') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="admin-content">
        <?php
        $flash = getFlash();
        if ($flash):
        ?>
        <div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : $flash['type']; ?> alert-dismissible fade show" role="alert">
            <?php echo sanitize($flash['message']); ?>
            <button type="button" class="btn-close" data-mdb-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <script>
            // Sidebar toggle for mobile
            document.getElementById('sidebarToggle')?.addEventListener('click', function() {
                document.getElementById('adminSidebar').classList.toggle('show');
            });
        </script>
