<?php

class OrderItem {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        return $this->db->insert('order_items', $data);
    }
    
    public function findById($id) {
        $sql = "SELECT oi.*, p.title, p.thumbnail 
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE oi.id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function getByOrderId($orderId) {
        $sql = "SELECT oi.*, p.title, p.thumbnail 
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?";
        return $this->db->fetchAll($sql, [$orderId]);
    }
    
    public function delete($id) {
        return $this->db->delete('order_items', 'id = ?', [$id]);
    }
}
