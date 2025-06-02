<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../config/pdo.php'; // تأكد من مسار ملف pdo.php

// --- Remember Me Auto-Login Logic ---
// Check if session is not set but remember me cookies are present
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_user']) && isset($_COOKIE['remember_token'])) {
    $user_id = $_COOKIE['remember_user'];
    $token = $_COOKIE['remember_token'];

    try {
        // Verify the token in the database
        $stmt = $pdo->prepare("SELECT u.* FROM users u JOIN remember_tokens rt ON u.id = rt.user_id WHERE rt.user_id = ? AND rt.token = ? AND rt.expires_at > NOW()");
        $stmt->execute([$user_id, $token]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data) {
            // Token is valid, log the user in
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['username'] = $user_data['username'] ?? $user_data['name'];
            $_SESSION['first_name'] = $user_data['first_name'] ?? null;
            $_SESSION['last_name'] = $user_data['last_name'] ?? null;
            $_SESSION['user_email'] = $user_data['email'];
            $_SESSION['user_type'] = 'user';
            $_SESSION['profile_image'] = $user_data['profile_image'] ?? null;
            $_SESSION['phone'] = $user_data['phone'] ?? null;
            $_SESSION['date_of_birth'] = $user_data['date_of_birth'] ?? null;
            $_SESSION['gender'] = $user_data['gender'] ?? null;
            $_SESSION['address'] = $user_data['address'] ?? null;
            error_log("Auto-login success for user_id: $user_id");
        } else {
            // Invalid or expired token, clear cookies
            setcookie('remember_user', '', time() - 3600, "/", $_SERVER['HTTP_HOST'], true, true);
            setcookie('remember_token', '', time() - 3600, "/", $_SERVER['HTTP_HOST'], true, true);
            error_log("Auto-login failed: invalid or expired token for user_id: $user_id");
        }
    } catch (PDOException $e) {
        error_log("Remember Me auto-login error: " . $e->getMessage());
        // Handle database error (optional)
    }
}
// --- End Remember Me Auto-Login Logic ---

if (!isset($_SESSION['user_id'])) {
    error_log("No user session found, redirecting to login.");
    header("Location: login/login.html");
    exit();
}

