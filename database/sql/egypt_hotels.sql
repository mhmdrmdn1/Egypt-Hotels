-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 12:26 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `egypt_hotels`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(6, 'mhmd_rmdn_1', '$2y$10$Ki4oXn83rJYE7kmnlo8Mn.kmAydUFduNOfYXFM7AhpQcShSWCOEdi');

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `name`, `icon`, `details`) VALUES
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

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `guests` int(11) DEFAULT NULL,
  `rooms` int(11) DEFAULT NULL,
  `special_requests` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cancelled` tinyint(1) DEFAULT 0,
  `cancelled_at` datetime DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT 0.00,
  `hotel_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `hotel_id`, `room_id`, `first_name`, `last_name`, `email`, `phone`, `check_in`, `check_out`, `guests`, `rooms`, `special_requests`, `created_at`, `cancelled`, `cancelled_at`, `total_price`, `hotel_name`, `room_name`, `status`, `user_id`) VALUES
(11, 4, 4, 'Egypt', 'Hotels', '01069787819mohammed@gmail.com', '01126042334', '2025-05-22', '2025-05-23', 1, 4, 'hellow', '2025-05-18 14:02:17', 0, NULL, '4000.00', 'Serry Beach Resort', 'Beachfront Suite', 'approved', 0),
(12, 4, 4, 'Mohammed', 'Ramdan', '01069787819mohammed@gmail.com', '+201069787819', '2025-05-29', '2025-05-30', 3, 3, 'hiiiiiiiiii', '2025-05-18 14:17:22', 0, NULL, '3000.00', 'Serry Beach Resort', 'Beachfront Suite', 'approved', 0),
(13, 1, 1, 'Egypt', 'Hotels', 'egypthotels25@gmail.com', '+201069787819', '2025-05-29', '2025-05-30', 5, 4, 'hellow', '2025-05-18 14:25:23', 0, NULL, '7200.00', 'The Nile Ritz-Carlton', 'Panoramic Room', 'approved', 5),
(14, 3, 3, 'Egypt', 'Hotels', 'egypthotels25@gmail.com', '+201069787819', '2025-05-27', '2025-05-28', 3, 3, 'any', '2025-05-18 14:32:57', 0, NULL, '5400.00', 'Royal Savoy Sharm El Sheikh', 'Family Room', 'approved', 5),
(15, 1, 1, 'Egypt', 'Hotels', 'egypthotels25@gmail.com', '+201069787819', '2025-05-19', '2025-05-20', 1, 1, 'hi', '2025-05-18 14:53:08', 0, NULL, '1800.00', 'The Nile Ritz-Carlton', 'Panoramic Room', 'approved', 5),
(16, 2, 2, 'Egypt', 'Hotels', 'egypthotels25@gmail.com', '+201069787819', '2025-05-21', '2025-05-23', 2, 4, 'hjgjh', '2025-05-18 15:09:08', 0, NULL, '12000.00', 'Four Seasons At San Stefano', 'Panoramic Room', 'approved', 5);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `reviews` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `name`, `location`, `description`, `image`, `rating`, `reviews`, `price`, `created_at`) VALUES
(1, 'The Nile Ritz-Carlton', 'Cairo, Egypt', 'The Nile Ritz-Carlton is a landmark in luxury hospitality, located in the heart of Egypt\'s capital with stunning views of the Nile River. The hotel blends modern luxury with authentic Egyptian hospitality.', 'images/filter/ritz.jpeg', '8.7', 1363, '600.00', '2025-05-19 20:45:37'),
(2, 'Four Seasons At San Stefano', 'Alexandria, Egypt', 'Four Seasons Hotel Alexandria At San Stefano is located directly on the Mediterranean coast, offering an exceptional stay with panoramic sea views. The hotel features elegant design and modern facilities.', 'images/filter/Four Seasons Alexandria at San Stefano.jpeg', '9.2', 1477, '1000.00', '2025-05-19 20:45:37'),
(3, 'Royal Savoy Sharm El Sheikh', 'Sharm El Sheikh, South Sinai', 'A luxurious resort located directly on the Red Sea beach in Sharm El Sheikh, offering stunning views of coral reefs and the sea. The resort features modern design and comprehensive leisure and relaxation services.', 'images/filter/sharm-royal.jpg', '9.3', 1149, '800.00', '2025-05-19 20:45:37'),
(4, 'Serry Beach Resort', 'Hurghada, Red Sea', 'A luxurious beachfront resort in Hurghada offering a distinguished stay with stunning views of the Red Sea. The resort features a private beach, tropical gardens, and diverse entertainment facilities.', 'images/filter/hurghada-beach.jpg', '8.8', 308, '500.00', '2025-05-19 20:45:37'),
(5, 'Sofitel Winter Palace Luxor', 'Luxor, Egypt', 'A historic luxury hotel located on the banks of the Nile in Luxor, combining ancient Egyptian authenticity with modern elegance. It offers charming views of the Luxor Temple and the Nile River.', 'images/filter/luxor-palace.jpg', '8.9', 781, '600.00', '2025-05-19 20:45:37'),
(6, 'Mövenpick Resort Aswan', 'Aswan, Egypt', 'A luxury hotel located on Elephantine Island in the heart of the Nile River, offering panoramic views of the river and stunning natural scenery. The design blends authentic Nubian style with modern elegance.', 'images/filter/aswan-movenpick.jpg', '8.7', 677, '800.00', '2025-05-19 20:45:37'),
(7, 'Dahab Lodge', 'Dahab, South Sinai', 'A charming resort that combines simplicity and comfort, located directly on the Gulf of Aqaba in Dahab. It features authentic Bedouin design and a calm atmosphere ideal for diving lovers and relaxation.', 'images/filter/dahab-lodge.jpg', '7.0', 80, '200.00', '2025-05-19 20:45:37'),
(8, 'Marriott Mena House', 'Zamalek, Cairo', 'A luxury hotel located in the heart of upscale Zamalek, offering breathtaking views of the Nile River and Cairo skyline. It combines global luxury with authentic Egyptian hospitality.', 'images/filter/cairo-marriott.jpg', '8.8', 2050, '900.00', '2025-05-19 20:45:37'),
(9, 'Casa Blue Resort', 'Marsa Alam, Red Sea', 'A luxury beachfront resort located on a private beach in Marsa Alam, surrounded by stunning coral reefs and turquoise waters. It offers an exceptional diving experience and a relaxing stay.', 'images/filter/marsa-alam-blue.jpg', '10.0', 15, '800.00', '2025-05-19 20:45:37'),
(10, 'Steigenberger Hotel & Nelson Village', 'Taba, South Sinai', 'A unique mountain resort overlooking the Gulf of Aqaba and four countries at once. It features a strategic location and a design harmonizing with the surrounding mountains. Offers a distinctive stay with panoramic views of the Red Sea and nearby mountains.', 'images/filter/taba-heights.jpg', '9.8', 9895, '400.00', '2025-05-19 20:45:37'),
(11, 'Helnan Auberge Fayoum', 'Fayoum, Egypt', 'A unique desert resort located on the edge of the Fayoum desert, offering a special stay combining desert charm and modern comfort. Inspired by local architecture, it offers stunning views of Lake Qarun and activities like horseback riding and desert safari.', 'images/filter/fayoum-desert.jpg', '7.8', 704, '600.00', '2025-05-19 20:45:37'),
(12, 'Siwa Tarriott eco lodge hotel', 'Siwa, Egypt', 'A unique eco-resort located in the enchanting Siwa Oasis, combining local heritage with environmental sustainability. It features a design inspired by traditional Siwan architecture and the use of local materials. It offers a unique accommodation experience in the heart of the desert while preserving the environment.', 'images/filter/siwa-eco.jpg', '7.0', 60, '500.00', '2025-05-19 20:45:37'),
(13, 'Chalet in Marassi Marina, Canal view with luxurious furniture', 'New Alamein, Matrouh', 'A luxurious coastal resort overlooking the Mediterranean Sea in New Alamein city. It features modern design and a prime location on a golden sandy beach. It offers a unique stay experience with stunning sea views.', 'images/filter/alamein-beach.jpg', '0.0', 0, '500.00', '2025-05-19 20:45:37'),
(14, 'Intercontinental Cairo Citystars', 'Downtown, Cairo', 'A modern hotel located in the heart of Cairo, featuring contemporary design and a strategic location near major tourist and commercial landmarks. It offers stunning city views and modern services.', 'images/filter/cairo-citystars.jpg', '8.8', 2261, '700.00', '2025-05-19 20:45:37'),
(15, 'Rhactus House San Stefano', 'Alexandria', 'A luxurious hotel overlooking the Mediterranean Sea in the heart of Alexandria. It features modern design and panoramic sea views. It offers a unique stay experience with premium services.', 'images/filter/alex-seaview.jpg', '9.4', 157, '500.00', '2025-05-19 20:45:37'),
(16, 'Domina Coral Bay Resort', 'Sharm El Sheikh', 'A luxurious resort overlooking Naama Bay in Sharm El Sheikh, featuring a prime beachfront location and stunning views of the Red Sea. It offers a unique stay experience with comprehensive entertainment and relaxation facilities.', 'images/filter/sharm-reef.jpg', '9.5', 2, '1100.00', '2025-05-19 20:45:37'),
(17, 'Sunrise Aqua Joy Resort', 'Hurghada, Red Sea', 'A luxurious beachfront resort overlooking the Red Sea in Hurghada, featuring modern design and a prime location on a golden sandy beach. It offers a comfortable stay experience with comprehensive entertainment and relaxation facilities.', 'images/filter/hurghada-sunrise.jpg', '9.1', 5206, '750.00', '2025-05-19 20:45:37'),
(18, 'Luxor Nile View Flats', 'Nile Corniche, Luxor', 'A luxurious hotel overlooking the Nile River in the heart of Luxor, featuring design inspired by ancient Egyptian architecture and a prime location near archaeological sites. It offers a unique stay experience with stunning Nile views.', 'images/filter/luxor-nile.jpg', '8.1', 53, '400.00', '2025-05-19 20:45:37'),
(19, 'Sofitel Legend Old Cataract', 'Aswan Island, Aswan', 'A unique resort located on Aswan Island in the heart of the Nile River, offering panoramic views of the river and stunning natural scenery. The design blends authentic Nubian style with modern elegance.', 'images/filter/aswan-cataract.jpg', '9.3', 931, '1000.00', '2025-05-19 20:45:37'),
(20, 'Beit Theresa', 'Dahab, South Sinai', 'A charming resort located directly on the Gulf of Aqaba in Dahab, offering a unique blend of Bedouin charm and modern comfort. It features authentic design and a calm atmosphere ideal for diving lovers and relaxation.', 'images/filter/dahab-blue.jpg', '9.7', 223, '1100.00', '2025-05-19 20:45:37'),
(21, 'New Cairo Nyoum Porto New Cairo, Elite Apartments', 'Cairo, Egypt', 'A luxurious hotel located on the banks of the Nile in Cairo, combining modern elegance with ancient Egyptian charm. It offers panoramic views of the Nile River and a prime location near major tourist attractions.', 'images/filter/cairo-nile.jpg', '7.1', 7, '200.00', '2025-05-19 20:45:37'),
(22, 'Marsa CoralCoral Hills Resort & SPA', 'Marsa Matrouh, Egypt', 'A luxurious resort located on the coast of Marsa Matrouh, offering stunning views of the Red Sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.', 'images/filter/marsa-alam-coral.jpg', '7.4', 149, '250.00', '2025-05-19 20:45:37'),
(23, 'Taba Sands Hotel & Casino', 'Taba, Egypt', 'A luxurious resort located on the shores of the Red Sea in Taba, offering stunning views of the sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.', 'images/filter/taba-sands.jpg', '8.7', 351, '600.00', '2025-05-19 20:45:37'),
(24, 'Tache Boutique Hotel Fayoum', 'Fayoum, Egypt', 'A unique resort located on the shores of Lake Qarun in Fayoum, offering stunning views of the lake and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.', 'images/filter/fayoum-tunis.jpg', '7.3', 51, '200.00', '2025-05-19 20:45:37'),
(25, 'Siwa Shali Resort', 'Siwa Oasis, Egypt', 'A unique eco-resort located in the enchanting Siwa Oasis, combining local heritage with environmental sustainability. It features a design inspired by traditional Siwan architecture and the use of local materials.', 'images/filter/siwa-shali.jpg', '8.3', 152, '450.00', '2025-05-19 20:45:37'),
(26, 'Palma Bay Rotana Resort', 'Alamein, Matrouh', 'A luxurious resort located on the shores of the Mediterranean Sea in Alamein, offering stunning views of the sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.', 'images/filter/alamein-marina.jpg', '9.2', 164, '900.00', '2025-05-19 20:45:37'),
(27, 'Concorde El Salam Cairo Hotel & Casino', 'Downtown, Cairo', 'A modern hotel located in the heart of Cairo, featuring contemporary design and a strategic location near major tourist and commercial landmarks. It offers stunning city views and modern services.', 'images/filter/cairo-concorde.jpg', '8.5', 2843, '600.00', '2025-05-19 20:45:37'),
(28, 'Hotel appartment alexandria sea view', 'Alexandria, Egypt', 'A luxurious hotel located on the shores of the Mediterranean Sea in Alexandria, offering stunning views of the sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.', 'images/filter/alex-royal.jpg', '8.2', 65, '400.00', '2025-05-19 20:45:37'),
(29, 'Reef Oasis Beach Aqua Park Resort', 'Sharm El Sheikh, South Sinai', 'A luxurious desert resort located on the shores of the Gulf of Aqaba in Sharm El Sheikh, offering stunning views of the desert and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.', 'images/filter/sharm-reef.jpg', '9.1', 3254, '800.00', '2025-05-19 20:45:37'),
(30, 'Golden Beach Resort', 'Hurghada, Egypt', 'A luxurious resort located on the shores of the Red Sea in Hurghada, offering stunning views of the sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.', 'images/filter/hurghada-golden.jpg', '8.5', 2951, '700.00', '2025-05-19 20:45:37'),
(31, 'Mohamed Ramadan', 'Cairo', '0', 'assets/images/hotels/hotel_682ba3bd2bbf93.11570950.png', NULL, NULL, '500.00', '2025-05-19 20:49:34');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_amenities`
--

CREATE TABLE `hotel_amenities` (
  `hotel_id` int(11) NOT NULL,
  `amenity_id` int(11) NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_amenities`
