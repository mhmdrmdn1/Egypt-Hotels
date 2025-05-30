<?php
require_once '../config/database.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Invalid booking ID']);
    exit;
}

try {
    // First check if the booking exists
    $check_query = "SELECT id FROM bookings WHERE id = ?";
    $check_stmt = $pdo->prepare($check_query);
    $check_stmt->execute([$id]);
    
    if ($check_stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Booking not found']);
        exit;
    }

    // Delete the booking
    $delete_query = "DELETE FROM bookings WHERE id = ?";
    $delete_stmt = $pdo->prepare($delete_query);
    $delete_stmt->execute([$id]);

    if ($delete_stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Booking deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete booking']);
    }

} catch (PDOException $e) {
    error_log("Delete booking error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
} 