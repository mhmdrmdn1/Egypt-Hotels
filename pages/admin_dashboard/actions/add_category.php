<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../config/auth.php';
require_once '../../../classes/Settings.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    // header('Location: ../../admin_login.php'); // تم التعطيل بناءً على طلب الإدارة
    exit;
}

try {
    // Validate input
    if (empty($_POST['name'])) {
        throw new Exception('Category name is required.');
    }
    
    $name = trim($_POST['name']);
    $description = trim($_POST['description'] ?? '');
    $icon = trim($_POST['icon'] ?? 'fa-tag');
    
    // Add category
    $pdo = getPDO();
    $settings = Settings::getInstance($pdo);
    
    if ($settings->addCategory($name, $description, $icon)) {
        $_SESSION['success'] = 'Category added successfully.';
    } else {
        throw new Exception('Failed to add category.');
    }
    
} catch (Exception $e) {
    error_log("[" . date('Y-m-d H:i:s') . "] Add category error: " . $e->getMessage() . "\n", 3, "../../../logs/admin_errors.log");
    $_SESSION['error'] = $e->getMessage();
}

// Redirect back to settings page
header('Location: ../settings.php?tab=categories');
exit; 