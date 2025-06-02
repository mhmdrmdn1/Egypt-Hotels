<?php

class ImageManager {
    private $uploadDir;
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    private $maxFileSize = 5242880; // 5MB
    private $defaultImage = 'assets/images/hotels/default.jpg';
    private $hasGD;

    public function __construct() {
        $this->uploadDir = __DIR__ . '/../assets/images/hotels/';
        $this->hasGD = extension_loaded('gd') && function_exists('imagecreatetruecolor');
        $this->ensureUploadDirectoryExists();
        $this->ensureDefaultImageExists();
    }

    protected function ensureUploadDirectoryExists() {
        if (!file_exists($this->uploadDir)) {
            if (!mkdir($this->uploadDir, 0777, true)) {
                throw new Exception("Failed to create upload directory");
            }
        }
        
        // Create thumbnails directory
        $thumbDir = $this->uploadDir . 'thumbnails/';
        if (!file_exists($thumbDir)) {
            if (!mkdir($thumbDir, 0777, true)) {
                throw new Exception("Failed to create thumbnails directory");
            }
        }
    }

    private function ensureDefaultImageExists() {
        $defaultImagePath = __DIR__ . '/../' . $this->defaultImage;
        
        if (!file_exists($defaultImagePath)) {
            if ($this->hasGD) {
                // Create default image using GD if available
                $width = 800;
                $height = 600;
                $image = imagecreatetruecolor($width, $height);
                
                // Set background color (light gray)
                $bgColor = imagecolorallocate($image, 240, 240, 240);
                imagefill($image, 0, 0, $bgColor);
                
                // Add text
                $textColor = imagecolorallocate($image, 128, 128, 128);
                $text = "No Image Available";
                $font = 5; // Built-in font
                
                // Calculate text position to center it
                $textWidth = imagefontwidth($font) * strlen($text);
                $textHeight = imagefontheight($font);
                $x = ($width - $textWidth) / 2;
                $y = ($height - $textHeight) / 2;
                
                imagestring($image, $font, $x, $y, $text, $textColor);
                
                // Save the image
                imagejpeg($image, $defaultImagePath, 90);
                imagedestroy($image);
            } else {
                // Copy a static default image if GD is not available
                $staticDefaultImage = __DIR__ . '/../assets/images/default.jpg';
                if (file_exists($staticDefaultImage)) {
                    copy($staticDefaultImage, $defaultImagePath);
                } else {
                    // Create an empty file as last resort
                    file_put_contents($defaultImagePath, '');
                }
            }
        }
    }

    public function getDefaultImage() {
        return '/' . basename(__DIR__) . '/../' . $this->defaultImage;
    }

    public function validateImage($file) {
        $errors = [];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $errors[] = 'The uploaded file exceeds the maximum file size limit.';
                    break;
                default:
                    $errors[] = 'An error occurred during file upload.';
            }
            return $errors;
        }
        
        if (!in_array($file['type'], $this->allowedTypes)) {
            $errors[] = 'Invalid file type. Allowed types: JPEG, PNG, WebP';
        }
        
        if ($file['size'] > $this->maxFileSize) {
            $errors[] = 'File size exceeds the limit of ' . $this->formatSize($this->maxFileSize);
        }
        
        return $errors;
    }

    public function uploadImage($file, $oldFile = null) {
        try {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $destination = $this->uploadDir . $filename;
            
            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                throw new Exception("Failed to move uploaded file.");
            }
            
            // Create thumbnail if GD is available
            if ($this->hasGD) {
                $this->createThumbnail($destination);
            }
            
            // Delete old file if it exists
            if ($oldFile && file_exists($this->uploadDir . $oldFile)) {
                unlink($this->uploadDir . $oldFile);
                // Delete old thumbnail if it exists
                $oldThumb = $this->uploadDir . 'thumbnails/' . $oldFile;
                if (file_exists($oldThumb)) {
                    unlink($oldThumb);
                }
            }
            
            return 'assets/images/hotels/' . $filename;
        } catch (Exception $e) {
            throw new Exception("Failed to process the uploaded image.");
        }
    }

    private function createThumbnail($source, $width = 300, $height = 200) {
        if (!$this->hasGD) {
            return;
        }

        $thumbPath = dirname($source) . '/thumbnails/' . basename($source);
        
        list($origWidth, $origHeight) = getimagesize($source);
        
        $ratio = min($width / $origWidth, $height / $origHeight);
        $newWidth = round($origWidth * $ratio);
        $newHeight = round($origHeight * $ratio);
        
        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        
        $sourceImage = null;
        $extension = strtolower(pathinfo($source, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $sourceImage = imagecreatefromjpeg($source);
                break;
            case 'png':
                $sourceImage = imagecreatefrompng($source);
                // Preserve transparency
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
                break;
            case 'webp':
                $sourceImage = imagecreatefromwebp($source);
                break;
        }
        
        if (!$sourceImage) {
            throw new Exception("Could not create image from source");
        }
        
        imagecopyresampled(
            $thumb, $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $origWidth, $origHeight
        );
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($thumb, $thumbPath, 90);
                break;
            case 'png':
                imagepng($thumb, $thumbPath, 9);
                break;
            case 'webp':
                imagewebp($thumb, $thumbPath, 90);
                break;
        }
        
        imagedestroy($sourceImage);
        imagedestroy($thumb);
    }

    public function getMaxFileSize() {
        return $this->maxFileSize;
    }

    public function formatSize($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
} 