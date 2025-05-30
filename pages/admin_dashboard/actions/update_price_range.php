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
    if (empty($_POST['id']) || !is_numeric($_POST['id'])) {
        throw new Exception('Invalid price range ID.');
    }
    
    if (empty($_POST['name'])) {
        throw new Exception('Price range name is required.');
    }
    
    if (!isset($_POST['min_price']) || !is_numeric($_POST['min_price'])) {
        throw new Exception('Minimum price is required and must be a number.');
    }
    
    if (!isset($_POST['max_price']) || !is_numeric($_POST['max_price'])) {
        throw new Exception('Maximum price is required and must be a number.');
    }
    
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $min_price = floatval($_POST['min_price']);
    $max_price = floatval($_POST['max_price']);
    
    if ($min_price < 0) {
        throw new Exception('Minimum price cannot be negative.');
    }
    
    if ($max_price <= $min_price) {
        throw new Exception('Maximum price must be greater than minimum price.');
    }
    
    // Update price range
    $pdo = getPDO();
    $settings = Settings::getInstance($pdo);
    
    if ($settings->updatePriceRange($id, $name, $min_price, $max_price)) {
        $_SESSION['success'] = 'Price range updated successfully.';
    } else {
        throw new Exception('Failed to update price range.');
    }
    
} catch (Exception $e) {
    error_log("[" . date('Y-m-d H:i:s') . "] Update price range error: " . $e->getMessage() . "\n", 3, "../../../logs/admin_errors.log");
    $_SESSION['error'] = $e->getMessage();
}

// Redirect back to settings page
header('Location: ../settings.php?tab=price_ranges');
exit; 