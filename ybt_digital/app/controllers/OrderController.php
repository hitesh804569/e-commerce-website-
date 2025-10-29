<?php

class OrderController {
    private $orderModel;
    private $orderItemModel;
    private $downloadModel;
    
    public function __construct() {
        $this->orderModel = new Order();
        $this->orderItemModel = new OrderItem();
        $this->downloadModel = new Download();
    }
    
    public function index() {
        requireLogin();
        
        $page = $_GET['page'] ?? 1;
        $limit = ORDERS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        
        $orders = $this->orderModel->getByUserId($_SESSION['user_id'], $limit, $offset);
        $totalOrders = $this->orderModel->count($_SESSION['user_id']);
        $totalPages = ceil($totalOrders / $limit);
        
        require_once APP_PATH . '/views/orders/index.php';
    }
    
    public function detail() {
        requireLogin();
        
        $orderId = $_GET['id'] ?? null;
        
        if (!$orderId) {
            redirect('/orders');
        }
        
        $order = $this->orderModel->findById($orderId);
        
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            setFlash('error', 'Order not found');
            redirect('/orders');
        }
        
        $orderItems = $this->orderItemModel->getByOrderId($orderId);
        $downloads = $this->downloadModel->getByUserId($_SESSION['user_id']);
        
        require_once APP_PATH . '/views/orders/detail.php';
    }
}
