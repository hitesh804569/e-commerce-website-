# YBT Digital — Digital Product Marketplace

A complete, secure digital product marketplace built with vanilla PHP 8+ and MySQL using clean MVC architecture. Features user authentication, secure token-based downloads, admin panel, payment integration (Stripe/Razorpay), and comprehensive product/order management.

## 🌟 Features

### User Side (Frontend)
- ✅ **Authentication System**
  - User registration and login
  - Password reset functionality
  - Profile management
  
- ✅ **Product Browsing**
  - Responsive landing page with hero section
  - Product listing with filters (category, price, search)
  - Detailed product pages with descriptions
  - Related products suggestions
  
- ✅ **Shopping & Checkout**
  - Shopping cart functionality
  - Coupon/discount code system
  - Secure checkout process
  - Multiple payment gateway support (Razorpay/Stripe/PayPal)
  
- ✅ **Orders & Downloads**
  - Order history and tracking
  - Secure download system with token-based access
  - Download limits and expiry dates
  - Invoice generation
  
- ✅ **Support**
  - Contact form
  - FAQ page
  - Support ticket system

### Admin Side (Backend)
- ✅ **Dashboard**
  - Overview statistics
  - Recent orders
  - Top-selling products
  - Revenue analytics
  
- ✅ **Product Management**
  - Add/Edit/Delete products
  - Upload digital files
  - Manage categories
  - Product status control
  
- ✅ **Order Management**
  - View all orders
  - Order status tracking
  - Transaction details
  - Refund management
  
- ✅ **User Management**
  - View registered users
  - Block/Unblock users
  - Purchase history
  
- ✅ **Coupons & Discounts**
  - Create discount codes
  - Set expiry dates
  - Usage limits
  
- ✅ **Settings**
  - Payment gateway configuration
  - Tax settings
  - Site branding
  - Email notifications

### Design Features
- 📱 **100% Responsive Design**
  - Mobile-first approach
  - Native app-like experience on mobile
  - Professional desktop layout
  - Adaptive components
  
- 🌓 **Dark/Light Mode**
  - Theme toggle
  - Persistent theme preference
  
- 🎨 **Modern UI**
  - MDBootstrap framework
  - Smooth animations
  - Clean, professional design
  - Touch-friendly mobile interface

## 🛠️ Tech Stack

- **Backend:** PHP 8+ (No frameworks - pure PHP with MVC pattern)
- **Database:** MySQL 8.0+ with PDO
- **Frontend:** TailwindCSS 3.x (CDN)
- **Icons:** Font Awesome 6.x
- **Server:** Apache with mod_rewrite
- **Security:** Prepared statements, CSRF protection, secure file downloads

## 📋 Requirements

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache Web Server with mod_rewrite enabled
- XAMPP/WAMP (recommended for local development)
- Composer (optional, for future dependencies)

## 🚀 Installation

### Step 1: Clone or Download
```bash
# Clone the repository or download the ZIP file
# Extract to your XAMPP htdocs folder
```

### Step 2: Database Setup
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Import the database schema:
   - Click "Import" tab
   - Select `database/schema.sql`
   - Click "Go" (this will create the database and all tables)

