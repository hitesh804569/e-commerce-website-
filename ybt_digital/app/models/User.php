<?php

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->db->insert('users', $data);
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->db->fetchOne($sql, [$email]);
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function update($id, $data) {
        return $this->db->update('users', $data, 'id = ?', [$id]);
    }
    
    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->db->update('users', ['password' => $hashedPassword], 'id = ?', [$id]);
    }
    
    public function verifyPassword($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    public function getAll($limit = null, $offset = 0, $search = '', $role = '', $blocked = '') {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];
        
        if ($search) {
            $sql .= " AND (name LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($role) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }
        
        if ($blocked !== '') {
            $sql .= " AND is_blocked = ?";
            $params[] = $blocked;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function count($search = '', $role = '', $blocked = '') {
        $sql = "SELECT COUNT(*) FROM users WHERE 1=1";
        $params = [];
        
        if ($search) {
            $sql .= " AND (name LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($role) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }
        
        if ($blocked !== '') {
            $sql .= " AND is_blocked = ?";
            $params[] = $blocked;
        }
        
        return $this->db->fetchColumn($sql, $params);
    }
    
    public function toggleBlock($id) {
        $sql = "UPDATE users SET is_blocked = NOT is_blocked WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    public function changeRole($id, $role) {
        return $this->db->update('users', ['role' => $role], 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('users', 'id = ?', [$id]);
    }
    
    public function getUserOrders($userId) {
        $sql = "SELECT o.*, COUNT(oi.id) as item_count 
                FROM orders o 
                LEFT JOIN order_items oi ON o.id = oi.order_id 
                WHERE o.user_id = ? 
                GROUP BY o.id 
                ORDER BY o.created_at DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    public function createPasswordResetToken($email) {
        $token = generateToken(64);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $this->db->insert('password_resets', [
            'email' => $email,
            'token' => $token,
            'expires_at' => $expiresAt
        ]);
        
        return $token;
    }
    
    public function verifyPasswordResetToken($token) {
        $sql = "SELECT * FROM password_resets 
                WHERE token = ? AND expires_at > NOW() 
                ORDER BY created_at DESC LIMIT 1";
        return $this->db->fetchOne($sql, [$token]);
    }
    
    public function deletePasswordResetToken($token) {
        return $this->db->delete('password_resets', 'token = ?', [$token]);
    }
}
