<?php
// Demo Data Seeder Script

require_once __DIR__ . '/../app/config/config.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/Helpers.php';

$db = Database::getInstance();

echo "Starting demo data seeding...\n\n";

// Create demo users
echo "Creating demo users...\n";
$demoUsers = [
    [
        'name' => 'Demo User',
        'email' => 'user@demo.com',
        'password' => password_hash('user123', PASSWORD_DEFAULT),
        'role' => 'user'
    ],
    [
        'name' => 'John Doe',
        'email' => 'john@demo.com',
        'password' => password_hash('demo123', PASSWORD_DEFAULT),
        'role' => 'user'
    ]
];

foreach ($demoUsers as $user) {
    try {
        $db->insert('users', $user);
        echo "Created user: {$user['email']}\n";
    } catch (Exception $e) {
        echo "User {$user['email']} already exists\n";
    }
}

// Get demo user ID
$demoUser = $db->fetchOne("SELECT * FROM users WHERE email = ?", ['user@demo.com']);
$demoUserId = $demoUser['id'];

// Create demo order
echo "\nCreating demo order...\n";

$db->beginTransaction();

try {
    // Create order
    $orderId = $db->insert('orders', [
        'user_id' => $demoUserId,
        'order_number' => 'ORD-DEMO-' . strtoupper(uniqid()),
        'subtotal' => 49.99,
        'discount' => 0,
        'total' => 49.99,
        'payment_provider' => 'stripe',
        'payment_provider_id' => 'demo_' . uniqid(),
        'payment_status' => 'completed',
        'order_status' => 'completed'
    ]);
    
    echo "Created order #$orderId\n";
    
    // Get first product
    $product = $db->fetchOne("SELECT * FROM products LIMIT 1");
    
    if ($product) {
        // Create order item
        $orderItemId = $db->insert('order_items', [
            'order_id' => $orderId,
            'product_id' => $product['id'],
            'product_title' => $product['title'],
            'price' => $product['price'],
            'quantity' => 1
        ]);
        
        echo "Created order item for product: {$product['title']}\n";
        
        // Create download token
        $downloadToken = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        $downloadId = $db->insert('downloads', [
            'order_item_id' => $orderItemId,
            'user_id' => $demoUserId,
            'product_id' => $product['id'],
            'download_token' => $downloadToken,
            'expires_at' => $expiresAt,
            'max_uses' => 5,
            'used_count' => 0
        ]);
        
        echo "Created download token: $downloadToken\n";
        echo "Download URL: " . BASE_URL . "/download.php?token=$downloadToken\n";
    }
    
    $db->commit();
    echo "\nDemo order created successfully!\n";
    
} catch (Exception $e) {
    $db->rollBack();
    echo "Error creating demo order: " . $e->getMessage() . "\n";
}

// Create demo product file references
echo "\nCreating product file references...\n";

$products = $db->fetchAll("SELECT * FROM products");

foreach ($products as $product) {
    // Check if file already exists
    $existingFile = $db->fetchOne("SELECT * FROM product_files WHERE product_id = ?", [$product['id']]);
    
    if (!$existingFile) {
        $db->insert('product_files', [
            'product_id' => $product['id'],
            'filename' => 'demo_' . $product['id'] . '.zip',
            'original_filename' => sanitize($product['title']) . '.zip',
            'file_size' => 1024 * 1024, // 1MB placeholder
            'mime_type' => 'application/zip'
        ]);
        echo "Created file reference for product: {$product['title']}\n";
    }
}

// Create demo admin actions
echo "\nLogging demo admin actions...\n";

$adminUser = $db->fetchOne("SELECT * FROM users WHERE role = 'admin' LIMIT 1");

if ($adminUser) {
    $adminActions = [
        [
            'admin_id' => $adminUser['id'],
            'action_type' => 'system_setup',
            'action_description' => 'Demo data seeded successfully',
            'ip_address' => '127.0.0.1'
        ]
    ];
    
    foreach ($adminActions as $action) {
        $db->insert('admin_actions', $action);
    }
    
    echo "Logged admin actions\n";
}

echo "\n===========================================\n";
echo "Demo data seeding completed!\n";
echo "===========================================\n\n";
echo "Login Credentials:\n";
echo "-------------------\n";
echo "Admin:\n";
echo "  Email: admin@ybtdigital.com\n";
echo "  Password: admin123\n\n";
echo "Demo User:\n";
echo "  Email: user@demo.com\n";
echo "  Password: user123\n\n";
echo "Additional User:\n";
echo "  Email: john@demo.com\n";
echo "  Password: demo123\n\n";
echo "===========================================\n";
