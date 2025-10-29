<?php

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token');
                redirect('/login');
            }
            
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];
            
            $user = $this->userModel->verifyPassword($email, $password);
            
            if ($user) {
                if ($user['is_blocked']) {
                    setFlash('error', 'Your account has been blocked. Please contact support.');
                    redirect('/login');
                }
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                
                if ($user['role'] === 'admin') {
                    redirect('/admin/dashboard');
                } else {
                    redirect('/');
                }
            } else {
                setFlash('error', 'Invalid email or password');
                redirect('/login');
            }
        }
        
        require_once APP_PATH . '/views/auth/login.php';
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token');
                redirect('/register');
            }
            
            $name = sanitize($_POST['name']);
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            
            // Validation
            if (empty($name) || empty($email) || empty($password)) {
                setFlash('error', 'All fields are required');
                redirect('/register');
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                setFlash('error', 'Invalid email address');
                redirect('/register');
            }
            
            if (strlen($password) < PASSWORD_MIN_LENGTH) {
                setFlash('error', 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters');
                redirect('/register');
            }
            
            if ($password !== $confirmPassword) {
                setFlash('error', 'Passwords do not match');
                redirect('/register');
            }
            
            // Check if email exists
            if ($this->userModel->findByEmail($email)) {
                setFlash('error', 'Email already registered');
                redirect('/register');
            }
            
            // Create user
            $userId = $this->userModel->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => 'user'
            ]);
            
            if ($userId) {
                setFlash('success', 'Registration successful! Please login.');
                redirect('/login');
            } else {
                setFlash('error', 'Registration failed. Please try again.');
                redirect('/register');
            }
        }
        
        require_once APP_PATH . '/views/auth/register.php';
    }
    
    public function logout() {
        session_destroy();
        redirect('/');
    }
    
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token');
                redirect('/forgot-password');
            }
            
            $email = sanitize($_POST['email']);
            $user = $this->userModel->findByEmail($email);
            
            if ($user) {
                $token = $this->userModel->createPasswordResetToken($email);
                $resetLink = BASE_URL . '/reset-password?token=' . $token;
                
                // Send email (placeholder)
                // sendEmail($email, 'Password Reset', 'Click here to reset: ' . $resetLink);
                
                setFlash('success', 'Password reset link sent to your email (placeholder - check token: ' . $token . ')');
            } else {
                setFlash('success', 'If the email exists, a reset link will be sent');
            }
            
            redirect('/forgot-password');
        }
        
        require_once APP_PATH . '/views/auth/forgot.php';
    }
    
    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token');
                redirect('/reset-password?token=' . $token);
            }
            
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            
            if (strlen($password) < PASSWORD_MIN_LENGTH) {
                setFlash('error', 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters');
                redirect('/reset-password?token=' . $token);
            }
            
            if ($password !== $confirmPassword) {
                setFlash('error', 'Passwords do not match');
                redirect('/reset-password?token=' . $token);
            }
            
            $reset = $this->userModel->verifyPasswordResetToken($token);
            if ($reset) {
                $user = $this->userModel->findByEmail($reset['email']);
                if ($user) {
                    $this->userModel->updatePassword($user['id'], $password);
                    $this->userModel->deletePasswordResetToken($token);
                    setFlash('success', 'Password reset successful! Please login.');
                    redirect('/login');
                }
            }
            
            setFlash('error', 'Invalid or expired reset token');
            redirect('/forgot-password');
        }
        
        $reset = $this->userModel->verifyPasswordResetToken($token);
        if (!$reset) {
            setFlash('error', 'Invalid or expired reset token');
            redirect('/forgot-password');
        }
        
        require_once APP_PATH . '/views/auth/reset.php';
    }
    
    public function profile() {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Invalid CSRF token');
                redirect('/profile');
            }
            
            $name = sanitize($_POST['name']);
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            
            $updateData = ['name' => $name];
            
            if (!empty($newPassword)) {
                $user = $this->userModel->findById($_SESSION['user_id']);
                if (!password_verify($currentPassword, $user['password'])) {
                    setFlash('error', 'Current password is incorrect');
                    redirect('/profile');
                }
                
                if (strlen($newPassword) < PASSWORD_MIN_LENGTH) {
                    setFlash('error', 'New password must be at least ' . PASSWORD_MIN_LENGTH . ' characters');
                    redirect('/profile');
                }
                
                $this->userModel->updatePassword($_SESSION['user_id'], $newPassword);
            }
            
            $this->userModel->update($_SESSION['user_id'], $updateData);
            $_SESSION['user_name'] = $name;
            
            setFlash('success', 'Profile updated successfully');
            redirect('/profile');
        }
        
        $user = $this->userModel->findById($_SESSION['user_id']);
        require_once APP_PATH . '/views/profile/index.php';
    }
}
