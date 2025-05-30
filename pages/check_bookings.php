<?php
require_once '../config/database.php';

try {
    // Check table structure
    $structure_query = "DESCRIBE bookings";
    $structure_result = $pdo->query($structure_query);
    
    echo "<h3>Table Structure:</h3>";
    echo "<pre>";
    while ($row = $structure_result->fetch()) {
        print_r($row);
    }
    echo "</pre>";

    // Check cancelled bookings
    $cancelled_query = "SELECT * FROM bookings WHERE cancelled = 1";
    $cancelled_result = $pdo->query($cancelled_query);
    
    echo "<h3>Cancelled Bookings:</h3>";
    echo "<pre>";
    while ($row = $cancelled_result->fetch()) {
        print_r($row);
    }
    echo "</pre>";

    // Add cancelled column if not exists
    $add_column_query = "ALTER TABLE bookings 
        ADD COLUMN IF NOT EXISTS cancelled TINYINT(1) DEFAULT 0,
        ADD COLUMN IF NOT EXISTS cancelled_at DATETIME DEFAULT NULL";
    $pdo->exec($add_column_query);
    
    echo "<h3>Added cancelled columns if they didn't exist.</h3>";

} catch (PDOException $e) {
    echo "<h3>Error:</h3>";
    echo "<pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?> 