<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../classes/Auth.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set JSON content type
header('Content-Type: application/json');

// Check if user is logged in and is admin
if (!Auth::isAdmin()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = [];

    // Get total counts with error handling
    try {
        $data['counts'] = [
            'total_hotels' => (int)$pdo->query("SELECT COUNT(*) FROM hotels")->fetchColumn(),
            'active_hotels' => (int)$pdo->query("SELECT COUNT(*) FROM hotels WHERE is_active = 1")->fetchColumn(),
            'total_users' => (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'pending_reviews' => (int)$pdo->query("SELECT COUNT(*) FROM reviews WHERE status = 'pending'")->fetchColumn()
        ];
    } catch (PDOException $e) {
        error_log("Error fetching counts: " . $e->getMessage());
        $data['counts'] = [
            'total_hotels' => 0,
            'active_hotels' => 0,
            'total_users' => 0,
            'pending_reviews' => 0
        ];
    }

    // Get recent bookings (last 7 days)
    try {
        $stmt = $pdo->query("
            SELECT DATE(created_at) as date, COUNT(*) as count
            FROM bookings 
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date
        ");
        $data['recent_bookings'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching recent bookings: " . $e->getMessage());
        $data['recent_bookings'] = [];
    }

    // Get monthly revenue (last 6 months)
    try {
        $stmt = $pdo->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COALESCE(SUM(total_price), 0) as revenue
            FROM bookings
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                AND status = 'confirmed'
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month
        ");
        $data['monthly_revenue'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching monthly revenue: " . $e->getMessage());
        $data['monthly_revenue'] = [];
    }

    // Get top 5 hotels by booking count
    try {
        $stmt = $pdo->query("
            SELECT 
                h.name,
                COUNT(b.id) as booking_count
            FROM hotels h
            LEFT JOIN bookings b ON h.id = b.hotel_id
            GROUP BY h.id, h.name
            ORDER BY booking_count DESC
            LIMIT 5
        ");
        $data['top_hotels'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching top hotels: " . $e->getMessage());
        $data['top_hotels'] = [];
    }

    // Get rating distribution
    try {
        $stmt = $pdo->query("
            SELECT 
                rating,
                COUNT(*) as count
            FROM reviews
            WHERE status = 'approved'
            GROUP BY rating
            ORDER BY rating
        ");
        $data['rating_distribution'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching rating distribution: " . $e->getMessage());
        $data['rating_distribution'] = [];
    }

    // Get recent activities (combined bookings and reviews)
    try {
        $stmt = $pdo->query("
            (SELECT 
                'booking' as type,
                b.created_at,
                CONCAT(b.first_name, ' ', b.last_name) as user,
                h.name as hotel_name,
                NULL as rating
            FROM bookings b
            JOIN hotels h ON b.hotel_id = h.id
            ORDER BY b.created_at DESC
            LIMIT 5)
            UNION ALL
            (SELECT 
                'review' as type,
                r.created_at,
                CONCAT(u.first_name, ' ', u.last_name) as user,
                h.name as hotel_name,
                r.rating
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            JOIN hotels h ON r.hotel_id = h.id
            ORDER BY r.created_at DESC
            LIMIT 5)
            ORDER BY created_at DESC
            LIMIT 10
        ");
        $data['recent_activities'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching recent activities: " . $e->getMessage());
        $data['recent_activities'] = [];
    }

    // Return JSON response
    echo json_encode($data);

} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
} catch (Exception $e) {
    error_log("General error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'An unexpected error occurred']);
    exit;
} 