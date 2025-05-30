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
    $pdo = getPDO();
    $gallery = new HotelGallery($pdo);
    $action = $_POST['action'] ?? '';
    $hotelId = $_POST['hotel_id'] ?? null;

    if (!$hotelId) {
        throw new Exception('Hotel ID is required');
    }

    $response = [];

    switch ($action) {
        case 'upload':
            if (!isset($_FILES['images'])) {
                throw new Exception('No images uploaded');
            }
            
            $images = [];
            $files = $_FILES['images'];
            
            // Restructure files array
            foreach ($files['name'] as $index => $name) {
                if ($files['error'][$index] === UPLOAD_ERR_OK) {
                    $images[] = [
                        'name' => $files['name'][$index],
                        'type' => $files['type'][$index],
                        'tmp_name' => $files['tmp_name'][$index],
                        'error' => $files['error'][$index],
                        'size' => $files['size'][$index]
                    ];
                }
            }
            
            $result = $gallery->addImages($hotelId, $images);
            $response = [
                'success' => true,
                'images' => $result['uploaded'],
                'errors' => $result['errors']
            ];
            break;

        case 'delete':
            $imageId = $_POST['image_id'] ?? null;
            if (!$imageId) {
                throw new Exception('Image ID is required');
            }
            
            $success = $gallery->deleteImage($imageId, $hotelId);
            $response = ['success' => $success];
            break;

        case 'set_featured':
            $imageId = $_POST['image_id'] ?? null;
            if (!$imageId) {
                throw new Exception('Image ID is required');
            }
            
            $success = $gallery->setFeaturedImage($imageId, $hotelId);
            $response = ['success' => $success];
            break;

        case 'reorder':
            $imageIds = $_POST['image_ids'] ?? null;
            if (!$imageIds || !is_array($imageIds)) {
                throw new Exception('Image IDs array is required');
            }
            
            $success = $gallery->updateSortOrder($hotelId, $imageIds);
            $response = ['success' => $success];
            break;

        case 'toggle_status':
            $status = isset($_POST['status']) ? (bool)$_POST['status'] : null;
            if ($status === null) {
                throw new Exception('Status is required');
            }
            
            $success = $gallery->toggleHotelStatus($hotelId, $status);
            $response = ['success' => $success];
            break;

        case 'get_images':
            $images = $gallery->getImages($hotelId);
            $response = ['success' => true, 'images' => $images];
            break;

        default:
            throw new Exception('Invalid action');
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} 