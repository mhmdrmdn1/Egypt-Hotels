<?php
session_start();
require_once '../../config/database.php';
require_once '../../config/auth.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    $pdo = getPDO();
    $action = $_GET['action'] ?? '';
    $response = [];

    switch ($action) {
        case 'stats':
            try {
                $stats = [
                    'total_hotels' => $pdo->query("SELECT COUNT(*) FROM hotels")->fetchColumn(),
                    'total_users' => $pdo->query("SELECT COUNT(*) FROM users WHERE status = 'active'")->fetchColumn(),
                    'active_hotels' => $pdo->query("SELECT COUNT(*) FROM hotels WHERE is_active = 1")->fetchColumn(),
                    'pending_reviews' => $pdo->query("SELECT COUNT(*) FROM reviews WHERE approved = 0")->fetchColumn()
                ];
                $response = $stats;
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Stats query failed', 'debug' => $e->getMessage()]);
                exit;
            }
            break;

        case 'revenue':
            try {
                $stmt = $pdo->query("
                    SELECT 
                        DATE_FORMAT(created_at, '%Y-%m') as month,
                        SUM(total_price) as revenue
                    FROM bookings
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                    ORDER BY month ASC
                ");
                $revenue_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $response = $revenue_data;
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Revenue query failed', 'debug' => $e->getMessage()]);
                exit;
            }
            break;

        case 'ratings':
            try {
                $stmt = $pdo->query("
                    SELECT 
                        ROUND(rating) as rating,
                        COUNT(*) as count
                    FROM reviews
                    WHERE approved = 1
                    GROUP BY ROUND(rating)
                    ORDER BY rating ASC
                ");
                $ratings_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $response = $ratings_data;
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Ratings query failed', 'debug' => $e->getMessage()]);
                exit;
            }
            break;

        case 'activities':
            try {
                $stmt = $pdo->query("
                    (SELECT 
                        'booking' as type,
                        b.created_at,
                        CONCAT(u.name, ' booked ', h.name) as description
                    FROM bookings b
                    JOIN users u ON b.user_id = u.id
                    JOIN hotels h ON b.hotel_id = h.id
                    ORDER BY b.created_at DESC
                    LIMIT 5)
                    UNION
                    (SELECT 
                        'review' as type,
                        r.created_at,
                        CONCAT(u.name, ' reviewed ', h.name) as description
                    FROM reviews r
                    JOIN users u ON r.user_id = u.id
                    JOIN hotels h ON r.hotel_id = h.id
                    WHERE r.approved = 1
                    ORDER BY r.created_at DESC
                    LIMIT 5)
                    ORDER BY created_at DESC
                    LIMIT 10
                ");
                $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $response = $activities;
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Activities query failed', 'debug' => $e->getMessage()]);
                exit;
            }
            break;

        case 'top_hotels':
            try {
                $stmt = $pdo->query("
                    SELECT 
                        h.id,
                        h.name,
                        h.rating,
                        COUNT(b.id) as bookings,
                        h.image as thumbnail
                    FROM hotels h
                    LEFT JOIN bookings b ON h.id = b.hotel_id
                    WHERE h.is_active = 1
                    GROUP BY h.id, h.name, h.rating, h.image
                    ORDER BY h.rating DESC, bookings DESC
                    LIMIT 5
                ");
                $top_hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $response = $top_hotels;
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Top hotels query failed', 'debug' => $e->getMessage()]);
                exit;
            }
            break;

        default:
            throw new Exception('Invalid action');
    }

    echo json_encode(['success' => true, 'data' => $response]);

} catch (Exception $e) {
    error_log("Dashboard API error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred while fetching dashboard data',
        'debug' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
} 