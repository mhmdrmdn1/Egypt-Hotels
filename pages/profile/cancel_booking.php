<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../config/pdo.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$booking_id = $data['booking_id'] ?? null;

if (!$booking_id) {
    echo json_encode(['success' => false, 'message' => 'Booking ID is required']);
    exit;
}

try {
    // Check if booking exists and belongs to the user
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->execute([$booking_id, $_SESSION['user_id']]);
    $booking = $stmt->fetch();

    if (!$booking) {
        echo json_encode(['success' => false, 'message' => 'Booking not found or unauthorized']);
        exit;
    }

    // Check if booking is in a cancellable state
    if ($booking['status'] !== 'pending' && $booking['status'] !== 'confirmed') {
        echo json_encode(['success' => false, 'message' => 'This booking cannot be cancelled']);
        exit;
    }

    // Update booking status to cancelled (بدون updated_at)
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
    $stmt->execute([$booking_id]);

    // If payment was made, handle refund process here
    if (isset($booking['payment_status']) && $booking['payment_status'] === 'paid') {
        // Add refund logic here if needed
        // For example: update payment_status to 'refunded'
        $stmt = $pdo->prepare("UPDATE bookings SET payment_status = 'refunded' WHERE id = ?");
        $stmt->execute([$booking_id]);
    }

    echo json_encode(['success' => true, 'message' => 'Booking cancelled successfully']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?> 