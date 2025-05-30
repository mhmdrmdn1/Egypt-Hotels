<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../config/autoload.php';

// Check if user is logged in and has permission
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    die(json_encode(['success' => false, 'error' => 'Unauthorized access']));
}

try {
    // Validate input
    if (empty($_POST['id']) || !is_numeric($_POST['id'])) {
        throw new Exception('Invalid category ID.');
    }
    
    if (empty($_POST['name'])) {
        throw new Exception('Category name is required.');
    }
    
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description'] ?? '');
    $icon = trim($_POST['icon'] ?? 'fa-tag');
    
    // Update category
    $pdo = getPDO();
    $settings = Settings::getInstance($pdo);
    
    if ($settings->updateCategory($id, $name, $description, $icon)) {
        $_SESSION['success'] = 'Category updated successfully.';
        die(json_encode(['success' => true, 'message' => 'Category updated successfully.']));
    } else {
        throw new Exception('Failed to update category.');
    }
    
} catch (Exception $e) {
    error_log("Update category error: " . $e->getMessage());
    die(json_encode(['success' => false, 'error' => 'Failed to update category']));
}

// Redirect back to settings page
header('Location: ../settings.php?tab=categories');
exit; 