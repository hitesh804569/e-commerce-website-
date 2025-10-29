<?php
if (!defined('ROOT_PATH')) {
    require_once __DIR__ . '/../config/config.php';
}

$currentUser = isLoggedIn() ? getCurrentUser() : null;
$settings = getSettings();
$cartCount = count(getCart());
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? SITE_NAME; ?></title>
    
    <!-- MDBootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    
    <style>
        :root {
            --primary-color: #1266f1;
            --secondary-color: #b23cfd;
            --success-color: #00b74a;
            --danger-color: #f93154;
            --warning-color: #ffa900;
            --info-color: #39c0ed;
            --light-bg: #f8f9fa;
            --dark-bg: #1a1a1a;
            --card-bg: #ffffff;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --border-color: #dee2e6;
        }
        
        [data-theme="dark"] {
            --light-bg: #121212;
            --dark-bg: #1e1e1e;
            --card-bg: #2a2a2a;
            --text-primary: #e0e0e0;
            --text-secondary: #a0a0a0;
            --border-color: #3a3a3a;
        }
        
        body {
            background-color: var(--light-bg);
            color: var(--text-primary);
            transition: background-color 0.3s, color 0.3s;
        }
        
        .card {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        /* Desktop Navbar */
        .desktop-nav {
            display: block;
        }
        
        .mobile-nav {
            display: none;
        }
        
        /* Mobile Bottom Navigation */
        @media (max-width: 768px) {
            .desktop-nav {
                display: none;
            }
            
            .mobile-nav {
                display: block;
            }
            
            .bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: var(--card-bg);
                border-top: 1px solid var(--border-color);
                z-index: 1000;
                padding: 8px 0;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            }
            
            .bottom-nav-item {
                flex: 1;
                text-align: center;
                padding: 8px;
                color: var(--text-secondary);
                text-decoration: none;
                display: flex;
                flex-direction: column;
                align-items: center;
                font-size: 0.75rem;
                transition: color 0.3s;
            }
            
            .bottom-nav-item i {
                font-size: 1.5rem;
                margin-bottom: 4px;
            }
            
            .bottom-nav-item.active,
            .bottom-nav-item:hover {
                color: var(--primary-color);
            }
            
            .badge-notification {
                position: absolute;
                top: 0;
                right: 25%;
                background: var(--danger-color);
                color: white;
                border-radius: 10px;
                padding: 2px 6px;
                font-size: 0.65rem;
            }
            
            body {
                padding-bottom: 70px;
            }
        }
        
        /* Mobile AppBar */
        .mobile-appbar {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .theme-toggle {
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: background 0.3s;
        }
        
        .theme-toggle:hover {
            background: var(--light-bg);
        }
    </style>
</head>
<body>
    <!-- Desktop Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm desktop-nav">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?php echo SITE_URL; ?>">
                <i class="fas fa-shopping-bag text-primary"></i> <?php echo $settings['site_name'] ?? SITE_NAME; ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/products">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/pages/contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/pages/faq.php">FAQ</a>
                    </li>
                    
                    <li class="nav-item">
                        <span class="theme-toggle" onclick="toggleTheme()">
                            <i class="fas fa-moon" id="theme-icon"></i>
                        </span>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="<?php echo SITE_URL; ?>/cart">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if ($cartCount > 0): ?>
                                <span class="badge rounded-pill badge-notification bg-danger"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    
                    <?php if ($currentUser): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-mdb-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> <?php echo sanitize($currentUser['name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/user/dashboard.php">Dashboard</a></li>
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/user/orders.php">My Orders</a></li>
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/user/profile.php">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/auth/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm" href="<?php echo SITE_URL; ?>/auth/login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Mobile AppBar -->
    <div class="mobile-appbar mobile-nav">
        <div>
            <a href="<?php echo SITE_URL; ?>" class="text-decoration-none">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-shopping-bag text-primary"></i> <?php echo $settings['site_name'] ?? SITE_NAME; ?>
                </h5>
            </a>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="theme-toggle" onclick="toggleTheme()">
                <i class="fas fa-moon" id="theme-icon-mobile"></i>
            </span>
            <?php if ($currentUser): ?>
                <a href="<?php echo SITE_URL; ?>/user/profile.php" class="text-decoration-none">
                    <i class="fas fa-user-circle fa-lg"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Flash Messages -->
    <?php
    $flash = getFlash();
    if ($flash):
    ?>
    <div class="container mt-3">
        <div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : $flash['type']; ?> alert-dismissible fade show" role="alert">
            <?php echo sanitize($flash['message']); ?>
            <button type="button" class="btn-close" data-mdb-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>
    
    <script>
        // Theme toggle functionality
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            updateThemeIcon(newTheme);
        }
        
        function updateThemeIcon(theme) {
            const icons = document.querySelectorAll('#theme-icon, #theme-icon-mobile');
            icons.forEach(icon => {
                icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            });
        }
        
        // Load saved theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
        });
    </script>
