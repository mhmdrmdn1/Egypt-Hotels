<?php
session_start();
require_once '../../config/pdo.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Get JSON data from request
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid data received']);
    exit();
}

try {
    // Prepare the update query
    $stmt = $pdo->prepare("
        UPDATE user_preferences 
        SET login_notifications = :login_notifications,
            remember_devices = :remember_devices,
            session_timeout = :session_timeout,
            updated_at = NOW()
        WHERE user_id = :user_id
    ");

    // Execute the query with the received data
    $result = $stmt->execute([
        'login_notifications' => $data['loginNotifications'] ? 1 : 0,
        'remember_devices' => $data['rememberDevices'] ? 1 : 0,
        'session_timeout' => $data['sessionTimeout'] ? 1 : 0,
        'user_id' => $_SESSION['user_id']
    ]);

    if ($result) {
        // Update successful
        echo json_encode(['success' => true, 'message' => 'Preferences saved successfully']);
    } else {
        // Update failed
        echo json_encode(['success' => false, 'message' => 'Failed to save preferences']);
    }
} catch (PDOException $e) {
    // Log the error
    error_log("Error saving user preferences: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?> 