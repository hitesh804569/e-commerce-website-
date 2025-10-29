# YBT Digital - Digital Product Selling Website

A complete, responsive digital product marketplace built with PHP and MySQL. Features include user authentication, product management, shopping cart, payment integration, secure downloads, and a comprehensive admin panel.

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

- **Frontend:** PHP, HTML5, CSS3, JavaScript
- **UI Framework:** MDBootstrap 6.4.2
- **Icons:** Font Awesome 6.4.0
- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Server:** Apache (XAMPP)

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache Web Server
- XAMPP (recommended for local development)

## 🚀 Installation

### Step 1: Clone or Download
```bash
# Clone the repository or download the ZIP file
# Extract to your XAMPP htdocs folder
```

### Step 2: Database Setup
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named `ybt_digital`
3. Import the database schema:
   - Navigate to the database
   - Click on "Import" tab
   - Select `database/schema.sql`
   - Click "Go" to import

### Step 3: Configuration
1. Open `config/database.php`
2. Update database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'ybt_digital');
   ```

3. Open `config/config.php`
4. Update the SITE_URL if needed:
   ```php
   define('SITE_URL', 'http://localhost/YBT%20Digital%20%20%E2%80%93%20Digital%20Product%20Selling%20Website');
   ```

### Step 4: Directory Permissions
Ensure the following directories are writable:
- `uploads/`
- `uploads/products/`
- `uploads/screenshots/`
- `temp/`

### Step 5: Access the Website
- **Frontend:** http://localhost/YBT%20Digital%20%20%E2%80%93%20Digital%20Product%20Selling%20Website/
- **Admin Panel:** http://localhost/YBT%20Digital%20%20%E2%80%93%20Digital%20Product%20Selling%20Website/admin/

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
YBT Digital/
├── admin/                  # Admin panel files
│   ├── includes/          # Admin header/footer
│   ├── dashboard.php      # Admin dashboard
│   ├── products.php       # Product management
│   ├── orders.php         # Order management
│   └── ...
├── auth/                  # Authentication files
│   ├── login.php
│   ├── signup.php
│   └── logout.php
├── config/                # Configuration files
│   ├── config.php
│   └── database.php
├── database/              # Database schema
│   └── schema.sql
├── includes/              # Shared includes
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── pages/                 # Static pages
│   ├── contact.php
│   ├── faq.php
│   └── ...
├── payment/               # Payment handlers
│   └── demo.php
├── products/              # Product pages
│   └── index.php
├── uploads/               # Upload directory
│   ├── products/
│   └── screenshots/
├── user/                  # User dashboard
│   ├── dashboard.php
│   ├── orders.php
│   └── profile.php
├── cart/                  # Shopping cart
├── index.php              # Homepage
├── product.php            # Product detail
├── checkout.php           # Checkout page
└── download.php           # Download handler
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
