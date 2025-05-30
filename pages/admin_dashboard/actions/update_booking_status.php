<?php
require_once '../../../config/database.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in and has admin privileges
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

// Validate input
if (!isset($_POST['booking_id']) || !isset($_POST['status'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    exit;
}

$booking_id = intval($_POST['booking_id']);
$status = $_POST['status'];

// Validate status
$valid_statuses = ['pending', 'confirmed', 'cancelled'];
if (!in_array($status, $valid_statuses)) {
    echo json_encode(['success' => false, 'error' => 'Invalid status']);
    exit;
}

try {
    $pdo = getPDO();
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Update booking status
    $stmt = $pdo->prepare("
        UPDATE bookings 
        SET status = ?, 
            updated_at = CURRENT_TIMESTAMP
        WHERE id = ?
    ");
    
    if (!$stmt->execute([$status, $booking_id])) {
        throw new Exception('Failed to update booking status');
    }
    
    // If status is cancelled, update room availability
    if ($status === 'cancelled') {
        $stmt = $pdo->prepare("
            UPDATE rooms r
            JOIN bookings b ON b.room_id = r.id
            SET r.is_available = 1
            WHERE b.id = ?
        ");
        $stmt->execute([$booking_id]);
    }
    
    // Commit transaction
    $pdo->commit();
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("[" . date('Y-m-d H:i:s') . "] Update booking status error: " . $e->getMessage() . "\n", 3, "../../../logs/admin_errors.log");
    echo json_encode(['success' => false, 'error' => 'Failed to update booking status']);
}
?> 
require_once '../../../config/database.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in and has admin privileges
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

// Validate input
if (!isset($_POST['booking_id']) || !isset($_POST['status'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    exit;
}

$booking_id = intval($_POST['booking_id']);
$status = $_POST['status'];

// Validate status
$valid_statuses = ['pending', 'confirmed', 'cancelled'];
if (!in_array($status, $valid_statuses)) {
    echo json_encode(['success' => false, 'error' => 'Invalid status']);
    exit;
}

try {
    $pdo = getPDO();
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Update booking status
    $stmt = $pdo->prepare("
        UPDATE bookings 
        SET status = ?, 
            updated_at = CURRENT_TIMESTAMP
        WHERE id = ?
    ");
    
    if (!$stmt->execute([$status, $booking_id])) {
        throw new Exception('Failed to update booking status');
    }
    
    // If status is cancelled, update room availability
    if ($status === 'cancelled') {
        $stmt = $pdo->prepare("
            UPDATE rooms r
            JOIN bookings b ON b.room_id = r.id
            SET r.is_available = 1
            WHERE b.id = ?
        ");
        $stmt->execute([$booking_id]);
    }
    
    // Commit transaction
    $pdo->commit();
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("[" . date('Y-m-d H:i:s') . "] Update booking status error: " . $e->getMessage() . "\n", 3, "../../../logs/admin_errors.log");
    echo json_encode(['success' => false, 'error' => 'Failed to update booking status']);
}
?> 