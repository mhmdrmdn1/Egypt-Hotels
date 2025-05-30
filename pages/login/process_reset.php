<?php
require_once '../../config/pdo.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (!$token || !$email || !$password || !$confirm_password) {
    echo json_encode([
        'status' => 'error',
        'message' => 'All fields are required'
    ]);
    exit;
}

if ($password !== $confirm_password) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Passwords do not match'
    ]);
    exit;
}

if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'The password must be at least 8 characters long and contain uppercase and lowercase letters and numbers'
    ]);
    exit;
}

try {
    // التحقق من صلاحية الرمز
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND email = ? AND expires_at > NOW()");
    $stmt->execute([$token, $email]);
    
    if (!$stmt->fetch()) {
        echo json_encode([
            'status' => 'error',
            'message' => 'The token is invalid or expired'
        ]);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->execute([$hashed_password, $email]);

    $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Password changed successfully'
    ]);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred while updating the password. Please try again'
    ]);
}
?> 