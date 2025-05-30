<?php
// تفعيل عرض الأخطاء أثناء التطوير
ini_set('display_errors', 1);
error_reporting(E_ALL);

// الاتصال بقاعدة البيانات من ملف واحد فقط
require_once __DIR__ . '/../../config/database.php';

// تأكد من وجود اتصال قاعدة البيانات
if (!isset($pdo) || !$pdo) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

file_put_contents(__DIR__ . '/test_log.txt', 'test log ' . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
header('Content-Type: application/json');

// تعريف مسارات الصور
define('ROOMS_PATH', '/Booking-Hotel-Project/pages/images/rooms/');
define('HOTELS_PATH', '/Booking-Hotel-Project/pages/images/hotels/');
define('FILTER_PATH', '/Booking-Hotel-Project/pages/images/filter/');
define('DEFAULT_IMAGE', '/Booking-Hotel-Project/pages/images/hotels/default.jpg');
define('DEFAULT_ROOM_IMAGE', '/Booking-Hotel-Project/pages/images/rooms/default.jpg');

/**
 * إرجاع مسار صورة الفندق بناءً على قيمة folder أو الحقل image
 * - إذا كان هناك صورة مخصصة في الحقل image (assets أو http) تُستخدم مباشرة
 * - إذا لم يوجد، يبحث عن صورة في مجلد filter بنفس اسم folder مع أي امتداد شائع
 * - إذا لم يجد، يستخدم صورة افتراضية
 */
function getHotelImageByFolder($folder, $image = null, $hotelName = '') {
    $logFile = __DIR__ . '/hotel_image_debug.log';
    $log = "Hotel: $hotelName | Folder: $folder | Image field: $image\n";
    
    // If image is already a full URL or starts with assets/, use it directly
    if (!empty($image) && (strpos($image, 'assets/') === 0 || strpos($image, 'http') === 0)) {
        $log .= "-> Using custom image: $image\n";
        file_put_contents($logFile, $log, FILE_APPEND);
        return '/' . ltrim($image, '/');
    }
    
    // If no folder specified, return default image
    if (!$folder) {
        $log .= "-> No folder value, returning default image\n";
        file_put_contents($logFile, $log, FILE_APPEND);
        return DEFAULT_IMAGE;
    }
    
    // Try to find image in filter directory
    $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $tried = [];
    foreach ($extensions as $ext) {
        $filename = $folder . '.' . $ext;
        $full_path = $_SERVER['DOCUMENT_ROOT'] . FILTER_PATH . $filename;
        $tried[] = $filename;
        if (file_exists($full_path)) {
            $log .= "-> Found: $filename\n";
            file_put_contents($logFile, $log, FILE_APPEND);
            return FILTER_PATH . $filename;
        }
    }
    
    // Try with spaces instead of hyphens
    $folder_space = str_replace('-', ' ', $folder);
    foreach ($extensions as $ext) {
        $filename = $folder_space . '.' . $ext;
        $full_path = $_SERVER['DOCUMENT_ROOT'] . FILTER_PATH . $filename;
        $tried[] = $filename;
        if (file_exists($full_path)) {
            $log .= "-> Found: $filename\n";
            file_put_contents($logFile, $log, FILE_APPEND);
            return FILTER_PATH . $filename;
        }
    }
    
    $log .= "-> Not found, tried: " . implode(', ', $tried) . "\n";
    file_put_contents($logFile, $log, FILE_APPEND);
    return DEFAULT_IMAGE;
}

/**
 * Get the correct room image path based on hotel folder and room name
 */
function getRoomImagePath($hotel_folder, $room_name, $room_image = null) {
    $logFile = __DIR__ . '/room_image_debug.log';
    $log = "Hotel Folder: $hotel_folder | Room: $room_name | Image field: $room_image\n";
    
    // If a specific image is provided, check both in hotels and rooms directories
    if ($room_image) {
        // First try the exact path as stored in database
        $full_path = $_SERVER['DOCUMENT_ROOT'] . '/Booking-Hotel-Project/pages/' . ltrim($room_image, '/');
        if (file_exists($full_path)) {
            $log .= "-> Using exact image path: $room_image\n";
            file_put_contents($logFile, $log, FILE_APPEND);
            return ltrim($room_image, '/');
        }
    }
    
    // Try to find room image in hotel-specific folders
    if ($hotel_folder) {
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        // Check in hotels directory (where images currently exist)
        foreach ($extensions as $ext) {
            // Try numbered room format (room1.jpg)
            $filename = "images/hotels/{$hotel_folder}/room" . substr($room_name, -1) . '.' . $ext;
            $full_path = $_SERVER['DOCUMENT_ROOT'] . '/Booking-Hotel-Project/pages/' . $filename;
            if (file_exists($full_path)) {
                $log .= "-> Found numbered room image: $filename\n";
                file_put_contents($logFile, $log, FILE_APPEND);
                return $filename;
            }
        }
    }
    
    $log .= "-> No room image found, using default\n";
    file_put_contents($logFile, $log, FILE_APPEND);
    return 'images/rooms/default.jpg';
}

try {
    if (isset($_GET['id'])) {
        // Get specific hotel details
        $id = intval($_GET['id']);
        
        // Get basic hotel data
        $stmt = $pdo->prepare("SELECT * FROM hotels WHERE id = ?");
        $stmt->execute([$id]);
        $hotel = $stmt->fetch();
        
        if (!$hotel) {
            http_response_code(404);
            echo json_encode(['error' => 'Hotel not found', 'id' => $id]);
            exit;
        }

        // Get rooms with features
        $rooms_stmt = $pdo->prepare("
            SELECT r.*, 
                (SELECT COUNT(*) FROM bookings b WHERE b.room_id = r.id AND b.status = 'active') as booked_count,
                (SELECT GROUP_CONCAT(f.name) FROM room_features rf 
                 JOIN features f ON rf.feature_id = f.id 
                 WHERE rf.room_id = r.id) as features
            FROM rooms r 
            WHERE r.hotel_id = ?");
        
        $rooms_stmt->execute([$id]);
        $rooms = [];
        
        while ($room = $rooms_stmt->fetch()) {
            // Convert concatenated features to array
            $features = $room['features'] ? explode(',', $room['features']) : [];
            
            // Process room image using the new function
            $room_image = getRoomImagePath($hotel['folder'], $room['name'], $room['image']);
            
            $rooms[] = [
                'id' => $room['id'],
                'room_id' => $room['id'],
                'name' => $room['name'],
                'room_name' => $room['name'],
                'description' => $room['description'] ?: 'Experience luxury and comfort in our beautifully designed room.',
                'image' => $room_image,
                'price' => floatval($room['price']),
                'max_guests' => intval($room['max_guests']),
                'is_available' => ($room['booked_count'] < $room['max_guests']),
                'features' => $features,
                'room_type' => $room['room_type'] ?: 'Standard Room',
                'bed_type' => $room['bed_type'] ?: 'King Size',
                'room_size' => $room['room_size'] ?: '40 m²'
            ];
        }

        // Process hotel image
        $hotel_image = getHotelImageByFolder($hotel['folder'], $hotel['image'], $hotel['name']);
        
        // Get hotel images from hotel_images table
        $hotel_images = [];
        $img_stmt = $pdo->prepare("SELECT image FROM hotel_images WHERE hotel_id = ? ORDER BY is_main DESC, id ASC");
        $img_stmt->execute([$hotel['id']]);
        while ($img = $img_stmt->fetch()) {
            if ($img['image'] && strtolower($img['image']) !== 'null') {
                $img_path = '/' . ltrim($img['image'], '/');
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $img_path)) {
                    $hotel_images[] = $img_path;
                }
            }
        }

        // If no images found in hotel_images table, try to get from folder
        if (empty($hotel_images)) {
            $hotel_image = getHotelImageByFolder($hotel['folder'], $hotel['image'], $hotel['name']);
            if ($hotel_image && strtolower($hotel_image) !== 'null' && $hotel_image !== DEFAULT_IMAGE) {
                $hotel_images[] = $hotel_image;
            }
        }

        // If still no images, use default
        if (empty($hotel_images)) {
            $hotel_images[] = DEFAULT_IMAGE;
        }

        // Initialize main_image
        $main_image = null;

        // Priority 1: Use hotel_images if available
        if (!empty($hotel_images) && $hotel_images[0] && strtolower($hotel_images[0]) !== 'null') {
            $main_image = $hotel_images[0];
        }
        // Priority 2: Use hotel_image from filter directory
        else if ($hotel_image && strpos($hotel_image, 'filter/') !== false) {
            $main_image = $hotel_image;
        }
        // Priority 3: Use default image
        if (!$main_image || $main_image === '' || strtolower($main_image) === 'null') {
            $main_image = '/Booking-Hotel-Project/pages/images/hotels/default.jpg';
        }

        // Get amenities
        $amenities_stmt = $pdo->prepare("
            SELECT a.*, ha.details, ha.features, ha.hours, ha.price 
            FROM amenities a 
            JOIN hotel_amenities ha ON a.id = ha.amenity_id 
            WHERE ha.hotel_id = ?");
        $amenities_stmt->execute([$id]);
        $amenities = [];
        while ($amenity = $amenities_stmt->fetch()) {
            $amenities[] = [
                'name' => $amenity['name'],
                'icon' => $amenity['icon'],
                'description' => $amenity['description'] ?: $amenity['details'],
                'features' => $amenity['features'] ? json_decode($amenity['features']) : [],
                'hours' => $amenity['hours'],
                'price' => $amenity['price']
            ];
        }

        // Get reviews
        $reviews = [];
        $reviews_stmt = $pdo->prepare("
            SELECT r.*, u.username, u.profile_image
            FROM reviews r 
            LEFT JOIN users u ON r.user_id = u.id 
            WHERE r.hotel_id = ? 
            LIMIT 10");
        $reviews_stmt->execute([$id]);
        while ($review = $reviews_stmt->fetch()) {
            $reviews[] = [
                'user' => $review['username'] ?: 'Anonymous',
                'profile_image' => !empty($review['profile_image']) ? 'assets/images/profiles/' . $review['profile_image'] : 'pages/images/default-avatar.png',
                'rating' => intval($review['rating']),
                'comment' => $review['comment'],
                'date' => isset($review['created_at']) ? date('M d, Y', strtotime($review['created_at'])) : date('M d, Y')
            ];
        }

        // Get features/amenities for this hotel
        $features = [];
        $features_stmt = $pdo->prepare("SELECT a.name FROM hotel_amenities ha JOIN amenities a ON ha.amenity_id = a.id WHERE ha.hotel_id = ?");
        $features_stmt->execute([$hotel['id']]);
        while ($f = $features_stmt->fetch()) {
            $features[] = $f['name'];
        }
        // إذا لم توجد ميزات، أضف عنصر افتراضي
        if (empty($features)) {
            $features[] = 'No Features';
        }

        // Combine all data
        $hotel_data = [
            'id' => $hotel['id'],
            'name' => $hotel['name'],
            'location' => $hotel['location'],
            'description' => $hotel['description'],
            'folder' => $hotel['folder'],
            'image' => $main_image,
            'images' => array_values($hotel_images),
            'rating' => floatval($hotel['rating'] ?: '4.5'),
            'reviews' => $reviews,
            'price' => floatval($hotel['price']),
            'rooms' => $rooms,
            'amenities' => $amenities,
            'lat' => floatval($hotel['latitude']),
            'lng' => floatval($hotel['longitude']),
            'policies' => [
                'cancellation' => $hotel['cancellation_policy'],
                'children' => $hotel['children_policy'],
                'pets' => $hotel['pets_policy'],
                'smoking' => $hotel['smoking_policy']
            ]
        ];

        // LOGGING: إذا لم توجد صورة صالحة، سجل في ملف لوج
        if (empty($hotel_images) || !$main_image || $main_image === '' || $main_image === null || strtolower($main_image) === 'null' || strpos($main_image, 'default') !== false) {
            $logFile = __DIR__ . '/hotels_api_error.log';
            $logMsg = date('Y-m-d H:i:s') . " | Hotel ID: {$hotel['id']} | Name: {$hotel['name']} | Folder: {$hotel['folder']} | main_image: {$main_image} | images: [" . implode(',', $hotel_images) . "]\n";
            file_put_contents($logFile, $logMsg, FILE_APPEND);
        }

        echo json_encode($hotel_data);
        exit;
    } else {
        // Get all hotels list
        $hotels = [];
        $stmt = $pdo->prepare("
            SELECT h.*,
                   COALESCE(h.reviews, 0) as review_count,
                   h.latitude,
                   h.longitude
            FROM hotels h 
            ORDER BY h.id DESC");
        $stmt->execute();

        while ($hotel = $stmt->fetch()) {
            // Process hotel image
            $hotel_image = getHotelImageByFolder($hotel['folder'], $hotel['image'], $hotel['name']);
            
            // Get hotel images
            $hotel_images = [];
            $img_stmt = $pdo->prepare("SELECT image FROM hotel_images WHERE hotel_id = ? ORDER BY is_main DESC, id ASC");
            $img_stmt->execute([$hotel['id']]);
            while ($img = $img_stmt->fetch()) {
                if ($img['image'] && strtolower($img['image']) !== 'null') {
                    $img_path = '/' . ltrim($img['image'], '/');
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $img_path)) {
                        $hotel_images[] = $img_path;
                    }
                }
            }

            // Get features/amenities for this hotel
            $features = [];
            $features_stmt = $pdo->prepare("SELECT a.name FROM hotel_amenities ha JOIN amenities a ON ha.amenity_id = a.id WHERE ha.hotel_id = ?");
            $features_stmt->execute([$hotel['id']]);
            while ($f = $features_stmt->fetch()) {
                $features[] = $f['name'];
            }
            // إذا لم توجد ميزات، أضف عنصر افتراضي
            if (empty($features)) {
                $features[] = 'No Features';
            }

            // Initialize main_image
            $main_image = null;

            // Priority 1: Use hotel_images if available
            if (!empty($hotel_images) && $hotel_images[0] && strtolower($hotel_images[0]) !== 'null') {
                $main_image = $hotel_images[0];
            }
            // Priority 2: Use hotel_image from filter directory
            else if ($hotel_image && strpos($hotel_image, 'filter/') !== false) {
                $main_image = $hotel_image;
            }
            // Priority 3: Use default image
            if (!$main_image || $main_image === '' || strtolower($main_image) === 'null') {
                $main_image = '/Booking-Hotel-Project/pages/images/hotels/default.jpg';
            }

            // Log coordinates for debugging
            error_log("Hotel: {$hotel['name']}, Lat: {$hotel['latitude']}, Lng: {$hotel['longitude']}");

            $hotels[] = [
                'id' => $hotel['id'],
                'name' => $hotel['name'],
                'location' => $hotel['location'],
                'description' => $hotel['description'],
                'image' => $main_image,
                'price' => floatval($hotel['price']),
                'rating' => floatval($hotel['rating'] ?: '4.5'),
                'reviews' => intval($hotel['review_count']),
                'latitude' => floatval($hotel['latitude'] ?: 26.8206),
                'longitude' => floatval($hotel['longitude'] ?: 30.8025),
                'features' => $features
            ];
        }
        
        echo json_encode($hotels);
    }
} catch (PDOException $e) {
    error_log("API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error occurred',
        'message' => $e->getMessage()
    ]);
}

// Close database connection
if (isset($pdo)) {
    $pdo = null;
}
?> 