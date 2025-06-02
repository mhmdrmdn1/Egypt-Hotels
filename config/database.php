<?php
// Enable error reporting for development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database configuration constants
define('DB_HOST', 'localhost');
define('DB_NAME', 'egypt_hotels');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Global PDO connection instance
$pdo = null;

// Function to get PDO connection
function getPDO() {
    static $pdo = null;
    
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true // Use persistent connections
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            // Test the connection
            $pdo->query('SELECT 1');
        } catch (PDOException $e) {
            // Throw a generic error for production
            throw new Exception('Database connection failed. Please try again later.');
        }
    }
    
    return $pdo;
}

function closeConnection() {
    global $pdo;
    $pdo = null;
}

// Initialize the connection
try {
    $pdo = getPDO();
} catch (Exception $e) {
    // Handle initial connection error
    if (getenv('ENVIRONMENT') === 'development') {
        die(json_encode([
            'error' => 'Database connection failed',
            'message' => $e->getMessage()
        ]));
    } else {
        die(json_encode([
            'error' => 'Database connection failed',
            'message' => 'A database error occurred. Please try again later.'
        ]));
    }
}
?> 