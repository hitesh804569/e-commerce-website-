<?php

class Order {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        return $this->db->insert('orders', $data);
    }
    
    public function findById($id) {
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function findByOrderNumber($orderNumber) {
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.order_number = ?";
        return $this->db->fetchOne($sql, [$orderNumber]);
    }
    
    public function getByUserId($userId, $limit = null, $offset = 0) {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
        $params = [$userId];
        
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getAll($limit = null, $offset = 0, $status = null) {
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE 1=1";
        $params = [];
        
        if ($status) {
            $sql .= " AND o.payment_status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY o.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function count($userId = null, $status = null) {
        $sql = "SELECT COUNT(*) FROM orders WHERE 1=1";
        $params = [];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        
        if ($status) {
            $sql .= " AND payment_status = ?";
            $params[] = $status;
        }
        
        return $this->db->fetchColumn($sql, $params);
    }
    
    public function update($id, $data) {
        return $this->db->update('orders', $data, 'id = ?', [$id]);
    }
    
    public function updateStatus($id, $paymentStatus, $orderStatus) {
        return $this->db->update('orders', [
            'payment_status' => $paymentStatus,
            'order_status' => $orderStatus
        ], 'id = ?', [$id]);
    }
    
    public function getRecentOrders($limit = 10) {
        return $this->getAll($limit);
    }
    
    public function getTotalRevenue($startDate = null, $endDate = null) {
        $sql = "SELECT SUM(total) FROM orders WHERE payment_status = 'completed'";
        $params = [];
        
        if ($startDate) {
            $sql .= " AND created_at >= ?";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $sql .= " AND created_at <= ?";
            $params[] = $endDate;
        }
        
        return $this->db->fetchColumn($sql, $params) ?: 0;
    }
    
    public function getRevenueByDate($startDate, $endDate) {
        $sql = "SELECT DATE(created_at) as date, SUM(total) as revenue 
                FROM orders 
                WHERE payment_status = 'completed' 
                AND created_at >= ? AND created_at <= ? 
                GROUP BY DATE(created_at) 
                ORDER BY date ASC";
        return $this->db->fetchAll($sql, [$startDate, $endDate]);
    }
}
