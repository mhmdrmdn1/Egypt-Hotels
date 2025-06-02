<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}
require_once '../../config/pdo.php';
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['review_id'] ?? 0;
$rating = $data['rating'] ?? 0;
$comment = $data['comment'] ?? '';
$room_name = $data['room_name'] ?? '';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT id FROM reviews WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $user_id]);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Review not found or not yours']);
    exit;
}
$pdo->prepare('UPDATE reviews SET rating = ?, comment = ?, room_name = ? WHERE id = ? AND user_id = ?')->execute([$rating, $comment, $room_name, $id, $user_id]);
echo json_encode(['success' => true]); 