### Step 3: Configuration
1. Copy `.env.example` to `.env` (optional, or use defaults)
2. Open `app/config/config.php`
3. Update database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'ybt_digital');
   ```

4. Update BASE_URL for your environment:
   ```php
   define('BASE_URL', 'http://localhost/ybt_digital/public');
   ```

5. Configure payment gateway (choose one):
   ```php
   // For Stripe
   define('PAYMENT_PROVIDER', 'stripe');
   define('STRIPE_PUBLIC_KEY', 'pk_test_XXXX');
   define('STRIPE_SECRET_KEY', 'sk_test_XXXX');
   
   // For Razorpay
   define('PAYMENT_PROVIDER', 'razorpay');
   define('RAZORPAY_KEY_ID', 'rzp_test_XXXX');
   define('RAZORPAY_KEY_SECRET', 'XXXX');
   ```

### Step 4: Secure Storage Setup
**IMPORTANT:** Product files must be stored outside the public directory.

1. Create secure storage directory:
   ```bash
   mkdir storage_secure
   mkdir storage_secure/product_files
   chmod 755 storage_secure
   chmod 755 storage_secure/product_files
   ```

2. Ensure `storage_secure` is NOT inside the `public` folder
3. Update path in `app/config/config.php` if needed

### Step 5: Directory Permissions
Ensure the following directories are writable:
- `public/uploads/`
- `public/uploads/thumbnails/`
- `public/uploads/products/`
- `storage_secure/product_files/`

```bash
chmod -R 755 public/uploads
chmod -R 755 storage_secure
```

### Step 6: Run Demo Data Seeder (Optional)
```bash
php scripts/seed_demo_data.php
```

### Step 7: Access the Website
- **Frontend:** http://localhost/ybt_digital/public
- **Admin Panel:** http://localhost/ybt_digital/public/admin/dashboard

## 🔐 Default Admin Credentials

```
Email: admin@ybtdigital.com
Password: admin123
```

**⚠️ IMPORTANT:** Change the default admin password immediately after first login!

## 📱 Mobile Features

The website includes a native app-like experience on mobile devices:
- **Bottom Navigation Bar** with 4 tabs (Home, Products, Cart, Profile)
- **AppBar** at the top with logo and menu
- **Full-width cards** and touch-friendly buttons
- **Vertical scrolling** product lists
- **Material Design** inspired UI components

## 💳 Payment Gateway Setup

### Demo Mode (Default)
The system comes with a demo payment mode that auto-completes orders for testing.

### Razorpay Integration
1. Sign up at https://razorpay.com
2. Get your API keys
3. Go to Admin Panel → Settings
4. Enter Razorpay Key ID and Secret
5. Select Razorpay as payment gateway

### Stripe Integration
1. Sign up at https://stripe.com
2. Get your API keys
3. Go to Admin Panel → Settings
4. Enter Stripe Public and Secret keys
5. Select Stripe as payment gateway

### PayPal Integration
1. Sign up at https://developer.paypal.com
2. Get your Client ID and Secret
3. Go to Admin Panel → Settings
4. Enter PayPal credentials
5. Select PayPal as payment gateway

## 📂 Project Structure

```
ybt_digital/
├── app/
│   ├── config/
│   │   └── config.php              # Main configuration file
│   ├── core/
│   │   ├── Database.php            # PDO singleton
│   │   └── Helpers.php             # Helper functions
│   ├── controllers/
│   │   ├── AuthController.php      # Authentication logic
│   │   ├── HomeController.php      # Homepage
│   │   ├── ProductController.php   # Product listing/detail
│   │   ├── CartController.php      # Shopping cart
│   │   ├── CheckoutController.php  # Checkout process
│   │   ├── OrderController.php     # Order management
│   │   ├── DownloadController.php  # Secure downloads
│   │   ├── AdminController.php     # Admin panel
│   │   └── UserController.php      # User management (admin)
│   ├── models/
│   │   ├── User.php                # User model
│   │   ├── Product.php             # Product model
│   │   ├── ProductFile.php         # Product files
│   │   ├── Category.php            # Categories
│   │   ├── Order.php               # Orders
│   │   ├── OrderItem.php           # Order items
│   │   ├── Coupon.php              # Discount coupons
│   │   ├── Download.php            # Download tokens
│   │   └── AdminAction.php         # Audit logging
│   └── views/
│       ├── layouts/
│       │   ├── header.php          # Main header
│       │   ├── footer.php          # Main footer
│       │   └── admin_header.php    # Admin layout
│       ├── home.php                # Homepage view
│       ├── auth/                   # Auth views
│       ├── products/               # Product views
│       ├── cart/                   # Cart views
│       ├── checkout/               # Checkout views
│       ├── orders/                 # Order views
│       ├── profile/                # Profile views
│       └── admin/                  # Admin views
├── public/
│   ├── index.php                   # Front controller (router)
│   ├── download.php                # Secure download handler
│   ├── .htaccess                   # URL rewriting
│   ├── css/
│   ├── js/
│   └── uploads/                    # Public uploads (thumbnails)
├── storage_secure/
│   └── product_files/              # Secure file storage (outside public)
├── database/
│   └── schema.sql                  # Database schema & seed data
├── scripts/
│   └── seed_demo_data.php          # Demo data seeder
├── tailwind.config.js              # Tailwind configuration
├── .env.example                    # Environment variables template
├── README.md                       # This file
└── ACCEPTANCE_CHECKLIST.md         # Testing checklist
```

## 🔧 Configuration Options

### Site Settings (Admin Panel → Settings)
- Site name and logo
- Currency and tax rates
- Payment gateway selection
- Email notifications
- SMTP configuration

### Security Settings
- Password minimum length
- Session timeout
- Max login attempts
- Download limits
- File upload restrictions

## 📝 Usage Guide

### For Users
1. **Browse Products:** Visit the homepage and explore products
2. **Add to Cart:** Click "Add to Cart" on product pages
3. **Checkout:** Review cart and proceed to checkout
4. **Payment:** Complete payment through selected gateway
5. **Download:** Access downloads from "My Orders" page

### For Admins
1. **Login:** Access admin panel at `/admin/`
2. **Add Products:** Navigate to Products → Add New
3. **Upload Files:** Upload digital product files
4. **Manage Orders:** View and process orders
5. **Create Coupons:** Set up discount codes
6. **Configure Settings:** Customize site settings

## 🎨 Customization

### Changing Colors
Edit the CSS variables in `includes/header.php`:
```css
:root {
    --primary-color: #1266f1;
    --secondary-color: #b23cfd;
    /* ... other colors */
}
```

### Adding Payment Gateways
1. Create handler file in `payment/` directory
2. Update `process-payment.php` routing
3. Add configuration in admin settings

## 🐛 Troubleshooting

### Database Connection Error
- Check database credentials in `config/database.php`
- Ensure MySQL service is running
- Verify database exists

### File Upload Issues
- Check directory permissions (755 or 777)
- Verify `upload_max_filesize` in php.ini
- Check `post_max_size` in php.ini

### Payment Not Working
- Verify API keys are correct
- Check payment gateway is selected in settings
- Review error logs

## 📄 License

This project is open-source and available for personal and commercial use.

## 🤝 Support

For support, please:
1. Check the FAQ page
2. Submit a support ticket through the contact form
3. Email: admin@ybtdigital.com

## 🔄 Updates

To update the system:
1. Backup your database
2. Backup your files
3. Replace files with new version
4. Run any new database migrations

## ⚠️ Security Notes

- Change default admin credentials immediately
- Use strong passwords
- Keep PHP and MySQL updated
- Enable HTTPS in production
- Regularly backup your database
- Sanitize all user inputs
- Use prepared statements for database queries

## 🎯 Future Enhancements

- Multi-language support
- Advanced analytics
- Email marketing integration
- Social media login
- Product reviews and ratings
- Wishlist functionality
- Affiliate system
- API for mobile apps

---

**Developed with ❤️ for YBT Digital**

For questions or feature requests, please contact the development team.
