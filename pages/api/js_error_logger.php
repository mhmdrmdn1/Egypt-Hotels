<?php
$data = json_decode(file_get_contents('php://input'), true);
$logFile = __DIR__ . '/frontend_errors.log';
$log = "[".date('Y-m-d H:i:s')."] ";
$log .= "URL: " . ($data['url'] ?? '') . " | ";
$log .= "Message: " . ($data['message'] ?? '') . " | ";
$log .= "Source: " . ($data['source'] ?? '') . " | ";
$log .= "Line: " . ($data['lineno'] ?? '') . " | ";
$log .= "Col: " . ($data['colno'] ?? '') . " | ";
$log .= "Stack: " . ($data['error'] ?? '') . "\n";
file_put_contents($logFile, $log, FILE_APPEND);
http_response_code(204); 