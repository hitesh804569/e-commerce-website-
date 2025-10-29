<?php
// Helper Functions

// Sanitize input
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Generate random token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Get current user
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    $query = "SELECT id, name, email, phone, status FROM users WHERE id = ?";
    return fetchOne($query, [$_SESSION['user_id']], 'i');
}

// Get current admin
function getCurrentAdmin() {
    if (!isAdminLoggedIn()) return null;
    
    $query = "SELECT id, name, email, role, status FROM admins WHERE id = ?";
    return fetchOne($query, [$_SESSION['admin_id']], 'i');
}

// Redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Set flash message
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

// Get and clear flash message
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Format price
function formatPrice($price) {
    $settings = getSettings();
    $symbol = $settings['currency_symbol'] ?? '$';
    return $symbol . number_format($price, 2);
}

// Format date
function formatDate($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

// Get settings
function getSettings() {
    static $settings = null;
    
    if ($settings === null) {
        $query = "SELECT setting_key, setting_value FROM settings";
        $results = fetchAll($query);
        
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    
    return $settings;
}

// Update setting
function updateSetting($key, $value) {
    $query = "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
              ON DUPLICATE KEY UPDATE setting_value = ?";
    return executeQuery($query, [$key, $value, $value], 'sss');
}

// Generate order number
function generateOrderNumber() {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

// Generate slug
function generateSlug($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    
    if (empty($text)) {
        return 'n-a';
    }
    
    return $text;
}

// Upload file
function uploadFile($file, $destination, $allowedTypes = []) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Upload error occurred'];
    }
    
    $fileSize = $file['size'];
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    if (!empty($allowedTypes) && !in_array($fileExt, $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    if ($fileSize > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File size exceeds limit'];
    }
    
    $newFileName = uniqid() . '_' . time() . '.' . $fileExt;
    $uploadPath = $destination . '/' . $newFileName;
    
    if (move_uploaded_file($fileTmp, $uploadPath)) {
        return ['success' => true, 'filename' => $newFileName, 'path' => $uploadPath];
    }
    
    return ['success' => false, 'message' => 'Failed to upload file'];
}

// Delete file
function deleteFile($path) {
    if (file_exists($path)) {
        return unlink($path);
    }
    return false;
}

// Get file size in readable format
function getFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    
    return round($bytes, 2) . ' ' . $units[$pow];
}

// Send email (basic implementation - can be enhanced with PHPMailer)
function sendEmail($to, $subject, $message) {
    $settings = getSettings();
    $fromEmail = $settings['smtp_from_email'] ?? ADMIN_EMAIL;
    $fromName = $settings['smtp_from_name'] ?? SITE_NAME;
    
    $headers = "From: $fromName <$fromEmail>\r\n";
    $headers .= "Reply-To: $fromEmail\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $message, $headers);
}

// Calculate tax
function calculateTax($amount) {
    $settings = getSettings();
    $taxPercentage = floatval($settings['tax_percentage'] ?? 0);
    return ($amount * $taxPercentage) / 100;
}

// Apply coupon
function applyCoupon($code, $subtotal) {
    $query = "SELECT * FROM coupons WHERE code = ? AND status = 'active' 
              AND (expiry_date IS NULL OR expiry_date >= CURDATE()) 
              AND (usage_limit IS NULL OR used_count < usage_limit)";
    $coupon = fetchOne($query, [$code], 's');
    
    if (!$coupon) {
        return ['success' => false, 'message' => 'Invalid or expired coupon'];
    }
    
    if ($subtotal < $coupon['min_purchase']) {
        return ['success' => false, 'message' => 'Minimum purchase amount not met'];
    }
    
    $discount = 0;
    if ($coupon['type'] === 'flat') {
        $discount = $coupon['value'];
    } else {
        $discount = ($subtotal * $coupon['value']) / 100;
        if ($coupon['max_discount'] && $discount > $coupon['max_discount']) {
            $discount = $coupon['max_discount'];
        }
    }
    
    return ['success' => true, 'discount' => $discount, 'coupon' => $coupon];
}

// Increment coupon usage
function incrementCouponUsage($code) {
    $query = "UPDATE coupons SET used_count = used_count + 1 WHERE code = ?";
    return executeQuery($query, [$code], 's');
}

// Check if user owns product
function userOwnsProduct($userId, $productId) {
    $query = "SELECT COUNT(*) as count FROM order_items oi 
              JOIN orders o ON oi.order_id = o.id 
              WHERE o.user_id = ? AND oi.product_id = ? AND o.payment_status = 'completed'";
    $result = fetchOne($query, [$userId, $productId], 'ii');
    return $result['count'] > 0;
}

// Get cart from session
function getCart() {
    return $_SESSION['cart'] ?? [];
}

// Add to cart
function addToCart($productId) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (!in_array($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $productId;
        return true;
    }
    
    return false;
}

// Remove from cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array_diff($_SESSION['cart'], [$productId]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Clear cart
function clearCart() {
    unset($_SESSION['cart']);
}

// Get cart items with details
function getCartItems() {
    $cart = getCart();
    if (empty($cart)) return [];
    
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $query = "SELECT * FROM products WHERE id IN ($placeholders) AND status = 'active'";
    $types = str_repeat('i', count($cart));
    
    return fetchAll($query, $cart, $types);
}

// Calculate cart total
function getCartTotal() {
    $items = getCartItems();
    $total = 0;
    
    foreach ($items as $item) {
        $price = $item['discount_price'] ?? $item['price'];
        $total += $price;
    }
    
    return $total;
}

// Pagination helper
function paginate($totalItems, $currentPage, $itemsPerPage) {
    $totalPages = ceil($totalItems / $itemsPerPage);
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $itemsPerPage;
    
    return [
        'total_items' => $totalItems,
        'total_pages' => $totalPages,
        'current_page' => $currentPage,
        'items_per_page' => $itemsPerPage,
        'offset' => $offset
    ];
}

// Require login
function requireLogin() {
    if (!isLoggedIn()) {
        setFlash('error', 'Please login to continue');
        redirect(SITE_URL . '/auth/login.php');
    }
}

// Require admin login
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        setFlash('error', 'Please login to continue');
        redirect(SITE_URL . '/admin/login.php');
    }
}

// Check admin permission
function hasAdminPermission($permission = 'editor') {
    if (!isAdminLoggedIn()) return false;
    
    $admin = getCurrentAdmin();
    if ($permission === 'super_admin') {
        return $admin['role'] === 'super_admin';
    }
    
    return true;
}

// Time ago function
function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;
    
    $periods = [
        'year' => 31536000,
        'month' => 2592000,
        'week' => 604800,
        'day' => 86400,
        'hour' => 3600,
        'minute' => 60,
        'second' => 1
    ];
    
    foreach ($periods as $key => $value) {
        if ($difference >= $value) {
            $time = floor($difference / $value);
            return $time . ' ' . $key . ($time > 1 ? 's' : '') . ' ago';
        }
    }
    
    return 'Just now';
}
?>
