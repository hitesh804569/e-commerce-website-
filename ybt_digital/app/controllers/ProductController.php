<?php

class ProductController {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    public function list() {
        $page = $_GET['page'] ?? 1;
        $categoryId = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? '';
        
        $limit = PRODUCTS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        
        $products = $this->productModel->getAll($limit, $offset, $categoryId, $search);
        $totalProducts = $this->productModel->count($categoryId, $search);
        $totalPages = ceil($totalProducts / $limit);
        
        $categories = $this->categoryModel->getAll();
        $selectedCategory = $categoryId ? $this->categoryModel->findById($categoryId) : null;
        
        require_once APP_PATH . '/views/products/list.php';
    }
    
    public function detail() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            redirect('/products');
        }
        
        $product = $this->productModel->findById($id);
        
        if (!$product) {
            setFlash('error', 'Product not found');
            redirect('/products');
        }
        
        $screenshots = $this->productModel->getScreenshots($id);
        $relatedProducts = $this->productModel->getAll(4, 0, $product['category_id']);
        
        require_once APP_PATH . '/views/products/detail.php';
    }
}
