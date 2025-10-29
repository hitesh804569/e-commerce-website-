# YBT Digital - Acceptance Testing Checklist

## User Features

### Authentication
- [ ] User can register with name, email, and password
- [ ] User can login with email and password
- [ ] User can logout
- [ ] User can request password reset
- [ ] User can reset password with valid token
- [ ] Invalid login attempts show error message
- [ ] Duplicate email registration is prevented

### Profile Management
- [ ] User can view their profile
- [ ] User can update their name
- [ ] User can change their password
- [ ] Current password is required to change password
- [ ] Profile updates are saved correctly

### Product Browsing
- [ ] Homepage displays featured products
- [ ] Homepage displays product categories
- [ ] Product listing page shows all products
- [ ] Products can be filtered by category
- [ ] Products can be searched by keyword
- [ ] Pagination works correctly on product listing
- [ ] Product detail page shows full information
- [ ] Product detail page displays screenshots (if any)

### Shopping Cart
- [ ] User can add products to cart
- [ ] User can update quantity in cart
- [ ] User can remove items from cart
- [ ] Cart displays correct subtotal
- [ ] Cart persists across sessions
- [ ] Empty cart shows appropriate message

### Checkout & Orders
- [ ] User must be logged in to checkout
- [ ] Checkout page displays cart items
- [ ] Checkout calculates total correctly
- [ ] User can apply coupon codes
- [ ] Valid coupons apply discount
- [ ] Invalid/expired coupons show error
- [ ] Order is created successfully after payment
- [ ] Order number is generated uniquely
- [ ] Payment provider ID is recorded

### Order Management
- [ ] User can view their order history
- [ ] Order detail page shows all items
- [ ] Order detail displays payment status
- [ ] Order detail displays correct totals

### Secure Downloads
- [ ] Download tokens are generated for completed orders
- [ ] Download URL works with valid token
- [ ] Download token validates expiry date
- [ ] Download token validates usage limit
- [ ] Download counter increments correctly
- [ ] Expired tokens are rejected
- [ ] Exceeded usage limit tokens are rejected
- [ ] Download logs are created
- [ ] Files are streamed (not exposed via direct path)
- [ ] User can only download their own purchases

## Admin Features

### Admin Authentication
- [ ] Admin can login with admin credentials
- [ ] Regular users cannot access admin panel
- [ ] Admin role is verified on all admin pages

### Dashboard
- [ ] Dashboard displays total revenue
- [ ] Dashboard displays monthly revenue
- [ ] Dashboard shows recent orders
- [ ] Dashboard shows top-selling products

### Product Management
- [ ] Admin can view all products
- [ ] Admin can add new product with details
- [ ] Admin can upload product thumbnail
- [ ] Admin can upload digital product file
- [ ] Product file is stored in secure location
- [ ] Admin can edit existing products
- [ ] Admin can delete products
- [ ] Product deletion removes associated files
- [ ] Admin can mark products as featured
- [ ] Admin actions are logged

### Order Management
- [ ] Admin can view all orders
- [ ] Admin can filter orders by status
- [ ] Admin can view order details
- [ ] Admin can see payment provider ID
- [ ] Admin can view customer information
- [ ] Orders display correctly with pagination

### User Management
- [ ] Admin can view all users
- [ ] Admin can search users by name/email
- [ ] Admin can filter users by role
- [ ] Admin can filter users by blocked status
- [ ] Admin can view user details
- [ ] Admin can view user's purchase history
- [ ] Admin can block/unblock users
- [ ] Admin can change user roles
- [ ] Admin can delete users (except themselves)
- [ ] Blocked users cannot login
- [ ] User management actions are logged

### Coupon Management
- [ ] Admin can create new coupons
- [ ] Admin can set coupon type (flat/percentage)
- [ ] Admin can set coupon value
- [ ] Admin can set minimum purchase amount
- [ ] Admin can set usage limits
- [ ] Admin can set expiry dates
- [ ] Admin can delete coupons
- [ ] Coupon usage is tracked

### Reports
- [ ] Reports show revenue by date range
- [ ] Reports display top-selling products
- [ ] Date filters work correctly
- [ ] Revenue calculations are accurate

### Audit Logging
- [ ] Product creation is logged
- [ ] Product updates are logged
- [ ] Product deletions are logged
- [ ] User blocks/unblocks are logged
- [ ] User role changes are logged
- [ ] User deletions are logged
- [ ] Coupon creation is logged
- [ ] Coupon deletions are logged
- [ ] Admin actions include timestamp
- [ ] Admin actions include IP address

## Security

### Input Validation
- [ ] All form inputs are sanitized
- [ ] SQL injection is prevented (prepared statements)
- [ ] XSS attacks are prevented
- [ ] CSRF tokens are validated on all forms
- [ ] File upload types are validated
- [ ] File upload sizes are limited

### Authentication & Authorization
- [ ] Passwords are hashed (not stored plain text)
- [ ] Session management is secure
- [ ] Login required for protected pages
- [ ] Admin role required for admin pages
- [ ] Users can only access their own data

### File Security
- [ ] Product files are outside public directory
- [ ] Direct file access is prevented
- [ ] Download tokens are required
- [ ] Thumbnail uploads are validated
- [ ] Malicious file uploads are prevented

## Performance & UX

### Responsive Design
- [ ] Homepage is mobile responsive
- [ ] Product listing is mobile responsive
- [ ] Product detail is mobile responsive
- [ ] Cart is mobile responsive
- [ ] Checkout is mobile responsive
- [ ] Admin panel is usable on tablets
- [ ] Navigation works on all screen sizes

### User Experience
- [ ] Flash messages display correctly
- [ ] Error messages are clear and helpful
- [ ] Success messages confirm actions
- [ ] Loading states are indicated
- [ ] Forms have proper validation feedback
- [ ] Breadcrumbs/navigation is clear

### Database
- [ ] All queries use prepared statements
- [ ] Proper indexes are in place
- [ ] Foreign keys maintain integrity
- [ ] Transactions are used where needed

## Edge Cases

- [ ] Empty search returns all products
- [ ] Invalid product ID shows 404
- [ ] Invalid order ID redirects appropriately
- [ ] Invalid download token shows error
- [ ] Cart works with session timeout
- [ ] Concurrent order creation is handled
- [ ] Download counter is atomic
- [ ] Payment failures are handled gracefully

---

## Test Accounts

**Admin:**
- Email: admin@ybtdigital.com
- Password: admin123

**Demo User:**
- Email: user@demo.com
- Password: user123

**Test Coupon:**
- Code: WELCOME10 (10% off, min $20 purchase)

---

## Notes

- Run `php scripts/seed_demo_data.php` to populate test data
- Use Stripe test keys for payment testing
- Download tokens expire in 30 days by default
- Maximum 5 downloads per purchase by default
