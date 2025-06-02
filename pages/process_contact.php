<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once '../config/database.php';

try {
    // استقبال البيانات بصيغة JSON
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (!$data) throw new Exception('No data received or invalid JSON');

    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $message = trim($data['message'] ?? '');

    if (!$name || !$email || !$message) throw new Exception('All fields are required');

    // تحقق من صحة البريد الإلكتروني
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new Exception('Invalid email address');

    // حفظ البيانات في قاعدة البيانات
    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $exec = $stmt->execute([$name, $email, $message]);
    if (!$exec) {
        throw new Exception('Failed to save message');
    }

    echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

if (isset($stmt) && $stmt) $stmt = null;
if (isset($pdo) && $pdo) $pdo = null;
?> 