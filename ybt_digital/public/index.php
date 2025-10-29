<?php
// Front Controller

session_start();

// Load configuration
require_once __DIR__ . '/../app/config/config.php';

// Load core files
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/Helpers.php';

// Load all models
require_once APP_PATH . '/models/User.php';
require_once APP_PATH . '/models/Product.php';
require_once APP_PATH . '/models/ProductFile.php';
require_once APP_PATH . '/models/Category.php';
require_once APP_PATH . '/models/Order.php';
require_once APP_PATH . '/models/OrderItem.php';
require_once APP_PATH . '/models/Coupon.php';
require_once APP_PATH . '/models/Download.php';
require_once APP_PATH . '/models/AdminAction.php';

// Load all controllers
require_once APP_PATH . '/controllers/AuthController.php';
require_once APP_PATH . '/controllers/HomeController.php';
require_once APP_PATH . '/controllers/ProductController.php';
require_once APP_PATH . '/controllers/CartController.php';
require_once APP_PATH . '/controllers/CheckoutController.php';
require_once APP_PATH . '/controllers/OrderController.php';
require_once APP_PATH . '/controllers/DownloadController.php';
require_once APP_PATH . '/controllers/AdminController.php';
require_once APP_PATH . '/controllers/UserController.php';

// Simple routing
$request = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$path = str_replace($scriptName, '', $request);
$path = strtok($path, '?');
$path = trim($path, '/');

// Route handling
switch ($path) {
    // Home
    case '':
    case 'index.php':
        $controller = new HomeController();
        $controller->index();
        break;
    
    // Auth routes
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;
    
    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;
    
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    
    case 'forgot-password':
        $controller = new AuthController();
        $controller->forgotPassword();
        break;
    
    case 'reset-password':
        $controller = new AuthController();
        $controller->resetPassword();
        break;
    
    // Product routes
    case 'products':
        $controller = new ProductController();
        $controller->list();
        break;
    
    case 'product':
        $controller = new ProductController();
        $controller->detail();
        break;
    
    // Cart routes
    case 'cart':
        $controller = new CartController();
        $controller->index();
        break;
    
    case 'cart/add':
        $controller = new CartController();
        $controller->add();
        break;
    
    case 'cart/update':
        $controller = new CartController();
        $controller->update();
        break;
    
    case 'cart/remove':
        $controller = new CartController();
        $controller->remove();
        break;
    
    // Checkout routes
    case 'checkout':
        $controller = new CheckoutController();
        $controller->index();
        break;
    
    case 'checkout/process':
        $controller = new CheckoutController();
        $controller->process();
        break;
    
    // Order routes
    case 'orders':
        $controller = new OrderController();
        $controller->index();
        break;
    
    case 'order':
        $controller = new OrderController();
        $controller->detail();
        break;
    
    // Profile routes
    case 'profile':
        requireLogin();
        $controller = new AuthController();
        $controller->profile();
        break;
    
    // Admin routes
    case 'admin':
    case 'admin/dashboard':
        requireAdmin();
        $controller = new AdminController();
        $controller->dashboard();
        break;
    
    case 'admin/products':
        requireAdmin();
        $controller = new AdminController();
        $controller->products();
        break;
    
    case 'admin/product/add':
        requireAdmin();
        $controller = new AdminController();
        $controller->addProduct();
        break;
    
    case 'admin/product/edit':
        requireAdmin();
        $controller = new AdminController();
        $controller->editProduct();
        break;
    
    case 'admin/product/delete':
        requireAdmin();
        $controller = new AdminController();
        $controller->deleteProduct();
        break;
    
    case 'admin/orders':
        requireAdmin();
        $controller = new AdminController();
        $controller->orders();
        break;
    
    case 'admin/coupons':
        requireAdmin();
        $controller = new AdminController();
        $controller->coupons();
        break;
    
    case 'admin/users':
        requireAdmin();
        $controller = new UserController();
        $controller->index();
        break;
    
    case 'admin/user/view':
        requireAdmin();
        $controller = new UserController();
        $controller->view();
        break;
    
    case 'admin/user/block':
        requireAdmin();
        $controller = new UserController();
        $controller->toggleBlock();
        break;
    
    case 'admin/user/role':
        requireAdmin();
        $controller = new UserController();
        $controller->changeRole();
        break;
    
    case 'admin/user/delete':
        requireAdmin();
        $controller = new UserController();
        $controller->delete();
        break;
    
    case 'admin/reports':
        requireAdmin();
        $controller = new AdminController();
        $controller->reports();
        break;
    
    // 404 Not Found
    default:
        http_response_code(404);
        $requestedPath = htmlspecialchars($path);
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Page Not Found</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-100">
            <div class="min-h-screen flex items-center justify-center">
                <div class="text-center">
                    <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
                    <p class="text-xl text-gray-600 mb-4">Page Not Found</p>
                    ' . (APP_DEBUG ? '<p class="text-sm text-gray-500 mb-8">Requested path: <code class="bg-gray-200 px-2 py-1 rounded">' . $requestedPath . '</code></p>' : '<p class="mb-8"></p>') . '
                    <a href="' . BASE_URL . '" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Go Home</a>
                </div>
            </div>
        </body>
        </html>';
        break;
}
