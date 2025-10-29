<?php
require_once __DIR__ . '/../config/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect(SITE_URL . '/user/dashboard.php');
}

$pageTitle = 'Sign Up - ' . SITE_NAME;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Please fill in all fields';
    } elseif (!isValidEmail($email)) {
        $error = 'Invalid email address';
    } elseif (strlen($password) < PASSWORD_MIN_LENGTH) {
        $error = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } else {
        // Check if email already exists
        $checkQuery = "SELECT id FROM users WHERE email = ?";
        $existing = fetchOne($checkQuery, [$email], 's');
        
        if ($existing) {
            $error = 'Email already registered';
        } else {
            $hashedPassword = hashPassword($password);
            $insertQuery = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $userId = insertAndGetId($insertQuery, [$name, $email, $hashedPassword], 'sss');
            
            if ($userId) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                
                setFlash('success', 'Account created successfully! Welcome to ' . SITE_NAME);
                redirect(SITE_URL . '/user/dashboard.php');
            } else {
                $error = 'Failed to create account. Please try again.';
            }
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
    
    .password-strength {
        height: 4px;
        background: var(--border-color);
        border-radius: 2px;
        margin-top: 0.5rem;
        overflow: hidden;
    }
    
    .password-strength-bar {
        height: 100%;
        width: 0;
        transition: all 0.3s;
    }
    
    .strength-weak { background: var(--danger-color); width: 33%; }
    .strength-medium { background: var(--warning-color); width: 66%; }
    .strength-strong { background: var(--success-color); width: 100%; }
</style>

<div class="auth-container">
    <div class="container">
        <div class="auth-card mx-auto">
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2 class="fw-bold mb-2">Create Account</h2>
                <p class="text-muted">Join us and start shopping</p>
            </div>
            
            <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="form-label fw-bold">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="name" class="form-control" placeholder="Enter your full name" 
                               value="<?php echo $name ?? ''; ?>" required>
                    </div>
                </div>
                
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
                        <input type="password" name="password" id="password" class="form-control" 
                               placeholder="Create a password" required>
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strength-bar"></div>
                    </div>
                    <small class="text-muted">Minimum <?php echo PASSWORD_MIN_LENGTH; ?> characters</small>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="confirm_password" class="form-control" 
                               placeholder="Confirm your password" required>
                    </div>
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="<?php echo SITE_URL; ?>/pages/terms.php" class="text-primary">Terms of Service</a> 
                        and <a href="<?php echo SITE_URL; ?>/pages/privacy.php" class="text-primary">Privacy Policy</a>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">
                    <i class="fas fa-user-plus me-2"></i> Create Account
                </button>
                
                <div class="text-center">
                    <p class="mb-0">Already have an account? 
                        <a href="<?php echo SITE_URL; ?>/auth/login.php" class="text-primary fw-bold text-decoration-none">
                            Login
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Password strength indicator
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.getElementById('strength-bar');
        
        let strength = 0;
        if (password.length >= 6) strength++;
        if (password.length >= 10) strength++;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
        if (/\d/.test(password)) strength++;
        if (/[^a-zA-Z\d]/.test(password)) strength++;
        
        strengthBar.className = 'password-strength-bar';
        if (strength <= 2) {
            strengthBar.classList.add('strength-weak');
        } else if (strength <= 4) {
            strengthBar.classList.add('strength-medium');
        } else {
            strengthBar.classList.add('strength-strong');
        }
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
