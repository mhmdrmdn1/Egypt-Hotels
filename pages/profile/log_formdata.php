<?php
// ملف لتشخيص محتويات الفورم
$log_file = __DIR__ . '/debug_formdata.log';
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['log'])) {
    file_put_contents($log_file, date('Y-m-d H:i:s') . "\n" . $data['log'] . "\n----------------------\n", FILE_APPEND);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'No log data received']);
} 