// جلب بيانات المستخدم من قاعدة البيانات عند تحميل الصفحة
try {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch();

    if ($user_data) {
        // تحديث بيانات الجلسة ببيانات قاعدة البيانات
        $_SESSION['username'] = $user_data['username'] ?? null;
        $_SESSION['first_name'] = $user_data['first_name'] ?? null;
        $_SESSION['last_name'] = $user_data['last_name'] ?? null;
        $_SESSION['user_email'] = $user_data['email'];
        $_SESSION['phone'] = $user_data['phone'] ?? null;
        $_SESSION['date_of_birth'] = $user_data['date_of_birth'] ?? null;
        $_SESSION['gender'] = $user_data['gender'] ?? null;
        $_SESSION['address'] = $user_data['address'] ?? null;
        $_SESSION['profile_image'] = isset($user_data['profile_image']) && $user_data['profile_image'] ? '../' . $user_data['profile_image'] : '../assets/images/default-profile.png';
        $cover_image = isset($user_data['cover_image']) && $user_data['cover_image'] ? '../' . $user_data['cover_image'] : '../assets/images/cover-default.jpg';
        error_log("User data loaded for user_id: $user_id");
    } else {
        // If user ID from session is not found in database (very rare after auto-login)
        error_log("User ID from session not found in database after auth check: " . $user_id);
        // Clear session and cookies and redirect to login
        session_unset();
        session_destroy();
        setcookie('remember_user', '', time() - 3600, "/", $_SERVER['HTTP_HOST'], true, true);
        setcookie('remember_token', '', time() - 3600, "/", $_SERVER['HTTP_HOST'], true, true);
        header("Location: login/login.html");
        exit();
    }

    // جلب حجوزات المستخدم
    $stmt = $pdo->prepare("SELECT b.*, h.name as hotel_name FROM bookings b 
                          JOIN hotels h ON b.hotel_id = h.id 
                          WHERE b.user_id = ? ORDER BY b.created_at DESC");
    $stmt->execute([$user_id]);
    $bookings = $stmt->fetchAll();
    error_log("Bookings loaded for user_id: $user_id, count: " . count($bookings));

    // Fetch user's uploaded gallery images
    $user_gallery_images = [];
    try {
        $stmt = $pdo->prepare("SELECT * FROM user_gallery WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        $user_gallery_images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Fetched user gallery images for user {$user_id}: " . print_r($user_gallery_images, true));
    } catch (PDOException $e) {
        error_log("Error fetching user gallery images for user {$user_id}: " . $e->getMessage());
    }

    // Fetch all reviews with hotel, user, and room details
    $user_reviews = [];
    try {
        // First, let's check if the reviews table exists
        $check_table = $pdo->query("SHOW TABLES LIKE 'reviews'");
        if ($check_table->rowCount() == 0) {
            error_log("Reviews table does not exist in the database");
            // Create the reviews table if it doesn't exist
            $pdo->exec("CREATE TABLE IF NOT EXISTS reviews (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                hotel_id INT NOT NULL,
                room_name VARCHAR(255),
                rating INT NOT NULL,
                comment TEXT,
                review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                approved TINYINT(1) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (hotel_id) REFERENCES hotels(id)
            )");
            error_log("Created reviews table");
        }

        // Fetch only reviews for the current user
        $stmt = $pdo->prepare("
            SELECT r.id, r.user_id, u.username, u.profile_image, r.hotel_id, h.name AS hotel_name, r.room_name, r.rating, r.comment, r.review_date, r.approved, r.created_at
            FROM reviews r
            JOIN hotels h ON r.hotel_id = h.id
            JOIN users u ON r.user_id = u.id
            WHERE r.user_id = ?
            ORDER BY r.review_date DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $user_reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Fetched " . count($user_reviews) . " reviews (all users)");
        if (count($user_reviews) > 0) {
            error_log("First review data: " . print_r($user_reviews[0], true));
        }
    } catch (PDOException $e) {
        error_log("Error in reviews section: " . $e->getMessage());
        error_log("SQL State: " . $e->getCode());
        error_log("Error Info: " . print_r($e->errorInfo, true));
    }

    // Calculate review statistics
    $total_reviews = count($user_reviews);
    $helpful_votes = array_sum(array_column($user_reviews, 'helpful_count'));
    $unique_hotels = count(array_unique(array_column($user_reviews, 'hotel_id')));
    $avg_rating = $total_reviews > 0 ? array_sum(array_column($user_reviews, 'rating')) / $total_reviews : 0;

    error_log("Review statistics - Total: $total_reviews, Helpful: $helpful_votes, Unique Hotels: $unique_hotels, Avg Rating: $avg_rating");

    // تعريف المتغيرات الخاصة بالاسم واسم المستخدم
    $first_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
    $last_name = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    $bio = isset($_SESSION['bio']) ? $_SESSION['bio'] : '';

} catch (PDOException $e) {
    error_log("Database error loading user data after auth check in profile.php: " . $e->getMessage());
    $bookings = []; // تعيين مصفوفة فارغة في حالة حدوث خطأ
}

// Get booking statistics
$user_id = $_SESSION['user_id'];
$stats_query = "SELECT 
    COUNT(*) as total_bookings,
    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as active_bookings,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_bookings,
    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings
FROM bookings 
WHERE user_id = ?";

$stmt = $pdo->prepare($stats_query);
$stmt->execute([$user_id]);
$stats_result = $stmt->fetch(PDO::FETCH_ASSOC);

$total_bookings = $stats_result['total_bookings'] ?? 0;
$active_bookings = $stats_result['active_bookings'] ?? 0;
$completed_bookings = $stats_result['completed_bookings'] ?? 0;
$cancelled_bookings = $stats_result['cancelled_bookings'] ?? 0;

// Get user bookings with room details
$bookings_query = "SELECT b.*, r.name as room_name, r.room_type, r.price as room_price 
                  FROM bookings b 
                  LEFT JOIN rooms r ON b.room_id = r.id 
                  WHERE b.user_id = ? 
                  ORDER BY b.created_at DESC";

$stmt = $pdo->prepare($bookings_query);
$stmt->execute([$user_id]);
$bookings_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// التحقق من وجود المجلدات المطلوبة
$base_dir = dirname(__DIR__);
$required_dirs = [
    $base_dir . '/assets/images',
    $base_dir . '/assets/images/profiles',
    $base_dir . '/assets/images/covers',
    $base_dir . '/assets/images/gallery',
    $base_dir . '/assets/user_uploads'
];

// إنشاء المجلدات إذا لم تكن موجودة
foreach ($required_dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
        error_log("Created directory: " . $dir);
    }
}

// تحديد مسارات الصور الافتراضية
$default_image_path = $base_dir . '/assets/images/gallery/default.jpg';
$default_profile = $base_dir . '/assets/images/profiles/default.jpg';
$default_cover = $base_dir . '/assets/images/covers/default.jpg';

// نسخ الصورة الافتراضية من مجلد الأصول إذا كانت موجودة
$source_default_image = $base_dir . '/assets/images/default.jpg';
if (file_exists($source_default_image)) {
    if (!file_exists($default_image_path)) {
        copy($source_default_image, $default_image_path);
        error_log("Copied default image to gallery directory");
    }
    if (!file_exists($default_profile)) {
        copy($source_default_image, $default_profile);
        error_log("Copied default image to profiles directory");
    }
    if (!file_exists($default_cover)) {
        copy($source_default_image, $default_cover);
        error_log("Copied default image to covers directory");
    }
} else {
    // إذا لم تكن الصورة الافتراضية موجودة، قم بإنشاء ملف نصي بسيط
    $placeholder_content = "This is a placeholder for the default image.";
    if (!file_exists($default_image_path)) {
        file_put_contents($default_image_path, $placeholder_content);
    }
    if (!file_exists($default_profile)) {
        file_put_contents($default_profile, $placeholder_content);
    }
    if (!file_exists($default_cover)) {
        file_put_contents($default_cover, $placeholder_content);
    }
}

// تحديث مسارات الصور في الجلسة
if (!isset($_SESSION['profile_image']) || empty($_SESSION['profile_image'])) {
    $_SESSION['profile_image'] = '../assets/images/profiles/default.jpg';
}
if (!isset($_SESSION['cover_image']) || empty($_SESSION['cover_image'])) {
    $_SESSION['cover_image'] = '../assets/images/covers/default.jpg';
}

// دالة لتصحيح مسار الصورة
function fix_image_path($path) {
    $path = ltrim($path, '/');
    $path = preg_replace('/^\.\.\//', '', $path); // إزالة ../ من البداية
    return $path;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Egypt Hotels</title>
    <link rel="shortcut icon" href="../assets/images/icons/web-icon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="../assets/css/profile.css">
    <style>
    /* Security Section Styling */
    .security-card {
        background: #fff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
        border: 1px solid #eef0f7;
        transition: all 0.3s ease;
    }

    .security-card:hover {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .security-card h6 {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f8f9fa;
    }

    .security-card h6 i {
        color: #3498db;
        margin-right: 10px;
    }

    /* Password Strength Meter */
    .password-strength {
        margin-top: 10px;
    }

    .password-strength .progress {
        height: 6px !important;
        border-radius: 3px;
        background-color: #f0f0f0;
        margin-bottom: 5px;
    }

    .password-strength .progress-bar {
        transition: all 0.3s ease;
    }

    .password-strength-text {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 5px;
    }

    /* Form Controls */
    .form-control {
        border: 1px solid #e0e0e0;
        padding: 10px 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
    }

    .input-group .btn-outline-secondary {
        border-color: #e0e0e0;
        color: #6c757d;
    }

    .input-group .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        color: #3498db;
    }

    /* 2FA Section */
    #2faSetup {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-top: 15px;
    }

    #2faQRCode {
        max-width: 200px;
        margin: 15px auto;
        padding: 10px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    /* Security Preferences */
    .form-check {
        padding-left: 2rem;
        margin-bottom: 1rem;
    }

    .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        margin-left: -2rem;
        border: 2px solid #e0e0e0;
    }

    .form-check-input:checked {
        background-color: #3498db;
        border-color: #3498db;
    }

    .form-check-label {
        color: #2c3e50;
        font-weight: 500;
    }

    /* Buttons */
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
        transform: translateY(-1px);
    }

    .btn-success {
        background-color: #2ecc71;
        border-color: #2ecc71;
    }

    .btn-success:hover {
        background-color: #27ae60;
        border-color: #27ae60;
        transform: translateY(-1px);
    }

    /* Alerts */
    .alert {
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 20px;
        border: none;
    }

    .alert-info {
        background-color: #e8f4fd;
        color: #2980b9;
    }

    /* Form Switch */
    .form-switch .form-check-input {
        width: 2.5rem;
        height: 1.25rem;
        margin-left: -2.5rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3e%3c/svg%3e");
        background-position: left center;
        border-radius: 2rem;
        transition: background-position .15s ease-in-out;
    }

    .form-switch .form-check-input:checked {
        background-position: right center;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
    }

    /* Section Title */
    .info-section h5 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f8f9fa;
    }

    .info-section h5 i {
        color: #3498db;
        margin-right: 10px;
    }

    /* Gallery Styles */
    .gallery-header {
        margin-bottom: 2rem;
    }

    .gallery-stats .stat-card {
        background: #fff;
        border-radius: 10px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .gallery-stats .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .gallery-stats .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .gallery-stats .stat-icon i {
        font-size: 1.5rem;
        color: #3498db;
    }

    .gallery-stats .stat-info h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .gallery-stats .stat-info p {
        margin: 0;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .gallery-filters {
        margin-bottom: 2rem;
    }

    .gallery-filters .btn-group {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        overflow: hidden;
    }

    .gallery-filters .btn {
        padding: 0.5rem 1.5rem;
        border: none;
    }

    .gallery-filters .btn.active {
        background-color: #3498db;
        color: white;
    }

    .gallery-grid {
        margin-top: 2rem;
    }

    .gallery-item {
        transition: all 0.3s ease;
    }

    .gallery-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 8px 8px 0 0;
    }

    .gallery-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .gallery-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .gallery-image-container:hover .gallery-image-overlay {
        opacity: 1;
    }

    .gallery-image-actions {
        display: flex;
        gap: 0.5rem;
    }

    .gallery-image-actions .btn {
        width: 35px;
        height: 35px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: white;
        color: #2c3e50;
        transition: all 0.3s ease;
    }

    .gallery-image-actions .btn:hover {
        background: #3498db;
        color: white;
        transform: scale(1.1);
    }

    .user-gallery-image-card {
        border: none;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .user-gallery-image-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 1.25rem;
    }

    .gallery-image-status .badge {
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    .no-gallery-items {
        padding: 3rem;
        background: #f8f9fa;
        border-radius: 10px;
        text-align: center;
    }

    .no-gallery-items i {
        color: #6c757d;
        margin-bottom: 1rem;
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 12px;
    }

    .modal-header {
        border-bottom: 1px solid #f0f0f0;
        padding: 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #f0f0f0;
        padding: 1.5rem;
    }

    #modalImage {
        max-height: 70vh;
        object-fit: contain;
    }

    /* Reviews Section Styles */
    .reviews-header {
        margin-bottom: 2rem;
    }

    .reviews-stats .stat-card {
        background: #fff;
        border-radius: 10px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    /* Add these styles to your existing CSS */
    .review-card {
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 2px 12px rgba(30,93,209,0.07);
        padding: 2rem 1.5rem 1.5rem 1.5rem;
        margin-bottom: 2rem;
        border: none;
        transition: box-shadow 0.2s, transform 0.2s;
        position: relative;
    }

    .review-card:hover {
        box-shadow: 0 6px 24px rgba(30,93,209,0.13);
        transform: translateY(-2px) scale(1.01);
    }

    .review-card .badge {
        font-size: 0.95rem;
        border-radius: 1rem;
        font-weight: 500;
    }

    .review-card .review-rating i {
        font-size: 1.5rem;
        margin-right: 2px;
    }

    .review-card .review-footer .btn {
        border-radius: 50%;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        padding: 0;
    }

    .review-card .review-footer .btn-outline-primary {
        border-color: #1e5dd1;
        color: #1e5dd1;
    }

    .review-card .review-footer .btn-outline-primary:hover {
        background: #1e5dd1;
        color: #fff;
    }

    .review-card .review-footer .btn-outline-danger {
        border-color: #e74c3c;
        color: #e74c3c;
    }

    .review-card .review-footer .btn-outline-danger:hover {
        background: #e74c3c;
        color: #fff;
    }

    .review-card .review-footer .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }

    .review-card .review-footer .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        color: #3498db;
    }

    .review-card .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .review-card .hotel-info h5 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .review-card .room-type {
        color: #666;
        font-size: 0.9rem;
    }

    .review-card .review-meta {
        text-align: right;
    }

    .review-card .review-rating {
        margin-bottom: 0.5rem;
    }

    .review-card .stars {
        color: #ffc107;
        font-size: 1.1rem;
    }

    .review-card .rating-value {
        color: #666;
        font-size: 0.9rem;
        margin-left: 0.5rem;
    }

    .review-card .review-date {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .review-card .review-content {
        margin: 1rem 0;
        color: #444;
        line-height: 1.6;
    }

    .review-card .review-footer {
        border-top: 1px solid #eee;
        padding-top: 1rem;
        margin-top: 1rem;
    }

    .review-card .review-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .review-card .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: transform 0.2s;
    }

    .review-card .stat-card:hover {
        transform: translateY(-2px);
    }

    .review-card .stat-icon {
        font-size: 2rem;
        color: #1e5dd1;
        margin-bottom: 1rem;
    }

    .review-card .stat-info h3 {
        font-size: 1.8rem;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .review-card .stat-info p {
        color: #666;
        margin: 0;
    }

    .review-card .reviews-filters {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }

    .review-card .no-reviews {
        background: #fff;
        border-radius: 12px;
        padding: 3rem;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }

    .review-card .no-reviews i {
        color: #ccc;
        margin-bottom: 1rem;
    }

    .review-card .no-reviews h4 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .review-card .no-reviews p {
        color: #666;
        margin-bottom: 1.5rem;
    }

    .show-more-link {
        cursor: pointer;
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        .review-card {
            padding: 1.2rem 0.7rem;
        }
        .review-card .review-rating i {
            font-size: 1.1rem;
        }
    }
    .sidebar {
        width: 280px;
        background: #fff;
        box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        position: fixed;
        height: 100vh;
        overflow-y: auto;
        z-index: 1000;
    }

    .sidebar-header {
        padding: 2rem;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .sidebar-menu {
        padding: 1rem 0;
    }

    .sidebar-menu .nav-item {
        margin: 0.5rem 0;
    }

    .sidebar-menu .nav-link {
        padding: 0.8rem 2rem;
        color: #2c3e50;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .sidebar-menu .nav-link i {
        width: 24px;
        margin-right: 10px;
        font-size: 1.1rem;
    }

    .sidebar-menu .nav-link:hover,
    .sidebar-menu .nav-link.active {
        background: #f8f9fa;
        color: #1e5dd1;
    }

    .sidebar-menu .nav-link.active {
        border-right: 3px solid #1e5dd1;
    }

    .main-content {
        flex: 1;
        margin-left: 280px;
        padding: 2rem;
    }

    .bio {
        color: #6c757d;
        font-size: 0.95rem;
        max-width: 600px;
        margin: 1rem auto;
        line-height: 1.6;
    }

    .user-stats {
        padding-top: 1.5rem;
        border-top: 1px solid #eee;
        margin-top: 1.5rem;
    }

    .stat-item {
        text-align: center;
        padding: 0 1.5rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    /* Hover Effects */
    .profile-image-container:hover .profile-image,
    .cover-image-container:hover .cover-image {
        transform: scale(1.05);
    }

    .profile-image-container:hover .edit-profile-image,
    .cover-image-container:hover .edit-cover {
        opacity: 1;
    }

    /* Responsive Styles */
    @media (max-width: 992px) {
        .sidebar {
            width: 80px;
        }
        
        .sidebar-header {
            padding: 1rem;
        }
        
        .sidebar-menu .nav-link span {
            display: none;
        }
        
        .sidebar-menu .nav-link i {
            margin: 0;
            font-size: 1.3rem;
        }
        
        .main-content {
            margin-left: 80px;
        }
    }

    @media (max-width: 768px) {
        .profile-container {
            flex-direction: column;
        }
        
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }
        
        .main-content {
            margin-left: 0;
        }
    }

    /* تحديث أنماط الأزرار */
    .edit-cover {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .cover-menu-top-left {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 11;
        margin: 0;
    }

    .edit-cover:hover {
        background: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-radius: 8px;
    }

    .dropdown-item {
        padding: 8px 16px;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #1e5dd1;
    }

    .dropdown-item i {
        width: 20px;
        margin-right: 8px;
    }

    /* Profile Header Improvements */
    .profile-header {
        position: relative;
        margin-bottom: 2rem;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    }

    .cover-image-container {
        position: relative;
        height: 300px;
        background: #f8f9fa;
        overflow: hidden;
    }

    .cover-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .cover-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .cover-image-container:hover .cover-image-overlay {
        opacity: 1;
    }

    .upload-cover-btn {
        background: rgba(255,255,255,0.9);
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .upload-cover-btn:hover {
        background: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Profile Image Improvements */
    .profile-image-container {
        position: relative;
        width: 180px;
        height: 180px;
        margin: -50px 0 0 50px;
        border-radius: 50%;
        border: 5px solid #fff;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        overflow: hidden;
        background: #f8f9fa;
    }

    .profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .profile-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .profile-image-container:hover .profile-image-overlay {
        opacity: 1;
    }

    .upload-profile-btn {
        background: rgba(255,255,255,0.9);
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        display: flex;
        gap: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .upload-profile-btn:hover {
        background: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }


    /* Stats Cards Improvements */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        padding: 2rem;
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .stat-icon i {
        font-size: 1.5rem;
        color: #1e5dd1;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
    }

    /* Sidebar Improvements */
    .sidebar {
        width: 280px;
        background: #fff;
        box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        position: fixed;
        height: 100vh;
        overflow-y: auto;
        z-index: 1000;
    }

    .sidebar-header {
        padding: 2rem;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .sidebar-menu {
        padding: 1rem 0;
    }

    .sidebar-menu .nav-item {
        margin: 0.5rem 0;
    }

    .sidebar-menu .nav-link {
        padding: 1rem 2rem;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .sidebar-menu .nav-link i {
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
    }

    .sidebar-menu .nav-link:hover,
    .sidebar-menu .nav-link.active {
        background: #f8f9fa;
        color: #1e5dd1;
        border-right: 3px solid #1e5dd1;
    }

    .sidebar-footer {
        padding: 1rem;
        border-top: 1px solid #eee;
        text-align: center;
    }

    .logout-btn {
        width: 100%;
        padding: 0.8rem;
        background: #dc3545;
        color: #fff;
        border: none;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background: #c82333;
        transform: translateY(-2px);
    }

    /* Toast Notifications */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast {
        background: #fff;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 0.8rem;
        animation: slideIn 0.3s ease;
    }

    .toast.success {
        border-left: 4px solid #28a745;
    }

    .toast.error {
        border-left: 4px solid #dc3545;
    }

    .toast-icon {
        font-size: 1.2rem;
    }

    .toast.success .toast-icon {
        color: #28a745;
    }

    .toast.error .toast-icon {
        color: #dc3545;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Loading Spinner */
    .spinner-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #1e5dd1;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .sidebar {
            width: 80px;
        }
        
        .sidebar-header {
            padding: 1rem;
        }
        
        .sidebar-menu .nav-link span {
            display: none;
        }
        
        .sidebar-menu .nav-link i {
            margin: 0;
            font-size: 1.3rem;
        }
        
        .main-content {
            margin-left: 80px;
        }
        
        .stats-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .profile-container {
            flex-direction: column;
        }
        
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }
        
        .main-content {
            margin-left: 0;
        }
        
        .stats-container {
            grid-template-columns: 1fr;
        }
        
        .profile-image-container {
            width: 150px;
            height: 150px;
            margin-top: -75px;
        }
        
        .cover-image-container {
            height: 200px;
        }
    }

    .d-flex.justify-content-center.mt-2.mb-3 {
        margin-top: -10px !important;
        margin-bottom: 20px !important;
    }

    .dropdown .btn {
        min-width: 150px;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(30,93,209,0.07);
    }

    /* احذف التنسيقات القديمة المتعلقة بـ .profile-info, .profile-image-container, .user-info, .user-name, .user-username */

    /* تنسيقات جديدة للعرض الأفقي */
    .profile-header-flex {
        display: flex;
        align-items: center;
        gap: 32px;
        padding: 0 2rem 2rem 2rem;
    }
    .profile-avatar {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .profile-avatar-img {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 5px solid #fff;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        object-fit: cover;
        background: #f8f9fa;
    }
    .profile-avatar .change-photo-btn {
        margin-top: 14px;
        margin-left: 50px;
    }
    .profile-username-block {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        min-width: 220px;
        margin-top: -70px;
    }
    .profile-username-block .profile-fullname {
        font-size: 2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    .profile-username-block .profile-username {
        color: #6c757d;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    @media (max-width: 768px) {
        .profile-header-flex {
            flex-direction: column;
            align-items: center;
            gap: 16px;
            padding: 0 0 2rem 0;
        }
        .profile-avatar-img {
            width: 120px;
            height: 120px;
        }
        .profile-username-block {
            align-items: center;
            min-width: unset;
        }
        .profile-username-block .profile-fullname {
            font-size: 1.3rem;
        }
    }
    </style>
</head>
<body>
    <div class="profile-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header d-flex align-items-center justify-content-center gap-2" style="padding: 2rem 1rem 1rem 1rem; border-bottom: 1px solid #eee;">
                <div class="sidebar-profile-img" style="width:44px;height:44px;overflow:hidden;border-radius:50%;border:2px solid #e0e0e0;box-shadow:0 2px 8px #1e5dd122;">
                    <?php
                    $sidebar_profile_image = '../assets/images/default-profile.png';
                    if (!empty($_SESSION['profile_image']) && $_SESSION['profile_image'] !== '../assets/images/default-profile.png') {
                        if (strpos($_SESSION['profile_image'], '../assets/') === 0) {
                            $sidebar_profile_image = $_SESSION['profile_image'];
                        } else {
                            $sidebar_profile_image = '../assets/images/profiles/' . $_SESSION['profile_image'];
                        }
                    }
                    ?>
                    <img src="<?php echo $sidebar_profile_image; ?>" alt="Profile" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <div class="sidebar-profile-username" style="font-weight:600;font-size:1.08rem;color:#1e5dd1;">
                    @<?php echo htmlspecialchars($username); ?>
                </div>
            </div>
            <div class="sidebar-menu">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#info" data-bs-toggle="tab">
                            <i class="fas fa-user"></i>
                            <span>Account Info</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#bookings" data-bs-toggle="tab">
                            <i class="fas fa-calendar-check"></i>
                            <span>My Bookings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#security" data-bs-toggle="tab">
                            <i class="fas fa-shield-alt"></i>
                            <span>Security</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gallery" data-bs-toggle="tab">
                            <i class="fas fa-images"></i>
                            <span>My Gallery</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#reviews" data-bs-toggle="tab">
                            <i class="fas fa-star"></i>
                            <span>My Reviews</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="sidebar-footer text-center mt-auto mb-3">
                <a href="index.php" class="btn btn-outline-secondary w-75">
                    <i class="fas fa-arrow-left"></i> Back to website
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Profile Header -->
            <div class="profile-header">
                <!-- Cover Image Container -->
                <div class="cover-image-container" id="coverImageDropZone">
                    <img id="coverImagePreview" src="/Booking-Hotel-Project/<?php echo htmlspecialchars(fix_image_path($_SESSION['cover_image'] ?? 'assets/images/covers/default.jpg')); ?>" 
                         alt="Cover Image" 
                         class="cover-image"
                         data-default="/Booking-Hotel-Project/assets/images/covers/default.jpg"
                         onerror="if (!this.src.includes('default.jpg')) this.src = this.dataset.default;">
                    <div class="cover-image-overlay align-items-start justify-content-start" style="padding: 20px;">
                        <div class="dropdown d-inline-block cover-menu-top-left">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="coverMenuBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-camera"></i> <span>Change Cover</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="coverMenuBtn">
                                <li><a class="dropdown-item" href="#" id="changeCoverOption">Change Cover</a></li>
                                <li><a class="dropdown-item text-danger" href="#" id="deleteCoverOption">Delete Cover</a></li>
                            </ul>
                        </div>
                        <input type="file" name="cover_image" id="coverImageInputForm" accept="image/*" style="display:none;">
                    </div>
                    <div id="coverImageMsg" class="mt-2"></div>
                </div>
                
                <!-- Profile Image Container -->
                <div class="profile-header-flex">
                    <div class="profile-avatar">
                        <div class="profile-image-container position-relative" style="background:none;box-shadow:none;border:none;padding:0;">
                            <img id="profileImagePreview" src="/Booking-Hotel-Project/<?php echo htmlspecialchars(fix_image_path($_SESSION['profile_image'] ?? 'assets/images/profiles/default.jpg')); ?>" alt="Profile Image" class="profile-avatar-img" data-default="/Booking-Hotel-Project/assets/images/profiles/default.jpg" onerror="if (!this.src.includes('default.jpg')) this.src = this.dataset.default;">
                            <div class="profile-image-overlay"></div>
                            <div id="profileImageMsg" class="mt-2"></div>
                        </div>
                        <div class="change-photo-btn text-center mt-2 mb-3">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="profileMenuBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-camera"></i> <span>Change Photo</span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="profileMenuBtn">
                                    <li><a class="dropdown-item" href="#" id="changeProfileOption">Change Photo</a></li>
                                    <li><a class="dropdown-item text-danger" href="#" id="deleteProfileOption">Delete Photo</a></li>
                                </ul>
                            </div>
                            <input type="file" name="profile_image" id="profileImageInputForm" accept="image/*" style="display:none;">
                        </div>
                    </div>
                    <div class="profile-username-block">
                        <h1 class="profile-fullname mb-1">
                            <?php 
                            $display_name = trim($first_name . ' ' . $last_name);
                            echo htmlspecialchars($display_name ?: 'User');
                            ?>
                        </h1>
                        <div class="profile-username">
                            @<?php echo htmlspecialchars($username ?: 'username'); ?>
                        </div>
                        <?php if (!empty($_SESSION['bio'])): ?>
                        <div class="profile-bio mt-2">
                            <p class="text-muted mb-0" style="font-size: 0.95rem; max-width: 500px;">
                                <?php echo nl2br(htmlspecialchars($_SESSION['bio'])); ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Toast Container -->
            <div class="toast-container"></div>

            <!-- Loading Spinner -->
            <div class="spinner-overlay" style="display: none;">
                <div class="spinner"></div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
        <div class="tab-content" id="profileTabsContent">
            <!-- Account Info Tab -->
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <div class="info-section">
                    <?php if (isset($_SESSION['profile_errors']) && is_array($_SESSION['profile_errors']) && count($_SESSION['profile_errors'])): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($_SESSION['profile_errors'] as $err): ?>
                                    <li><?php echo htmlspecialchars($err); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['profile_errors']); ?>
                    <?php endif; ?>
                            <div class="info-section">
                        <form id="profileUpdateForm" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" required placeholder="Enter your username">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>" required placeholder="Enter your email">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($_SESSION['first_name'] ?? ''); ?>" required placeholder="Enter your first name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($_SESSION['last_name'] ?? ''); ?>" required placeholder="Enter your last name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="phone">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['phone'] ?? ''); ?>" placeholder="Enter your phone number">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="date_of_birth">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo (!empty($_SESSION['date_of_birth']) && $_SESSION['date_of_birth'] != '0000-00-00') ? htmlspecialchars($_SESSION['date_of_birth']) : ''; ?>" placeholder="mm/dd/yyyy">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="gender">Gender</label>
                                    <select class="form-control" id="gender" name="gender" title="Select your gender">
                                        <option value="" <?php if(empty($_SESSION['gender'])) echo 'selected'; ?>>Select</option>
                                        <option value="male" <?php if(isset($_SESSION['gender']) && $_SESSION['gender'] == 'male') echo 'selected'; ?>>Male</option>
                                        <option value="female" <?php if(isset($_SESSION['gender']) && $_SESSION['gender'] == 'female') echo 'selected'; ?>>Female</option>
                                        <option value="other" <?php if(isset($_SESSION['gender']) && $_SESSION['gender'] == 'other') echo 'selected'; ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter your address"><?php echo htmlspecialchars($_SESSION['address'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- Social Media Links -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3"><i class="fas fa-share-alt"></i> Social Media Links</h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fab fa-facebook text-primary"></i> Facebook</label>
                                    <input type="url" class="form-control" name="facebook_url" value="<?php echo htmlspecialchars($_SESSION['facebook_url'] ?? ''); ?>" placeholder="https://facebook.com/yourusername">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fab fa-twitter text-info"></i> Twitter</label>
                                    <input type="url" class="form-control" name="twitter_url" value="<?php echo htmlspecialchars($_SESSION['twitter_url'] ?? ''); ?>" placeholder="https://twitter.com/yourusername">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fab fa-instagram text-danger"></i> Instagram</label>
                                    <input type="url" class="form-control" name="instagram_url" value="<?php echo htmlspecialchars($_SESSION['instagram_url'] ?? ''); ?>" placeholder="https://instagram.com/yourusername">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fab fa-linkedin text-primary"></i> LinkedIn</label>
                                    <input type="url" class="form-control" name="linkedin_url" value="<?php echo htmlspecialchars($_SESSION['linkedin_url'] ?? ''); ?>" placeholder="https://linkedin.com/in/yourusername">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fas fa-globe text-success"></i> Website</label>
                                    <input type="url" class="form-control" name="website_url" value="<?php echo htmlspecialchars($_SESSION['website_url'] ?? ''); ?>" placeholder="https://yourwebsite.com">
                                </div>
                            </div>

                            <!-- Bio and Skills -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3"><i class="fas fa-user-edit"></i> About Me</h5>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Bio</label>
                                    <textarea class="form-control" name="bio" rows="4" placeholder="Tell us about yourself..."><?php echo htmlspecialchars($_SESSION['bio'] ?? ''); ?></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Skills (comma separated)</label>
                                    <input type="text" class="form-control" name="skills" value="<?php echo htmlspecialchars($_SESSION['skills'] ?? ''); ?>" placeholder="e.g., Photography, Travel, Cooking, Languages">
                                    <small class="text-muted">Add your skills, hobbies, or interests separated by commas</small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Bookings Tab -->
            <div class="tab-pane fade" id="bookings" role="tabpanel">
                <div class="bookings-container">
                    <div class="bookings-header">
                        <h2>My Bookings</h2>
                        <div class="booking-stats">
                            <div class="stat-card">
                                <i class="fas fa-calendar-check"></i>
                                <div class="stat-info">
                                    <h3><?php echo $total_bookings; ?></h3>
                                    <p>Total Bookings</p>
                                </div>
                            </div>
                            <div class="stat-card">
                                <i class="fas fa-clock"></i>
                                <div class="stat-info">
                                    <h3><?php echo $active_bookings; ?></h3>
                                    <p>Active Bookings</p>
                                </div>
                            </div>
                            <div class="stat-card">
                                <i class="fas fa-check-circle"></i>
                                <div class="stat-info">
                                    <h3><?php echo $completed_bookings; ?></h3>
                                    <p>Completed</p>
                                </div>
                            </div>
                            <div class="stat-card">
                                <i class="fas fa-times-circle"></i>
                                <div class="stat-info">
                                    <h3><?php echo $cancelled_bookings; ?></h3>
                                    <p>Cancelled</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (empty($bookings_result)): ?>
                        <div class="no-bookings">
                            <i class="fas fa-calendar-times"></i>
                            <h3>No Bookings Found</h3>
                            <p>You haven't made any bookings yet.</p>
                            <a href="explore.php" class="btn btn-primary">Book a Room</a>
                        </div>
                    <?php else: ?>
                        <div class="bookings-list">
                            <?php foreach ($bookings_result as $booking): ?>
                                <div class="booking-card">
                                    <div class="booking-header">
                                        <div class="booking-info">
                                            <h3><?php echo htmlspecialchars($booking['hotel_name'] ?? 'Hotel'); ?></h3>
                                            <span class="room-type"><?php echo htmlspecialchars($booking['room_name'] ?? 'Room'); ?></span>
                                        </div>
                                        <div class="booking-status <?php echo strtolower($booking['status'] ?? 'pending'); ?>">
                                            <?php echo ucfirst($booking['status'] ?? 'Pending'); ?>
                                        </div>
                                    </div>
                                    <div class="booking-details">
                                        <div class="detail-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <div>
                                                <label>Check-in</label>
                                                <p><?php echo date('M d, Y', strtotime($booking['check_in_date'] ?? '1970-01-01')); ?></p>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <div>
                                                <label>Check-out</label>
                                                <p><?php echo date('M d, Y', strtotime($booking['check_out_date'] ?? '1970-01-01')); ?></p>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-users"></i>
                                            <div>
                                                <label>Guests</label>
                                                <p><?php echo ($booking['guests'] ?? 0) . ' persons'; ?></p>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-money-bill-wave"></i>
                                            <div>
                                                <label>Total Price</label>
                                                <p><?php echo number_format($booking['total_price'] ?? 0, 2); ?> EGP</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="booking-footer">
                                        <div class="payment-status <?php echo strtolower($booking['payment_status'] ?? 'pending'); ?>">
                                            <i class="fas fa-credit-card"></i>
                                            <?php echo ucfirst($booking['payment_status'] ?? 'Pending'); ?>
                                        </div>
                                        <?php if (($booking['status'] ?? '') === 'pending'): ?>
                                            <button class="btn btn-danger cancel-booking" data-booking-id="<?php echo $booking['id']; ?>">
                                                <i class="fas fa-times"></i> Cancel Booking
                                            </button>
                                        <?php endif; ?>
                                        <?php if (($booking['status'] ?? '') === 'confirmed'): ?>
                                            <button class="btn btn-primary view-details" data-booking-id="<?php echo $booking['id']; ?>">
                                                <i class="fas fa-info-circle"></i> View Details
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Security Tab -->
            <div class="tab-pane fade" id="security" role="tabpanel">
                <div class="info-section">
                    <h5><i class="fas fa-shield-alt"></i> Security Settings</h5>
                    
                    <!-- Password Change Section -->
                    <div class="security-card mb-4">
                        <h6 class="mb-3"><i class="fas fa-key"></i> Change Password</h6>
                        <form id="passwordForm" action="profile/update_password.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="current_password" id="currentPassword" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="currentPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="new_password" id="newPassword" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="newPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength mt-2">
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <small class="password-strength-text text-muted"></small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="confirm_password" id="confirmPassword" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </form>
                    </div>

                    <!-- Two-Factor Authentication Section -->
                    <div class="security-card mb-4">
                        <h6 class="mb-3"><i class="fas fa-lock"></i> Two-Factor Authentication</h6>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="enable2FA">
                            <label class="form-check-label" for="enable2FA">Enable Two-Factor Authentication</label>
                        </div>
                        <div id="2faSetup" class="d-none">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Scan this QR code with your authenticator app
                            </div>
                            <div class="text-center mb-3">
                                <img src="" alt="2FA QR Code" id="2faQRCode" class="img-fluid">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Enter the 6-digit code from your authenticator app</label>
                                <input type="text" class="form-control" id="2faCode" maxlength="6" pattern="[0-9]{6}">
                            </div>
                            <button class="btn btn-success" id="verify2FA">
                                <i class="fas fa-check"></i> Verify and Enable
                            </button>
                        </div>
                    </div>

                    <!-- Security Preferences -->
                    <div class="security-card">
                        <h6 class="mb-3"><i class="fas fa-cog"></i> Security Preferences</h6>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="loginNotifications">
                            <label class="form-check-label" for="loginNotifications">
                                Receive email notifications for new logins
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="rememberDevices">
                            <label class="form-check-label" for="rememberDevices">
                                Remember this device for 30 days
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="sessionTimeout">
                            <label class="form-check-label" for="sessionTimeout">
                                Auto-logout after 30 minutes of inactivity
                            </label>
                        </div>
                        <button class="btn btn-primary" id="saveSecurityPreferences">
                            <i class="fas fa-save"></i> Save Preferences
                        </button>
                    </div>
                </div>
            </div>
            <!-- Gallery Tab -->
            <div class="tab-pane fade" id="gallery" role="tabpanel">
                <div class="info-section">
                    <div class="gallery-header d-flex justify-content-between align-items-center mb-4">
                        <h5><i class="fas fa-images"></i> My Gallery</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadImageModal">
                            <i class="fas fa-upload"></i> Upload New Image
                        </button>
                    </div>

                    <!-- Gallery Stats -->
                    <div class="gallery-stats mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h3><?php echo count($user_gallery_images); ?></h3>
                                        <p>Total Images</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h3><?php echo count(array_filter($user_gallery_images, function($img) { return $img['approved'] == 1; })); ?></h3>
                                        <p>Approved</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h3><?php echo count(array_filter($user_gallery_images, function($img) { return $img['approved'] == 0; })); ?></h3>
                                        <p>Pending</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h3><?php echo count(array_filter($user_gallery_images, function($img) { return $img['approved'] == 2; })); ?></h3>
                                        <p>Rejected</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery Filters -->
                    <div class="gallery-filters mb-4">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary active" data-filter="all">All</button>
                            <button type="button" class="btn btn-outline-success" data-filter="approved">Approved</button>
                            <button type="button" class="btn btn-outline-warning" data-filter="pending">Pending</button>
                            <button type="button" class="btn btn-outline-danger" data-filter="rejected">Rejected</button>
                        </div>
                    </div>

                    <?php if (!empty($user_gallery_images)): ?>
                        <div class="row row-cols-1 row-cols-md-3 g-4 gallery-grid">
                            <?php foreach ($user_gallery_images as $image): ?>
                                <div class="col gallery-item" data-status="<?php echo $image['approved']; ?>">
                                    <div class="card h-100 user-gallery-image-card">
                                        <div class="gallery-image-container">
                                            <?php
                                            $imagePath = "/Booking-Hotel-Project/assets/user_uploads/" . $image['image'];
                                            $defaultImage = "/Booking-Hotel-Project/assets/images/gallery/default.jpg";
                                            
                                            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $imagePath)) {
                                                $parts = explode('_', $image['image'], 2);
                                                if (count($parts) == 2) {
                                                    $imagePathAlt = "/Booking-Hotel-Project/assets/images/gallery/" . $parts[1];
                                                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $imagePathAlt)) {
                                                        $imagePath = $imagePathAlt;
                                                    } else {
                                                        $imagePath = $defaultImage;
                                                    }
                                                } else {
                                                    $imagePath = $defaultImage;
                                                }
                                            }
                                            ?>
                                            <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                                 class="card-img-top gallery-image" 
                                                 alt="User Uploaded Image"
                                                 data-default="/Booking-Hotel-Project/assets/images/gallery/default.jpg"
                                                 onerror="if (!this.src.includes('default.jpg')) this.src = this.dataset.default;">
                                            <div class="gallery-image-overlay">
                                                <div class="gallery-image-actions">
                                                    <button class="btn btn-light btn-sm view-image" data-image="<?php echo htmlspecialchars($imagePath); ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-light btn-sm edit-image" data-id="<?php echo $image['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-light btn-sm delete-image" data-id="<?php echo $image['id']; ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text"><?php echo htmlspecialchars($image['caption'] ?? 'No caption'); ?></p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="gallery-image-status">
                                                    <?php if ($image['approved'] == 1): ?>
                                                        <span class="badge bg-success">Approved</span>
                                                    <?php elseif ($image['approved'] == 2): ?>
                                                        <span class="badge bg-danger">Rejected</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">Pending Review</span>
                                                    <?php endif; ?>
                                                </div>
                                                <small class="text-muted">
                                                    <?php echo date('M d, Y', strtotime($image['created_at'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-gallery-items text-center py-5">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <h4>No Images Yet</h4>
                            <p class="text-muted">Start building your gallery by uploading some images!</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadImageModal">
                                <i class="fas fa-upload"></i> Upload Your First Image
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Upload Image Modal -->
            <div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadImageModalLabel">
                                <i class="fas fa-upload"></i> Upload New Image
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="uploadImageForm" action="/Booking-Hotel-Project/upload_user_image.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Select Image</label>
                                    <input type="file" class="form-control" id="user_image" name="user_image" accept="image/*" required>
                                    <img id="previewImage" src="" alt="Preview" style="display:none;max-width:100%;max-height:150px;margin-top:10px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);" />
                                    <small class="text-muted">Maximum file size: 5MB. Supported formats: JPG, PNG, GIF</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Caption</label>
                                    <textarea class="form-control" name="caption" rows="3" placeholder="Add a description for your image..."></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" form="uploadImageForm" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Upload Image
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <div class="info-section">
                    <div class="reviews-header d-flex justify-content-between align-items-center mb-4">
                        <h5><i class="fas fa-star"></i> My Reviews</h5>
                    </div>

                    <!-- Reviews Stats -->
                    <div class="reviews-stats mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h3><?php echo $total_reviews; ?></h3>
                                        <p>Total Reviews</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-hotel"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h3><?php echo $unique_hotels; ?></h3>
                                        <p>Hotels Reviewed</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h3><?php echo number_format($avg_rating, 1); ?></h3>
                                        <p>Average Rating</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Filters -->
                    <div class="reviews-filters mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <select class="form-select" id="hotelFilter">
                                    <option value="">All Hotels</option>
                                    <?php
                                    $hotels = $pdo->query("SELECT DISTINCT h.id, h.name FROM hotels h JOIN reviews r ON h.id = r.hotel_id WHERE r.user_id = " . $_SESSION['user_id'])->fetchAll();
                                    foreach ($hotels as $hotel) {
                                        echo "<option value='{$hotel['id']}'>{$hotel['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="ratingFilter">
                                    <option value="">All Ratings</option>
                                    <option value="5">5 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="2">2 Stars</option>
                                    <option value="1">1 Star</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="sortFilter">
                                    <option value="newest">Newest First</option>
                                    <option value="oldest">Oldest First</option>
                                    <option value="highest">Highest Rating</option>
                                    <option value="lowest">Lowest Rating</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($user_reviews)): ?>
                        <div class="row reviews-grid">
                            <?php foreach ($user_reviews as $review): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="review-card position-relative shadow-sm p-4 rounded-4 border-0 h-100" 
                                         data-hotel-id="<?php echo $review['hotel_id']; ?>"
                                         data-rating="<?php echo $review['rating']; ?>" 
                                         data-date="<?php echo strtotime($review['review_date']); ?>"
                                         data-approved="<?php echo $review['approved']; ?>">
                                        <!-- حالة التقييم -->
                                        <div class="position-absolute top-0 end-0 m-3">
                                            <?php if ($review['approved']): ?>
                                                <span class="badge bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i> Approved</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark px-3 py-2"><i class="fas fa-clock me-1"></i> Pending</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="<?php echo isset($review['profile_image']) && $review['profile_image'] ? (str_starts_with($review['profile_image'], 'assets/') ? '../' . $review['profile_image'] : '../assets/images/default-profile.png') : '../assets/images/default-profile.png'; ?>" class="rounded-circle me-3 border border-2" style="width:56px;height:56px;object-fit:cover;box-shadow:0 2px 8px #1e5dd122;" alt="User">
                                            <div>
                                                <div class="fw-bold text-primary mb-1">
                                                    <?php echo htmlspecialchars($review['username'] ?? 'User'); ?>
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    <?php echo date('M j, Y', strtotime($review['review_date'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <span class="fw-bold fs-5 text-dark"><i class="fas fa-hotel me-2"></i><?php echo htmlspecialchars($review['hotel_name']); ?></span>
                                            <?php if (!empty($review['room_name'])): ?>
                                                <span class="badge bg-light text-secondary ms-2"><i class="fas fa-door-open me-1"></i><?php echo htmlspecialchars($review['room_name']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="review-rating me-2" style="font-size:1.5rem;">
                                                <?php
                                                $stars = round($review['rating']/2);
                                                echo str_repeat('<i class=\'fas fa-star text-warning\'></i>', $stars);
                                                echo str_repeat('<i class=\'far fa-star text-warning\'></i>', 5-$stars);
                                                ?>
                                            </div>
                                            <span class="fs-6 text-muted ms-2">(<?php echo htmlspecialchars($review['rating']); ?>/10)</span>
                                        </div>
                                        <div class="review-content mb-3">
                                            <p class="review-text mb-0" style="max-height: 4.5em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                                <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                                            </p>
                                            <?php if (strlen($review['comment']) > 150): ?>
                                                <a href="#" class="show-more-link small text-primary mt-1" onclick="this.previousElementSibling.style.maxHeight='none';this.style.display='none';return false;">Show more</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-reviews text-center py-5">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h4>No Reviews Yet</h4>
                            <p class="text-muted">Share your experiences by writing your first review!</p>
                        </div>
                    <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.getElementById('profileUpdateForm');
    let formChanged = false;

    if (profileForm) {
        // Handle form submission
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submission started');

            const formData = new FormData(profileForm);
            
            // Add profile image if selected
            const profileImageInput = document.getElementById('profileImageInputForm');
            if (profileImageInput && profileImageInput.files.length > 0) {
                formData.append('profile_image', profileImageInput.files[0]);
                console.log('Profile image added to FormData');
            }

            // Add cover image if selected
            const coverImageInput = document.getElementById('coverImageInputForm');
            if (coverImageInput && coverImageInput.files.length > 0) {
                formData.append('cover_image', coverImageInput.files[0]);
                console.log('Cover image added to FormData');
            }
            
            // Log FormData contents for debugging
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + (pair[1] instanceof File ? pair[1].name : pair[1]));
            }

            // Show loading spinner
            document.querySelector('.spinner-overlay').style.display = 'flex';

            // Add loading text to spinner
            const spinnerText = document.createElement('div');
            spinnerText.className = 'spinner-text';
            spinnerText.style.color = '#1e5dd1';
            spinnerText.style.marginTop = '15px';
            spinnerText.style.fontWeight = '500';
            spinnerText.textContent = 'Updating profile...';
            document.querySelector('.spinner-overlay').appendChild(spinnerText);

            fetch('profile/update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Server response:', data);
                
                // Wait for 2 seconds before hiding spinner and showing result
                setTimeout(() => {
                    document.querySelector('.spinner-overlay').style.display = 'none';
                    // Remove the loading text
                    const existingText = document.querySelector('.spinner-text');
                    if (existingText) {
                        existingText.remove();
                    }
                    
                    if (data.success) {
                        showToast('Profile updated successfully', 'success');
                        formChanged = false;
                        
                        // Reload the page after successful update
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showToast(data.message || 'Failed to update profile', 'danger');
                    }
                }, 2000); // 2 seconds delay
            })
            .catch(error => {
                console.error('Error:', error);
                setTimeout(() => {
                    document.querySelector('.spinner-overlay').style.display = 'none';
                    // Remove the loading text
                    const existingText = document.querySelector('.spinner-text');
                    if (existingText) {
                        existingText.remove();
                    }
                    showToast('An error occurred while updating the profile', 'danger');
                }, 2000); // 2 seconds delay
            });
        });

        // Track form changes
        profileForm.querySelectorAll('input, textarea, select').forEach(function(input) {
            input.addEventListener('change', function() {
                formChanged = true;
            });
            input.addEventListener('input', function() {
                formChanged = true;
            });
        });
    }

    // Handle beforeunload event
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave this page?';
            return e.returnValue;
        }
    });

    // Handle Cancel Booking
    document.querySelectorAll('.cancel-booking').forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-booking-id');
            
            if (confirm('Are you sure you want to cancel this booking?')) {
                // Show loading spinner
                document.querySelector('.spinner-overlay').style.display = 'flex';
                
                fetch('profile/cancel_booking.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        booking_id: bookingId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Hide loading spinner
                    document.querySelector('.spinner-overlay').style.display = 'none';
                    
                    if (data.success) {
                        showToast('Booking cancelled successfully', 'success');
                        // Reload the page after 1 second
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showToast(data.message || 'Failed to cancel booking', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.querySelector('.spinner-overlay').style.display = 'none';
                    showToast('An error occurred while cancelling the booking', 'danger');
                });
            }
        });
    });

    // Delegate gallery image actions (view, edit, delete)
    document.body.addEventListener('click', function(e) {
        // Delete image
        if (e.target.closest('.delete-image')) {
            const btn = e.target.closest('.delete-image');
            const imageId = btn.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this image?')) {
                fetch('profile/delete_gallery_image.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: imageId })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Image deleted successfully', 'success');
                        refreshUserGallery();
                    } else {
                        showToast(data.message || 'Failed to delete image', 'danger');
                    }
                });
            }
        }
        // View image
        if (e.target.closest('.view-image')) {
            const btn = e.target.closest('.view-image');
            const imgSrc = btn.getAttribute('data-image');
            const card = btn.closest('.user-gallery-image-card');
            const caption = card.querySelector('.card-text').textContent;
            openLightboxModal(imgSrc, caption);
        }
        // Edit image
        if (e.target.closest('.edit-image')) {
            const btn = e.target.closest('.edit-image');
            const imageId = btn.getAttribute('data-id');
            const card = btn.closest('.user-gallery-image-card');
            const caption = card.querySelector('.card-text').textContent;
            openEditGalleryModal(imageId, caption);
        }
    });

    // Gallery filter logic
    const filterButtons = document.querySelectorAll('.gallery-filters .btn');
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.getAttribute('data-filter');
            filterGalleryImages(filter);
        });
    });
});

