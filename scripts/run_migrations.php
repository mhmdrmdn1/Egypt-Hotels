#!/usr/bin/env php
<?php

echo "Starting migration process...\n";

if (!file_exists(__DIR__ . '/../config/database.php')) {
    echo "Error: Database configuration file not found!\n";
    exit(1);
}

require_once __DIR__ . '/../config/database.php';

echo "Database configuration loaded.\n";

try {
    echo "Connecting to database...\n";
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully.\n\n";

    $sqlFile = __DIR__ . '/../database/migrations/create_hotel_tables.sql';
    if (!file_exists($sqlFile)) {
        echo "Error: SQL migration file not found at: $sqlFile\n";
        exit(1);
    }

    echo "Reading SQL file...\n";
    $sql = file_get_contents($sqlFile);
    echo "SQL file read successfully.\n";
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    echo "Found " . count($statements) . " SQL statements to execute.\n\n";
    
    // Begin transaction
    echo "Beginning transaction...\n";
    $pdo->beginTransaction();
    
    foreach ($statements as $index => $statement) {
        if (!empty($statement)) {
            echo "Executing statement " . ($index + 1) . "...\n";
            echo "Statement: " . substr($statement, 0, 100) . "...\n";
            $pdo->exec($statement);
            echo "Statement executed successfully.\n\n";
        }
    }
    
    // Commit transaction
    echo "Committing transaction...\n";
    $pdo->commit();
    echo "\nAll migrations completed successfully!\n";
    
} catch (PDOException $e) {
    echo "\nDatabase Error:\n";
    echo "Code: " . $e->getCode() . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    
    if ($pdo->inTransaction()) {
        echo "Rolling back transaction...\n";
        $pdo->rollBack();
    }
    exit(1);
} catch (Exception $e) {
    echo "\nGeneral Error:\n";
    echo "Message: " . $e->getMessage() . "\n";
    
    if (isset($pdo) && $pdo->inTransaction()) {
        echo "Rolling back transaction...\n";
        $pdo->rollBack();
    }
    exit(1);
} 