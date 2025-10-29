<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ybt_digital');

// Create database connection
function getDBConnection() {
    static $conn = null;
    
    if ($conn === null) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }
            
            $conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            error_log($e->getMessage());
            die("Database connection failed. Please try again later.");
        }
    }
    
    return $conn;
}

// Execute query with prepared statement
function executeQuery($query, $params = [], $types = '') {
    $conn = getDBConnection();
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $result = $stmt->execute();
    
    if (!$result) {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }
    
    return $stmt;
}

// Fetch single row
function fetchOne($query, $params = [], $types = '') {
    $stmt = executeQuery($query, $params, $types);
    if (!$stmt) return null;
    
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Fetch all rows
function fetchAll($query, $params = [], $types = '') {
    $stmt = executeQuery($query, $params, $types);
    if (!$stmt) return [];
    
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Insert and return last ID
function insertAndGetId($query, $params = [], $types = '') {
    $stmt = executeQuery($query, $params, $types);
    if (!$stmt) return false;
    
    return $stmt->insert_id;
}
?>
