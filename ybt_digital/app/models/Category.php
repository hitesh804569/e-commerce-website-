<?php

class Category {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        return $this->db->insert('categories', $data);
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM categories WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function findBySlug($slug) {
        $sql = "SELECT * FROM categories WHERE slug = ?";
        return $this->db->fetchOne($sql, [$slug]);
    }
    
    public function getAll() {
        $sql = "SELECT c.*, COUNT(p.id) as product_count 
                FROM categories c 
                LEFT JOIN products p ON c.id = p.category_id 
                GROUP BY c.id 
                ORDER BY c.name ASC";
        return $this->db->fetchAll($sql);
    }
    
    public function update($id, $data) {
        return $this->db->update('categories', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('categories', 'id = ?', [$id]);
    }
}
