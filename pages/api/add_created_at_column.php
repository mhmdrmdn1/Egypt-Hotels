<?php
require_once '../../config/database.php';

try {
    // Add created_at column
    $pdo->exec("ALTER TABLE reviews ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    
    // Update existing records
    $pdo->exec("UPDATE reviews SET created_at = CURRENT_TIMESTAMP WHERE created_at IS NULL");
    
    echo "Successfully added created_at column to reviews table\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 