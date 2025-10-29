<?php
require_once __DIR__ . '/../config/config.php';

requireLogin();

$pageTitle = 'My Profile - ' . SITE_NAME;
$user = getCurrentUser();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        
        if (empty($name) || empty($email)) {
            $error = 'Name and email are required';
        } elseif (!isValidEmail($email)) {
            $error = 'Invalid email address';
        } else {
            // Check if email is taken by another user
            $checkQuery = "SELECT id FROM users WHERE email = ? AND id != ?";
            $existing = fetchOne($checkQuery, [$email, $_SESSION['user_id']], 'si');
            
            if ($existing) {
                $error = 'Email already in use';
            } else {
                $updateQuery = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
                if (executeQuery($updateQuery, [$name, $email, $phone, $_SESSION['user_id']], 'sssi')) {
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                    $success = 'Profile updated successfully';
                    $user = getCurrentUser();
                } else {
                    $error = 'Failed to update profile';
                }
            }
        }
    } elseif (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $error = 'All password fields are required';
        } elseif (strlen($newPassword) < PASSWORD_MIN_LENGTH) {
            $error = 'New password must be at least ' . PASSWORD_MIN_LENGTH . ' characters';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'New passwords do not match';
        } else {
            // Verify current password
            $userQuery = "SELECT password FROM users WHERE id = ?";
            $userData = fetchOne($userQuery, [$_SESSION['user_id']], 'i');
            
            if (!verifyPassword($currentPassword, $userData['password'])) {
                $error = 'Current password is incorrect';
            } else {
                $hashedPassword = hashPassword($newPassword);
                $updateQuery = "UPDATE users SET password = ? WHERE id = ?";
                if (executeQuery($updateQuery, [$hashedPassword, $_SESSION['user_id']], 'si')) {
                    $success = 'Password changed successfully';
                } else {
                    $error = 'Failed to change password';
                }
            }
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<style>
    .profile-container {
        padding: 3rem 0;
    }
    
    .profile-section {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .section-title {
        font-weight: bold;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }
    
    .profile-avatar {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        font-weight: bold;
        margin: 0 auto 1rem;
    }
</style>

<div class="container profile-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">
            <i class="fas fa-user-circle me-2"></i> My Profile
        </h1>
        <a href="<?php echo SITE_URL; ?>/user/dashboard.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>
    
    <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
        <button type="button" class="btn-close" data-mdb-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
        <button type="button" class="btn-close" data-mdb-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="profile-section text-center">
                <div class="profile-avatar">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <h4 class="fw-bold mb-1"><?php echo sanitize($user['name']); ?></h4>
                <p class="text-muted mb-3"><?php echo sanitize($user['email']); ?></p>
                <span class="badge bg-success">Active Account</span>
                
                <div class="mt-4 pt-4 border-top">
                    <div class="d-grid gap-2">
                        <a href="<?php echo SITE_URL; ?>/user/orders.php" class="btn btn-outline-primary">
                            <i class="fas fa-box me-2"></i> My Orders
                        </a>
                        <a href="<?php echo SITE_URL; ?>/auth/logout.php" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Update Profile -->
            <div class="profile-section">
                <h4 class="section-title">
                    <i class="fas fa-user-edit me-2"></i> Update Profile
                </h4>
                
                <form method="POST" action="">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" 
                               value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" 
                               value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" 
                               value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" 
                               placeholder="Optional">
                    </div>
                    
                    <button type="submit" name="update_profile" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Update Profile
                    </button>
                </form>
            </div>
            
            <!-- Change Password -->
            <div class="profile-section">
                <h4 class="section-title">
                    <i class="fas fa-lock me-2"></i> Change Password
                </h4>
                
                <form method="POST" action="">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Current Password</label>
                        <input type="password" name="current_password" class="form-control" 
                               placeholder="Enter current password" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">New Password</label>
                        <input type="password" name="new_password" class="form-control" 
                               placeholder="Enter new password" required>
                        <small class="text-muted">Minimum <?php echo PASSWORD_MIN_LENGTH; ?> characters</small>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" 
                               placeholder="Confirm new password" required>
                    </div>
                    
                    <button type="submit" name="change_password" class="btn btn-primary">
                        <i class="fas fa-key me-2"></i> Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