function filterGalleryImages(filter) {
    const items = document.querySelectorAll('.gallery-item');
    items.forEach(item => {
        const status = item.getAttribute('data-status');
        if (filter === 'all') {
            item.style.display = '';
        } else if (filter === 'approved' && status == '1') {
            item.style.display = '';
        } else if (filter === 'pending' && status == '0') {
            item.style.display = '';
        } else if (filter === 'rejected' && status == '2') {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

let formChanged = false;

// راقب تغيّر حقول الصور أو أي حقل آخر في النموذج
if (document.getElementById('profileUpdateForm')) {
    document.querySelectorAll('#profileUpdateForm input, #profileUpdateForm textarea, #profileUpdateForm select').forEach(function(input) {
        input.addEventListener('change', function() {
            formChanged = true;
        });
    });

    // عند محاولة الخروج أو تحديث الصفحة
    window.addEventListener('beforeunload', function (e) {
        if (formChanged) {
            const message = "You have unsaved changes. Are you sure you want to leave this page?";
            e.preventDefault();
            e.returnValue = message; // للمتصفحات الحديثة
            return message; // للمتصفحات القديمة
        }
    });

    // عند حفظ النموذج بنجاح، أزل التحذير
    document.getElementById('profileUpdateForm').addEventListener('submit', function() {
        formChanged = false;
    });
}

// تحديث معاينة صورة البروفايل مباشرة
const profileInput = document.getElementById('profileImageInputForm');
if (profileInput) {
    profileInput.addEventListener('change', function(e) {
        formChanged = true; // تم إضافته
        if (e.target.files && e.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('profileImagePreview').src = ev.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
}
// تحديث معاينة صورة الغلاف مباشرة
const coverInput = document.getElementById('coverImageInputForm');
if (coverInput) {
    coverInput.addEventListener('change', function(e) {
        formChanged = true; // تم إضافته
        if (e.target.files && e.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('coverImagePreview').src = ev.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
}

// خيارات صورة البروفايل والغلاف
document.getElementById('changeProfileOption').addEventListener('click', function(e) {
    e.preventDefault();
    var profileInput = document.getElementById('profileImageInputForm');
    if (profileInput) profileInput.click();
});
document.getElementById('deleteProfileOption').addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('Are you sure you want to delete the profile photo?')) {
        fetch('profile/delete_image.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'profile' })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('profileImagePreview').src = document.getElementById('profileImagePreview').dataset.default;
                showToast('Profile photo deleted successfully', 'success');
            } else {
                showToast(data.message || 'An error occurred while deleting the photo', 'danger');
            }
        });
    }
});
document.getElementById('changeCoverOption').addEventListener('click', function(e) {
    e.preventDefault();
    var coverInput = document.getElementById('coverImageInputForm');
    if (coverInput) coverInput.click();
});
document.getElementById('deleteCoverOption').addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('Are you sure you want to delete the cover photo?')) {
        fetch('profile/delete_image.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'cover' })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.querySelector('.cover-image').src = document.querySelector('.cover-image').dataset.default;
                showToast('Cover photo deleted successfully', 'success');
            } else {
                showToast(data.message || 'An error occurred while deleting the photo', 'danger');
            }
        });
    }
});

