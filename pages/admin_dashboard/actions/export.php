<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../config/auth.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    // header('Location: ../login.php'); // تم التعطيل بناءً على طلب الإدارة
    exit;
}

// Check if user has permission to export data
if (!hasPermission('export_data')) {
    $_SESSION['error'] = 'You do not have permission to export data.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$type = $_GET['type'] ?? '';
$format = $_GET['format'] ?? 'csv';

try {
    $pdo = getPDO();
    
    switch ($type) {
        case 'bookings':
            $sql = "
                SELECT 
                    b.id as booking_id,
                    h.name as hotel_name,
                    r.room_number,
                    r.type as room_type,
                    u.username as guest_name,
                    u.email as guest_email,
                    b.check_in,
                    b.check_out,
                    b.status,
                    b.payment_status,
                    b.payment_method,
                    b.transaction_id,
                    b.created_at as booking_date
                FROM bookings b
                JOIN hotels h ON b.hotel_id = h.id
                JOIN rooms r ON b.room_id = r.id
                JOIN users u ON b.user_id = u.id
                ORDER BY b.created_at DESC
            ";
            $headers = [
                'Booking ID', 'Hotel', 'Room Number', 'Room Type', 'Guest Name', 
                'Guest Email', 'Check-in', 'Check-out', 'Status', 'Payment Status',
                'Payment Method', 'Transaction ID', 'Booking Date'
            ];
            break;
            
        case 'reviews':
            $sql = "
                SELECT 
                    r.id as review_id,
                    h.name as hotel_name,
                    u.username as reviewer,
                    r.rating,
                    r.comment,
                    r.status,
                    r.created_at as review_date
                FROM hotel_ratings r
                JOIN hotels h ON r.hotel_id = h.id
                JOIN users u ON r.user_id = u.id
                ORDER BY r.created_at DESC
            ";
            $headers = [
                'Review ID', 'Hotel', 'Reviewer', 'Rating', 'Comment',
                'Status', 'Review Date'
            ];
            break;
            
        default:
            throw new Exception('Invalid export type');
    }
    
    $stmt = $pdo->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Set headers for file download
    $filename = $type . '_' . date('Y-m-d_His');
    
    if ($format === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add UTF-8 BOM for Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Write headers
        fputcsv($output, $headers);
        
        // Write data
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
    } elseif ($format === 'excel') {
        require_once '../../../vendor/autoload.php';
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Write headers
        foreach ($headers as $col => $header) {
            $sheet->setCellValueByColumnAndRow($col + 1, 1, $header);
        }
        
        // Write data
        foreach ($data as $row => $record) {
            foreach (array_values($record) as $col => $value) {
                $sheet->setCellValueByColumnAndRow($col + 1, $row + 2, $value);
            }
        }
        
        // Auto-size columns
        foreach (range(1, count($headers)) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    
} catch (Exception $e) {
    error_log("Export error: " . $e->getMessage());
    $_SESSION['error'] = 'An error occurred while exporting data.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} 