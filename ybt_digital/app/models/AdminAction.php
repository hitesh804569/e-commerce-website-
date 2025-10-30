<?php

class AdminAction {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function log($adminId, $actionType, $actionDescription, $targetType = null, $targetId = null) {
        return $this->db->insert('admin_actions', [
            'admin_id' => $adminId,
            'action_type' => $actionType,
            'action_description' => $actionDescription,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'ip_address' => getClientIP()
        ]);
    }
    
    public function getAll($limit = 100, $offset = 0) {
        $sql = "SELECT aa.*, u.name as admin_name, u.email as admin_email 
                FROM admin_actions aa 
                LEFT JOIN users u ON aa.admin_id = u.id 
                ORDER BY aa.created_at DESC 
                LIMIT ? OFFSET ?";
        return $this->db->fetchAll($sql, [$limit, $offset]);
    }
    
    public function getByAdmin($adminId, $limit = 50) {
        $sql = "SELECT aa.*, u.name as admin_name 
                FROM admin_actions aa 
                LEFT JOIN users u ON aa.admin_id = u.id 
                WHERE aa.admin_id = ? 
                ORDER BY aa.created_at DESC 
                LIMIT ?";
        return $this->db->fetchAll($sql, [$adminId, $limit]);
    }
    
    public function getByType($actionType, $limit = 50) {
        $sql = "SELECT aa.*, u.name as admin_name 
                FROM admin_actions aa 
                LEFT JOIN users u ON aa.admin_id = u.id 
                WHERE aa.action_type = ? 
                ORDER BY aa.created_at DESC 
                LIMIT ?";
        return $this->db->fetchAll($sql, [$actionType, $limit]);
    }
    
    public function count() {
        $sql = "SELECT COUNT(*) FROM admin_actions";
        return $this->db->fetchColumn($sql);
    }
}
