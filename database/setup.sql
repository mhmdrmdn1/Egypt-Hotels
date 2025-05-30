-- Create features table if not exists
CREATE TABLE IF NOT EXISTS `features` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `icon` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create room_features table if not exists
CREATE TABLE IF NOT EXISTS `room_features` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_id` INT,
  `feature_id` INT,
  FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`feature_id`) REFERENCES `features`(`id`) ON DELETE CASCADE
);

-- Insert default features
INSERT IGNORE INTO `features` (`name`, `icon`) VALUES 
('WiFi', 'fa-wifi'),
('TV', 'fa-tv'),
('Air Conditioning', 'fa-snowflake'),
('Breakfast', 'fa-coffee'),
('Parking', 'fa-parking'),
('Swimming Pool', 'fa-swimming-pool'),
('Gym', 'fa-dumbbell'),
('Spa', 'fa-spa'),
('Restaurant', 'fa-utensils'),
('Bar', 'fa-glass-martini'),
('Room Service', 'fa-concierge-bell'),
('Minibar', 'fa-wine-bottle'),
('Safe', 'fa-vault');

-- Create default room features for existing rooms
INSERT IGNORE INTO `room_features` (`room_id`, `feature_id`)
SELECT r.id, f.id
FROM `rooms` r
CROSS JOIN `features` f
WHERE f.name IN ('WiFi', 'TV', 'Air Conditioning'); 