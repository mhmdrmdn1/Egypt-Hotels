<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';

echo "<h2>Database Check Results</h2>";

if (!isset($pdo)) {
    die("Database connection failed!");
}

try {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<h3>Available Tables:</h3>";
    echo "<pre>";
    print_r($tables);
    echo "</pre>";

    echo "<h3>Bookings Table Structure:</h3>";
    $structure = $pdo->query("DESCRIBE bookings")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($structure);
    echo "</pre>";

    $bookings = $pdo->query("SELECT * FROM bookings")->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>All Bookings:</h3>";
    echo "<pre>";
    print_r($bookings);
    echo "</pre>";

    echo "<h3>Hotels Table Structure:</h3>";
    $hotels = $pdo->query("DESCRIBE hotels")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($hotels);
    echo "</pre>";

    echo "<h3>Rooms Table Structure:</h3>";
    $rooms = $pdo->query("DESCRIBE rooms")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($rooms);
    echo "</pre>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} 