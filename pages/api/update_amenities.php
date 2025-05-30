<?php
require_once '../../config/database.php';

try {
    // Read and execute SQL file
    $sql = file_get_contents(__DIR__ . '/update_amenities.sql');
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    // Execute each statement
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "Successfully updated amenities database structure and data.";
    
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
} 