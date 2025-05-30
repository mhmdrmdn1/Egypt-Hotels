-- First ensure the admins table exists
CREATE TABLE IF NOT EXISTS `admins` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `last_login` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Delete existing admin user to ensure clean state
DELETE FROM `admins` WHERE username = 'mhmd_rmdn_1';

-- Insert admin user (password: 01142377524m)
INSERT INTO `admins` (`username`, `password`) 
VALUES ('mhmd_rmdn_1', '$2y$10$dBDvZoXe4nlgoE8HQn6O5.ahd0D6RFk4Y/A/NxEpD0YnxFgT6Kqoy');

-- Ensure the roles table exists
CREATE TABLE IF NOT EXISTS `roles` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `description` text,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin role if not exists
INSERT IGNORE INTO `roles` (`name`, `description`) 
VALUES ('admin', 'Full system administrator with all permissions');

-- Ensure the user_roles table exists
CREATE TABLE IF NOT EXISTS `user_roles` (
    `user_id` int(11) NOT NULL,
    `role_id` int(11) NOT NULL,
    PRIMARY KEY (`user_id`, `role_id`),
    FOREIGN KEY (`user_id`) REFERENCES `admins`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Assign admin role to the admin user
INSERT IGNORE INTO `user_roles` (`user_id`, `role_id`)
SELECT a.id, r.id 
FROM `admins` a 
CROSS JOIN `roles` r 
WHERE a.username = 'mhmd_rmdn_1' 
AND r.name = 'admin';

-- Grant all permissions to admin
INSERT IGNORE INTO `user_permissions` (`user_id`, `permission_id`)
SELECT a.id, p.id 
FROM `admins` a 
CROSS JOIN `permissions` p 
WHERE a.username = 'mhmd_rmdn_1'; 