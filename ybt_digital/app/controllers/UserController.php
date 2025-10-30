<?php

class UserController {
    private $userModel;
    private $adminActionModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->adminActionModel = new AdminAction();
    }
    
    public function index() {
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        $role = $_GET['role'] ?? '';
        $blocked = $_GET['blocked'] ?? '';
        
        $limit = USERS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        
        $users = $this->userModel->getAll($limit, $offset, $search, $role, $blocked);
        $totalUsers = $this->userModel->count($search, $role, $blocked);
        $totalPages = ceil($totalUsers / $limit);
        
        require_once APP_PATH . '/views/admin/users.php';
    }
    
    public function view() {
        $userId = $_GET['id'] ?? null;
        
        if (!$userId) {
            redirect('/admin/users');
        }
        
        $user = $this->userModel->findById($userId);
        
        if (!$user) {
            setFlash('error', 'User not found');
            redirect('/admin/users');
        }
        
        $orders = $this->userModel->getUserOrders($userId);
        
        require_once APP_PATH . '/views/admin/user_view.php';
    }
    
    public function toggleBlock() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/users');
        }
        
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Invalid CSRF token');
            redirect('/admin/users');
        }
        
        $userId = $_POST['user_id'] ?? null;
        
        if (!$userId) {
            redirect('/admin/users');
        }
        
        $user = $this->userModel->findById($userId);
        
        if ($user) {
            $this->userModel->toggleBlock($userId);
            
            $action = $user['is_blocked'] ? 'unblocked' : 'blocked';
            
            // Log action
            $this->adminActionModel->log(
                $_SESSION['user_id'],
                'user_' . $action,
                ucfirst($action) . " user: {$user['email']}",
                'user',
                $userId
            );
            
            setFlash('success', 'User ' . $action . ' successfully');
        }
        
        redirect('/admin/users');
    }
    
    public function changeRole() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/users');
        }
        
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Invalid CSRF token');
            redirect('/admin/users');
        }
        
        $userId = $_POST['user_id'] ?? null;
        $newRole = $_POST['role'] ?? 'user';
        
        if (!$userId || !in_array($newRole, ['user', 'admin'])) {
            redirect('/admin/users');
        }
        
        $user = $this->userModel->findById($userId);
        
        if ($user) {
            $this->userModel->changeRole($userId, $newRole);
            
            // Log action
            $this->adminActionModel->log(
                $_SESSION['user_id'],
                'user_role_change',
                "Changed role of {$user['email']} to $newRole",
                'user',
                $userId
            );
            
            setFlash('success', 'User role updated successfully');
        }
        
        redirect('/admin/users');
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/users');
        }
        
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Invalid CSRF token');
            redirect('/admin/users');
        }
        
        $userId = $_POST['user_id'] ?? null;
        
        if (!$userId) {
            redirect('/admin/users');
        }
        
        // Prevent deleting yourself
        if ($userId == $_SESSION['user_id']) {
            setFlash('error', 'You cannot delete your own account');
            redirect('/admin/users');
        }
        
        $user = $this->userModel->findById($userId);
        
        if ($user) {
            $this->userModel->delete($userId);
            
            // Log action
            $this->adminActionModel->log(
                $_SESSION['user_id'],
                'user_delete',
                "Deleted user: {$user['email']}",
                'user',
                $userId
            );
            
            setFlash('success', 'User deleted successfully');
        }
        
        redirect('/admin/users');
    }
}
