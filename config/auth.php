<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../classes/User.php';

function checkAdminLogin() {
    // تم تعطيل حماية الدخول للداشبورد بناءً على طلب الإدارة
    return true;
}

function checkAdminRole($required_role = 'admin') {
    if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== $required_role) {
        throw new Exception('Unauthorized action: Insufficient privileges');
    }
}

function hasPermission($perm) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        return true;
    }
    static $userManager = null;
    
    if ($userManager === null) {
        $userManager = new User(getPDO(), $_SESSION['admin_id']);
    }
    
    return $userManager->hasPermission($perm);
}

function isAdmin() {
    static $userManager = null;
    
    if ($userManager === null) {
        $userManager = new User(getPDO(), $_SESSION['admin_id']);
    }
    
    return $userManager->hasRole('admin');
}

function logAdminAction($action, $details) {
    $log_entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'admin_id' => $_SESSION['admin_id'] ?? 0,
        'admin_username' => $_SESSION['admin_username'] ?? 'unknown',
        'action' => $action,
        'details' => $details,
        'ip' => $_SERVER['REMOTE_ADDR']
    ];
    
    error_log(
        json_encode($log_entry) . "\n",
        3,
        __DIR__ . '/../logs/admin_actions.log'
    );
}

function generateToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateToken($token) {
    if (!isset($_SESSION['csrf_token']) || empty($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
        throw new Exception('Invalid CSRF token.');
    }
}

function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    // Remove any non-digit characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Check if the number is between 10 and 15 digits
    return strlen($phone) >= 10 && strlen($phone) <= 15;
}

function validatePassword($password) {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
    return strlen($password) >= 8 && 
           preg_match('/[A-Z]/', $password) && 
           preg_match('/[a-z]/', $password) && 
           preg_match('/[0-9]/', $password);
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function generateResetToken() {
    return bin2hex(random_bytes(32));
}

function isValidResetToken($token, $email) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("
        SELECT created_at 
        FROM password_resets 
        WHERE token = ? AND email = ? AND used = 0
    ");
    $stmt->execute([$token, $email]);
    $reset = $stmt->fetch();
    
    if (!$reset) {
        return false;
    }
    
    // Check if token is less than 24 hours old
    $tokenTime = strtotime($reset['created_at']);
    return (time() - $tokenTime) < 86400;
}

// دالة التحقق من الصلاحية بناءً على قاعدة البيانات
function userHasPermission($user_id, $permission_name, $pdo) {
    $sql = "SELECT COUNT(*) FROM user_roles ur
            JOIN role_permissions rp ON ur.role_id = rp.role_id
            JOIN permissions p ON rp.permission_id = p.id
            WHERE ur.user_id = ? AND p.name = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $permission_name]);
    return $stmt->fetchColumn() > 0;
}

if (!isset($_SESSION['user_id'])) {
    header('Location: /Booking-Hotel-Project/pages/login/login.php');
    exit;
} 