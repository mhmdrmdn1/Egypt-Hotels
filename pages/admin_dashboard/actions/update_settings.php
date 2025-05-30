<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../config/auth.php';
require_once '../../../config/autoload.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ../../admin_login.php');
    exit;
}

try {
    $pdo = getPDO();
    $settings = Settings::getInstance($pdo);
    
    // Handle regular settings
    if (isset($_POST['settings']) && is_array($_POST['settings'])) {
        foreach ($_POST['settings'] as $key => $value) {
            $settings->set($key, $value);
        }
    }
    
    // Handle file uploads
    foreach ($_FILES as $key => $file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Validate file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mime_type, $allowed_types)) {
                throw new Exception('Invalid file type. Only images are allowed.');
            }
            
            // Upload file
            if (!$settings->uploadImage($key, $file)) {
                throw new Exception('Failed to upload image.');
            }
        }
    }
    
    $_SESSION['success'] = 'Settings updated successfully.';
    
} catch (Exception $e) {
    error_log("[" . date('Y-m-d H:i:s') . "] Update settings error: " . $e->getMessage() . "\n", 3, "../../../logs/admin_errors.log");
    $_SESSION['error'] = $e->getMessage();
}

// Redirect back to settings page
$tab = isset($_POST['category']) ? '?tab=' . urlencode($_POST['category']) : '';
header('Location: ../settings.php' . $tab);
exit; 