<?php
require_once '../app/config/config.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/Helpers.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if not exists
if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
}

$errors = [];
$name = '';
$email = '';

// Process registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST[CSRF_TOKEN_NAME]) || $_POST[CSRF_TOKEN_NAME] !== $_SESSION[CSRF_TOKEN_NAME]) {
        $errors[] = 'Security validation failed. Please try again.';
    } else {
        // Validate input
        $name = trim($_POST['name'] ?? '');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        // Validate name
        if (empty($name)) {
            $errors[] = 'Name is required.';
        } elseif (strlen($name) > 80) {
            $errors[] = 'Name must be less than 80 characters.';
        }

        // Validate email
        if (!$email) {
            $errors[] = 'Please enter a valid email address.';
        }

        // Validate password
        if (empty($password)) {
            $errors[] = 'Password is required.';
        } elseif (strlen($password) < PASSWORD_MIN_LENGTH) {
            $errors[] = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters.';
        } elseif ($password !== $password_confirm) {
            $errors[] = 'Passwords do not match.';
        }

        // If no validation errors, attempt registration
        if (empty($errors)) {
            try {
                $db = Database::getInstance();
                $pdo = $db->getConnection();

                // Check if email already exists
                $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                
                if ($stmt->fetch()) {
                    $errors[] = 'Email address is already registered.';
                } else {
                    // Hash password
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Insert new user
                    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)');
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password_hash);
                    $role = 'user'; // Default role
                    $stmt->bindParam(':role', $role);
                    
                    if ($stmt->execute()) {
                        // Registration successful - set session variables
                        $_SESSION['user_id'] = $pdo->lastInsertId();
                        $_SESSION['name'] = $name;
                        $_SESSION['role'] = $role;
                        
                        // Regenerate session ID for security
                        session_regenerate_id(true);
                        
                        // Redirect to home page
                        header('Location: ' . BASE_URL . '/index.php');
                        exit;
                    } else {
                        $errors[] = 'Registration failed. Please try again.';
                    }
                }
            } catch (PDOException $e) {
                $errors[] = 'Database error: ' . (APP_DEBUG ? $e->getMessage() : 'Please try again later.');
            }
        }
    }
}

// Regenerate CSRF token after form submission (regardless of success/failure)
$_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - YBT Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Create an Account</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $_SESSION[CSRF_TOKEN_NAME] ?>">
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Full Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    required>
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    required>
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" id="password" name="password" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    required minlength="<?= PASSWORD_MIN_LENGTH ?>">
                <p class="text-sm text-gray-600 mt-1">Must be at least <?= PASSWORD_MIN_LENGTH ?> characters</p>
            </div>
            
            <div class="mb-6">
                <label for="password_confirm" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                <input type="password" id="password_confirm" name="password_confirm" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    required minlength="<?= PASSWORD_MIN_LENGTH ?>">
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
                Register
            </button>
        </form>
        
        <div class="mt-4 text-center">
            <p>Already have an account? <a href="login.php" class="text-blue-500 hover:underline">Login here</a></p>
        </div>
    </div>
</body>
</html>