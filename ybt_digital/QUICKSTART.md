# Quick Start Guide - YBT Digital

Get your digital marketplace up and running in 5 minutes!

## 🚀 Quick Installation (5 Minutes)

### Step 1: Setup XAMPP (1 minute)
1. Start XAMPP Control Panel
2. Click "Start" for **Apache**
3. Click "Start" for **MySQL**
4. Wait for green indicators

### Step 2: Create Database (1 minute)
1. Open browser: `http://localhost/phpmyadmin`
2. Click "New" → Enter name: `ybt_digital`
3. Click "Create"
4. Click "Import" → Choose `database/schema.sql`
5. Click "Go"

### Step 3: Access Website (30 seconds)
**Frontend:**
```
http://localhost/YBT%20Digital%20%20%E2%80%93%20Digital%20Product%20Selling%20Website/
```

**Admin Panel:**
```
http://localhost/YBT%20Digital%20%20%E2%80%93%20Digital%20Product%20Selling%20Website/admin/
```

### Step 4: Login to Admin (30 seconds)
- Email: `admin@ybtdigital.com`
- Password: `admin123`

### Step 5: Add Your First Product (2 minutes)
1. Go to Admin → Products
2. Click "Add New Product"
3. Fill in details:
   - Title: "My First Product"
   - Description: "Product description"
   - Price: 9.99
   - Category: Select one
4. Click "Save"

## ✅ You're Done!

Your marketplace is now live and ready to use!

## 🎯 Next Steps

### Immediate Actions:
1. **Change Admin Password**
   - Admin → Settings → Change Password

2. **Configure Site Settings**
   - Admin → Settings
   - Update site name
   - Set currency
   - Configure tax rate

3. **Add More Products**
   - Admin → Products → Add New
   - Upload product files
   - Add screenshots

### Test User Flow:
1. Go to frontend homepage
2. Click "Sign Up" → Create account
3. Browse products
4. Add to cart
5. Checkout (demo mode)
6. Download product

## 📱 Mobile Testing

Open on your phone:
```
http://YOUR-LOCAL-IP/YBT%20Digital%20%20%E2%80%93%20Digital%20Product%20Selling%20Website/
```

Find your local IP:
- Windows: `ipconfig` → Look for IPv4
- Mac/Linux: `ifconfig` → Look for inet

## 🎨 Customization

### Change Colors:
Edit `includes/header.php` - Look for CSS variables:
```css
:root {
    --primary-color: #1266f1;  /* Change this */
    --secondary-color: #b23cfd; /* Change this */
}
```

### Change Logo:
1. Admin → Settings
2. Upload logo image
3. Save

## 💳 Enable Real Payments

### Razorpay (Recommended for India):
1. Sign up: https://razorpay.com
2. Get API keys
3. Admin → Settings → Payment Gateway
4. Enter keys → Save

### Stripe (International):
1. Sign up: https://stripe.com
2. Get API keys
3. Admin → Settings → Payment Gateway
4. Enter keys → Save

## 🔧 Common Issues

### Can't access website?
- Check Apache is running (green in XAMPP)
- Try: `http://localhost/` to test Apache

### Database error?
- Check MySQL is running (green in XAMPP)
- Verify database name is `ybt_digital`

### Blank page?
- Check PHP errors in: `C:\xampp\apache\logs\error.log`

## 📚 Full Documentation

For detailed instructions, see:
- `README.md` - Complete feature list
- `INSTALLATION.md` - Detailed installation guide

## 🆘 Need Help?

- Email: admin@ybtdigital.com
- Check error logs in XAMPP
- Review installation guide

## 🎉 Features Overview

### User Features:
- ✅ Browse products with filters
- ✅ Shopping cart
- ✅ Secure checkout
- ✅ Instant downloads
- ✅ Order history
- ✅ Profile management

### Admin Features:
- ✅ Product management
- ✅ Order tracking
- ✅ User management
- ✅ Coupon system
- ✅ Sales reports
- ✅ Settings control

### Design Features:
- ✅ Mobile responsive
- ✅ Dark/Light mode
- ✅ Modern UI
- ✅ Fast loading

## 🔐 Security Checklist

Before going live:
- [ ] Change admin password
- [ ] Enable HTTPS
- [ ] Update database password
- [ ] Configure firewall
- [ ] Set up backups
- [ ] Test all features

## 📊 Test Data

The system includes:
- 1 Admin account
- 6 Sample categories
- 5 Sample FAQs
- Default settings

Add your own:
- Products
- Users (via signup)
- Orders (via checkout)

## 🚀 Go Live Checklist

1. **Domain Setup**
   - Point domain to server
   - Update SITE_URL in config

2. **SSL Certificate**
   - Install SSL certificate
   - Force HTTPS in .htaccess

3. **Email Configuration**
   - Set up SMTP
   - Test email sending

4. **Payment Gateway**
   - Add production API keys
   - Test transactions

5. **Backup System**
   - Set up automated backups
   - Test restore process

6. **Performance**
   - Enable caching
   - Optimize images
   - Test load times

## 💡 Pro Tips

1. **Add products regularly** - Keep your store fresh
2. **Use high-quality images** - Better conversions
3. **Create coupons** - Attract customers
4. **Monitor orders daily** - Quick support
5. **Backup weekly** - Protect your data
6. **Test on mobile** - Most users are mobile
7. **Update prices** - Stay competitive
8. **Read reviews** - Improve products

## 🎓 Learning Resources

### PHP & MySQL:
- PHP.net documentation
- MySQL documentation
- W3Schools tutorials

### Payment Gateways:
- Razorpay docs: https://razorpay.com/docs
- Stripe docs: https://stripe.com/docs
- PayPal docs: https://developer.paypal.com

### Web Development:
- MDBootstrap: https://mdbootstrap.com
- Font Awesome: https://fontawesome.com
- PHP best practices

## 📈 Growth Tips

1. **SEO Optimization**
   - Add meta descriptions
   - Use proper headings
   - Create sitemap

2. **Marketing**
   - Social media presence
   - Email newsletters
   - Affiliate program

3. **Customer Service**
   - Quick support responses
   - Clear refund policy
   - FAQ updates

4. **Analytics**
   - Track sales
   - Monitor traffic
   - Analyze conversions

## 🎯 Success Metrics

Track these KPIs:
- Total sales
- Conversion rate
- Average order value
- Customer retention
- Product views
- Cart abandonment

---

**Congratulations!** You're now ready to run your digital marketplace.

Start adding products and watch your business grow! 🚀

For detailed help, check `README.md` and `INSTALLATION.md`
