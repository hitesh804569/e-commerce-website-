<?php

class AdminController {
    private $productModel;
    private $productFileModel;
    private $orderModel;
    private $couponModel;
    private $categoryModel;
    private $adminActionModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->productFileModel = new ProductFile();
        $this->orderModel = new Order();
        $this->couponModel = new Coupon();
        $this->categoryModel = new Category();
        $this->adminActionModel = new AdminAction();
    }
    
    public function dashboard() {
        $totalRevenue = $this->orderModel->getTotalRevenue();
        $monthlyRevenue = $this->orderModel->getTotalRevenue(date('Y-m-01'), date('Y-m-t'));
        $recentOrders = $this->orderModel->getRecentOrders(10);
        $topProducts = $this->productModel->getTopSelling(5);
        
        require_once APP_PATH . '/views/admin/dashboard.php';
    }
    
    public function products() {
        $products = $this->productModel->getAll();
        require_once APP_PATH . '/views/admin/products.php';
    }
    
    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token');
                redirect('/admin/products');
            }
            
            $title = sanitize($_POST['title']);
            $categoryId = $_POST['category_id'] ?: null;
            $description = sanitize($_POST['description']);
            $price = $_POST['price'];
            $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
            $slug = generateSlug($title);
            
            // Handle thumbnail upload
            $thumbnail = null;
            if (!empty($_FILES['thumbnail']['name'])) {
                $uploadResult = uploadFile(
                    $_FILES['thumbnail'],
                    UPLOAD_PATH . '/thumbnails',
                    ALLOWED_IMAGE_MIMES
                );
                
                if ($uploadResult['success']) {
                    $thumbnail = 'thumbnails/' . $uploadResult['filename'];
                } else {
                    setFlash('error', 'Thumbnail upload failed: ' . $uploadResult['error']);
                    redirect('/admin/product/add');
                }
            }
            
            // Create product
            $productId = $this->productModel->create([
                'category_id' => $categoryId,
                'title' => $title,
                'slug' => $slug,
                'description' => $description,
                'price' => $price,
                'thumbnail' => $thumbnail,
                'is_featured' => $isFeatured
            ]);
            
            // Handle product file upload
            if (!empty($_FILES['product_file']['name'])) {
                $fileUploadResult = uploadFile(
                    $_FILES['product_file'],
                    STORAGE_SECURE_PATH . '/product_files',
                    ALLOWED_PRODUCT_MIMES
                );
                
                if ($fileUploadResult['success']) {
                    $this->productFileModel->create([
                        'product_id' => $productId,
                        'filename' => $fileUploadResult['filename'],
                        'original_filename' => $_FILES['product_file']['name'],
                        'file_size' => $_FILES['product_file']['size'],
                        'mime_type' => $_FILES['product_file']['type']
                    ]);
                }
            }
            
            // Log action
            $this->adminActionModel->log(
                $_SESSION['user_id'],
                'product_create',
                "Created product: $title",
                'product',
                $productId
            );
            
            setFlash('success', 'Product created successfully');
            redirect('/admin/products');
        }
        
        $categories = $this->categoryModel->getAll();
        require_once APP_PATH . '/views/admin/product_form.php';
    }
    
    public function editProduct() {
        $productId = $_GET['id'] ?? null;
        
        if (!$productId) {
            redirect('/admin/products');
        }
        
        $product = $this->productModel->findById($productId);
        
        if (!$product) {
            setFlash('error', 'Product not found');
            redirect('/admin/products');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token');
                redirect('/admin/product/edit?id=' . $productId);
            }
            
            $title = sanitize($_POST['title']);
            $categoryId = $_POST['category_id'] ?: null;
            $description = sanitize($_POST['description']);
            $price = $_POST['price'];
            $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
            
            $updateData = [
                'category_id' => $categoryId,
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'is_featured' => $isFeatured
            ];
            
            // Handle thumbnail upload
            if (!empty($_FILES['thumbnail']['name'])) {
                $uploadResult = uploadFile(
                    $_FILES['thumbnail'],
                    UPLOAD_PATH . '/thumbnails',
                    ALLOWED_IMAGE_MIMES
                );
                
                if ($uploadResult['success']) {
                    // Delete old thumbnail
                    if ($product['thumbnail']) {
                        deleteFile(UPLOAD_PATH . '/' . $product['thumbnail']);
                    }
                    $updateData['thumbnail'] = 'thumbnails/' . $uploadResult['filename'];
                }
            }
            
            $this->productModel->update($productId, $updateData);
            
            // Handle product file upload
            if (!empty($_FILES['product_file']['name'])) {
                $fileUploadResult = uploadFile(
                    $_FILES['product_file'],
                    STORAGE_SECURE_PATH . '/product_files',
                    ALLOWED_PRODUCT_MIMES
                );
                
                if ($fileUploadResult['success']) {
                    // Delete old file
                    $oldFile = $this->productFileModel->findByProductId($productId);
                    if ($oldFile) {
                        deleteFile(STORAGE_SECURE_PATH . '/product_files/' . $oldFile['filename']);
                        $this->productFileModel->delete($oldFile['id']);
                    }
                    
                    $this->productFileModel->create([
                        'product_id' => $productId,
                        'filename' => $fileUploadResult['filename'],
                        'original_filename' => $_FILES['product_file']['name'],
                        'file_size' => $_FILES['product_file']['size'],
                        'mime_type' => $_FILES['product_file']['type']
                    ]);
                }
            }
            
            // Log action
            $this->adminActionModel->log(
                $_SESSION['user_id'],
                'product_update',
                "Updated product: $title",
                'product',
                $productId
            );
            
            setFlash('success', 'Product updated successfully');
            redirect('/admin/products');
        }
        
        $categories = $this->categoryModel->getAll();
        require_once APP_PATH . '/views/admin/product_form.php';
    }
    
    public function deleteProduct() {
        requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/products');
        }
        
        $productId = $_POST['id'] ?? null;
        
        if (!$productId) {
            redirect('/admin/products');
        }
        
        $product = $this->productModel->findById($productId);
        
        if ($product) {
            // Delete files
            if ($product['thumbnail']) {
                deleteFile(UPLOAD_PATH . '/' . $product['thumbnail']);
            }
            
            $productFile = $this->productFileModel->findByProductId($productId);
            if ($productFile) {
                deleteFile(STORAGE_SECURE_PATH . '/product_files/' . $productFile['filename']);
            }
            
            // Delete product
            $this->productModel->delete($productId);
            
            // Log action
            $this->adminActionModel->log(
                $_SESSION['user_id'],
                'product_delete',
                "Deleted product: {$product['title']}",
                'product',
                $productId
            );
            
            setFlash('success', 'Product deleted successfully');
        }
        
        redirect('/admin/products');
    }
    
    public function orders() {
        $page = $_GET['page'] ?? 1;
        $limit = ORDERS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        
        $orders = $this->orderModel->getAll($limit, $offset);
        $totalOrders = $this->orderModel->count();
        $totalPages = ceil($totalOrders / $limit);
        
        require_once APP_PATH . '/views/admin/orders.php';
    }
    
    public function coupons() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'create') {
                if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                    setFlash('error', 'Invalid CSRF token');
                    redirect('/admin/coupons');
                }
                
                $code = strtoupper(sanitize($_POST['code']));
                $discountType = $_POST['discount_type'];
                $discountValue = $_POST['discount_value'];
                $minPurchase = $_POST['min_purchase'] ?: 0;
                $usageLimit = $_POST['usage_limit'] ?: null;
                $expiresAt = $_POST['expires_at'] ?: null;
                
                $this->couponModel->create([
                    'code' => $code,
                    'discount_type' => $discountType,
                    'discount_value' => $discountValue,
                    'min_purchase' => $minPurchase,
                    'usage_limit' => $usageLimit,
                    'expires_at' => $expiresAt
                ]);
                
                // Log action
                $this->adminActionModel->log(
                    $_SESSION['user_id'],
                    'coupon_create',
                    "Created coupon: $code"
                );
                
                setFlash('success', 'Coupon created successfully');
                redirect('/admin/coupons');
            }
            
            if ($action === 'delete') {
                $couponId = $_POST['id'] ?? null;
                
                if ($couponId) {
                    $coupon = $this->couponModel->findById($couponId);
                    $this->couponModel->delete($couponId);
                    
                    // Log action
                    $this->adminActionModel->log(
                        $_SESSION['user_id'],
                        'coupon_delete',
                        "Deleted coupon: {$coupon['code']}"
                    );
                    
                    setFlash('success', 'Coupon deleted successfully');
                }
                
                redirect('/admin/coupons');
            }
        }
        
        $coupons = $this->couponModel->getAll();
        require_once APP_PATH . '/views/admin/coupons.php';
    }
    
    public function reports() {
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');
        
        $totalRevenue = $this->orderModel->getTotalRevenue($startDate, $endDate);
        $revenueByDate = $this->orderModel->getRevenueByDate($startDate, $endDate);
        $topProducts = $this->productModel->getTopSelling(10);
        
        require_once APP_PATH . '/views/admin/reports.php';
    }
}
