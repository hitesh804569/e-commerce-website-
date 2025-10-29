<?php
require_once __DIR__ . '/../config/config.php';

$pageTitle = 'Contact Us - ' . SITE_NAME;
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all fields';
    } elseif (!isValidEmail($email)) {
        $error = 'Invalid email address';
    } else {
        $userId = isLoggedIn() ? $_SESSION['user_id'] : null;
        
        $query = "INSERT INTO support_tickets (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)";
        if (insertAndGetId($query, [$userId, $name, $email, $subject, $message], 'issss')) {
            $success = 'Your message has been sent successfully! We will get back to you soon.';
            $name = $email = $subject = $message = '';
        } else {
            $error = 'Failed to send message. Please try again.';
        }
    }
}

$user = isLoggedIn() ? getCurrentUser() : null;

include __DIR__ . '/../includes/header.php';
?>

<style>
    .contact-container {
        padding: 3rem 0;
    }
    
    .contact-card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .contact-info-item {
        display: flex;
        align-items-center;
        gap: 1rem;
        padding: 1.5rem;
        background: var(--light-bg);
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    
    .contact-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }
</style>

<div class="container contact-container">
    <div class="text-center mb-5">
        <h1 class="fw-bold mb-3">Get In Touch</h1>
        <p class="text-muted">Have questions? We'd love to hear from you.</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="contact-card">
                <h4 class="fw-bold mb-4">Send us a Message</h4>
                
                <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Your Name</label>
                            <input type="text" name="name" class="form-control" 
                                   value="<?php echo $user ? htmlspecialchars($user['name']) : ($name ?? ''); ?>" 
                                   placeholder="Enter your name" required>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?php echo $user ? htmlspecialchars($user['email']) : ($email ?? ''); ?>" 
                                   placeholder="Enter your email" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Subject</label>
                        <input type="text" name="subject" class="form-control" 
                               value="<?php echo $subject ?? ''; ?>" 
                               placeholder="What is this about?" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Message</label>
                        <textarea name="message" class="form-control" rows="6" 
                                  placeholder="Tell us more..." required><?php echo $message ?? ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane me-2"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="contact-card mb-4">
                <h5 class="fw-bold mb-4">Contact Information</h5>
                
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Email</h6>
                        <p class="mb-0 text-muted"><?php echo ADMIN_EMAIL; ?></p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Phone</h6>
                        <p class="mb-0 text-muted">+1 234 567 8900</p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Address</h6>
                        <p class="mb-0 text-muted">123 Digital Street<br>Tech City, TC 12345</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-card">
                <h5 class="fw-bold mb-3">Business Hours</h5>
                <p class="mb-2"><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM</p>
                <p class="mb-2"><strong>Saturday:</strong> 10:00 AM - 4:00 PM</p>
                <p class="mb-0"><strong>Sunday:</strong> Closed</p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
