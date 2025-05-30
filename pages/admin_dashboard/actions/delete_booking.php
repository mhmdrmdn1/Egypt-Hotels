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

// Check if booking ID is provided
if (!isset($_POST['booking_id']) || !is_numeric($_POST['booking_id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid booking ID']);
    exit;
}

$booking_id = intval($_POST['booking_id']);

try {
    $pdo = getPDO();
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Get room ID before deleting booking
    $stmt = $pdo->prepare("SELECT room_id FROM bookings WHERE id = ?");
    $stmt->execute([$booking_id]);
    $room_id = $stmt->fetchColumn();
    
    // Delete booking
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
    
    if (!$stmt->execute([$booking_id])) {
        throw new Exception('Failed to delete booking');
    }
    
    // Check if booking was actually deleted
    if ($stmt->rowCount() === 0) {
        throw new Exception('Booking not found');
    }
    
    // Update room availability
    if ($room_id) {
        $stmt = $pdo->prepare("UPDATE rooms SET is_available = 1 WHERE id = ?");
        $stmt->execute([$room_id]);
    }
    
    // Commit transaction
    $pdo->commit();
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("[" . date('Y-m-d H:i:s') . "] Delete booking error: " . $e->getMessage() . "\n", 3, "../../../logs/admin_errors.log");
    echo json_encode(['success' => false, 'error' => 'Failed to delete booking']);
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

// Check if booking ID is provided
if (!isset($_POST['booking_id']) || !is_numeric($_POST['booking_id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid booking ID']);
    exit;
}

$booking_id = intval($_POST['booking_id']);

try {
    $pdo = getPDO();
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Get room ID before deleting booking
    $stmt = $pdo->prepare("SELECT room_id FROM bookings WHERE id = ?");
    $stmt->execute([$booking_id]);
    $room_id = $stmt->fetchColumn();
    
    // Delete booking
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
    
    if (!$stmt->execute([$booking_id])) {
        throw new Exception('Failed to delete booking');
    }
    
    // Check if booking was actually deleted
    if ($stmt->rowCount() === 0) {
        throw new Exception('Booking not found');
    }
    
    // Update room availability
    if ($room_id) {
        $stmt = $pdo->prepare("UPDATE rooms SET is_available = 1 WHERE id = ?");
        $stmt->execute([$room_id]);
    }
    
    // Commit transaction
    $pdo->commit();
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("[" . date('Y-m-d H:i:s') . "] Delete booking error: " . $e->getMessage() . "\n", 3, "../../../logs/admin_errors.log");
    echo json_encode(['success' => false, 'error' => 'Failed to delete booking']);
}
?> 