<?php
require_once '../config/database.php';

try {
    // Check if column exists
    $check_column = "SHOW COLUMNS FROM bookings LIKE 'cancelled'";
    $result = $pdo->query($check_column);
    
    if ($result->rowCount() == 0) {
        // Add cancelled and cancelled_at columns
        $add_column = "ALTER TABLE bookings 
            ADD COLUMN cancelled TINYINT(1) DEFAULT 0,
            ADD COLUMN cancelled_at DATETIME DEFAULT NULL";
            
        if ($pdo->exec($add_column)) {
            echo "Columns added successfully<br>";
        } else {
            echo "Error adding columns<br>";
        }
    } else {
        echo "Cancelled column already exists<br>";
    }

    // Show table structure
    echo "<h3>Current Table Structure:</h3>";
    $structure = $pdo->query("DESCRIBE bookings");
    echo "<pre>";
    while ($row = $structure->fetch()) {
        print_r($row);
    }
    echo "</pre>";

    // Show current bookings data
    echo "<h3>Current Bookings Data:</h3>";
    $bookings = $pdo->query("SELECT id, email, cancelled, cancelled_at FROM bookings");
    echo "<pre>";
    while ($row = $bookings->fetch()) {
        print_r($row);
    }
    echo "</pre>";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?> 