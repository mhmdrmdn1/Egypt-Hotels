-- ALTER TABLE statements for missing columns, indexes, and constraints

-- hotels table (collation adjustments)
ALTER TABLE hotels
    MODIFY name VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    MODIFY location VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    MODIFY description TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    MODIFY image VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL;

-- users table (add missing columns if not exist)
ALTER TABLE users
    ADD COLUMN IF NOT EXISTS first_name VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS last_name VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS name VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL AFTER last_name,
    ADD COLUMN IF NOT EXISTS phone VARCHAR(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS date_of_birth DATE DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS gender ENUM('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS address TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS email_notifications TINYINT(1) DEFAULT 1,
    ADD COLUMN IF NOT EXISTS sms_notifications TINYINT(1) DEFAULT 1,
    ADD COLUMN IF NOT EXISTS marketing_emails TINYINT(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- amenities table (collation adjustments)
ALTER TABLE amenities
    MODIFY name VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    MODIFY icon VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    MODIFY details TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL;

-- rooms table (collation adjustments)
ALTER TABLE rooms
    MODIFY name VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    MODIFY description TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    MODIFY image VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL;

-- bookings table (add missing indexes)
ALTER TABLE bookings
    ADD INDEX idx_bookings_email (email),
    ADD INDEX idx_bookings_cancelled (cancelled);

-- admins table (collation and unique index)
ALTER TABLE admins
    MODIFY username VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    MODIFY password VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    ADD UNIQUE KEY username (username);

-- contact_messages table (collation adjustments)
ALTER TABLE contact_messages
    MODIFY name VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    MODIFY email VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    MODIFY subject VARCHAR(200) COLLATE utf8mb4_unicode_ci NOT NULL,
    MODIFY message TEXT COLLATE utf8mb4_unicode_ci NOT NULL;

-- password_resets table (collation and indexes)
ALTER TABLE password_resets
    MODIFY email VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    MODIFY token VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    ADD UNIQUE KEY token (token),
    ADD INDEX email (email);

-- remember_tokens table (collation and unique index)
ALTER TABLE remember_tokens
    MODIFY token VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    ADD UNIQUE KEY token (token);

-- user_gallery table (collation adjustments)
ALTER TABLE user_gallery
    MODIFY image VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    MODIFY caption TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL;

-- notifications table (create if missing)
CREATE TABLE IF NOT EXISTS notifications (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    message VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP(),
    read_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- policies table (create if missing)
CREATE TABLE IF NOT EXISTS policies (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT(11) DEFAULT NULL,
    cancellation TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    children TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    pets TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    smoking TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- reviews table (create if missing)
CREATE TABLE IF NOT EXISTS reviews (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    hotel_id INT(11) NOT NULL,
    room_name VARCHAR(255) NOT NULL,
    rating INT(11) NOT NULL,
    comment TEXT NOT NULL,
    review_date DATETIME DEFAULT CURRENT_TIMESTAMP(),
    approved TINYINT(1) DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- hotel_amenities table (create if missing)
CREATE TABLE IF NOT EXISTS hotel_amenities (
    hotel_id INT(11) NOT NULL,
    amenity_id INT(11) NOT NULL,
    details TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY (hotel_id, amenity_id),
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add folder column to hotels if not exists
ALTER TABLE hotels ADD COLUMN IF NOT EXISTS folder VARCHAR(100) DEFAULT NULL;

-- Update each hotel with its image folder
UPDATE hotels SET folder = 'taba-sands' WHERE name = 'Taba Sands Hotel & Casino';
UPDATE hotels SET folder = 'taba-heights' WHERE name = 'Steigenberger Hotel & Nelson Village';
UPDATE hotels SET folder = 'siwa-shali' WHERE name = 'Siwa Shali Resort';
UPDATE hotels SET folder = 'siwa-eco' WHERE name = 'Siwa Tarriott eco lodge hotel';
UPDATE hotels SET folder = 'sharm-royal' WHERE name = 'Royal Savoy Sharm El Sheikh';
UPDATE hotels SET folder = 'sharm-riv-oasis' WHERE name = 'Reef Oasis Beach Aqua Park Resort';
UPDATE hotels SET folder = 'sharm-coral' WHERE name = 'Domina Coral Bay Resort';
UPDATE hotels SET folder = 'ritz-cairo' WHERE name = 'The Nile Ritz-Carlton';
UPDATE hotels SET folder = 'movenpick-aswan' WHERE name = 'Mövenpick Resort Aswan';
UPDATE hotels SET folder = 'marsa-coral' WHERE name = 'Marsa CoralCoral Hills Resort & SPA';
UPDATE hotels SET folder = 'marsa-alam-blue' WHERE name = 'Casa Blue Resort';
UPDATE hotels SET folder = 'luxor-palace' WHERE name = 'Sofitel Winter Palace Luxor';
UPDATE hotels SET folder = 'luxor-nile' WHERE name = 'Luxor Nile View Flats';
UPDATE hotels SET folder = 'hurghada-sun' WHERE name = 'Sunrise Aqua Joy Resort';
UPDATE hotels SET folder = 'hurghada-golden' WHERE name = 'Golden Beach Resort';
UPDATE hotels SET folder = 'hurghada-beach' WHERE name = 'Serry Beach Resort';
UPDATE hotels SET folder = 'fourseasons-alex' WHERE name = 'Four Seasons At San Stefano';
UPDATE hotels SET folder = 'fayoum-tunzi' WHERE name = 'Tache Boutique Hotel Fayoum';
UPDATE hotels SET folder = 'fayoum-desert' WHERE name = 'Helnan Auberge Fayoum';
UPDATE hotels SET folder = 'dahab-lodge' WHERE name = 'Dahab Lodge';
UPDATE hotels SET folder = 'dahab-blue' WHERE name = 'Beit Theresa';
UPDATE hotels SET folder = 'cairo-nile' WHERE name = 'New Cairo Nyoum Porto New Cairo, Elite Apartments';
UPDATE hotels SET folder = 'cairo-marriott' WHERE name = 'Marriott Mena House';
UPDATE hotels SET folder = 'cairo-concorde' WHERE name = 'Concorde El Salam Cairo Hotel & Casino';
UPDATE hotels SET folder = 'cairo-city' WHERE name = 'Intercontinental Cairo Citystars';
UPDATE hotels SET folder = 'aswan-cataract' WHERE name = 'Sofitel Legend Old Cataract';
UPDATE hotels SET folder = 'alexandria-royal' WHERE name = 'Hotel appartment alexandria sea view';
UPDATE hotels SET folder = 'alex-sea' WHERE name = 'Rhactus House San Stefano';
UPDATE hotels SET folder = 'alamein-marina' WHERE name = 'Palma Bay Rotana Resort';
UPDATE hotels SET folder = 'alamein-beach' WHERE name = 'Chalet in Marassi Marina, Canal view with luxurious furniture';

-- =====================
-- INSERT DATA: admins
-- =====================
INSERT IGNORE INTO admins (id, username, password) VALUES
(6, 'mhmd_rmdn_1', '$2y$10$Ki4oXn83rJYE7kmnlo8Mn.kmAydUFduNOfYXFM7AhpQcShSWCOEdi');

-- =====================
-- INSERT DATA: amenities
-- =====================
INSERT IGNORE INTO amenities (id, name, icon, details) VALUES
(1, 'Eco Design', 'fa-leaf', NULL),
(2, 'Swimming Pool', 'fa-swimming-pool', NULL),
(3, 'Free Wi-Fi', 'fa-wifi', NULL),
(4, 'Restaurant', 'fa-utensils', NULL),
(5, 'Spa', 'fa-spa', NULL),
(6, 'Fitness Center', 'fa-dumbbell', NULL),
(7, 'Private Beach', 'fa-umbrella-beach', NULL),
(8, 'Kids Club', 'fa-child', NULL),
(9, 'Parking', 'fa-car', NULL),
(10, 'Room Service', 'fa-concierge-bell', NULL),
(11, 'Air Conditioning', 'fa-snowflake', NULL),
(12, 'Business Center', 'fa-briefcase', NULL),
(13, 'Conference Facilities', 'fa-users', NULL),
(14, 'Laundry Service', 'fa-tshirt', NULL),
(15, 'Bar/Lounge', 'fa-glass-martini-alt', NULL),
(16, 'Garden', 'fa-tree', NULL),
(17, 'Tennis Court', 'fa-table-tennis', NULL),
(18, 'Diving Center', 'fa-water', NULL),
(19, 'Water Sports', 'fa-swimmer', NULL),
(20, 'Shuttle Service', 'fa-shuttle-van', NULL),
(21, 'Currency Exchange', 'fa-exchange-alt', NULL),
(22, 'Gift Shop', 'fa-gift', NULL),
(23, 'Medical Service', 'fa-medkit', NULL),
(24, 'Security', 'fa-shield-alt', NULL),
(25, 'Tour Desk', 'fa-map-marked-alt', NULL);

-- =====================
-- INSERT DATA: hotels
-- =====================
-- (Paste all INSERTs for hotels here from egypt_hotels.sql)

-- =====================
-- INSERT DATA: hotel_amenities
-- =====================
-- (Paste all INSERTs for hotel_amenities here from egypt_hotels.sql)

-- =====================
-- INSERT DATA: policies
-- =====================
-- (Paste all INSERTs for policies here from egypt_hotels.sql)

-- =====================
-- INSERT DATA: rooms
-- =====================
-- (Paste all INSERTs for rooms here from egypt_hotels.sql)

-- =====================
-- INSERT DATA: reviews
-- =====================
-- (Paste all INSERTs for reviews here from egypt_hotels.sql)

-- =====================
-- INSERT DATA: users
-- =====================
-- (Paste all INSERTs for users here from egypt_hotels.sql)

-- =====================
-- INSERT DATA: bookings
-- =====================
-- (Paste all INSERTs for bookings here from egypt_hotels.sql)

-- =====================
-- INSERT DATA: user_gallery
-- =====================
-- (Paste all INSERTs for user_gallery here from egypt_hotels.sql)

-- =====================
-- INSERT DATA: notifications
-- =====================
-- (Paste all INSERTs for notifications here from egypt_hotels.sql)

-- hotel_amenities table (add missing columns)
ALTER TABLE hotel_amenities
    ADD COLUMN IF NOT EXISTS features TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS hours VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS price VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL; 