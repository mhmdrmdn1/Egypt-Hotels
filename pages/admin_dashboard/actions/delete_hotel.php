<?php
require_once '../../../config/database.php';
require_once '../../../classes/HotelGallery.php';

// Check if user is logged in and has admin privileges
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

try {
    if (!isset($_POST['hotel_id'])) {
        throw new Exception('Hotel ID is required');
    }

    $pdo = getPDO();
    $hotelId = $_POST['hotel_id'];

    // Start transaction
    $pdo->beginTransaction();

    try {
        // Get all images for the hotel
        $gallery = new HotelGallery($pdo);
        $images = $gallery->getImages($hotelId);

        // Delete all images
        foreach ($images as $image) {
            $gallery->deleteImage($image['id'], $hotelId);
        }

        // Delete all bookings
        $stmt = $pdo->prepare("DELETE FROM bookings WHERE hotel_id = ?");
        $stmt->execute([$hotelId]);

        // Delete the hotel
        $stmt = $pdo->prepare("DELETE FROM hotels WHERE id = ?");
        $stmt->execute([$hotelId]);

        $pdo->commit();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} 
require_once '../../../config/database.php';
require_once '../../../classes/HotelGallery.php';

// Check if user is logged in and has admin privileges
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

try {
    if (!isset($_POST['hotel_id'])) {
        throw new Exception('Hotel ID is required');
    }

    $pdo = getPDO();
    $hotelId = $_POST['hotel_id'];

    // Start transaction
    $pdo->beginTransaction();

    try {
        // Get all images for the hotel
        $gallery = new HotelGallery($pdo);
        $images = $gallery->getImages($hotelId);

        // Delete all images
        foreach ($images as $image) {
            $gallery->deleteImage($image['id'], $hotelId);
        }

        // Delete all bookings
        $stmt = $pdo->prepare("DELETE FROM bookings WHERE hotel_id = ?");
        $stmt->execute([$hotelId]);

        // Delete the hotel
        $stmt = $pdo->prepare("DELETE FROM hotels WHERE id = ?");
        $stmt->execute([$hotelId]);

        $pdo->commit();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} 