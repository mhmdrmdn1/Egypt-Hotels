<?php
require_once '../config/database.php';

// The password we want to hash
$password = '01142377524m';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $pdo = getPDO();
    
    // Update the admin's password
    $sql = "UPDATE admins SET password = ? WHERE username = 'mhmd_rmdn_1'";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$hash]);
    
    if ($result) {
        echo "Successfully updated admin password hash!\n";
        echo "Username: mhmd_rmdn_1\n";
        echo "Password: " . $password . "\n";
        echo "Hash: " . $hash . "\n";
    } else {
        echo "Failed to update password hash.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 