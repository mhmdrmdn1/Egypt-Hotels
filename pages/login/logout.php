<?php
session_start();
session_unset();
session_destroy();
header("Location: ../index.php");
exit();

require_once __DIR__ . '/../../config/pdo.php';

if (isset($_COOKIE['remember_user']) && isset($_COOKIE['remember_token'])) {
    $user_id = $_COOKIE['remember_user'];
    $token = $_COOKIE['remember_token'];

    try {
        $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE user_id = ? AND token = ?");
        $stmt->execute([$user_id, $token]);
    } catch (PDOException $e) {
        error_log("Error deleting remember token on logout: " . $e->getMessage());
    }

    setcookie('remember_user', '', time() - 3600, "/", $_SERVER['HTTP_HOST'], true, true);
    setcookie('remember_token', '', time() - 3600, "/", $_SERVER['HTTP_HOST'], true, true);
}

session_unset();
session_destroy();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

header("Location: ../index.php");
exit();
?>