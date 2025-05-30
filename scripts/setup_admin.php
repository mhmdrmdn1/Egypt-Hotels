<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../config/database.php');

// Admin credentials
$username = 'mhmd_rmdn_1';
$password = '01142377524m';

try {
    $pdo = getPDO();
    
    // Start transaction
    $pdo->beginTransaction();
    
    try {
        // Create tables if they don't exist
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `admins` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `username` varchar(255) NOT NULL,
                `password` varchar(255) NOT NULL,
                `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `last_login` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `username` (`username`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            
            CREATE TABLE IF NOT EXISTS `roles` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(50) NOT NULL,
                `description` text,
                `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `name` (`name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            
            CREATE TABLE IF NOT EXISTS `user_roles` (
                `user_id` int(11) NOT NULL,
                `role_id` int(11) NOT NULL,
                PRIMARY KEY (`user_id`, `role_id`),
                FOREIGN KEY (`user_id`) REFERENCES `admins`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
        
        echo "Tables created successfully\n";
        
        // Create password hash
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // First, remove existing admin user and their roles
        $pdo->exec("DELETE FROM user_roles WHERE user_id IN (SELECT id FROM admins WHERE username = '$username')");
        $pdo->exec("DELETE FROM admins WHERE username = '$username'");
        
        // Insert admin user
        $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hash]);
        $admin_id = $pdo->lastInsertId();
        
        echo "Admin user created with ID: $admin_id\n";
        echo "Username: $username\n";
        echo "Password hash: $hash\n";
        
        // Ensure admin role exists
        $pdo->exec("INSERT INTO roles (name, description) VALUES ('admin', 'Full system administrator') ON DUPLICATE KEY UPDATE description = VALUES(description)");
        
        // Get admin role ID
        $stmt = $pdo->query("SELECT id FROM roles WHERE name = 'admin'");
        $role = $stmt->fetch();
        
        if (!$role) {
            throw new Exception("Failed to find admin role after creation");
        }
        
        $role_id = $role['id'];
        
        // Remove any existing role assignments for this user
        $stmt = $pdo->prepare("DELETE FROM user_roles WHERE user_id = ?");
        $stmt->execute([$admin_id]);
        
        // Assign admin role to user
        $stmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
        $stmt->execute([$admin_id, $role_id]);
        
        echo "Admin role assigned successfully\n";
        
        // Commit transaction
        $pdo->commit();
        
        // Verify setup
        echo "\nVerifying setup:\n";
        
        // Test password verification
        $stmt = $pdo->prepare("SELECT password FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $stored = $stmt->fetch();
        
        if (!$stored) {
            echo "❌ Admin user not found after creation!\n";
        } else if (password_verify($password, $stored['password'])) {
            echo "✅ Password verification successful\n";
        } else {
            echo "❌ Password verification failed\n";
        }
        
        // Check role assignment
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as has_role 
            FROM user_roles ur 
            JOIN roles r ON ur.role_id = r.id 
            WHERE ur.user_id = ? AND r.name = 'admin'
        ");
        $stmt->execute([$admin_id]);
        $role_check = $stmt->fetch();
        
        if ($role_check['has_role'] > 0) {
            echo "✅ Admin role verified\n";
        } else {
            echo "❌ Admin role not found\n";
            
            // Show current roles
            $stmt = $pdo->prepare("
                SELECT r.name 
                FROM roles r 
                JOIN user_roles ur ON r.id = ur.role_id 
                WHERE ur.user_id = ?
            ");
            $stmt->execute([$admin_id]);
            $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "Current roles: " . implode(", ", $roles) . "\n";
        }
        
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    
    // Show current database state
    try {
        echo "\nCurrent database state:\n";
        
        // Check admins table
        $result = $pdo->query("SELECT id, username FROM admins WHERE username = '$username'")->fetch();
        echo $result ? "Admin user exists with ID: {$result['id']}\n" : "Admin user does not exist\n";
        
        // Check roles table
        $result = $pdo->query("SELECT id, name FROM roles WHERE name = 'admin'")->fetch();
        echo $result ? "Admin role exists with ID: {$result['id']}\n" : "Admin role does not exist\n";
        
        // Check user_roles table
        if (isset($admin_id)) {
            $stmt = $pdo->prepare("SELECT role_id FROM user_roles WHERE user_id = ?");
            $stmt->execute([$admin_id]);
            $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "User roles: " . implode(", ", $roles) . "\n";
        }
    } catch (Exception $e) {
        echo "Failed to check database state: " . $e->getMessage() . "\n";
    }
} 