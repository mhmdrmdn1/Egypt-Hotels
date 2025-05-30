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
    if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception('Invalid price range ID.');
    }
    
    $id = intval($_GET['id']);
    
    // Delete price range
    $pdo = getPDO();
    $settings = Settings::getInstance($pdo);
    
    // Check if price range is in use
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM hotels WHERE price_range_id = ?");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        throw new Exception('Cannot delete price range. It is being used by ' . $count . ' hotel(s).');
    }
    
    if ($settings->deletePriceRange($id)) {
        $_SESSION['success'] = 'Price range deleted successfully.';
    } else {
        throw new Exception('Failed to delete price range.');
    }
    
} catch (Exception $e) {
    error_log("Delete price range error: " . $e->getMessage());
    die(json_encode(['success' => false, 'error' => 'Failed to delete price range']));
}

// Redirect back to settings page
header('Location: ../settings.php?tab=price_ranges');
exit; 