<?php

class CartController {
    private $productModel;
    
    public function __construct() {
        $this->productModel = new Product();
    }
    
    private function getCart() {
        return $_SESSION['cart'] ?? [];
    }
    
    private function saveCart($cart) {
        $_SESSION['cart'] = $cart;
    }
    
    public function index() {
        $cart = $this->getCart();
        $cartItems = [];
        $subtotal = 0;
        
        foreach ($cart as $productId => $quantity) {
            $product = $this->productModel->findById($productId);
            if ($product) {
                $product['quantity'] = $quantity;
                $product['line_total'] = $product['price'] * $quantity;
                $cartItems[] = $product;
                $subtotal += $product['line_total'];
            }
        }
        
        require_once APP_PATH . '/views/cart/index.php';
    }
    
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/cart');
        }
        
        $productId = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;
        
        if (!$productId) {
            setFlash('error', 'Invalid product');
            redirect('/products');
        }
        
        $product = $this->productModel->findById($productId);
        if (!$product) {
            setFlash('error', 'Product not found');
            redirect('/products');
        }
        
        $cart = $this->getCart();
        
        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }
        
        $this->saveCart($cart);
        setFlash('success', 'Product added to cart');
        redirect('/cart');
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/cart');
        }
        
        $productId = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;
        
        if (!$productId || $quantity < 1) {
            redirect('/cart');
        }
        
        $cart = $this->getCart();
        
        if (isset($cart[$productId])) {
            $cart[$productId] = $quantity;
            $this->saveCart($cart);
            setFlash('success', 'Cart updated');
        }
        
        redirect('/cart');
    }
    
    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/cart');
        }
        
        $productId = $_POST['product_id'] ?? null;
        
        if (!$productId) {
            redirect('/cart');
        }
        
        $cart = $this->getCart();
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $this->saveCart($cart);
            setFlash('success', 'Product removed from cart');
        }
        
        redirect('/cart');
    }
}
