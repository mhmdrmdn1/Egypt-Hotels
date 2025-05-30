<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/pdo.php';

$debug_file = __DIR__ . '/search_debug.log';
file_put_contents($debug_file, "=== New Search ===\n", FILE_APPEND);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    file_put_contents($debug_file, "Received email: " . $email . "\n", FILE_APPEND);
    
    try {
        file_put_contents($debug_file, "Database connected: " . ($pdo ? "Yes" : "No") . "\n", FILE_APPEND);
        
        $check_query = "SELECT COUNT(*) FROM bookings WHERE email = ?";
        $check_stmt = $pdo->prepare($check_query);
        $check_stmt->execute([$email]);
        $count = $check_stmt->fetchColumn();
        
        file_put_contents($debug_file, "Found bookings count: " . $count . "\n", FILE_APPEND);
        
        if ($count > 0) {
            $query = "SELECT * FROM bookings WHERE email = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$email]);
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            file_put_contents($debug_file, "Bookings found: " . print_r($bookings, true) . "\n", FILE_APPEND);
            
            foreach ($bookings as &$booking) {
                $hotel_query = "SELECT name FROM hotels WHERE id = ?";
                $hotel_stmt = $pdo->prepare($hotel_query);
                $hotel_stmt->execute([$booking['hotel_id']]);
                $hotel_name = $hotel_stmt->fetchColumn();
                $booking['hotel_name'] = $hotel_name ?: 'Unknown Hotel';
                
                $room_query = "SELECT name FROM rooms WHERE id = ?";
                $room_stmt = $pdo->prepare($room_query);
                $room_stmt->execute([$booking['room_id']]);
                $room_name = $room_stmt->fetchColumn();
                $booking['room_name'] = $room_name ?: 'Unknown Room';
            }
            unset($booking);
            
            echo json_encode([
                'status' => 'success',
                'bookings' => $bookings
            ]);
        } else {
            file_put_contents($debug_file, "No bookings found for email\n", FILE_APPEND);
            
            $all_emails_query = "SELECT DISTINCT email FROM bookings";
            $all_emails = $pdo->query($all_emails_query)->fetchAll(PDO::FETCH_COLUMN);
            file_put_contents($debug_file, "All emails in database: " . print_r($all_emails, true) . "\n", FILE_APPEND);
            
            echo json_encode([
                'status' => 'success',
                'bookings' => []
            ]);
        }
        
    } catch (PDOException $e) {
        
        file_put_contents($debug_file, "Error: " . $e->getMessage() . "\n", FILE_APPEND);
        
        echo json_encode([
            'status' => 'error',
            'message' => 'An error occurred while fetching your bookings.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
} 