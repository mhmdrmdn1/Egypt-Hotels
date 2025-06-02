<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'reviews' => [], 'message' => 'Not logged in']);
    exit;
}
require_once '../../config/pdo.php';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT r.id, r.hotel_id, h.name AS hotel_name, r.room_name, r.rating, r.comment, r.review_date, r.approved FROM reviews r JOIN hotels h ON r.hotel_id = h.id WHERE r.user_id = ? ORDER BY r.review_date DESC");
$stmt->execute([$user_id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['success' => true, 'reviews' => $reviews]); 