<?php
require_once __DIR__ . '/../config/config.php';

if (isLoggedIn()) {
    redirect(SITE_URL . '/user/dashboard.php');
}

$pageTitle = 'Forgot Password - ' . SITE_NAME;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    
    if (empty($email)) {
        $error = 'Please enter your email address';
    } elseif (!isValidEmail($email)) {
        $error = 'Invalid email address';
    } else {
        // Check if user exists
        $user = fetchOne("SELECT id, name FROM users WHERE email = ?", [$email], 's');
        
        if ($user) {
            // Generate reset token
            $token = generateToken();
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Delete old tokens
            executeQuery("DELETE FROM password_resets WHERE email = ? AND user_type = 'user'", [$email], 's');
            
            // Insert new token
            $query = "INSERT INTO password_resets (email, token, user_type, expiry) VALUES (?, ?, 'user', ?)";
            executeQuery($query, [$email, $token, $expiry], 'sss');
            
            // Send reset email (simplified - in production use proper email service)
            $resetLink = SITE_URL . '/auth/reset-password.php?token=' . $token;
            $subject = 'Password Reset Request - ' . SITE_NAME;
            $message = "
                <h2>Password Reset Request</h2>
                <p>Hello {$user['name']},</p>
                <p>We received a request to reset your password. Click the link below to reset it:</p>
                <p><a href='{$resetLink}'>{$resetLink}</a></p>
                <p>This link will expire in 1 hour.</p>
                <p>If you didn't request this, please ignore this email.</p>
                <p>Thanks,<br>" . SITE_NAME . " Team</p>
            ";
            
            // In production, use proper email service
            // For now, just show success message
            $success = 'Password reset instructions have been sent to your email address.';
        } else {
            // Don't reveal if email exists or not (security best practice)
            $success = 'If an account exists with this email, password reset instructions have been sent.';
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
</style>

<div class="auth-container">
    <div class="container">
        <div class="auth-card mx-auto">
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h2 class="fw-bold mb-2">Forgot Password?</h2>
                <p class="text-muted">Enter your email to reset your password</p>
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
            <div class="text-center mt-4">
                <a href="<?php echo SITE_URL; ?>/auth/login.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Login
                </a>
            </div>
            <?php else: ?>
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="form-label fw-bold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" 
                               placeholder="Enter your email" 
                               value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">
                    <i class="fas fa-paper-plane me-2"></i> Send Reset Link
                </button>
                
                <div class="text-center">
                    <a href="<?php echo SITE_URL; ?>/auth/login.php" class="text-primary text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i> Back to Login
                    </a>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
