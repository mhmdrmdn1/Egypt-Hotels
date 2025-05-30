<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingId = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
    
    if (!$bookingId) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid booking ID.'
        ]);
        exit;
    }
    
    try {
        $checkStmt = $pdo->prepare("
            SELECT check_in, cancelled 
            FROM bookings 
            WHERE id = ?
        ");
        $checkStmt->execute([$bookingId]);
        $booking = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$booking) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Booking not found.'
            ]);
            exit;
        }
        
        if ($booking['cancelled']) {
            echo json_encode([
                'status' => 'error',
                'message' => 'This booking is already cancelled.'
            ]);
            exit;
        }
        
        $checkIn = new DateTime($booking['check_in']);
        $now = new DateTime();
        if ($checkIn < $now) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Cannot cancel a booking that has already started.'
            ]);
            exit;
        }
        
        $updateStmt = $pdo->prepare("
            UPDATE bookings 
            SET cancelled = 1, 
                cancelled_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        $updateStmt->execute([$bookingId]);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Booking cancelled successfully.'
        ]);
    } catch (PDOException $e) {
        error_log("Database error in cancel_booking.php: " . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'An error occurred while cancelling the booking.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
} 