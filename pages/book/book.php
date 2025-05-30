<?php
session_start();
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

file_put_contents(__DIR__ . '/debug_session.log', print_r($_SESSION, true));

if (!isset($_SESSION['user_email'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to book.']);
    exit;
}

$config_path = __DIR__ . '/../../config/pdo.php';
file_put_contents(__DIR__ . '/debug_path.log', "Config path: " . $config_path . "\nFile exists: " . (file_exists($config_path) ? 'Yes' : 'No'));

require_once $config_path;

try {
    $table_check = $pdo->query("SHOW TABLES LIKE 'bookings'");
    $table_exists = $table_check->rowCount() > 0;
    
    if (!$table_exists) {
        $pdo->exec($create_table_sql);
        file_put_contents(__DIR__ . '/debug_table.log', "Created bookings table\n");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        file_put_contents(__DIR__ . '/debug_post.log', print_r($_POST, true));

        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $check_in = $_POST['check_in'] ?? '';
        $check_out = $_POST['check_out'] ?? '';
        $guests = (int)($_POST['guests'] ?? 1);
        $rooms = (int)($_POST['rooms'] ?? 1);
        $special_requests = $_POST['special_requests'] ?? '';
        $hotel_id = (int)($_POST['hotel_id'] ?? 0);
        $room_id = (int)($_POST['room_id'] ?? 0);
        $total_price = (float)($_POST['total_price'] ?? 0);
        $hotel_name = $_POST['hotel_name'] ?? '';
        $room_name = $_POST['room_name'] ?? '';
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        $debug_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'check_in' => $check_in,
            'check_out' => $check_out,
            'guests' => $guests,
            'rooms' => $rooms,
            'hotel_id' => $hotel_id,
            'room_id' => $room_id,
            'total_price' => $total_price,
            'hotel_name' => $hotel_name,
            'room_name' => $room_name
        ];
        file_put_contents(__DIR__ . '/debug_processed.log', print_r($debug_data, true));

        // Validate hotel and room IDs
        if ($hotel_id <= 0) {
            throw new Exception('Invalid hotel ID');
        }
        if ($room_id <= 0) {
            throw new Exception('Invalid room ID');
        }

        // Verify that the room belongs to the hotel
        $verify_stmt = $pdo->prepare("SELECT COUNT(*) FROM rooms WHERE id = ? AND hotel_id = ?");
        $verify_stmt->execute([$room_id, $hotel_id]);
        if ($verify_stmt->fetchColumn() == 0) {
            throw new Exception('Invalid room for selected hotel');
        }

        $required_fields = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'check_in' => $check_in,
            'check_out' => $check_out,
            'hotel_id' => $hotel_id,
            'room_id' => $room_id
        ];

        $missing_fields = array_keys(array_filter($required_fields, function($value) {
            return empty($value) || $value === 0;
        }));

        if (!empty($missing_fields)) {
            throw new Exception('Missing required fields: ' . implode(', ', $missing_fields));
        }

        $check_stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM bookings 
            WHERE email = ? 
            AND room_id = ? 
            AND check_in = ? 
            AND check_out = ? 
            AND cancelled = 0
        ");
        
        $check_stmt->execute([$email, $room_id, $check_in, $check_out]);
        $exists = $check_stmt->fetchColumn();
        
        if ($exists > 0) {
            throw new Exception('You have already booked this room for these dates.');
        }

        $insert_stmt = $pdo->prepare("
            INSERT INTO bookings (
                hotel_id, room_id, first_name, last_name, 
                email, phone, check_in, check_out, 
                guests, rooms, special_requests, total_price, 
                hotel_name, room_name, user_id
            ) VALUES (
                ?, ?, ?, ?, 
                ?, ?, ?, ?, 
                ?, ?, ?, ?,
                ?, ?, ?
            )
        ");
        
        $success = $insert_stmt->execute([
            $hotel_id, $room_id, $first_name, $last_name,
            $email, $phone, $check_in, $check_out,
            $guests, $rooms, $special_requests, $total_price,
            $hotel_name, $room_name, $user_id
        ]);

        if (!$success) {
            throw new Exception("Failed to save booking");
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Booking completed successfully!',
            'booking_id' => $pdo->lastInsertId()
        ]);
        
    } else {
        throw new Exception('Invalid request method.');
    }
} catch (Exception $e) {
    $error_details = [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
        'post_data' => $_POST ?? [],
        'session_data' => $_SESSION ?? []
    ];
    file_put_contents(
        __DIR__ . '/debug_error.log', 
        date('Y-m-d H:i:s') . " Error:\n" . print_r($error_details, true) . "\n\n",
        FILE_APPEND
    );
    
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred while processing your booking. Please try again.'
    ]);
} 