<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/pdo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'signin':
            handleSignIn($pdo);
            break;
        case 'signup':
            handleSignUp($pdo);
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password) {
    return strlen($password) >= 8 && preg_match('/[A-Za-z]/', $password) && preg_match('/[0-9]/', $password);
}

function handleSignIn($pdo) {
    $login_input = trim($_POST['email'] ?? ''); // can be email or username
    $password = $_POST['password'] ?? '';
    $rememberMe = $_POST['remember_me'] ?? 'false';
    
    if (empty($login_input) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields']);
        return;
    }
    
    try {
        // Allow login with either email or username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$login_input, $login_input]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'] ?? $user['name'];
            $_SESSION['first_name'] = $user['first_name'] ?? null;
            $_SESSION['last_name'] = $user['last_name'] ?? null;
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_profile_image'] = $user['profile_image'] ?? null;
            $_SESSION['user_type'] = 'user';
            $_SESSION['phone'] = $user['phone'] ?? null;
            $_SESSION['date_of_birth'] = $user['date_of_birth'] ?? null;
            $_SESSION['gender'] = $user['gender'] ?? null;
            $_SESSION['address'] = $user['address'] ?? null;
            $_SESSION['email_notifications'] = $user['email_notifications'] ?? 1;
            $_SESSION['sms_notifications'] = $user['sms_notifications'] ?? 1;
            $_SESSION['marketing_emails'] = $user['marketing_emails'] ?? 0;

            // جلب الدور الرئيسي للمستخدم من user_roles/roles
            $roleStmt = $pdo->prepare("SELECT r.name FROM roles r JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = ? LIMIT 1");
            $roleStmt->execute([$user['id']]);
            $mainRole = $roleStmt->fetchColumn();
            
            // تعريف الأدوار المسموح بها
            $allowed_roles = ['user', 'admin', 'manager', 'staff', 'editor'];
            $_SESSION['role'] = in_array($mainRole, $allowed_roles) ? $mainRole : 'user';

            // Check if this user is also an admin (by username)
            $stmtAdmin = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
            $stmtAdmin->execute([$_SESSION['username']]);
            $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);
            if ($admin) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_role'] = 'admin';
            } else {
                // التحقق من الأدوار الأخرى
                $otherRoles = ['manager', 'staff', 'editor'];
                if (in_array($_SESSION['role'], $otherRoles)) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_username'] = $user['username'];
                    $_SESSION['admin_role'] = $_SESSION['role'];
                } else {
                    unset($_SESSION['admin_logged_in']);
                    unset($_SESSION['admin_id']);
                    unset($_SESSION['admin_username']);
                    unset($_SESSION['admin_role']);
                }
            }

            if ($rememberMe === 'true') {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                $stmt = $pdo->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
                $stmt->execute([$user['id'], $token, $expires]);
                $cookie_expire = time() + (86400 * 30);
                setcookie('remember_token', $token, $cookie_expire, "/", $_SERVER['HTTP_HOST'], true, true);
                setcookie('remember_user', $user['id'], $cookie_expire, "/", $_SERVER['HTTP_HOST'], true, true);
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Login successful',
                'user_type' => $_SESSION['user_type'],
                'is_admin' => isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true,
                'user' => [
                    'username' => $_SESSION['username'],
                    'email' => $_SESSION['user_email']
                ]
            ]);
            return;
        }
        
        echo json_encode(['status' => 'error', 'message' => 'Incorrect email/username or password']);
    } catch(PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database error. Please try again later.']);
    }
}

function handleSignUp($pdo) {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');

    if (empty($name) || empty($username) || empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields including username']);
        return;
    }
    
    if (!empty($username) && !preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
         echo json_encode(['status' => 'error', 'message' => 'Username must be 3-20 characters long and contain only letters, numbers, and underscores.']);
         return;
    }

    if (!validateEmail($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address']);
        return;
    }
    
    if (!validatePassword($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters long and contain both letters and numbers']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?" . (!empty($username) ? " OR username = ?" : ""));
        $params = [$email];
        if (!empty($username)) {
            $params[] = $username;
        }
        $stmt->execute($params);

        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email or username already registered']);
            return;
        }
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (name, username, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $username, $first_name, $last_name, $email, $hashed_password]);

        $user_id = $pdo->lastInsertId();
        
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_type'] = 'user';


        echo json_encode([
            'status' => 'success', 
            'message' => 'Registration successful. Please sign in.'
        ]);
    } catch(PDOException $e) {
        error_log("Signup error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database error during registration. Please try again later.']);
    }
}
?> 