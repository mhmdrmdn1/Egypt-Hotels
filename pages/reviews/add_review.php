<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

require_once '../../config/pdo.php';

$user_id = $_SESSION['user_id'];
$hotel_id = isset($_POST['hotel_id']) ? intval($_POST['hotel_id']) : 0;
$room_name = isset($_POST['room_name']) ? trim($_POST['room_name']) : '';
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

if (!$hotel_id || !$room_name || !$rating || !$comment) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO reviews (user_id, hotel_id, room_name, rating, comment) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $hotel_id, $room_name, $rating, $comment]);
    echo json_encode(['success' => true, 'message' => 'Review added successfully.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} 