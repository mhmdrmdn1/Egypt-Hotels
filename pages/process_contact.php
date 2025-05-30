<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once '../config/database.php';

// دالة لتسجيل أي خطوة أو خطأ في ملف لوج
function log_debug($msg) {
    file_put_contents(__DIR__ . '/contact_debug.log', date('Y-m-d H:i:s') . ' | ' . $msg . PHP_EOL, FILE_APPEND);
}

try {
    log_debug('--- New request ---');

    // إنشاء جدول الرسائل إذا لم يكن موجوداً (بدون subject)
    $create_table = "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->query($create_table);
    log_debug('Table checked/created');

    // استقبال البيانات بصيغة JSON
    $raw = file_get_contents('php://input');
    log_debug('Raw input: ' . $raw);
    $data = json_decode($raw, true);
    if (!$data) throw new Exception('No data received or invalid JSON');

    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $message = trim($data['message'] ?? '');

    log_debug("Parsed: name=[$name], email=[$email], message=[$message]");

    if (!$name || !$email || !$message) throw new Exception('All fields are required');

    // تحقق من صحة البريد الإلكتروني
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new Exception('Invalid email address');

    // حفظ البيانات في قاعدة البيانات (بدون subject)
    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $exec = $stmt->execute([$name, $email, $message]);
    if (!$exec) {
        log_debug('Execute failed: ' . json_encode($stmt->errorInfo()));
        throw new Exception('DB execute failed: ' . json_encode($stmt->errorInfo()));
    }

    log_debug('Message saved successfully');
    echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
} catch (Exception $e) {
    log_debug('Exception: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

if (isset($stmt) && $stmt) $stmt = null;
if (isset($pdo) && $pdo) $pdo = null;
?> 