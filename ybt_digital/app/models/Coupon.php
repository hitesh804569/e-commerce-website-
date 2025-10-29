<?php

class Coupon {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        return $this->db->insert('coupons', $data);
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM coupons WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function findByCode($code) {
        $sql = "SELECT * FROM coupons WHERE code = ?";
        return $this->db->fetchOne($sql, [$code]);
    }
    
    public function getAll() {
        $sql = "SELECT * FROM coupons ORDER BY created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function update($id, $data) {
        return $this->db->update('coupons', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('coupons', 'id = ?', [$id]);
    }
    
    public function validate($code, $subtotal) {
        $coupon = $this->findByCode($code);
        
        if (!$coupon) {
            return ['valid' => false, 'error' => 'Invalid coupon code'];
        }
        
        if (!$coupon['is_active']) {
            return ['valid' => false, 'error' => 'Coupon is not active'];
        }
        
        if ($coupon['expires_at'] && strtotime($coupon['expires_at']) < time()) {
            return ['valid' => false, 'error' => 'Coupon has expired'];
        }
        
        if ($coupon['usage_limit'] && $coupon['used_count'] >= $coupon['usage_limit']) {
            return ['valid' => false, 'error' => 'Coupon usage limit reached'];
        }
        
        if ($subtotal < $coupon['min_purchase']) {
            return ['valid' => false, 'error' => 'Minimum purchase amount not met'];
        }
        
        $discount = 0;
        if ($coupon['discount_type'] === 'flat') {
            $discount = $coupon['discount_value'];
        } else {
            $discount = ($subtotal * $coupon['discount_value']) / 100;
        }
        
        return [
            'valid' => true,
            'discount' => $discount,
            'coupon' => $coupon
        ];
    }
    
    public function incrementUsage($id) {
        $sql = "UPDATE coupons SET used_count = used_count + 1 WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}
