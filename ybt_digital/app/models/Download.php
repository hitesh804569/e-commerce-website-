<?php

class Download {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        return $this->db->insert('downloads', $data);
    }
    
    public function findByToken($token) {
        $sql = "SELECT d.*, p.title as product_title, pf.filename, pf.original_filename 
                FROM downloads d 
                LEFT JOIN products p ON d.product_id = p.id 
                LEFT JOIN product_files pf ON p.id = pf.product_id 
                WHERE d.download_token = ?";
        return $this->db->fetchOne($sql, [$token]);
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM downloads WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function getByUserId($userId) {
        $sql = "SELECT d.*, p.title as product_title, o.order_number 
                FROM downloads d 
                LEFT JOIN products p ON d.product_id = p.id 
                LEFT JOIN order_items oi ON d.order_item_id = oi.id 
                LEFT JOIN orders o ON oi.order_id = o.id 
                WHERE d.user_id = ? 
                ORDER BY d.created_at DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    public function incrementUsedCount($id) {
        $sql = "UPDATE downloads SET used_count = used_count + 1, updated_at = NOW() WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    public function isValid($download) {
        if (!$download) {
            return false;
        }
        
        // Check expiry
        if (strtotime($download['expires_at']) < time()) {
            return false;
        }
        
        // Check usage limit
        if ($download['used_count'] >= $download['max_uses']) {
            return false;
        }
        
        return true;
    }
    
    public function logDownload($downloadId, $userId, $productId) {
        return $this->db->insert('downloads_log', [
            'download_id' => $downloadId,
            'user_id' => $userId,
            'product_id' => $productId,
            'ip_address' => getClientIP(),
            'user_agent' => getUserAgent()
        ]);
    }
    
    public function getDownloadLogs($downloadId) {
        $sql = "SELECT * FROM downloads_log WHERE download_id = ? ORDER BY downloaded_at DESC";
        return $this->db->fetchAll($sql, [$downloadId]);
    }
}
