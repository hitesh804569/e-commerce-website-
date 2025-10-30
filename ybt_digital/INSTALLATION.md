# Installation Guide - YBT Digital

This guide will walk you through the complete installation process of the YBT Digital marketplace platform.

## Prerequisites

Before you begin, ensure you have the following installed:

- **XAMPP** (or similar LAMP/WAMP stack)
  - PHP 7.4 or higher
  - MySQL 5.7 or higher
  - Apache Web Server
- **Web Browser** (Chrome, Firefox, Safari, or Edge)
- **Text Editor** (VS Code, Sublime Text, or similar)

## Step-by-Step Installation

### 1. Download and Extract

1. Download the project files
2. Extract the folder to your XAMPP `htdocs` directory
   - Windows: `C:\xampp\htdocs\`
   - Mac: `/Applications/XAMPP/htdocs/`
   - Linux: `/opt/lampp/htdocs/`

### 2. Start XAMPP Services

1. Open XAMPP Control Panel
2. Start **Apache** service
3. Start **MySQL** service
4. Verify both services are running (green indicators)

### 3. Create Database

#### Option A: Using phpMyAdmin (Recommended)

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click on "New" in the left sidebar
3. Enter database name: `ybt_digital`
4. Select collation: `utf8mb4_general_ci`
5. Click "Create"

#### Option B: Using MySQL Command Line

```sql
CREATE DATABASE ybt_digital CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

### 4. Import Database Schema

1. In phpMyAdmin, select the `ybt_digital` database
2. Click on the "Import" tab
3. Click "Choose File"
4. Navigate to: `YBT Digital – Digital Product Selling Website/database/schema.sql`
5. Click "Go" at the bottom of the page
6. Wait for the success message

**What gets imported:**
- All database tables (users, products, orders, etc.)
- Default admin account
- Sample categories
- Sample FAQs
- Default settings

### 5. Configure Database Connection

1. Open the file: `config/database.php`
2. Verify the database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Leave empty for default XAMPP
define('DB_NAME', 'ybt_digital');
```

3. If you're using custom MySQL credentials, update them here

### 6. Configure Site URL

1. Open the file: `config/config.php`
2. Update the SITE_URL constant:

```php
// For default XAMPP installation:
define('SITE_URL', 'http://localhost/YBT%20Digital%20%20%E2%80%93%20Digital%20Product%20Selling%20Website');

