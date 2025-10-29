<?php

class Product {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        return $this->db->insert('products', $data);
    }
    
    public function findById($id) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function findBySlug($slug) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.slug = ?";
        return $this->db->fetchOne($sql, [$slug]);
    }
    
    public function getAll($limit = null, $offset = 0, $categoryId = null, $search = '', $featured = null) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE 1=1";
        $params = [];
        
        if ($categoryId) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($search) {
            $sql .= " AND (p.title LIKE ? OR p.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($featured !== null) {
            $sql .= " AND p.is_featured = ?";
            $params[] = $featured;
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function count($categoryId = null, $search = '', $featured = null) {
        $sql = "SELECT COUNT(*) FROM products WHERE 1=1";
        $params = [];
        
        if ($categoryId) {
            $sql .= " AND category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($search) {
            $sql .= " AND (title LIKE ? OR description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($featured !== null) {
            $sql .= " AND is_featured = ?";
            $params[] = $featured;
        }
        
        return $this->db->fetchColumn($sql, $params);
    }
    
    public function update($id, $data) {
        return $this->db->update('products', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('products', 'id = ?', [$id]);
    }
    
    public function getFeatured($limit = 8) {
        return $this->getAll($limit, 0, null, '', true);
    }
    
    public function getScreenshots($productId) {
        $sql = "SELECT * FROM product_screenshots WHERE product_id = ? ORDER BY sort_order ASC";
        return $this->db->fetchAll($sql, [$productId]);
    }
    
    public function addScreenshot($productId, $imagePath, $sortOrder = 0) {
        return $this->db->insert('product_screenshots', [
            'product_id' => $productId,
            'image_path' => $imagePath,
            'sort_order' => $sortOrder
        ]);
    }
    
    public function deleteScreenshot($id) {
        return $this->db->delete('product_screenshots', 'id = ?', [$id]);
    }
    
    public function getTopSelling($limit = 10) {
        $sql = "SELECT p.*, c.name as category_name, COUNT(oi.id) as total_sales 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN order_items oi ON p.id = oi.product_id 
                GROUP BY p.id 
                ORDER BY total_sales DESC 
                LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }
}
