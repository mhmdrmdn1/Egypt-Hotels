-- Create features table if not exists
CREATE TABLE IF NOT EXISTS features (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    icon VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    category VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create room_features table if not exists
CREATE TABLE IF NOT EXISTS room_features (
    room_id INT NOT NULL,
    feature_id INT NOT NULL,
    PRIMARY KEY (room_id, feature_id),
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (feature_id) REFERENCES features(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add missing columns to bookings table
ALTER TABLE bookings
    ADD COLUMN IF NOT EXISTS cancelled TINYINT(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS cancelled_at DATETIME DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'pending',
    ADD COLUMN IF NOT EXISTS total_price DECIMAL(10,2) DEFAULT 0.00,
    ADD COLUMN IF NOT EXISTS hotel_name VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS room_name VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL;

-- Add missing columns to rooms table
ALTER TABLE rooms
    ADD COLUMN IF NOT EXISTS room_type VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Standard Room',
    ADD COLUMN IF NOT EXISTS bed_type VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT 'King Size',
    ADD COLUMN IF NOT EXISTS room_size VARCHAR(20) COLLATE utf8mb4_unicode_ci DEFAULT '40 m²';

-- Add missing columns to hotels table
ALTER TABLE hotels
    ADD COLUMN IF NOT EXISTS folder VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS rating DECIMAL(3,2) DEFAULT 4.5;

-- Add missing column to reviews table
ALTER TABLE reviews
    ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Create notifications table if not exists
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create remember_tokens table if not exists
CREATE TABLE IF NOT EXISTS remember_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default features if not exists
INSERT IGNORE INTO features (name, icon, category) VALUES
('King Bed', 'fa-bed', 'Bed Type'),
('Queen Bed', 'fa-bed', 'Bed Type'),
('Twin Beds', 'fa-bed', 'Bed Type'),
('Air Conditioning', 'fa-snowflake', 'Climate'),
('Heating', 'fa-temperature-high', 'Climate'),
('Free Wi-Fi', 'fa-wifi', 'Technology'),
('Smart TV', 'fa-tv', 'Technology'),
('Mini Bar', 'fa-glass-martini', 'Amenities'),
('Coffee Maker', 'fa-coffee', 'Amenities'),
('Safe', 'fa-lock', 'Amenities'),
('Desk', 'fa-desk', 'Furniture'),
('Balcony', 'fa-door-open', 'Layout'),
('Sea View', 'fa-water', 'View'),
('City View', 'fa-city', 'View'),
('Garden View', 'fa-tree', 'View'),
('Room Service', 'fa-concierge-bell', 'Service'),
('Bathtub', 'fa-bath', 'Bathroom'),
('Shower', 'fa-shower', 'Bathroom'),
('Hair Dryer', 'fa-wind', 'Bathroom'),
('Toiletries', 'fa-pump-soap', 'Bathroom');

-- Insert room features based on room types
INSERT IGNORE INTO room_features (room_id, feature_id)
SELECT r.id, f.id
FROM rooms r
CROSS JOIN features f
WHERE 
    (r.name LIKE '%Standard%' AND f.name IN ('Queen Bed', 'Air Conditioning', 'Free Wi-Fi', 'Smart TV', 'Safe', 'Shower', 'Hair Dryer', 'Toiletries')) OR
    (r.name LIKE '%Deluxe%' AND f.name IN ('King Bed', 'Air Conditioning', 'Free Wi-Fi', 'Smart TV', 'Mini Bar', 'Safe', 'Desk', 'Balcony', 'Bathtub', 'Shower', 'Hair Dryer', 'Toiletries')) OR
    (r.name LIKE '%Suite%' AND f.name IN ('King Bed', 'Air Conditioning', 'Free Wi-Fi', 'Smart TV', 'Mini Bar', 'Coffee Maker', 'Safe', 'Desk', 'Balcony', 'Bathtub', 'Shower', 'Hair Dryer', 'Toiletries')) OR
    (r.name LIKE '%Panoramic%' AND f.name IN ('King Bed', 'Air Conditioning', 'Free Wi-Fi', 'Smart TV', 'Mini Bar', 'Coffee Maker', 'Safe', 'Desk', 'Balcony', 'City View', 'Bathtub', 'Shower', 'Hair Dryer', 'Toiletries')) OR
    (r.name LIKE '%Beach%' AND f.name IN ('King Bed', 'Air Conditioning', 'Free Wi-Fi', 'Smart TV', 'Mini Bar', 'Coffee Maker', 'Safe', 'Desk', 'Balcony', 'Sea View', 'Bathtub', 'Shower', 'Hair Dryer', 'Toiletries')); 