function showToast(message, type = 'success') {
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.top = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    const toast = document.createElement('div');
    toast.className = `toast-message toast-${type}`;
    toast.style.background = type === 'success' ? '#28a745' : '#dc3545';
    toast.style.color = '#fff';
    toast.style.padding = '12px 20px';
    toast.style.marginTop = '10px';
    toast.style.borderRadius = '6px';
    toast.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
    toast.style.display = 'flex';
    toast.style.alignItems = 'center';
    toast.innerHTML = `<span style="font-size:18px;margin-right:8px;">${type === 'success' ? '✔️' : '❌'}</span> <span>${message}</span>`;
    toastContainer.appendChild(toast);
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// رفع صورة المعرض عبر AJAX
const uploadForm = document.getElementById('uploadImageForm');
if (uploadForm) {
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(uploadForm);
        const submitBtn = uploadForm.closest('.modal-content').querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;
        fetch('/Booking-Hotel-Project/upload_user_image.php', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (submitBtn) submitBtn.disabled = false;
            if (data.success) {
                showToast(data.message, 'success');
                // إغلاق المودال
                const modal = bootstrap.Modal.getInstance(document.getElementById('uploadImageModal'));
                if (modal) modal.hide();
                // إعادة تعيين النموذج والمعاينة
                uploadForm.reset();
                const preview = document.getElementById('previewImage');
                if (preview) preview.style.display = 'none';
                // تحديث صور المعرض
                refreshUserGallery();
            } else {
                showToast(data.message || 'Upload failed!', 'danger');
            }
        })
        .catch(err => {
            if (submitBtn) submitBtn.disabled = false;
            showToast('An error occurred while uploading the image', 'danger');
        });
    });
}
// معاينة الصورة قبل الرفع
const userImageInput = document.getElementById('user_image');
if (userImageInput) {
    userImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('previewImage');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.src = ev.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    });
}
// تحديث صور المعرض بعد رفع صورة جديدة
function refreshUserGallery() {
    fetch('profile/fetch_user_gallery.php', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            if (data.success && Array.isArray(data.images)) {
                const grid = document.querySelector('.gallery-grid');
                if (!grid) return;
                grid.innerHTML = '';
                if (data.images.length === 0) {
                    grid.innerHTML = `<div class="no-gallery-items text-center py-5">
                        <i class="fas fa-images fa-3x text-muted mb-3"></i>
                        <h4>No Images Yet</h4>
                        <p class="text-muted">Start building your gallery by uploading some images!</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadImageModal">
                            <i class="fas fa-upload"></i> Upload Your First Image
                        </button>
                    </div>`;
                } else {
                    data.images.forEach(image => {
                        let statusBadge = '';
                        if (image.approved == 1) statusBadge = '<span class="badge bg-success">Approved</span>';
                        else if (image.approved == 2) statusBadge = '<span class="badge bg-danger">Rejected</span>';
                        else statusBadge = '<span class="badge bg-warning">Pending Review</span>';
                        grid.innerHTML += `
                        <div class="col gallery-item" data-status="${image.approved}">
                            <div class="card h-100 user-gallery-image-card">
                                <div class="gallery-image-container">
                                    <img src="/Booking-Hotel-Project/assets/user_uploads/${image.image}" class="card-img-top gallery-image" alt="User Uploaded Image" data-default="/Booking-Hotel-Project/assets/images/gallery/default.jpg" onerror="if (!this.src.includes('default.jpg')) this.src = this.dataset.default;">
                                    <div class="gallery-image-overlay">
                                        <div class="gallery-image-actions">
                                            <button class="btn btn-light btn-sm view-image" data-image="/Booking-Hotel-Project/assets/user_uploads/${image.image}"><i class="fas fa-eye"></i></button>
                                            <button class="btn btn-light btn-sm edit-image" data-id="${image.id}"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-light btn-sm delete-image" data-id="${image.id}"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">${image.caption ? escapeHtml(image.caption) : 'No caption'}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="gallery-image-status">${statusBadge}</div>
                                        <small class="text-muted">${image.created_at ? formatDate(image.created_at) : ''}</small>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    });
                }
                // Re-apply current filter after refresh
                const activeBtn = document.querySelector('.gallery-filters .btn.active');
                if (activeBtn) {
                    filterGalleryImages(activeBtn.getAttribute('data-filter'));
                }
            }
        });
}
function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
function escapeHtml(text) {
    var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Lightbox modal logic
function openLightboxModal(imgSrc, caption) {
    let modal = document.getElementById('galleryLightboxModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'galleryLightboxModal';
        modal.tabIndex = -1;
        modal.innerHTML = `
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-body text-center">
                    <img id="lightboxImage" src="" class="img-fluid rounded mb-3" style="max-height:70vh;object-fit:contain;" alt="Gallery Image">
                    <div id="lightboxCaption" class="mb-2"></div>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>`;
        document.body.appendChild(modal);
    }
    document.getElementById('lightboxImage').src = imgSrc;
    document.getElementById('lightboxCaption').textContent = caption;
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

// Edit modal logic
function openEditGalleryModal(imageId, caption) {
    let modal = document.getElementById('editGalleryModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'editGalleryModal';
        modal.tabIndex = -1;
        modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editGalleryForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Image Caption</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editGalleryImageId">
                        <div class="mb-3">
                            <label for="editGalleryCaption" class="form-label">Caption</label>
                            <textarea class="form-control" name="caption" id="editGalleryCaption" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>`;
        document.body.appendChild(modal);
    }
    document.getElementById('editGalleryImageId').value = imageId;
    document.getElementById('editGalleryCaption').value = caption;
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    document.getElementById('editGalleryForm').onsubmit = function(e) {
        e.preventDefault();
        const id = document.getElementById('editGalleryImageId').value;
        const caption = document.getElementById('editGalleryCaption').value;
        fetch('profile/edit_gallery_image.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, caption })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Caption updated successfully', 'success');
                refreshUserGallery();
                bsModal.hide();
            } else {
                showToast(data.message || 'Failed to update caption', 'danger');
            }
        });
    };
}



function openEditReviewModal(reviewId, rating, comment, roomName) {
    let modal = document.getElementById('editReviewModal');
    if (!modal) return;
    document.getElementById('edit_review_id').value = reviewId;
    document.getElementById('edit_review_rating').value = rating;
    document.getElementById('edit_review_comment').value = comment;
    document.getElementById('edit_review_room_name').value = roomName;
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    document.getElementById('editReviewForm').onsubmit = function(e) {
        e.preventDefault();
        const review_id = document.getElementById('edit_review_id').value;
        const rating = document.getElementById('edit_review_rating').value;
        const comment = document.getElementById('edit_review_comment').value;
        const room_name = document.getElementById('edit_review_room_name').value;
        fetch('profile/edit_review.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ review_id, rating, comment, room_name })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Review updated successfully', 'success');
                refreshUserReviews();
                bsModal.hide();
            } else {
                showToast(data.message || 'Failed to update review', 'danger');
            }
        });
    };
}

