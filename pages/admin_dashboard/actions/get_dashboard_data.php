<?php
require_once '../../../config/database.php';
require_once '../../../classes/Auth.php';

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

    // Get counts
    $counts = [
        'hotels' => $pdo->query("SELECT COUNT(*) FROM hotels")->fetchColumn(),
        'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
        'bookings' => $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn(),
        'reviews' => $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn()
    ];

    // Get recent bookings
    $stmt = $pdo->query("
        SELECT b.*, h.name as hotel_name, u.name as user_name 
        FROM bookings b 
        JOIN hotels h ON b.hotel_id = h.id 
        JOIN users u ON b.user_id = u.id 
        ORDER BY b.created_at DESC 
        LIMIT 5
    ");
    $recent_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get monthly revenue
    $stmt = $pdo->query("
        SELECT DATE_FORMAT(created_at, '%Y-%m') as month, 
               SUM(total_price) as revenue 
        FROM bookings 
        WHERE status = 'completed' 
        GROUP BY month 
        ORDER BY month DESC 
        LIMIT 12
    ");
    $monthly_revenue = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get top hotels
    $stmt = $pdo->query("
        SELECT h.name, COUNT(b.id) as booking_count 
        FROM hotels h 
        LEFT JOIN bookings b ON h.id = b.hotel_id 
        GROUP BY h.id 
        ORDER BY booking_count DESC 
        LIMIT 5
    ");
    $top_hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get rating distribution
    $stmt = $pdo->query("
        SELECT rating, COUNT(*) as count 
        FROM reviews 
        GROUP BY rating 
        ORDER BY rating
    ");
    $rating_distribution = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get recent activities
    $stmt = $pdo->query("
        SELECT 'booking' as type, created_at, 
               CONCAT('New booking for ', h.name) as description 
        FROM bookings b 
        JOIN hotels h ON b.hotel_id = h.id 
        UNION ALL 
        SELECT 'review' as type, created_at, 
               CONCAT('New review for ', h.name) as description 
        FROM reviews r 
        JOIN hotels h ON r.hotel_id = h.id 
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    $recent_activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => [
            'counts' => $counts,
            'recent_bookings' => $recent_bookings,
            'monthly_revenue' => $monthly_revenue,
            'top_hotels' => $top_hotels,
            'rating_distribution' => $rating_distribution,
            'recent_activities' => $recent_activities
        ]
    ]);

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