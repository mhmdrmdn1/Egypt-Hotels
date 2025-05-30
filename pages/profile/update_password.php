<?php
session_start();
require_once '../../config/pdo.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    try {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($current_password, $user['password'])) {
            header("Location: ../profile.php?error=invalid_password");
            exit();
        }

        if ($new_password !== $confirm_password) {
            header("Location: ../profile.php?error=password_mismatch");
            exit();
        }

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed_password, $user_id]);

        header("Location: ../profile.php?success=password_updated");
        exit();

    } catch (PDOException $e) {
        error_log("Password update error: " . $e->getMessage());
        header("Location: ../profile.php?error=1");
        exit();
    }
} else {
    header("Location: ../profile.php");
    exit();
} 