function refreshUserReviews() {
    fetch('profile/fetch_user_reviews.php', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            if (data.success && Array.isArray(data.reviews)) {
                const grid = document.querySelector('.reviews-grid');
                if (!grid) return;
                grid.innerHTML = '';
                if (data.reviews.length === 0) {
                    grid.innerHTML = `<div class=\"no-reviews text-center py-5\">
                        <i class=\"fas fa-star fa-3x text-muted mb-3\"></i>
                        <h4>No Reviews Yet</h4>
                        <p class=\"text-muted\">Share your experiences by writing your first review!</p>
                    </div>`;
                } else {
                    data.reviews.forEach(review => {
                        let statusBadge = review.approved == 1 ? '<span class=\"badge bg-success px-3 py-2\"><i class=\"fas fa-check-circle me-1\"></i> Approved</span>' : '<span class=\"badge bg-warning text-dark px-3 py-2\"><i class=\"fas fa-clock me-1\"></i> Pending</span>';
                        grid.innerHTML += `
                        <div class=\"review-card position-relative shadow-sm p-4 rounded-4 border-0 h-100\" 
                             data-hotel-id=\"${review.hotel_id}\"
                             data-rating=\"${review.rating}\" 
                             data-date=\"${new Date(review.review_date).getTime()}\"
                             data-approved=\"${review.approved}\">
                            <div class=\"position-absolute top-0 end-0 m-3\">${statusBadge}</div>
                            <div class=\"d-flex align-items-center mb-2\">
                                <div class=\"review-rating me-2\" style=\"font-size:1.5rem;\">${'★'.repeat(Math.round(review.rating/2))}${'☆'.repeat(5-Math.round(review.rating/2))}</div>
                                <span class=\"fs-6 text-muted ms-2\">(${review.rating}/10)</span>
                            </div>
                            <div class=\"review-content mb-3\">
                                <p class=\"review-text mb-0\" style=\"max-height: 4.5em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;\">${escapeHtml(review.comment)}</p>
                                ${review.comment.length > 150 ? '<a href=\"#\" class=\"show-more-link small text-primary mt-1\" onclick=\"this.previousElementSibling.style.maxHeight=\'none\';this.style.display=\'none\';return false;\">Show more</a>' : ''}
                            </div>
                            <div class=\"review-footer d-flex justify-content-end gap-2\">
                                <button class=\"btn btn-sm btn-outline-primary edit-review\" data-review-id=\"${review.id}\"><i class=\"fas fa-edit\"></i></button>
                                <button class=\"btn btn-sm btn-outline-danger delete-review\" data-review-id=\"${review.id}\"><i class=\"fas fa-trash\"></i></button>
                            </div>
                        </div>`;
                    });
                }
            }
        });
}
</script>
</body>
</html>