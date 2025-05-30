<?php
class Settings {
    private $pdo;
    private static $instance = null;
    private $settings_cache = [];
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Get singleton instance
     */
    public static function getInstance($pdo = null) {
        if (self::$instance === null) {
            if ($pdo === null) {
                throw new Exception('PDO connection required for first initialization');
            }
            self::$instance = new self($pdo);
        }
        return self::$instance;
    }
    
    /**
     * Get a setting value
     */
    public function get($key, $default = null) {
        if (!isset($this->settings_cache[$key])) {
            $stmt = $this->pdo->prepare("SELECT setting_value, setting_type FROM site_settings WHERE setting_key = ?");
            $stmt->execute([$key]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $value = $this->formatValue($result['setting_value'], $result['setting_type']);
                $this->settings_cache[$key] = $value;
            } else {
                return $default;
            }
        }
        
        return $this->settings_cache[$key];
    }
    
    /**
     * Set a setting value
     */
    public function set($key, $value) {
        $stmt = $this->pdo->prepare("
            UPDATE site_settings 
            SET setting_value = ? 
            WHERE setting_key = ?
        ");
        $result = $stmt->execute([$value, $key]);
        
        if ($result) {
            $this->settings_cache[$key] = $value;
        }
        
        return $result;
    }
    
    /**
     * Get all settings by category
     */
    public function getAllByCategory($category = null) {
        $sql = "SELECT * FROM site_settings";
        $params = [];
        
        if ($category) {
            $sql .= " WHERE category = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY category, id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get all categories
     */
    public function getCategories() {
        $stmt = $this->pdo->prepare("SELECT * FROM hotel_categories ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Add hotel category
     */
    public function addCategory($name, $description, $icon) {
        $stmt = $this->pdo->prepare("
            INSERT INTO hotel_categories (name, description, icon)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$name, $description, $icon]);
    }
    
    /**
     * Update hotel category
     */
    public function updateCategory($id, $name, $description, $icon) {
        $stmt = $this->pdo->prepare("
            UPDATE hotel_categories 
            SET name = ?, description = ?, icon = ?
            WHERE id = ?
        ");
        return $stmt->execute([$name, $description, $icon, $id]);
    }
    
    /**
     * Delete hotel category
     */
    public function deleteCategory($id) {
        $stmt = $this->pdo->prepare("DELETE FROM hotel_categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Get all price ranges
     */
    public function getPriceRanges() {
        $stmt = $this->pdo->prepare("SELECT * FROM price_ranges ORDER BY min_price");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Add price range
     */
    public function addPriceRange($name, $min_price, $max_price) {
        $stmt = $this->pdo->prepare("
            INSERT INTO price_ranges (name, min_price, max_price)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$name, $min_price, $max_price]);
    }
    
    /**
     * Update price range
     */
    public function updatePriceRange($id, $name, $min_price, $max_price) {
        $stmt = $this->pdo->prepare("
            UPDATE price_ranges 
            SET name = ?, min_price = ?, max_price = ?
            WHERE id = ?
        ");
        return $stmt->execute([$name, $min_price, $max_price, $id]);
    }
    
    /**
     * Delete price range
     */
    public function deletePriceRange($id) {
        $stmt = $this->pdo->prepare("DELETE FROM price_ranges WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Format setting value based on type
     */
    private function formatValue($value, $type) {
        switch ($type) {
            case 'boolean':
                return (bool)$value;
            case 'number':
                return is_numeric($value) ? (float)$value : 0;
            case 'json':
                return json_decode($value, true) ?? [];
            default:
                return $value;
        }
    }
    
    /**
     * Upload and update an image setting
     */
    public function uploadImage($key, $file) {
        $setting = $this->pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ? AND setting_type = 'image'");
        $setting->execute([$key]);
        $current = $setting->fetchColumn();
        
        // Create uploads directory if it doesn't exist
        $upload_dir = dirname(__DIR__) . '/assets/uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Generate unique filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('img_') . '.' . $ext;
        $filepath = 'assets/uploads/' . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], dirname(__DIR__) . '/' . $filepath)) {
            // Delete old file if it exists
            if ($current && file_exists(dirname(__DIR__) . '/' . $current)) {
                unlink(dirname(__DIR__) . '/' . $current);
            }
            
            // Update setting
            return $this->set($key, $filepath);
        }
        
        return false;
    }
} 