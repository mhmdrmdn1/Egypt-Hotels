<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please provide a valid email address'
    ]);
    exit;
}

try {
    // Check if email exists in users table
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $result = $stmt->fetch();

    if (!$result) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No account found with this email address'
        ]);
        exit;
    }

    // Generate reset token
    $token = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Store reset token in database
    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
    $stmt->execute([$email, $token, $expires_at]);

    // For development: print the reset link instead of sending email
    $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/Booking-Hotel-Project/pages/login/reset_password.php?token=" . $token;
    echo json_encode([
        'status' => 'success',
        'message' => 'Password reset instructions (development): ' . $reset_link
    ]);
    exit;

} catch (Exception $e) {
    error_log("Password reset error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred. Please try again.'
    ]);
}
?> 