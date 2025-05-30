<?php
require_once '../config/database.php';

// Enable error reporting for development
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Read the SQL file
    $sql = file_get_contents('../sql/update_database.sql');
    
    // Split into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    // Execute each statement
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
                echo "Successfully executed: " . substr($statement, 0, 50) . "...<br>";
            } catch (PDOException $e) {
                echo "Error executing: " . substr($statement, 0, 50) . "...<br>";
                echo "Error message: " . $e->getMessage() . "<br><br>";
            }
        }
    }
    
    echo "<br>Database update completed!";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} 