// If you renamed the folder to 'ybt-digital':
define('SITE_URL', 'http://localhost/ybt-digital');
```

### 7. Set Directory Permissions

Ensure these directories are writable:

#### Windows:
Right-click each folder → Properties → Security → Edit → Add write permissions

#### Mac/Linux:
```bash
chmod -R 755 uploads/
chmod -R 755 uploads/products/
chmod -R 755 uploads/screenshots/
chmod -R 755 temp/
```

**Required directories:**
- `uploads/`
- `uploads/products/`
- `uploads/screenshots/`
- `temp/`

### 8. Test the Installation

#### Frontend Access:
Open your browser and navigate to:
```
http://localhost/YBT%20Digital%20%20%E2%80%93%20Digital%20Product%20Selling%20Website/
```

You should see the homepage with:
- Hero section
- Featured products section (empty initially)
- Categories
- Footer

#### Admin Panel Access:
Navigate to:
```
http://localhost/YBT%20Digital%20%20%E2%80%93%20Digital%20Product%20Selling%20Website/admin/
```

**Default Admin Credentials:**
- Email: `admin@ybtdigital.com`
- Password: `admin123`

⚠️ **IMPORTANT:** Change this password immediately after first login!

### 9. Initial Configuration

After logging into the admin panel:

1. **Change Admin Password**
   - Go to Settings
   - Update your password
   - Save changes

2. **Configure Site Settings**
   - Navigate to Admin → Settings
   - Update:
     - Site Name
     - Currency
     - Tax Percentage
     - Email Settings

3. **Add Categories**
   - Go to Admin → Categories
   - Add your product categories
   - Set status to "Active"

4. **Add Products**
   - Go to Admin → Products
   - Click "Add New Product"
   - Fill in product details
   - Upload product files
   - Add screenshots
   - Set pricing
   - Publish

### 10. Test User Registration

1. Go to the frontend homepage
2. Click "Sign Up"
3. Create a test user account
4. Verify you can:
   - Login successfully
   - Browse products
   - Add items to cart
   - Complete checkout (demo mode)
   - Download purchased products

## Common Issues and Solutions

### Issue: Database Connection Error

**Solution:**
- Verify MySQL service is running in XAMPP
- Check database credentials in `config/database.php`
- Ensure database `ybt_digital` exists
- Check if MySQL port (3306) is not blocked

### Issue: Page Not Found (404)

**Solution:**
- Verify Apache is running
- Check SITE_URL in `config/config.php`
- Ensure `.htaccess` file exists (if using mod_rewrite)
- Clear browser cache

### Issue: Cannot Upload Files

**Solution:**
- Check directory permissions (see Step 7)
- Verify `upload_max_filesize` in `php.ini`:
  ```ini
  upload_max_filesize = 100M
  post_max_size = 100M
  ```
- Restart Apache after changing `php.ini`

### Issue: Blank White Page

**Solution:**
- Enable error reporting in `config/config.php`:
  ```php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ```
- Check Apache error logs:
  - Windows: `C:\xampp\apache\logs\error.log`
  - Mac/Linux: `/opt/lampp/logs/error_log`

### Issue: Session Not Working

**Solution:**
- Check if `session.save_path` is writable in `php.ini`
- Verify cookies are enabled in browser
- Clear browser cookies and cache

## Payment Gateway Setup

### Demo Mode (Default)
The system comes with demo payment mode enabled. Orders are auto-completed for testing.

### Razorpay Setup
1. Sign up at https://razorpay.com
2. Get API keys from Dashboard
3. In Admin Panel:
   - Go to Settings
   - Enter Razorpay Key ID
   - Enter Razorpay Key Secret
   - Select "Razorpay" as payment gateway
   - Save settings

### Stripe Setup
1. Sign up at https://stripe.com
2. Get API keys from Dashboard
3. In Admin Panel:
   - Go to Settings
   - Enter Stripe Public Key
   - Enter Stripe Secret Key
   - Select "Stripe" as payment gateway
   - Save settings

### PayPal Setup
1. Sign up at https://developer.paypal.com
2. Create an app and get credentials
3. In Admin Panel:
   - Go to Settings
   - Enter PayPal Client ID
   - Enter PayPal Secret
   - Select "PayPal" as payment gateway
   - Save settings

## Email Configuration

For production, configure SMTP settings:

1. Go to Admin → Settings
2. Scroll to Email Configuration
3. Enter:
   - SMTP Host (e.g., smtp.gmail.com)
   - SMTP Port (e.g., 587)
   - SMTP Username
   - SMTP Password
   - From Email
   - From Name
4. Save settings

**Gmail SMTP Example:**
- Host: smtp.gmail.com
- Port: 587
- Username: your-email@gmail.com
- Password: your-app-password (not regular password)

## Security Checklist

Before going live:

- [ ] Change default admin password
- [ ] Update database credentials
- [ ] Disable error display in production
- [ ] Enable HTTPS
- [ ] Set strong session timeout
- [ ] Configure proper file permissions
- [ ] Set up regular database backups
- [ ] Configure firewall rules
- [ ] Update all API keys
- [ ] Test all payment gateways

## Performance Optimization

### Enable Caching
Add to `.htaccess`:
```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

### Enable Compression
Add to `.htaccess`:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css application/javascript
</IfModule>
```

### Optimize Database
Run regularly:
```sql
OPTIMIZE TABLE users, products, orders, order_items;
```

## Backup Procedures

### Database Backup
```bash
# Export database
mysqldump -u root -p ybt_digital > backup_$(date +%Y%m%d).sql

# Import database
mysql -u root -p ybt_digital < backup_20250101.sql
```

### File Backup
Backup these directories:
- `uploads/`
- `config/` (excluding sensitive files)

## Updating the System

1. Backup database and files
2. Download new version
3. Replace files (keep config files)
4. Run any database migrations
5. Clear cache
6. Test thoroughly

## Getting Help

If you encounter issues:

1. Check this installation guide
2. Review the README.md file
3. Check error logs
4. Contact support: admin@ybtdigital.com

## Next Steps

After successful installation:

1. Customize the design (colors, logo)
2. Add your products
3. Configure payment gateways
4. Set up email notifications
5. Test the complete user flow
6. Launch your store!

---

**Congratulations!** Your YBT Digital marketplace is now installed and ready to use.

For additional help, refer to the README.md file or contact support.
