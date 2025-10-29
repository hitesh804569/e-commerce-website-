    <!-- Mobile Bottom Navigation -->
    <div class="bottom-nav mobile-nav">
        <div class="d-flex justify-content-around">
            <a href="<?php echo SITE_URL; ?>" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/products" class="bottom-nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'products') !== false ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i>
                <span>Products</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/cart" class="bottom-nav-item position-relative <?php echo strpos($_SERVER['PHP_SELF'], 'cart') !== false ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i>
                <?php if ($cartCount > 0): ?>
                    <span class="badge-notification"><?php echo $cartCount; ?></span>
                <?php endif; ?>
                <span>Cart</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/user/dashboard.php" class="bottom-nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'user') !== false ? 'active' : ''; ?>">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        </div>
    </div>
    
    <!-- Desktop Footer -->
    <footer class="bg-dark text-white mt-5 desktop-nav">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-shopping-bag"></i> <?php echo $settings['site_name'] ?? SITE_NAME; ?>
                    </h5>
                    <p class="text-white-50">
                        Your trusted marketplace for premium digital products. Download instantly after purchase.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white-50"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
                
                <div class="col-md-2 mb-4">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>" class="text-white-50 text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/products" class="text-white-50 text-decoration-none">Products</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/pages/about.php" class="text-white-50 text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/pages/contact.php" class="text-white-50 text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h6 class="fw-bold mb-3">Support</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/pages/faq.php" class="text-white-50 text-decoration-none">FAQ</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/pages/privacy.php" class="text-white-50 text-decoration-none">Privacy Policy</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/pages/terms.php" class="text-white-50 text-decoration-none">Terms of Service</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/pages/refund.php" class="text-white-50 text-decoration-none">Refund Policy</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h6 class="fw-bold mb-3">Contact Info</h6>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> <?php echo ADMIN_EMAIL; ?></li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +1 234 567 8900</li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Digital Street, Tech City</li>
                    </ul>
                </div>
            </div>
            
            <hr class="bg-white-50 my-4">
            
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-white-50">
                        <?php echo $settings['site_footer'] ?? '© 2025 YBT Digital. All rights reserved.'; ?>
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0 text-white-50">
                        <i class="fas fa-lock me-1"></i> Secure Payment Gateway
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- MDBootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new mdb.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
