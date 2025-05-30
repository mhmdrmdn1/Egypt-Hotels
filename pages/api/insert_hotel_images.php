<?php
require_once '../../config/database.php';

$hotelsDir = __DIR__ . '/../images/hotels/';
$stmt = $pdo->query("SELECT id, folder FROM hotels");
$hotels = $stmt->fetchAll();

foreach ($hotels as $hotel) {
    $folder = $hotel['folder'];
    $hotelId = $hotel['id'];
    $dir = $hotelsDir . $folder;
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $imgPath = "/Booking-Hotel-Project/pages/images/hotels/$folder/$file";
                $check = $pdo->prepare("SELECT COUNT(*) FROM hotel_images WHERE hotel_id = ? AND image = ?");
                $check->execute([$hotelId, $imgPath]);
                if ($check->fetchColumn() == 0) {
                    $insert = $pdo->prepare("INSERT INTO hotel_images (hotel_id, image) VALUES (?, ?)");
                    $insert->execute([$hotelId, $imgPath]);
                }
            }
        }
    }
}
echo "All images uploaded successfully!"; 