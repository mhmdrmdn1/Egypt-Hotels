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

-- Insert default features
INSERT INTO features (name, icon, category) VALUES
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

-- Insert room features based on room data
INSERT INTO room_features (room_id, feature_id)
SELECT r.id, f.id
FROM rooms r
CROSS JOIN features f
WHERE 
    (r.name LIKE '%Standard%' AND f.name IN ('Queen Bed', 'Air Conditioning', 'Free Wi-Fi', 'Smart TV', 'Safe', 'Shower', 'Hair Dryer', 'Toiletries')) OR
    (r.name LIKE '%Deluxe%' AND f.name IN ('King Bed', 'Air Conditioning', 'Free Wi-Fi', 'Smart TV', 'Mini Bar', 'Safe', 'Desk', 'Balcony', 'Bathtub', 'Shower', 'Hair Dryer', 'Toiletries')) OR
    (r.name LIKE '%Suite%' AND f.name IN ('King Bed', 'Air Conditioning', 'Free Wi-Fi', 'Smart TV', 'Mini Bar', 'Coffee Maker', 'Safe', 'Desk', 'Balcony', 'Bathtub', 'Shower', 'Hair Dryer', 'Toiletries')) OR
    (r.name LIKE '%Panoramic%' AND f.name IN ('King Bed', 'Air Conditioning', 'Free Wi-Fi', 'Smart TV', 'Mini Bar', 'Coffee Maker', 'Safe', 'Desk', 'Balcony', 'City View', 'Bathtub', 'Shower', 'Hair Dryer', 'Toiletries')) OR
    (r.name LIKE '%Beach%' AND f.name IN ('King Bed', 'Air Conditioning', 'Free Wi-Fi', 'Smart TV', 'Mini Bar', 'Coffee Maker', 'Safe', 'Desk', 'Balcony', 'Sea View', 'Bathtub', 'Shower', 'Hair Dryer', 'Toiletries')); 