<?php
file_put_contents(__DIR__.'/rate_hotel_error.log', date('Y-m-d H:i:s')." -- API CALLED --\n", FILE_APPEND);
require_once '../../config/database.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$hotel_id = isset($data['hotel_id']) ? intval($data['hotel_id']) : 0;
$rating = isset($data['rating']) ? intval($data['rating']) : 0;

$response = [
    'received' => $data,
    'hotel_id' => $hotel_id,
    'rating' => $rating
];

if ($hotel_id > 0 && $rating >= 1 && $rating <= 5) {
    try {
        $stmt = $pdo->prepare("INSERT INTO hotel_ratings (hotel_id, rating) VALUES (?, ?)");
        if ($stmt->execute([$hotel_id, $rating])) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['error'] = 'Failed to save rating';
            file_put_contents(__DIR__.'/rate_hotel_error.log', date('Y-m-d H:i:s')." execute error: Failed to save rating\n", FILE_APPEND);
        }
    } catch (PDOException $e) {
        $response['success'] = false;
        $response['error'] = $e->getMessage();
        file_put_contents(__DIR__.'/rate_hotel_error.log', date('Y-m-d H:i:s')." PDO error: ".$e->getMessage()."\n", FILE_APPEND);
    }
    echo json_encode($response);
} else {
    $response['success'] = false;
    $response['error'] = 'Invalid input';
    file_put_contents(__DIR__.'/rate_hotel_error.log', date('Y-m-d H:i:s')." invalid input: ".json_encode($data)."\n", FILE_APPEND);
    echo json_encode($response);
} 