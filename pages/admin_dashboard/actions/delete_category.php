<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../config/auth.php';
require_once '../../../classes/Settings.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ../../admin_login.php');
    exit;
}

try {
    // Validate input
    if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception('Invalid category ID.');
    }
    
    $id = intval($_GET['id']);
    
    // Delete category
    $pdo = getPDO();
    $settings = Settings::getInstance($pdo);
    
    // Check if category is in use
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM hotels WHERE category_id = ?");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        throw new Exception('Cannot delete category. It is being used by ' . $count . ' hotel(s).');
    }
    
    if ($settings->deleteCategory($id)) {
        $_SESSION['success'] = 'Category deleted successfully.';
    } else {
        throw new Exception('Failed to delete category.');
    }
    
} catch (Exception $e) {
    error_log("[" . date('Y-m-d H:i:s') . "] Delete category error: " . $e->getMessage() . "\n", 3, "../../../logs/admin_errors.log");
    $_SESSION['error'] = $e->getMessage();
}

// Redirect back to settings page
header('Location: ../settings.php?tab=categories');
exit; 