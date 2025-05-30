<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';

// Test password
$password = '01142377524m';
$username = 'mhmd_rmdn_1';

// Function to create a test hash and verify it
function testPasswordHashing($password) {
    echo "Testing password hashing functionality:\n";
    $test_hash = password_hash($password, PASSWORD_DEFAULT);
    echo "Test hash created: " . $test_hash . "\n";
    $verify_result = password_verify($password, $test_hash);
    echo "Test verification result: " . ($verify_result ? "✅ Success" : "❌ Failed") . "\n\n";
    return $test_hash;
}

try {
    // Test password hashing functionality
    echo "Step 1: Testing PHP password functions\n";
    echo "----------------------------------------\n";
    $new_hash = testPasswordHashing($password);
    
    // Connect to database
    echo "Step 2: Checking database connection\n";
    echo "----------------------------------------\n";
    $pdo = getPDO();
    echo "✅ Database connection successful\n\n";
    
    // Check if admin exists
    echo "Step 3: Checking admin user in database\n";
    echo "----------------------------------------\n";
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "Found existing admin user:\n";
        echo "ID: " . $admin['id'] . "\n";
        echo "Username: " . $admin['username'] . "\n";
        echo "Current hash: " . $admin['password'] . "\n\n";
        
        echo "Step 4: Verifying password\n";
        echo "----------------------------------------\n";
        echo "Testing password: " . $password . "\n";
        $verify_result = password_verify($password, $admin['password']);
        echo "Verification result: " . ($verify_result ? "✅ Success" : "❌ Failed") . "\n\n";
        
        if (!$verify_result) {
            echo "Step 5: Updating password hash\n";
            echo "----------------------------------------\n";
            $update = $pdo->prepare("UPDATE admins SET password = ? WHERE username = ?");
            if ($update->execute([$new_hash, $username])) {
                echo "✅ Password hash updated successfully\n";
                echo "New hash: " . $new_hash . "\n\n";
            }
        }
    } else {
        echo "❌ Admin user not found\n\n";
        
        echo "Step 5: Creating admin user\n";
        echo "----------------------------------------\n";
        $insert = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        if ($insert->execute([$username, $new_hash])) {
            echo "✅ Admin user created successfully\n";
            echo "Username: " . $username . "\n";
            echo "Password hash: " . $new_hash . "\n\n";
            
            // Check if roles table exists and has admin role
            $stmt = $pdo->prepare("SELECT id FROM roles WHERE name = 'admin'");
            $stmt->execute();
            $role = $stmt->fetch();
            
            if ($role) {
                $stmt = $pdo->prepare("INSERT IGNORE INTO user_roles (user_id, role_id) VALUES ((SELECT id FROM admins WHERE username = ?), ?)");
                $stmt->execute([$username, $role['id']]);
                echo "✅ Admin role assigned\n";
            }
        }
    }
    
    echo "\nFinal verification:\n";
    echo "----------------------------------------\n";
    echo "You should now be able to login with:\n";
    echo "Username: " . $username . "\n";
    echo "Password: " . $password . "\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} 