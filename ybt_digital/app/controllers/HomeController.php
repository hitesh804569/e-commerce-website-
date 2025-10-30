<?php

class HomeController {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    public function index() {
        $featuredProducts = $this->productModel->getFeatured(8);
        $categories = $this->categoryModel->getAll();
        
        require_once APP_PATH . '/views/home.php';
    }
}
