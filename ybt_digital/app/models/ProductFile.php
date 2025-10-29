<?php

class ProductFile {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        return $this->db->insert('product_files', $data);
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM product_files WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function findByProductId($productId) {
        $sql = "SELECT * FROM product_files WHERE product_id = ? ORDER BY created_at DESC LIMIT 1";
        return $this->db->fetchOne($sql, [$productId]);
    }
    
    public function getAll($productId) {
        $sql = "SELECT * FROM product_files WHERE product_id = ? ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, [$productId]);
    }
    
    public function update($id, $data) {
        return $this->db->update('product_files', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('product_files', 'id = ?', [$id]);
    }
    
    public function deleteByProductId($productId) {
        return $this->db->delete('product_files', 'product_id = ?', [$productId]);
    }
}
