<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables
    foreach ($tables as $sql) {
        $pdo->exec($sql);
    }
    
    echo "Tables created successfully\n";
    
} catch(PDOException $e) {
    die("Error creating tables: " . $e->getMessage());
}
?> 