<?php

class HotelGallery {
    private $pdo;
    private const BASE_IMAGE_PATH = 'images/hotels/';
    private const POSSIBLE_IMAGE_NAMES = ['1.jpg'];
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Helper method to get hotel folder name
    private function getHotelFolderName($hotel_id) {
        $stmt = $this->pdo->prepare("SELECT name FROM hotels WHERE id = ?");
        $stmt->execute([$hotel_id]);
        $hotel = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$hotel) return null;
        
        // Convert hotel name to folder-friendly format
        return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($hotel['name'])));
    }

    // Helper method to get hotel's cover image path
    public function getHotelCoverImage($hotel_id) {
        $hotelFolder = $this->getHotelFolderName($hotel_id);
        if (!$hotelFolder) return null;

        foreach (self::POSSIBLE_IMAGE_NAMES as $imageName) {
            $imagePath = self::BASE_IMAGE_PATH . $hotelFolder . '/' . $imageName;
            if (file_exists(dirname(__DIR__) . '/' . $imagePath)) {
                return $imagePath;
            }
        }
        return null;
    }

    // جلب جميع صور فندق معين
    public function getHotelImages($hotel_id) {
        $hotelFolder = $this->getHotelFolderName($hotel_id);
        if (!$hotelFolder) return [];

        $images = [];
        $hotelPath = dirname(__DIR__) . '/' . self::BASE_IMAGE_PATH . $hotelFolder;
        
        if (is_dir($hotelPath)) {
            $files = scandir($hotelPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $images[] = [
                        'id' => md5($file), // Generate a unique ID for the image
                        'image' => self::BASE_IMAGE_PATH . $hotelFolder . '/' . $file,
                        'created_at' => date('Y-m-d H:i:s', filemtime($hotelPath . '/' . $file))
                    ];
                }
            }
        }
        
        return $images;
    }

    // حذف صورة
    public function deleteImage($image_id, $hotel_id) {
        $hotelFolder = $this->getHotelFolderName($hotel_id);
        if (!$hotelFolder) return false;

        $hotelPath = dirname(__DIR__) . '/' . self::BASE_IMAGE_PATH . $hotelFolder;
        if (!is_dir($hotelPath)) return false;

        $files = scandir($hotelPath);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && md5($file) === $image_id) {
                $filePath = $hotelPath . '/' . $file;
                if (file_exists($filePath)) {
                    return unlink($filePath);
                }
            }
        }
        return false;
    }

    // إضافة عدة صور دفعة واحدة
    public function addImages($hotel_id, $images) {
        $hotelFolder = $this->getHotelFolderName($hotel_id);
        if (!$hotelFolder) {
            return ['uploaded' => [], 'errors' => ['Hotel not found']];
        }

        $uploadDir = self::BASE_IMAGE_PATH . $hotelFolder . '/';
        $fullUploadDir = dirname(__DIR__) . '/' . $uploadDir;

        // Create directory only if it doesn't exist
        if (!is_dir($fullUploadDir)) {
            if (!mkdir($fullUploadDir, 0777, true)) {
                return ['uploaded' => [], 'errors' => ['Failed to create upload directory']];
            }
        }

        $uploaded = [];
        $errors = [];

        foreach ($images as $img) {
            $filename = uniqid() . '_' . basename($img['name']);
            $image_path = $uploadDir . $filename;
            $target = $fullUploadDir . $filename;

            if (move_uploaded_file($img['tmp_name'], $target)) {
                $uploaded[] = $image_path;
            } else {
                error_log('Failed to upload: ' . $img['name'] . ' (tmp: ' . $img['tmp_name'] . ')');
                $errors[] = $img['name'];
            }
        }
        return ['uploaded' => $uploaded, 'errors' => $errors];
    }
} 