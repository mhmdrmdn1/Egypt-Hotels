<?php
session_start();
require_once '../../config/pdo.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$type = $_POST['type'] ?? json_decode(file_get_contents('php://input'), true)['type'] ?? '';
$user_id = $_SESSION['user_id'];

if ($type !== 'profile' && $type !== 'cover') {
    echo json_encode(['success' => false, 'message' => 'Invalid image type']);
    exit;
}

$field = $type === 'profile' ? 'profile_image' : 'cover_image';
$default = $type === 'profile' ? 'assets/images/profiles/default.jpg' : 'assets/images/covers/default.jpg';

// جلب المسار الحالي للصورة
$stmt = $pdo->prepare("SELECT `$field` FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$current_image = $stmt->fetchColumn();

// حذف الصورة من السيرفر إذا لم تكن الصورة الافتراضية
if ($current_image && $current_image !== $default && file_exists('../../' . $current_image)) {
    @unlink('../../' . $current_image);
}

// تحديث قاعدة البيانات
$stmt = $pdo->prepare("UPDATE users SET `$field` = ? WHERE id = ?");
if ($stmt->execute([$default, $user_id])) {
    $_SESSION[$field] = $default;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update database']);
} 