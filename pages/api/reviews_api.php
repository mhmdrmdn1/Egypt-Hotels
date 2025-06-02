<?php
require_once '../../config/database.php';

session_start();
header('Content-Type: application/json');

function log_error($msg) {
    $logFile = __DIR__ . '/error_log.txt';
    $date = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$date] $msg\n", FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // إضافة تعليق جديد
    $room_name = isset($_POST['room_name']) ? trim($_POST['room_name']) : '';
    $hotel_id = isset($_POST['hotel_id']) ? intval($_POST['hotel_id']) : 0;
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    if ($room_name && $hotel_id && $rating && $comment) {
        $stmt = $pdo->prepare("INSERT INTO reviews (hotel_id, room_name, user_id, rating, comment, review_date, approved, created_at) VALUES (?, ?, ?, ?, ?, NOW(), 1, NOW())");
        $stmt->execute([$hotel_id, $room_name, $user_id, $rating, $comment]);
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing data']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // جلب التعليقات لغرفة معينة
    $room_name = isset($_GET['room_name']) ? trim($_GET['room_name']) : '';
    $hotel_id = isset($_GET['hotel_id']) ? intval($_GET['hotel_id']) : 0;
    $reviews = [];
    if ($room_name && $hotel_id) {
        $stmt = $pdo->prepare("SELECT user_id, rating, comment, review_date FROM reviews WHERE hotel_id = ? AND room_name = ? AND approved = 1 ORDER BY review_date DESC LIMIT 30");
        $stmt->execute([$hotel_id, $room_name]);
        while ($row = $stmt->fetch()) {
            $user = 'Anonymous';
            $profile_image = '/Booking-Hotel-Project/pages/images/default-avatar.png';
            if ($row['user_id']) {
                // جلب اسم وصورة المستخدم من جدول users إذا كان متاحًا
                $u = $pdo->prepare("SELECT username, profile_image FROM users WHERE id = ?");
                $u->execute([$row['user_id']]);
                $userRow = $u->fetch();
                if ($userRow) {
                    $user = $userRow['username'];
                    if (!empty($userRow['profile_image'])) {
                        if (strpos($userRow['profile_image'], 'assets/') === 0) {
                            $profile_image = '/Booking-Hotel-Project/' . htmlspecialchars($userRow['profile_image']);
                        } else {
                            $profile_image = '/Booking-Hotel-Project/assets/images/profiles/' . htmlspecialchars($userRow['profile_image']);
                        }
                    }
                }
            }
            $reviews[] = [
                'user' => $user,
                'profile_image' => $profile_image,
                'rating' => intval($row['rating']),
                'comment' => $row['comment'],
                'date' => $row['review_date'] ? date('M d, Y', strtotime($row['review_date'])) : ''
            ];
        }
    }
    echo json_encode(['reviews' => $reviews]);
    exit;
}

echo json_encode(['error' => 'Invalid request']);

try {
    // API logic here
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred']);
} 