--

INSERT INTO `hotel_amenities` (`hotel_id`, `amenity_id`, `details`) VALUES
(1, 2, 'Enjoy a temperature-controlled luxury pool with a stunning Nile view and poolside service.'),
(1, 3, 'Unlimited high-speed Wi-Fi available in all rooms and public areas for seamless connectivity.'),
(1, 4, 'A variety of international cuisines served in elegant settings by top chefs.'),
(1, 5, 'Relax and rejuvenate with world-class spa treatments and wellness therapies.'),
(1, 6, 'State-of-the-art gym with personal trainers and modern fitness equipment.'),
(1, 9, 'Convenient valet parking available 24/7 for all guests and visitors.'),
(1, 10, 'Order food, drinks, and amenities to your room at any hour of the day.'),
(1, 12, 'Fully equipped business center with meeting rooms, printers, and high-speed internet.'),
(2, 2, 'Stunning infinity pool overlooking the Mediterranean Sea.'),
(2, 3, 'Complimentary Wi-Fi throughout the hotel premises.'),
(2, 4, 'Multiple restaurants offering a range of local and international dishes.'),
(2, 5, 'Indulge in luxurious spa treatments and wellness programs.'),
(2, 6, 'Modern fitness center with the latest equipment and personal trainers.'),
(2, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(2, 10, 'Prompt and courteous room service available around the clock.'),
(2, 18, 'Organized sea cruises and boat trips for guests.'),
(3, 2, 'Enjoy multiple pools for swimming, lounging, and water sports.'),
(3, 4, 'A variety of restaurants serving local and international cuisine.'),
(3, 5, 'Relax and rejuvenate with spa treatments and massages.'),
(3, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(3, 8, 'Kid-friendly activities and a supervised play area.'),
(3, 15, 'Relax with a refreshing drink at the beach bar overlooking the Red Sea.'),
(3, 17, 'Outdoor sports courts for beach volleyball, tennis, and more.'),
(3, 19, 'Professional diving center offering PADI certification courses and underwater adventures.'),
(4, 2, 'Enjoy multiple pools for swimming, lounging, and water sports.'),
(4, 4, 'Five restaurants offering a variety of local and international dishes.'),
(4, 5, 'Relax and rejuvenate with spa treatments and wellness therapies.'),
(4, 6, 'Modern gym with state-of-the-art equipment and personal trainers.'),
(4, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(4, 8, 'Kid-friendly activities and a supervised play area.'),
(4, 15, 'Live music and entertainment in the evenings.'),
(4, 19, 'Water sports activities such as snorkeling, diving, and kayaking.'),
(5, 2, 'Stunning outdoor pool overlooking the Nile with poolside service.'),
(5, 3, 'Unlimited Wi-Fi available throughout the hotel premises.'),
(5, 4, 'Fine dining experiences with a variety of international cuisines.'),
(5, 5, 'Relax and rejuvenate with spa treatments and massages.'),
(5, 9, 'Convenient car rental service for exploring Luxor.'),
(5, 10, 'Room service available 24/7 for all your needs.'),
(5, 16, 'Guided tours of Luxor\'s historical sites and landmarks.'),
(5, 18, 'Organized Nile cruises for exploring the river and its landmarks.'),
(6, 2, 'Infinity pool with stunning views of the Nile and poolside service.'),
(6, 3, 'Unlimited Wi-Fi available throughout the hotel premises.'),
(6, 4, 'A variety of local and international dining options.'),
(6, 5, 'World-class spa treatments and wellness therapies.'),
(6, 15, 'Relax with a drink at the Nile Lounge overlooking the Nile.'),
(6, 16, 'Guided tours of Aswan\'s historical sites and landmarks.'),
(6, 18, 'Private marina for boat trips and water sports.'),
(6, 20, 'Free shuttle service to and from the Aswan Airport.'),
(7, 3, 'Free Wi-Fi available throughout the hotel premises.'),
(7, 4, 'A variety of local and international cuisine served in elegant settings.'),
(7, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(7, 11, 'Relax with a coffee at the beach café overlooking the Red Sea.'),
(7, 17, 'Bicycle rental service for exploring Dahab.'),
(7, 19, 'Professional diving center offering PADI certification courses and underwater adventures.'),
(7, 20, 'Shuttle service to and from Dahab Airport.'),
(7, 21, 'Bedouin-style seating areas for relaxation and socializing.'),
(8, 2, 'Luxury pool with stunning views of the Nile and poolside service.'),
(8, 4, 'Eight restaurants offering a variety of international cuisine.'),
(8, 5, 'Spa and beauty salon offering a range of treatments and services.'),
(8, 6, 'Fully equipped gym with personal trainers and modern fitness equipment.'),
(8, 9, 'Valet parking service available 24/7 for all guests and visitors.'),
(8, 10, 'Order food, drinks, and amenities to your room at any hour of the day.'),
(8, 12, 'Fully equipped business center with meeting rooms, printers, and high-speed internet.'),
(8, 15, 'Relax with a drink at the Nile View Lounge overlooking the Nile.'),
(9, 2, 'Four pools for swimming, lounging, and water sports.'),
(9, 4, 'Five restaurants offering a variety of local and international dishes.'),
(9, 5, 'Relax and rejuvenate with spa treatments and massages.'),
(9, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(9, 8, 'Kid-friendly activities and a supervised play area.'),
(9, 15, 'Relax with a refreshing drink at the beach bar overlooking the Red Sea.'),
(9, 17, 'Water sports activities such as snorkeling, diving, and kayaking.'),
(9, 19, 'PADI diving center offering diving courses and underwater adventures.'),
(10, 2, 'Stunning infinity pool overlooking the Mediterranean Sea.'),
(10, 4, 'Three restaurants offering a variety of local and international cuisine.'),
(10, 5, 'Relax and rejuvenate with spa treatments and wellness therapies.'),
(10, 17, 'Outdoor sports courts for beach volleyball, tennis, and more.'),
(10, 20, 'Shuttle service to and from Taba Airport.'),
(10, 22, 'Stunning views of the mountains and the Gulf of Aqaba.'),
(10, 23, 'Guided hikes through the beautiful Sinai Mountains.'),
(10, 24, 'Experience Bedouin culture with nightly entertainment and storytelling.'),
(11, 4, 'Local cuisine served in a traditional Bedouin tent.'),
(11, 5, 'Relaxation sessions in the spa with traditional treatments.'),
(11, 7, 'Private beach with sunbeds and umbrellas.'),
(11, 15, 'BBQ nights under the stars with Bedouin storytelling.'),
(11, 17, 'Safari trips for exploring the desert on camelback or bicycle.'),
(11, 21, 'Bedouin-style camp with traditional activities and overnight stays.'),
(11, 22, 'Guided horseback riding tours through the desert.'),
(11, 24, 'Evenings spent stargazing under the clear Egyptian skies.'),
(12, 1, 'Eco-friendly design with local materials and sustainable practices.'),
(12, 2, 'Natural pool with stunning views of the oasis and surrounding desert.'),
(12, 4, 'Organic cuisine served in a traditional Bedouin tent.'),
(12, 5, 'Natural spa treatments using local ingredients and techniques.'),
(12, 16, 'Beautiful gardens with local plants and trees.'),
(12, 17, 'Desert tours on camelback or bicycle for exploring the surrounding landscape.'),
(12, 20, 'Solar panels providing sustainable energy for the resort.'),
(12, 24, 'Traditional Bedouin storytelling and music in the evenings.'),
(13, 2, 'Three pools for swimming, lounging, and water sports.'),
(13, 4, 'Four restaurants offering a variety of local and international cuisine.'),
(13, 5, 'Luxury spa with a range of treatments and massages.'),
(13, 6, 'Modern gym with state-of-the-art equipment and personal trainers.'),
(13, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(13, 8, 'Kid-friendly activities and a supervised play area.'),
(13, 15, 'Relax with a refreshing drink at the beach bar overlooking the Mediterranean Sea.'),
(13, 17, 'Water sports activities such as snorkeling, diving, and kayaking.'),
(14, 3, 'Unlimited Wi-Fi available throughout the hotel premises.'),
(14, 4, 'Three restaurants offering a variety of local and international cuisine.'),
(14, 5, 'Spa offering a range of treatments and services.'),
(14, 6, 'Modern gym with state-of-the-art equipment and personal trainers.'),
(14, 9, 'Valet parking service available 24/7 for all guests and visitors.'),
(14, 10, 'Order food, drinks, and amenities to your room at any hour of the day.'),
(14, 12, 'Fully equipped business center with meeting rooms, printers, and high-speed internet.'),
(14, 15, 'Relax with a drink at the rooftop bar overlooking the city skyline.'),
(15, 2, 'Infinity pool overlooking the Mediterranean Sea with poolside service.'),
(15, 4, 'Four restaurants offering a variety of international cuisine.'),
(15, 5, 'World-class spa treatments and wellness therapies.'),
(15, 6, 'Modern gym with personal trainers and state-of-the-art equipment.'),
(15, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(15, 10, 'Room service available 24/7 for all your needs.'),
(15, 15, 'Relax with a drink at the sea bar overlooking the Mediterranean Sea.'),
(15, 18, 'Organized sea cruises for exploring the Mediterranean.'),
(16, 2, 'Four pools for swimming, lounging, and water sports.'),
(16, 4, 'Five restaurants offering a variety of local and international cuisine.'),
(16, 5, 'World-class spa treatments and wellness therapies.'),
(16, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(16, 8, 'Kid-friendly activities and a supervised play area.'),
(16, 15, 'Relax with a refreshing drink at the beach bar overlooking the Mediterranean Sea.'),
(16, 17, 'Water sports activities such as snorkeling, diving, and kayaking.'),
(16, 19, 'PADI diving center offering diving courses and underwater adventures.'),
(17, 2, 'Three pools for swimming, lounging, and water sports.'),
(17, 4, 'Four restaurants offering a variety of local and international cuisine.'),
(17, 5, 'Spa offering a range of treatments and massages.'),
(17, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(17, 8, 'Kid-friendly activities and a supervised play area.'),
(17, 15, 'Relax with a refreshing drink at the beach bar overlooking the Red Sea.'),
(17, 17, 'Water sports activities such as snorkeling, diving, and kayaking.'),
(17, 19, 'Professional diving center offering PADI certification courses and underwater adventures.'),
(18, 2, 'Stunning outdoor pool overlooking the Nile with poolside service.'),
(18, 4, 'Three restaurants offering a variety of local and international cuisine.'),
(18, 5, 'Spa offering a range of treatments and massages.'),
(18, 6, 'Modern gym with state-of-the-art equipment and personal trainers.'),
(18, 10, 'Order food, drinks, and amenities to your room at any hour of the day.'),
(18, 15, 'Relax with a drink at the Nile View Lounge overlooking the Nile.'),
(18, 16, 'Guided tours of Luxor\'s historical sites and landmarks.'),
(18, 18, 'Organized Nile cruises for exploring the river and its landmarks.'),
(19, 2, 'Infinity pool overlooking the Nile with poolside service.'),
(19, 4, 'Three restaurants offering a variety of local and international cuisine.'),
(19, 5, 'Nubian spa treatments and wellness therapies.'),
(19, 6, 'Modern gym with personal trainers and state-of-the-art equipment.'),
(19, 10, 'Room service available 24/7 for all your needs.'),
(19, 15, 'Relax with a drink at the Nile View Lounge overlooking the Nile.'),
(19, 16, 'Guided tours of Aswan\'s historical sites and landmarks.'),
(19, 18, 'Organized Nile cruises for exploring the river and its landmarks.'),
(20, 3, 'Free Wi-Fi available throughout the hotel premises.'),
(20, 4, 'Local cuisine served in a traditional Bedouin tent.'),
(20, 5, 'Bedouin spa treatments and wellness therapies.'),
(20, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(20, 15, 'Relax with a refreshing drink at the beach bar overlooking the Red Sea.'),
(20, 17, 'Water sports activities such as snorkeling, diving, and kayaking.'),
(20, 19, 'PADI diving center offering diving courses and underwater adventures.'),
(20, 21, 'Bedouin-style seating areas for relaxation and socializing.'),
(21, 2, 'Luxury pool with panoramic Nile views and poolside service.'),
(21, 3, 'Unlimited high-speed Wi-Fi available in all rooms and public areas.'),
(21, 4, 'International cuisine served in a modern setting.'),
(21, 6, 'Modern gym with personal trainers and state-of-the-art equipment.'),
(21, 9, 'Valet parking service available 24/7 for all guests and visitors.'),
(21, 10, 'Order food, drinks, and amenities to your room at any hour of the day.'),
(21, 12, 'Business center with meeting rooms and office services.'),
(21, 15, 'Relax with a drink at the Nile View Lounge overlooking the Nile.'),
(22, 2, 'Three pools for swimming, lounging, and water sports.'),
(22, 4, 'Four restaurants offering a variety of local and international cuisine.'),
(22, 5, 'Luxury spa with a range of treatments and massages.'),
(22, 6, 'Modern gym with state-of-the-art equipment and personal trainers.'),
(22, 7, 'Private sandy beach with sunbeds and umbrellas.'),
(22, 8, 'Kid-friendly activities and a supervised play area.'),
(22, 15, 'Relax with a refreshing drink at the beach bar overlooking the Red Sea.'),
(22, 17, 'Water sports activities such as snorkeling, diving, and kayaking.'),
(23, 2, 'Infinity pool overlooking the Red Sea with poolside service.'),
(23, 4, 'Three restaurants offering a variety of local and international cuisine.'),
(23, 5, 'World-class spa treatments and wellness therapies.'),
(23, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(23, 10, 'Room service available 24/7 for all your needs.'),
(23, 15, 'Relax with a drink at the casino lounge.'),
(23, 17, 'Outdoor sports courts for beach volleyball, tennis, and more.'),
(23, 24, 'Tour desk for organizing excursions and sightseeing.'),
(24, 2, 'Natural pool with stunning views of Lake Qarun.'),
(24, 4, 'Organic cuisine served in a traditional setting.'),
(24, 5, 'Natural spa treatments using local ingredients and techniques.'),
(24, 7, 'Private beach with sunbeds and umbrellas.'),
(24, 16, 'Beautiful gardens with local plants and trees.'),
(24, 17, 'Desert tours on camelback or bicycle for exploring the surrounding landscape.'),
(24, 20, 'Shuttle service to and from Fayoum city.'),
(24, 24, 'Traditional Bedouin storytelling and music in the evenings.'),
(25, 1, 'Eco-friendly design with local materials and sustainable practices.'),
(25, 2, 'Natural pool with stunning views of the oasis and surrounding desert.'),
(25, 4, 'Organic cuisine served in a traditional Bedouin tent.'),
(25, 5, 'Natural spa treatments using local ingredients and techniques.'),
(25, 16, 'Beautiful gardens with local plants and trees.'),
(25, 17, 'Desert tours on camelback or bicycle for exploring the surrounding landscape.'),
(25, 20, 'Solar panels providing sustainable energy for the resort.'),
(25, 24, 'Traditional Bedouin storytelling and music in the evenings.'),
(26, 2, 'Four pools for swimming, lounging, and water sports.'),
(26, 4, 'Five restaurants offering a variety of local and international cuisine.'),
(26, 5, 'World-class spa treatments and wellness therapies.'),
(26, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(26, 8, 'Kid-friendly activities and a supervised play area.'),
(26, 15, 'Relax with a refreshing drink at the beach bar overlooking the Mediterranean Sea.'),
(26, 17, 'Water sports activities such as snorkeling, diving, and kayaking.'),
(26, 19, 'PADI diving center offering diving courses and underwater adventures.'),
(27, 3, 'Unlimited Wi-Fi available throughout the hotel premises.'),
(27, 4, 'Three restaurants offering a variety of local and international cuisine.'),
(27, 5, 'Spa offering a range of treatments and services.'),
(27, 6, 'Modern gym with state-of-the-art equipment and personal trainers.'),
(27, 9, 'Valet parking service available 24/7 for all guests and visitors.'),
(27, 10, 'Order food, drinks, and amenities to your room at any hour of the day.'),
(27, 12, 'Fully equipped business center with meeting rooms, printers, and high-speed internet.'),
(27, 15, 'Relax with a drink at the rooftop bar overlooking the city skyline.'),
(28, 2, 'Infinity pool overlooking the Mediterranean Sea with poolside service.'),
(28, 4, 'Four restaurants offering a variety of international cuisine.'),
(28, 5, 'World-class spa treatments and wellness therapies.'),
(28, 6, 'Modern gym with personal trainers and state-of-the-art equipment.'),
(28, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(28, 10, 'Room service available 24/7 for all your needs.'),
(28, 15, 'Relax with a drink at the sea bar overlooking the Mediterranean Sea.'),
(28, 18, 'Organized sea cruises for exploring the Mediterranean.'),
(29, 2, 'Four pools for swimming, lounging, and water sports.'),
(29, 4, 'Five restaurants offering a variety of local and international cuisine.'),
(29, 5, 'World-class spa treatments and wellness therapies.'),
(29, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(29, 8, 'Kid-friendly activities and a supervised play area.'),
(29, 15, 'Relax with a refreshing drink at the beach bar overlooking the Red Sea.'),
(29, 17, 'Water sports activities such as snorkeling, diving, and kayaking.'),
(29, 19, 'PADI diving center offering diving courses and underwater adventures.'),
(30, 2, 'Three pools for swimming, lounging, and water sports.'),
(30, 4, 'Four restaurants offering a variety of local and international cuisine.'),
(30, 5, 'Luxury spa with a range of treatments and massages.'),
(30, 6, 'Modern gym with state-of-the-art equipment and personal trainers.'),
(30, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.'),
(30, 8, 'Kid-friendly activities and a supervised play area.'),
(30, 15, 'Relax with a refreshing drink at the beach bar overlooking the Red Sea.'),
(30, 17, 'Water sports activities such as snorkeling, diving, and kayaking.');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`, `read_at`) VALUES
(1, 0, 'تمت الموافقة على حجزك!', 0, '2025-05-18 16:53:05', NULL),
(2, 0, 'تمت الموافقة على حجزك!', 0, '2025-05-18 17:02:54', NULL),
(3, 0, 'تمت الموافقة على حجزك!', 0, '2025-05-18 17:03:08', NULL),
(4, 0, 'تمت الموافقة على حجزك!', 0, '2025-05-18 17:03:10', NULL),
(5, 0, 'تمت الموافقة على حجزك!', 0, '2025-05-18 17:03:13', NULL),
(6, 0, 'تمت الموافقة على حجزك!', 0, '2025-05-18 17:03:14', NULL),
(7, 0, 'تمت الموافقة على حجزك!', 0, '2025-05-18 17:03:19', NULL),
(8, 0, 'تمت الموافقة على حجزك!', 0, '2025-05-18 17:17:56', NULL),
(12, 5, 'تمت الموافقة على حجزك!', 1, '2025-05-18 18:11:46', '2025-05-18 15:12:42');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

CREATE TABLE `policies` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `cancellation` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `children` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pets` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smoking` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `policies`
--

INSERT INTO `policies` (`id`, `hotel_id`, `cancellation`, `children`, `pets`, `smoking`) VALUES
(1, 1, 'Free cancellation up to 72 hours before arrival. 50% charge for cancellations between 72-24 hours. No refund for cancellations within 24 hours.', 'Children under 12 years stay free when sharing parents room. Extra bed available for children 12-18 years at 50% of adult rate.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(2, 2, 'Free cancellation up to 48 hours before arrival. 50% charge for cancellations between 48-24 hours. No refund for cancellations within 24 hours.', 'Children under 6 years stay free. Children 6-12 years at 50% of adult rate. Extra bed available for children 12-18 years.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in restaurants and bars.'),
(3, 3, 'Free cancellation up to 72 hours before arrival. 50% charge for cancellations between 72-24 hours. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 75% of adult rate. Kids club available for children 4-12 years.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(4, 4, 'Free cancellation up to 48 hours before arrival. 50% charge for cancellations between 48-24 hours. No refund for cancellations within 24 hours.', 'Children under 6 years stay free. Children 6-12 years at 50% of adult rate. Kids club available for children 4-12 years.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(5, 5, 'Free cancellation up to 72 hours before arrival. 50% charge for cancellations between 72-24 hours. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 75% of adult rate. Extra bed available for children.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(6, 6, 'Free cancellation up to 48 hours before arrival. 50% charge for cancellations between 48-24 hours. No refund for cancellations within 24 hours.', 'Children under 6 years stay free. Children 6-12 years at 50% of adult rate. Kids club available for children 4-12 years.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(7, 7, 'Free cancellation up to 24 hours before arrival. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 50% of adult rate.', 'Pets are not allowed in the hotel.', 'Smoking allowed in designated areas only.'),
(8, 8, 'Free cancellation up to 72 hours before arrival. 50% charge for cancellations between 72-24 hours. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 75% of adult rate. Extra bed available for children.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(9, 9, 'Free cancellation up to 48 hours before arrival. 50% charge for cancellations between 48-24 hours. No refund for cancellations within 24 hours.', 'Children under 6 years stay free. Children 6-12 years at 50% of adult rate. Kids club available for children 4-12 years.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(10, 10, 'Free cancellation up to 72 hours before arrival. 50% charge for cancellations between 72-24 hours. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 75% of adult rate. Kids club available for children 4-12 years.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(11, 11, 'Free cancellation up to 48 hours before arrival. 50% charge for cancellations between 48-24 hours. No refund for cancellations within 24 hours.', 'Children under 6 years stay free. Children 6-12 years at 50% of adult rate.', 'Pets are not allowed in the hotel.', 'Smoking allowed in designated areas only.'),
(12, 12, 'Free cancellation up to 24 hours before arrival. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 50% of adult rate.', 'Pets are not allowed in the hotel.', 'Smoking allowed in designated areas only.'),
(13, 13, 'Free cancellation up to 24 hours before arrival. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 50% of adult rate.', 'Pets are not allowed in the hotel.', 'Smoking allowed in designated areas only.'),
(14, 14, 'Free cancellation up to 72 hours before arrival. 50% charge for cancellations between 72-24 hours. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 75% of adult rate. Extra bed available for children.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(15, 15, 'Free cancellation up to 48 hours before arrival. 50% charge for cancellations between 48-24 hours. No refund for cancellations within 24 hours.', 'Children under 6 years stay free. Children 6-12 years at 50% of adult rate.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(16, 16, 'Free cancellation up to 72 hours before arrival. 50% charge for cancellations between 72-24 hours. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 75% of adult rate. Kids club available for children 4-12 years.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(17, 17, 'Free cancellation up to 48 hours before arrival. 50% charge for cancellations between 48-24 hours. No refund for cancellations within 24 hours.', 'Children under 6 years stay free. Children 6-12 years at 50% of adult rate. Kids club available for children 4-12 years.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(18, 18, 'Free cancellation up to 24 hours before arrival. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 50% of adult rate.', 'Pets are not allowed in the hotel.', 'Smoking allowed in designated areas only.'),
(19, 19, 'Free cancellation up to 72 hours before arrival. 50% charge for cancellations between 72-24 hours. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 75% of adult rate. Extra bed available for children.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(20, 20, 'Free cancellation up to 48 hours before arrival. 50% charge for cancellations between 48-24 hours. No refund for cancellations within 24 hours.', 'Children under 6 years stay free. Children 6-12 years at 50% of adult rate.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(21, 21, 'Free cancellation up to 24 hours before arrival. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 50% of adult rate.', 'Pets are not allowed in the hotel.', 'Smoking allowed in designated areas only.'),
(22, 22, 'Free cancellation up to 48 hours before arrival. 50% charge for cancellations between 48-24 hours. No refund for cancellations within 24 hours.', 'Children under 6 years stay free. Children 6-12 years at 50% of adult rate. Kids club available for children 4-12 years.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(23, 23, 'Free cancellation up to 72 hours before arrival. 50% charge for cancellations between 72-24 hours. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 75% of adult rate. Kids club available for children 4-12 years.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(24, 24, 'Free cancellation up to 24 hours before arrival. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 50% of adult rate.', 'Pets are not allowed in the hotel.', 'Smoking allowed in designated areas only.'),
(25, 25, 'Free cancellation up to 24 hours before arrival. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 50% of adult rate.', 'Pets are not allowed in the hotel.', 'Smoking allowed in designated areas only.'),
(26, 26, 'Free cancellation up to 72 hours before arrival. 50% charge for cancellations between 72-24 hours. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 75% of adult rate. Kids club available for children 4-12 years.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(27, 27, 'Free cancellation up to 48 hours before arrival. 50% charge for cancellations between 48-24 hours. No refund for cancellations within 24 hours.', 'Children under 6 years stay free. Children 6-12 years at 50% of adult rate. Extra bed available for children.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(28, 28, 'Free cancellation up to 24 hours before arrival. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 50% of adult rate.', 'Pets are not allowed in the hotel.', 'Smoking allowed in designated areas only.'),
(29, 29, 'Free cancellation up to 72 hours before arrival. 50% charge for cancellations between 72-24 hours. No refund for cancellations within 24 hours.', 'Children under 12 years stay free. Children 12-18 years at 75% of adult rate. Kids club available for children 4-12 years.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.'),
(30, 30, 'Free cancellation up to 48 hours before arrival. 50% charge for cancellations between 48-24 hours. No refund for cancellations within 24 hours.', 'Children under 6 years stay free. Children 6-12 years at 50% of adult rate. Kids club available for children 4-12 years.', 'Pets are not allowed in the hotel.', 'Non-smoking rooms available. Designated smoking areas in public spaces.');

-- --------------------------------------------------------

--
-- Table structure for table `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `remember_tokens`
--

INSERT INTO `remember_tokens` (`id`, `user_id`, `token`, `expires_at`, `created_at`) VALUES
(2, 3, '247c5be1f40a87d7682bb85ee5c78ebcbc5f7fc8b05b723e26efc140c5777736', '2025-06-16 21:26:29', '2025-05-17 22:26:29');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text NOT NULL,
  `review_date` datetime DEFAULT current_timestamp(),
  `approved` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `hotel_id`, `room_name`, `rating`, `comment`, `review_date`, `approved`) VALUES
(1, 3, 1, 'Deluxe Suite', 7, 'very good', '2025-05-18 11:07:29', 1),
(2, 3, 11, 'Deluxe Suite', 5, 'not bad', '2025-05-18 11:09:08', 1),
(3, 5, 2, 'Deluxe Suite', 6, 'not bad', '2025-05-18 17:50:48', 1),
(4, 3, 4, 'Beachfront Suite', 2, 'mmmmmmmmmmmmmmmmmmmmmmmmm', '2025-05-19 13:04:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `max_guests` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `hotel_id`, `name`, `description`, `image`, `price`, `max_guests`) VALUES
(1, 1, 'Standard Room', 'Comfortable room with all essentials and a beautiful view.', 'images/hotels/ritz-cairo/room1.jpg', '600.00', 2),
(2, 1, 'Deluxe Suite', 'Spacious suite with a separate living area and luxury amenities.', 'images/hotels/ritz-cairo/room2.jpg', '1200.00', 4),
(3, 1, 'Panoramic Room', 'Room with panoramic views and premium comfort.', 'images/hotels/ritz-cairo/room3.jpeg', '1800.00', 4),
(4, 2, 'Standard Room', 'Elegant room with sea view and modern amenities.', 'images/hotels/fourseasons-alex/room1.jpg', '1000.00', 2),
(5, 2, 'Deluxe Suite', 'Spacious suite with private balcony and luxury features.', 'images/hotels/fourseasons-alex/room2.jpg', '2300.00', 3),
(6, 2, 'Panoramic Room', 'Room with panoramic sea views and premium comfort.', 'images/hotels/fourseasons-alex/room3.jpeg', '3000.00', 4),
(7, 3, 'Standard Room', 'Luxury room with balcony overlooking the Red Sea.', 'images/hotels/sharm-royal/room1.jpg', '800.00', 3),
(8, 3, 'Deluxe Suite', 'Luxury suite with private pool and panoramic view.', 'images/hotels/sharm-royal/room2.jpg', '1400.00', 4),
(9, 3, 'Family Room', 'Spacious room for families with extra beds and amenities.', 'images/hotels/sharm-royal/3.jpg', '1800.00', 4),
(10, 4, 'Superior Room', 'Elegant room with balcony and sea view.', 'images/hotels/hurghada-beach/room1.jpg', '500.00', 2),
(11, 4, 'Beachfront Suite', 'Luxury suite directly on the beach.', 'images/hotels/hurghada-beach/room2.jpg', '1000.00', 3),
(12, 5, 'Standard Room', 'Elegant room with a view of the Nile.', 'images/hotels/luxor-palace/room1.jpg', '600.00', 2),
(13, 5, 'Deluxe Suite', 'Luxury suite with balcony and panoramic view.', 'images/hotels/luxor-palace/room2.jpg', '1200.00', 3),
(14, 5, 'Panoramic Room', 'Room with panoramic views and premium comfort.', 'images/hotels/luxor-palace/room3.jpg', '1800.00', 4),
(15, 6, 'Standard Room', 'Elegant room with direct view of the Nile.', 'images/hotels/movenpick-aswan/room1.jpg', '800.00', 2),
(16, 6, 'Deluxe Suite', 'Luxury suite with balcony and 360° view.', 'images/hotels/movenpick-aswan/room2.jpg', '1500.00', 3),
(17, 6, 'Panoramic Room', 'Room with panoramic views and premium comfort.', 'images/hotels/movenpick-aswan/3.jpg', '2000.00', 4),
(18, 7, 'Beach Chalet', 'Cozy chalet with direct sea view and balcony.', 'images/hotels/dahab-lodge/room1.jpg', '200.00', 2),
(19, 7, 'Family Suite', 'Spacious suite with sitting area and large balcony.', 'images/hotels/dahab-lodge/room2.jpg', '400.00', 4),
(20, 8, 'Standard Room', 'Elegant room with panoramic view of the Nile.', 'images/hotels/cairo-marriott/room1.jpg', '900.00', 3),
(21, 8, 'Deluxe Suite', 'Luxury suite with lounge and premium view.', 'images/hotels/cairo-marriott/room2.jpg', '1700.00', 3),
(22, 8, 'Panoramic Room', 'Room with panoramic views and premium comfort.', 'images/hotels/cairo-marriott/3.jpg', '2200.00', 4),
(23, 9, 'Superior Lagoon Room', 'Room overlooking turquoise lagoon and gardens.', 'images/hotels/marsa-alam-blue/room1.jpg', '800.00', 3),
(24, 9, 'Beachfront Suite', 'Luxury suite with private terrace overlooking the sea.', 'images/hotels/marsa-alam-blue/room2.jpg', '1500.00', 4),
(25, 10, 'Standard Room', 'Elegant room with mountain and gulf view.', 'images/hotels/taba-heights/room1.jpg', '400.00', 2),
(26, 10, 'Deluxe Suite', 'Spacious suite with 180° view of the gulf and mountains.', 'images/hotels/taba-heights/room2.jpg', '700.00', 3),
(27, 10, 'Panoramic Room', 'Room with panoramic views and premium comfort.', 'images/hotels/taba-heights/room3.jpg', '1000.00', 4),
(28, 11, 'Standard Room', 'Private chalet surrounded by gardens with lake view.', 'images/hotels/fayoum-desert/room1.jpg', '600.00', 3),
(29, 11, 'Deluxe Suite', 'Luxury suite with terrace overlooking the desert and lake.', 'images/hotels/fayoum-desert/room2.jpg', '1100.00', 4),
(30, 11, 'Panoramic Room', 'Room with panoramic views and premium comfort.', 'images/hotels/fayoum-desert/3.jpg', '1500.00', 4),
(31, 12, 'Standard Room', 'Chalet built from local mud with oasis view.', 'images/hotels/siwa-eco/room1.jpg', '500.00', 2),
(32, 12, 'Deluxe Suite', 'Luxury suite with terrace overlooking the oasis and gardens.', 'images/hotels/siwa-eco/room2.jpg', '800.00', 3),
(33, 13, 'Mud Chalet', 'Chalet built from local mud with oasis view and terrace.', 'images/hotels/siwa-shali/room1.jpg', '450.00', 2),
(34, 13, 'Oasis Suite', 'Luxury suite with terrace overlooking the oasis and gardens.', 'images/hotels/siwa-shali/room2.jpg', '1000.00', 3),
(35, 13, 'Family Suite', 'Spacious suite perfect for families with oasis views.', 'images/hotels/siwa-shali/3.jpg', '1500.00', 4),
(36, 14, 'Standard Room', 'Modern room with city view and essential amenities.', 'images/hotels/cairo-citystars/room1.jpg', '700.00', 2),
(37, 14, 'Deluxe Suite', 'Spacious suite with separate living area and premium amenities.', 'images/hotels/cairo-citystars/room2.jpg', '1400.00', 3),
(38, 14, 'Executive Suite', 'Luxury suite with panoramic city views and premium services.', 'images/hotels/cairo-citystars/room3.jpg', '2000.00', 4),
(39, 15, 'Standard Room', 'Elegant room with sea view and modern amenities.', 'images/hotels/alex-seaview/room1.jpg', '500.00', 2),
(40, 15, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/alex-seaview/room2.jpg', '1000.00', 3),
(41, 15, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/alex-seaview/room3.jpg', '1500.00', 4),
(42, 16, 'Standard Room', 'Comfortable room with Red Sea view.', 'images/hotels/sharm-reef/room1.jpg', '1100.00', 2),
(43, 16, 'Deluxe Suite', 'Luxury suite with private balcony and sea view.', 'images/hotels/sharm-reef/room2.jpg', '2000.00', 3),
(44, 16, 'Family Suite', 'Spacious suite with separate bedrooms and sea view.', 'images/hotels/sharm-reef/room3.jpg', '3000.00', 6),
(45, 17, 'Standard Room', 'Modern room with sea view and essential amenities.', 'images/hotels/hurghada-sunrise/room1.jpg', '750.00', 2),
(46, 17, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/hurghada-sunrise/room2.jpg', '1500.00', 3),
(47, 17, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/hurghada-sunrise/room3.jpg', '2000.00', 4),
(48, 18, 'Standard Room', 'Comfortable room with Nile view.', 'images/hotels/luxor-nile/room1.jpg', '400.00', 2),
(49, 18, 'Deluxe Suite', 'Spacious suite with private balcony and Nile view.', 'images/hotels/luxor-nile/room2.jpg', '800.00', 3),
(50, 18, 'Family Suite', 'Large suite perfect for families with Nile views.', 'images/hotels/luxor-nile/room3.jpg', '1200.00', 4),
(51, 19, 'Standard Room', 'Elegant room with Nile view and premium amenities.', 'images/hotels/aswan-cataract/room1.jpg', '1000.00', 2),
(52, 19, 'Deluxe Suite', 'Luxury suite with private balcony and panoramic Nile view.', 'images/hotels/aswan-cataract/room2.jpg', '2000.00', 3),
(53, 19, 'Royal Suite', 'Exclusive suite with separate living area and premium services.', 'images/hotels/aswan-cataract/room3.jpg', '3000.00', 4),
(54, 20, 'Standard Room', 'Cozy room with sea view and essential amenities.', 'images/hotels/dahab-blue/room1.jpg', '1100.00', 2),
(55, 20, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/dahab-blue/room2.jpg', '2000.00', 3),
(56, 20, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/dahab-blue/room3.jpg', '3000.00', 4),
(57, 21, 'Standard Room', 'Modern room with city view and essential amenities.', 'images/hotels/cairo-nile/room1.jpg', '200.00', 2),
(58, 21, 'Deluxe Suite', 'Spacious suite with separate living area.', 'images/hotels/cairo-nile/room2.jpg', '400.00', 3),
(59, 21, 'Family Suite', 'Large suite perfect for families.', 'images/hotels/cairo-nile/room3.jpg', '600.00', 4),
(60, 22, 'Standard Room', 'Comfortable room with sea view.', 'images/hotels/marsa-alam-coral/room1.jpg', '250.00', 2),
(61, 22, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/marsa-alam-coral/room2.jpg', '500.00', 3),
(62, 22, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/marsa-alam-coral/room3.jpg', '750.00', 4),
(63, 23, 'Standard Room', 'Modern room with sea view and essential amenities.', 'images/hotels/taba-sands/room1.jpg', '600.00', 2),
(64, 23, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/taba-sands/room2.jpg', '1200.00', 3),
(65, 23, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/taba-sands/room3.jpg', '1800.00', 4),
(66, 24, 'Standard Room', 'Cozy room with lake view.', 'images/hotels/fayoum-tunis/room1.jpg', '200.00', 2),
(67, 24, 'Deluxe Suite', 'Spacious suite with private balcony and lake view.', 'images/hotels/fayoum-tunis/room2.jpg', '400.00', 3),
(68, 24, 'Family Suite', 'Large suite perfect for families with lake views.', 'images/hotels/fayoum-tunis/room3.jpg', '600.00', 4),
(69, 25, 'Standard Room', 'Traditional room with oasis view.', 'images/hotels/siwa-shali/room1.jpg', '450.00', 2),
(70, 25, 'Deluxe Suite', 'Spacious suite with private terrace and oasis view.', 'images/hotels/siwa-shali/room2.jpg', '900.00', 3),
(71, 25, 'Family Suite', 'Large suite perfect for families with oasis views.', 'images/hotels/siwa-shali/room3.jpg', '1350.00', 4),
(72, 26, 'Standard Room', 'Modern room with sea view and essential amenities.', 'images/hotels/alamein-marina/room1.jpg', '900.00', 2),
(73, 26, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/alamein-marina/room2.jpg', '1800.00', 3),
(74, 26, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/alamein-marina/room3.jpg', '2700.00', 4),
(75, 27, 'Standard Room', 'Comfortable room with city view.', 'images/hotels/cairo-concorde/room1.jpg', '600.00', 2),
(76, 27, 'Deluxe Suite', 'Spacious suite with separate living area.', 'images/hotels/cairo-concorde/room2.jpg', '1200.00', 3),
(77, 27, 'Executive Suite', 'Luxury suite with panoramic city views.', 'images/hotels/cairo-concorde/room3.jpg', '1800.00', 4),
(78, 28, 'Standard Room', 'Modern room with sea view and essential amenities.', 'images/hotels/alex-royal/room1.jpg', '400.00', 2),
(79, 28, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/alex-royal/room2.jpg', '800.00', 3),
(80, 28, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/alex-royal/room3.jpg', '1200.00', 4),
(81, 29, 'Standard Room', 'Comfortable room with sea view.', 'images/hotels/sharm-reef/room1.jpg', '800.00', 2),
(82, 29, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/sharm-reef/room2.jpg', '1600.00', 3),
(83, 29, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/sharm-reef/room3.jpg', '2400.00', 4),
(84, 30, 'Standard Room', 'Modern room with sea view and essential amenities.', 'images/hotels/hurghada-golden/room1.jpg', '700.00', 2),
(85, 30, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/hurghada-golden/room2.jpg', '1400.00', 3),
(86, 30, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/hurghada-golden/room3.jpg', '2100.00', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_notifications` tinyint(1) DEFAULT 1,
  `sms_notifications` tinyint(1) DEFAULT 1,
  `marketing_emails` tinyint(1) DEFAULT 0,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `name`, `email`, `phone`, `date_of_birth`, `gender`, `address`, `profile_image`, `email_notifications`, `sms_notifications`, `marketing_emails`, `password`, `created_at`, `updated_at`, `is_active`) VALUES
(3, 'mhmd_rmdn_1', 'Mohammed', 'Ramdan', 'name', '01069787819mohammed@gmail.com', '+201069787819', '2003-09-16', 'male', 'banisuef', 'assets/images/profiles/profile_3_1747510399.jpg', 1, 1, 1, '$2y$10$cwHEDCw6qgdU611.VLJQI.arBvWcLDDjwAlyPtJ3JY9MgF/Vascqa', '2025-05-12 13:41:43', '2025-05-17 20:45:10', 1),
(4, NULL, NULL, NULL, 'Mohamed', 'emk422003@gmail.com', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '$2y$10$dns.MWMQ/pTNcPJNyUTbFekxDdrzW2ADbzPEG.MgZZNPhBvHDsati', '2025-05-12 17:43:19', '2025-05-12 17:43:19', 1),
(5, 'MR', 'Egypt', 'Hotels', 'egypthotels', 'egypthotels25@gmail.com', '+201069787819', '2005-07-15', 'male', 'banisuef', 'assets/images/profiles/profile_5_1747561186.jpg', 1, 1, 0, '$2y$10$PrxyJQUo.MKh2VAFCQE9MOh6gO2vJbiE3WKYzjqKdSzlF4FSLHvPy', '2025-05-18 09:38:21', '2025-05-18 14:24:19', 1),
(6, '', '', '', 'ezat', 'ezat123@gmail.com', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '$2y$10$pmbuZ81fROySZ5A37iyINeRPVK3KuFhGwOMb9h9l7ff9GDmcC91LC', '2025-05-19 08:47:08', '2025-05-19 08:47:08', 1),
(13, 'mr1692003', '', '', 'mohammed', 'mohammed01142377524@gmail.com', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '$2y$10$BPMO6Nf42w8./NBP7FDCvOsbd982Jy3bPUCWMeoKfKSfBWo5CudCi', '2025-05-19 20:27:55', '2025-05-19 20:27:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_gallery`
--

CREATE TABLE `user_gallery` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_gallery`
--

INSERT INTO `user_gallery` (`id`, `user_id`, `image`, `caption`, `approved`, `created_at`) VALUES
(4, 3, '68276ffa4fdd5_contact-hero.jpg', 'Hellow World!', 2, '2025-05-16 17:03:54'),
(9, 3, '68277c199113e_Breakfast Buffet.jpeg', '', 1, '2025-05-16 17:55:37'),
(10, 3, '68277c259ab4f_Aswan Landscape.jpeg', '', 1, '2025-05-16 17:55:49'),
(11, 3, '68277c2a84019_Alexandria Beach.jpeg', '', 1, '2025-05-16 17:55:54'),
(12, 3, '68277c30b6f43_Accessible Room.jpeg', '', 1, '2025-05-16 17:56:00'),
(13, 3, '68277c3a05654_Deluxe Room.jpeg', '', 1, '2025-05-16 17:56:10'),
(14, 3, '68277c41d9fd8_Conference Room.jpeg', '', 1, '2025-05-16 17:56:17'),
(15, 3, '68277c47c4073_City Skyline.jpeg', '', 1, '2025-05-16 17:56:23'),
(16, 3, '68277c4e5fffb_Business Center.jpeg', '', 1, '2025-05-16 17:56:30'),
(17, 3, '68277c553769b_Desert View.jpeg', '', 1, '2025-05-16 17:56:37'),
(18, 3, '68277c5b5ca50_Room Service.jpeg', '', 1, '2025-05-16 17:56:43'),
(19, 3, '68277c631b20e_Suite Room.jpeg', '', 1, '2025-05-16 17:56:51'),
(20, 3, '6829e79676c26_˚⭑· ͟͟͞͞➳ ᴬⁱ ᴬʳᵗ_LE_upscale_balanced_x4.jpg', 'anime', 1, '2025-05-18 13:58:46'),
(21, 3, '682b0abe7e552_ITACHI UCHIHA_LE_upscale_balanced_x4.jpg', '', 1, '2025-05-19 10:41:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `idx_bookings_email` (`email`),
  ADD KEY `idx_bookings_cancelled` (`cancelled`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_amenities`
--
ALTER TABLE `hotel_amenities`
  ADD PRIMARY KEY (`hotel_id`,`amenity_id`),
  ADD KEY `amenity_id` (`amenity_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `email` (`email`),
  ADD KEY `token_2` (`token`);

--
-- Indexes for table `policies`
--
ALTER TABLE `policies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_gallery`
--
ALTER TABLE `user_gallery`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `policies`
--
ALTER TABLE `policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_gallery`
--
ALTER TABLE `user_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `hotel_amenities`
--
ALTER TABLE `hotel_amenities`
  ADD CONSTRAINT `hotel_amenities_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotel_amenities_ibfk_2` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `policies`
--
ALTER TABLE `policies`
  ADD CONSTRAINT `policies_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


UPDATE hotels SET latitude = 25.6872, longitude = 32.6396 WHERE id = 18;

UPDATE hotels SET
  cancellation_policy = 'Free cancellation up to 48 hours before arrival',
  children_policy = 'Children up to 6 years old stay free',
  pets_policy = 'Pets are not allowed',
  smoking_policy = 'Non-smoking rooms available'
WHERE id = 18;

UPDATE amenities SET description = 'Enjoy our luxurious spa with a variety of treatments.', icon = 'fa-spa' WHERE name = 'Spa';
UPDATE amenities SET description = 'Our outdoor swimming pool is open year-round.', icon = 'fa-swimming-pool' WHERE name = 'Swimming Pool';
UPDATE amenities SET description = 'Dine in our world-class restaurant.', icon = 'fa-utensils' WHERE name = 'Restaurant';
UPDATE amenities SET description = 'Stay fit in our modern fitness center.', icon = 'fa-dumbbell' WHERE name = 'Fitness Center';
UPDATE amenities SET description = '24/7 room service for your convenience.', icon = 'fa-bell-concierge' WHERE name = 'Room Service';
UPDATE amenities SET description = 'Relax at our bar and lounge.', icon = 'fa-glass-martini-alt' WHERE name = 'Bar/Lounge';
UPDATE amenities SET description = 'Beautiful garden for relaxation.', icon = 'fa-tree' WHERE name = 'Garden';
UPDATE amenities SET description = 'Professional diving center for guests.', icon = 'fa-water' WHERE name = 'Diving Center';

-- Add latitude and longitude columns if they don't exist
ALTER TABLE hotels
ADD COLUMN IF NOT EXISTS latitude DECIMAL(10, 8) DEFAULT 26.8206,
ADD COLUMN IF NOT EXISTS longitude DECIMAL(11, 8) DEFAULT 30.8025;

-- Update existing hotels with their coordinates
UPDATE hotels SET 
    latitude = 26.8206, longitude = 30.8025 
WHERE latitude IS NULL OR longitude IS NULL;

-- Add coordinates for specific hotels
UPDATE hotels SET 
    latitude = 27.9158, longitude = 34.3300 
WHERE name LIKE '%Sharm El Sheikh%';

UPDATE hotels SET 
    latitude = 27.2579, longitude = 33.8116 
WHERE name LIKE '%Hurghada%';

UPDATE hotels SET 
    latitude = 25.6872, longitude = 32.6396 
WHERE name LIKE '%Luxor%';

UPDATE hotels SET 
    latitude = 24.0889, longitude = 32.8998 
WHERE name LIKE '%Aswan%';

UPDATE hotels SET 
    latitude = 28.5097, longitude = 34.5164 
WHERE name LIKE '%Dahab%';