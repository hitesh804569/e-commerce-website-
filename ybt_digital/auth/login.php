<?php
require_once __DIR__ . '/../config/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect(SITE_URL . '/user/dashboard.php');
}

$pageTitle = 'Login - ' . SITE_NAME;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif (!isValidEmail($email)) {
        $error = 'Invalid email address';
    } else {
        $query = "SELECT * FROM users WHERE email = ? AND status = 'active'";
        $user = fetchOne($query, [$email], 's');
        
        if ($user && verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            if ($remember) {
                setcookie('user_id', $user['id'], time() + (86400 * 30), '/');
            }
            
            setFlash('success', 'Welcome back, ' . $user['name'] . '!');
            redirect(SITE_URL . '/user/dashboard.php');
        } else {
            $error = 'Invalid email or password';
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<style>
    .auth-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }
    
    .auth-card {
        max-width: 450px;
        width: 100%;
        background: var(--card-bg);
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        padding: 2.5rem;
    }
    
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .auth-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin: 0 auto 1rem;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(18, 102, 241, 0.25);
    }
    
    .divider {
        text-align: center;
        margin: 1.5rem 0;
        position: relative;
    }
    
    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: var(--border-color);
    }
    
    .divider span {
        background: var(--card-bg);
        padding: 0 1rem;
        position: relative;
        color: var(--text-secondary);
    }
</style>

<div class="auth-container">
    <div class="container">
        <div class="auth-card mx-auto">
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="fas fa-user"></i>
                </div>
                <h2 class="fw-bold mb-2">Welcome Back</h2>
                <p class="text-muted">Login to your account</p>
            </div>
            
            <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="form-label fw-bold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" 
                               value="<?php echo $email ?? ''; ?>" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                    <a href="<?php echo SITE_URL; ?>/auth/forgot-password.php" class="text-primary text-decoration-none">
                        Forgot Password?
                    </a>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>
                
                <div class="divider">
                    <span>OR</span>
                </div>
                
                <div class="text-center">
                    <p class="mb-0">Don't have an account? 
                        <a href="<?php echo SITE_URL; ?>/auth/signup.php" class="text-primary fw-bold text-decoration-none">
                            Sign Up
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
