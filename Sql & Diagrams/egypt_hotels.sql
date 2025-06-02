-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2025 at 11:42 AM
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
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `last_login`) VALUES
(13, 'mhmd_rmdn_1', '$2y$10$oey.luIZPUkRYBAb8/B6Kue9ANkjmVBgL/03ekguv99EjAtYXRC5W', '2025-05-25 12:07:22'),
(15, 'emk4', '$2y$10$4yqFJenJncqPNB573wYsxeEDuUZll7y/QNznvvk/SKytb1AwGH6Yy', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `name`, `icon`, `details`, `description`) VALUES
(1, 'Eco Design', 'fa-leaf', NULL, NULL),
(2, 'Swimming Pool', 'fa-swimming-pool', NULL, 'Our outdoor swimming pool is open year-round.'),
(3, 'Free Wi-Fi', 'fa-wifi', NULL, NULL),
(4, 'Restaurant', 'fa-utensils', NULL, 'Dine in our world-class restaurant.'),
(5, 'Spa', 'fa-spa', NULL, 'Enjoy our luxurious spa with a variety of treatments.'),
(6, 'Fitness Center', 'fa-dumbbell', NULL, 'Stay fit in our modern fitness center.'),
(7, 'Private Beach', 'fa-umbrella-beach', NULL, NULL),
(8, 'Kids Club', 'fa-child', NULL, NULL),
(9, 'Parking', 'fa-car', NULL, NULL),
(10, 'Room Service', 'fa-bell-concierge', NULL, '24/7 room service for your convenience.'),
(11, 'Air Conditioning', 'fa-snowflake', NULL, NULL),
(12, 'Business Center', 'fa-briefcase', NULL, NULL),
(13, 'Conference Facilities', 'fa-users', NULL, NULL),
(14, 'Laundry Service', 'fa-tshirt', NULL, NULL),
(15, 'Bar/Lounge', 'fa-glass-martini-alt', NULL, 'Relax at our bar and lounge.'),
(16, 'Garden', 'fa-tree', NULL, 'Beautiful garden for relaxation.'),
(17, 'Tennis Court', 'fa-table-tennis', NULL, NULL),
(18, 'Diving Center', 'fa-water', NULL, 'Professional diving center for guests.'),
(19, 'Water Sports', 'fa-swimmer', NULL, NULL),
(20, 'Shuttle Service', 'fa-shuttle-van', NULL, NULL),
(21, 'Currency Exchange', 'fa-exchange-alt', NULL, NULL),
(22, 'Gift Shop', 'fa-gift', NULL, NULL),
(23, 'Medical Service', 'fa-medkit', NULL, NULL),
(24, 'Security', 'fa-shield-alt', NULL, NULL),
(25, 'Tour Desk', 'fa-map-marked-alt', NULL, NULL),
(26, 'Private Beach', 'fas fa-umbrella-beach', NULL, 'Exclusive beach access for hotel guests with pristine shores and crystal-clear waters.'),
(27, 'Kids Club', 'fas fa-child', NULL, 'Supervised activities and entertainment for children of all ages.'),
(28, 'Bar/Lounge', 'fas fa-glass-martini-alt', NULL, 'Elegant bar and lounge area serving premium drinks and cocktails.'),
(29, 'Swimming Pool', 'fas fa-swimming-pool', NULL, 'Outdoor swimming pool with sun loungers and pool service.'),
(30, 'Spa', 'fas fa-spa', NULL, 'Full-service spa offering massages, treatments, and wellness services.'),
(31, 'Restaurant', 'fas fa-utensils', NULL, 'Fine dining restaurant serving international and local cuisine.'),
(32, 'Gym', 'fas fa-dumbbell', NULL, 'Modern fitness center with cardio and weight training equipment.'),
(33, 'WiFi', 'fas fa-wifi', NULL, 'Complimentary high-speed WiFi throughout the property.'),
(34, 'Room Service', 'fas fa-concierge-bell', NULL, '24-hour room service with extensive menu options.'),
(35, 'Beach Access', 'fas fa-umbrella-beach', NULL, 'Direct access to the beach with complimentary beach towels.'),
(36, 'Air Conditioning', 'fas fa-snowflake', NULL, 'Climate-controlled rooms for your comfort.'),
(37, 'Conference Room', 'fas fa-chalkboard-teacher', NULL, 'Modern meeting facilities for business events.'),
(38, 'Garden', 'fas fa-leaf', NULL, 'Beautiful landscaped gardens for relaxation.'),
(39, 'Tennis Court', 'fas fa-table-tennis', NULL, 'Professional tennis courts with equipment rental.'),
(40, 'Shuttle Service', 'fas fa-shuttle-van', NULL, 'Complimentary shuttle service to nearby attractions.'),
(41, 'Kids Pool', 'fas fa-swimming-pool', NULL, 'Dedicated swimming pool for children with safety features.'),
(42, 'Pool Bar', 'fas fa-cocktail', NULL, 'Poolside bar serving refreshing drinks and snacks.'),
(43, 'Business Center', 'fas fa-briefcase', NULL, 'Fully equipped business center with printing services.'),
(44, 'Eco Design', 'fas fa-seedling', NULL, 'Environmentally friendly design and practices.'),
(45, '24/7 Front Desk', 'fas fa-clock', NULL, 'Round-the-clock front desk service for guest assistance.'),
(46, 'Airport Transfer', 'fas fa-plane-departure', NULL, 'Convenient airport transfer service available.'),
(47, 'Medical Service', 'fas fa-first-aid', NULL, 'On-call medical assistance for emergencies.'),
(48, 'Housekeeping', 'fas fa-broom', NULL, 'Daily housekeeping service to maintain cleanliness.'),
(49, 'Meeting Rooms', 'fas fa-handshake', NULL, 'Various sized meeting rooms for business gatherings.'),
(50, 'Wheelchair Access', 'fas fa-wheelchair', NULL, 'Accessible facilities for guests with mobility needs.');

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
  `user_id` int(11) NOT NULL,
  `payment_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `hotel_id`, `room_id`, `first_name`, `last_name`, `email`, `phone`, `check_in`, `check_out`, `guests`, `rooms`, `special_requests`, `created_at`, `cancelled`, `cancelled_at`, `total_price`, `hotel_name`, `room_name`, `status`, `user_id`, `payment_status`) VALUES
(1, 30, 85, 'Mohammed', 'Ramadan', '01069787819m@gmail.com', '+201069787819', '2025-05-30', '2025-05-31', 1, 1, 'hi', '2025-05-28 23:29:45', 0, NULL, '1400.00', 'Golden Beach Resort', 'Deluxe Suite', 'confirmed', 3, NULL),
(2, 10, 26, 'Mohammed', 'Ramadan', '01069787819m@gmail.com', '+201069787819', '2025-05-31', '2025-06-01', 1, 1, 'hi i nead more information', '2025-05-29 21:29:29', 0, NULL, '700.00', 'Steigenberger Hotel & Nelson Village', 'Deluxe Suite', 'cancelled', 3, NULL),
(3, 24, 66, 'Mohammed', 'Ramadan', '01069787819m@gmail.com', '+201069787819', '2025-05-31', '2025-06-01', 1, 1, 'I hope this Shali turns out to be good', '2025-05-29 21:32:58', 0, NULL, '200.00', 'Tache Boutique Hotel Fayoum', 'Standard Room', 'cancelled', 3, NULL),
(4, 11, 29, 'Mohammed', 'Ramadan', '01069787819m@gmail.com', '+201069787819', '2025-06-01', '2025-06-02', 1, 1, 'hi im mohamed', '2025-05-30 23:12:27', 0, NULL, '1100.00', 'Helnan Auberge Fayoum', 'Deluxe Suite', 'pending', 3, NULL),
(5, 4, 10, 'Mohammed', 'Ramadan', '01069787819m@gmail.com', '+201069787819', '2025-06-01', '2025-06-03', 3, 2, 'any special', '2025-05-31 01:37:29', 0, NULL, '1000.00', 'Serry Beach Resort', 'Superior Room', 'pending', 3, NULL),
(6, 1, 1, 'Mohamed R', 'Ibrahim', '01069787819mohammed@gmail.com', '01069787819', '2025-06-18', '2025-06-19', 1, 1, '', '2025-06-02 09:10:14', 0, NULL, '600.00', 'The Nile Ritz-Carlton', 'Standard Room', 'pending', 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_notes`
--

CREATE TABLE `booking_notes` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `note` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','in_progress','resolved') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 'mohamed ramdan', '01069787819m@gmail.com', 'Hi Im Mohamed', 'pending', '2025-05-29 21:49:27', '2025-05-29 21:50:08');

-- --------------------------------------------------------

--
-- Table structure for table `created_at`
--

CREATE TABLE `created_at` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `name`, `icon`, `category`) VALUES
(1, 'King Bed', 'fa-bed', 'Bed Type'),
(2, 'Queen Bed', 'fa-bed', 'Bed Type'),
(3, 'Twin Beds', 'fa-bed', 'Bed Type'),
(4, 'Air Conditioning', 'fa-snowflake', 'Climate'),
(5, 'Heating', 'fa-temperature-high', 'Climate'),
(6, 'Free Wi-Fi', 'fa-wifi', 'Technology'),
(7, 'Smart TV', 'fa-tv', 'Technology'),
(8, 'Mini Bar', 'fa-glass-martini', 'Amenities'),
(9, 'Coffee Maker', 'fa-coffee', 'Amenities'),
(10, 'Safe', 'fa-lock', 'Amenities'),
(11, 'Desk', 'fa-desk', 'Furniture'),
(12, 'Balcony', 'fa-door-open', 'Layout'),
(13, 'Sea View', 'fa-water', 'View'),
(14, 'City View', 'fa-city', 'View'),
(15, 'Garden View', 'fa-tree', 'View'),
(16, 'Room Service', 'fa-concierge-bell', 'Service'),
(17, 'Bathtub', 'fa-bath', 'Bathroom'),
(18, 'Shower', 'fa-shower', 'Bathroom'),
(19, 'Hair Dryer', 'fa-wind', 'Bathroom'),
(20, 'Toiletries', 'fa-pump-soap', 'Bathroom');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `folder` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `cancellation_policy` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `children_policy` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pets_policy` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smoking_policy` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amenities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `category_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `name`, `location`, `description`, `image`, `rating`, `reviews`, `price`, `created_at`, `folder`, `latitude`, `longitude`, `cancellation_policy`, `children_policy`, `pets_policy`, `smoking_policy`, `amenities`, `is_active`, `category_id`, `updated_at`) VALUES
(1, 'The Nile Ritz-Carlton', 'Cairo, Egypt', 'The Nile Ritz-Carlton is a landmark in luxury hospitality, located in the heart of Egypt\'s capital with stunning views of the Nile River. The hotel blends modern luxury with authentic Egyptian hospitality.', '/assets/images/hotels/ritz.jpeg', '8.7', 1363, '600.00', '2025-05-19 20:45:37', 'ritz-cairo', '30.04440000', '31.23570000', 'Free cancellation up to 24 hours before arrival', 'Children under 12 stay free', 'Pets allowed with prior approval', 'Non-smoking rooms only', NULL, 1, NULL, NULL),
(2, 'Four Seasons At San Stefano', 'Alexandria, Egypt', 'Four Seasons Hotel Alexandria At San Stefano is located directly on the Mediterranean coast, offering an exceptional stay with panoramic sea views. The hotel features elegant design and modern facilities.', '/assets/images/hotels/Four Seasons Alexandria at San Stefano.jpeg', '9.2', 1477, '1000.00', '2025-05-19 20:45:37', 'fourseasons-alex', '31.21560000', '29.95530000', 'No cancellation after booking', 'Children under 6 stay free', 'No pets allowed', 'Smoking allowed in designated areas', NULL, 1, NULL, NULL),
(3, 'Royal Savoy Sharm El Sheikh', 'Sharm El Sheikh, South Sinai', 'A luxurious resort located directly on the Red Sea beach in Sharm El Sheikh, offering stunning views of coral reefs and the sea. The resort features modern design and comprehensive leisure and relaxation services.', '/assets/images/hotels/sharm-royal.jpg', '9.3', 1149, '800.00', '2025-05-19 20:45:37', 'sharm-royal', '27.91580000', '34.33000000', 'Free cancellation up to 48 hours before arrival', 'Children under 10 stay free', 'Pets not allowed', 'Non-smoking rooms available', NULL, 1, NULL, NULL),
(4, 'Serry Beach Resort', 'Hurghada, Red Sea', 'A luxurious beachfront resort in Hurghada offering a distinguished stay with stunning views of the Red Sea. The resort features a private beach, tropical gardens, and diverse entertainment facilities.', '/assets/images/hotels/hurghada-beach.jpg', '8.8', 308, '500.00', '2025-05-19 20:45:37', 'hurghada-beach', '27.25790000', '33.81160000', 'Partial refund for cancellation within 24 hours', 'Children under 8 stay free', 'Small pets allowed', 'Smoking allowed on balconies only', NULL, 1, NULL, NULL),
(5, 'Sofitel Winter Palace Luxor', 'Luxor, Egypt', 'A historic luxury hotel located on the banks of the Nile in Luxor, combining ancient Egyptian authenticity with modern elegance. It offers charming views of the Luxor Temple and the Nile River.', '/assets/images/hotels/luxor-palace.jpg', '8.9', 781, '600.00', '2025-05-19 20:45:37', 'luxor-palace', '25.68720000', '32.63960000', 'No refund for cancellation', 'Children under 5 stay free', 'Pets allowed with extra charge', 'No smoking allowed', NULL, 1, NULL, NULL),
(6, 'Mövenpick Resort Aswan', 'Aswan, Egypt', 'A luxury hotel located on Elephantine Island in the heart of the Nile River, offering panoramic views of the river and stunning natural scenery. The design blends authentic Nubian style with modern elegance.', '/assets/images/hotels/aswan-movenpick.jpg', '8.7', 677, '800.00', '2025-05-19 20:45:37', 'movenpick-aswan', '24.08890000', '32.89980000', 'Free cancellation up to 72 hours before arrival', 'Children under 7 stay free', 'No pets allowed', 'Smoking allowed in outdoor areas', NULL, 1, NULL, NULL),
(7, 'Dahab Lodge', 'Dahab, South Sinai', 'A charming resort that combines simplicity and comfort, located directly on the Gulf of Aqaba in Dahab. It features authentic Bedouin design and a calm atmosphere ideal for diving lovers and relaxation.', '/assets/images/hotels/dahab-lodge.jpg', '7.0', 80, '200.00', '2025-05-19 20:45:37', 'dahab-lodge', '28.50970000', '34.51640000', 'Full refund for cancellation within 12 hours', 'Children under 9 stay free', 'Pets allowed with deposit', 'Non-smoking rooms only', NULL, 1, NULL, NULL),
(8, 'Marriott Mena House', 'Zamalek, Cairo', 'A luxury hotel located in the heart of upscale Zamalek, offering breathtaking views of the Nile River and Cairo skyline. It combines global luxury with authentic Egyptian hospitality.', '/assets/images/hotels/cairo-marriott.jpg', '8.8', 2050, '900.00', '2025-05-19 20:45:37', 'cairo-marriott', '30.05710000', '31.22860000', 'No cancellation allowed', 'Children under 4 stay free', 'No pets allowed', 'Smoking allowed in designated areas', NULL, 1, NULL, NULL),
(9, 'Casa Blue Resort', 'Marsa Alam, Red Sea', 'A luxury beachfront resort located on a private beach in Marsa Alam, surrounded by stunning coral reefs and turquoise waters. It offers an exceptional diving experience and a relaxing stay.', '/assets/images/hotels/marsa-alam-blue.jpg', '10.0', 15, '800.00', '2025-05-19 20:45:37', 'marsa-alam-blue', '25.06760000', '34.89900000', 'Free cancellation up to 36 hours before arrival', 'Children under 11 stay free', 'Pets allowed with prior approval', 'No smoking allowed', NULL, 1, NULL, NULL),
(10, 'Steigenberger Hotel & Nelson Village', 'Taba, South Sinai', 'A unique mountain resort overlooking the Gulf of Aqaba and four countries at once. It features a strategic location and a design harmonizing with the surrounding mountains. Offers a distinctive stay with panoramic views of the Red Sea and nearby mountains.', '/assets/images/hotels/taba-heights.jpg', '9.8', 9895, '400.00', '2025-05-19 20:45:37', 'taba-heights', '29.49270000', '34.89670000', 'Partial refund for cancellation within 48 hours', 'Children under 13 stay free', 'No pets allowed', 'Smoking allowed on balconies only', NULL, 1, NULL, NULL),
(11, 'Helnan Auberge Fayoum', 'Fayoum, Egypt', 'A unique desert resort located on the edge of the Fayoum desert, offering a special stay combining desert charm and modern comfort. Inspired by local architecture, it offers stunning views of Lake Qarun and activities like horseback riding and desert safari.', '/assets/images/hotels/fayoum-desert.jpg', '7.8', 704, '600.00', '2025-05-19 20:45:37', 'fayoum-desert', '29.31020000', '30.84180000', 'Free cancellation up to 24 hours before arrival', 'Children under 6 stay free', 'Pets allowed with extra charge', 'Non-smoking rooms only', NULL, 1, NULL, NULL),
(12, 'Siwa Tarriott eco lodge hotel', 'Siwa, Egypt', 'A unique eco-resort located in the enchanting Siwa Oasis, combining local heritage with environmental sustainability. It features a design inspired by traditional Siwan architecture and the use of local materials. It offers a unique accommodation experience in the heart of the desert while preserving the environment.', '/assets/images/hotels/siwa-eco.jpg', '7.0', 60, '500.00', '2025-05-19 20:45:37', 'siwa-eco', '29.20410000', '25.51960000', 'No refund for cancellation', 'Children under 8 stay free', 'No pets allowed', 'Smoking allowed in outdoor areas', NULL, 1, NULL, NULL),
(13, 'Chalet in Marassi Marina, Canal view with luxurious furniture', 'New Alamein, Matrouh', 'A luxurious coastal resort overlooking the Mediterranean Sea in New Alamein city. It features modern design and a prime location on a golden sandy beach. It offers a unique stay experience with stunning sea views.', '/assets/images/hotels/alamein-beach.jpg', '0.0', 0, '500.00', '2025-05-19 20:45:37', 'alamein-beach', '30.85750000', '28.95500000', 'Free cancellation up to 48 hours before arrival', 'Children under 10 stay free', 'Small pets allowed', 'No smoking allowed', NULL, 1, NULL, NULL),
(14, 'Intercontinental Cairo Citystars', 'Downtown, Cairo', 'A modern hotel located in the heart of Cairo, featuring contemporary design and a strategic location near major tourist and commercial landmarks. It offers stunning city views and modern services.', '/assets/images/hotels/cairo-citystars.jpg', '8.8', 2261, '700.00', '2025-05-19 20:45:37', 'cairo-citystars', '30.06170000', '31.33000000', 'No cancellation after booking', 'Children under 7 stay free', 'No pets allowed', 'Smoking allowed in designated areas', NULL, 1, NULL, NULL),
(15, 'Rhactus House San Stefano', 'Alexandria', 'A luxurious hotel overlooking the Mediterranean Sea in the heart of Alexandria. It features modern design and panoramic sea views. It offers a unique stay experience with premium services.', '/assets/images/hotels/alex-seaview.jpg', '9.4', 157, '500.00', '2025-05-19 20:45:37', 'alex-seaview', '31.21560000', '29.95530000', 'Partial refund for cancellation within 24 hours', 'Children under 12 stay free', 'Pets allowed with prior approval', 'Non-smoking rooms only', NULL, 1, NULL, NULL),
(16, 'Domina Coral Bay Resort', 'Sharm El Sheikh', 'A luxurious resort overlooking Naama Bay in Sharm El Sheikh, featuring a prime beachfront location and stunning views of the Red Sea. It offers a unique stay experience with comprehensive entertainment and relaxation facilities.', '/assets/images/hotels/sharm-reef.jpg', '9.5', 2, '1100.00', '2025-05-19 20:45:37', 'sharm-coral', '27.91580000', '34.32990000', 'Free cancellation up to 72 hours before arrival', 'Children under 9 stay free', 'No pets allowed', 'Smoking allowed in outdoor areas', NULL, 1, NULL, NULL),
(17, 'Sunrise Aqua Joy Resort', 'Hurghada, Red Sea', 'A luxurious beachfront resort overlooking the Red Sea in Hurghada, featuring modern design and a prime location on a golden sandy beach. It offers a comfortable stay experience with comprehensive entertainment and relaxation facilities.', '/assets/images/hotels/hurghada-sunrise.jpg', '9.1', 5206, '750.00', '2025-05-19 20:45:37', 'hurghada-sunrise', '27.25780000', '33.81160000', 'Full refund for cancellation within 12 hours', 'Children under 8 stay free', 'Pets allowed with deposit', 'Non-smoking rooms only', NULL, 1, NULL, NULL),
(18, 'Luxor Nile View Flats', 'Nile Corniche, Luxor', 'A luxurious hotel overlooking the Nile River in the heart of Luxor, featuring design inspired by ancient Egyptian architecture and a prime location near archaeological sites. It offers a unique stay experience with stunning Nile views.', '/assets/images/hotels/luxor-nile.jpg', '8.1', 53, '400.00', '2025-05-19 20:45:37', 'luxor-nile', '25.68720000', '32.63960000', 'Free cancellation up to 48 hours before arrival', 'Children up to 6 years old stay free', 'Pets are not allowed', 'Non-smoking rooms available', NULL, 1, NULL, NULL),
(19, 'Sofitel Legend Old Cataract', 'Aswan Island, Aswan', 'A unique resort located on Aswan Island in the heart of the Nile River, offering panoramic views of the river and stunning natural scenery. The design blends authentic Nubian style with modern elegance.', '/assets/images/hotels/aswan-cataract.jpg', '9.3', 931, '1000.00', '2025-05-19 20:45:37', 'aswan-cataract', '24.08890000', '32.89980000', 'Free cancellation up to 36 hours before arrival', 'Children under 7 stay free', 'No pets allowed', 'Smoking allowed in outdoor areas', NULL, 1, NULL, NULL),
(20, 'Beit Theresa', 'Dahab, South Sinai', 'A charming resort located directly on the Gulf of Aqaba in Dahab, offering a unique blend of Bedouin charm and modern comfort. It features authentic design and a calm atmosphere ideal for diving lovers and relaxation.', '/assets/images/hotels/dahab-blue.jpg', '9.7', 223, '1100.00', '2025-05-19 20:45:37', 'dahab-blue', '28.49320000', '34.50410000', 'Partial refund for cancellation within 48 hours', 'Children under 9 stay free', 'Pets allowed with deposit', 'Non-smoking rooms only', NULL, 1, NULL, NULL),
(21, 'New Cairo Nyoum Porto New Cairo, Elite Apartments', 'Cairo, Egypt', 'A luxurious hotel located on the banks of the Nile in Cairo, combining modern elegance with ancient Egyptian charm. It offers panoramic views of the Nile River and a prime location near major tourist attractions.', '/assets/images/hotels/cairo-nile.jpg', '7.1', 7, '200.00', '2025-05-19 20:45:37', 'cairo-nile', '30.05710000', '31.22860000', 'No cancellation allowed', 'Children under 4 stay free', 'No pets allowed', 'Smoking allowed in designated areas', NULL, 1, NULL, NULL),
(22, 'Marsa CoralCoral Hills Resort & SPA', 'Marsa Matrouh, Egypt', 'A luxurious resort located on the coast of Marsa Matrouh, offering stunning views of the Red Sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.', '/assets/images/hotels/marsa-alam-coral.jpg', '7.4', 149, '250.00', '2025-05-19 20:45:37', 'marsa-alam-coral', '25.06760000', '34.89900000', 'Free cancellation up to 24 hours before arrival', 'Children under 11 stay free', 'Pets allowed with prior approval', 'No smoking allowed', NULL, 1, NULL, NULL),
(23, 'Taba Sands Hotel & Casino', 'Taba, Egypt', 'A luxurious resort located on the shores of the Red Sea in Taba, offering stunning views of the sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.', '/assets/images/hotels/taba-sands.jpg', '8.7', 351, '600.00', '2025-05-19 20:45:37', 'taba-sands', '29.49270000', '34.89670000', 'Partial refund for cancellation within 24 hours', 'Children under 13 stay free', 'No pets allowed', 'Smoking allowed on balconies only', NULL, 1, NULL, NULL),
(24, 'Tache Boutique Hotel Fayoum', 'Fayoum, Egypt', 'A unique resort located on the shores of Lake Qarun in Fayoum, offering stunning views of the lake and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.', '/assets/images/hotels/fayoum-tunis.jpg', '7.3', 51, '200.00', '2025-05-19 20:45:37', 'fayoum-tunis', '29.31020000', '30.84180000', 'Free cancellation up to 48 hours before arrival', 'Children under 6 stay free', 'Pets allowed with extra charge', 'Non-smoking rooms only', NULL, 1, NULL, NULL),
(25, 'Siwa Shali Resort', 'Siwa Oasis, Egypt', 'A unique eco-resort located in the enchanting Siwa Oasis, combining local heritage with environmental sustainability. It features a design inspired by traditional Siwan architecture and the use of local materials.', '/assets/images/hotels/siwa-shali.jpg', '8.3', 152, '450.00', '2025-05-19 20:45:37', 'siwa-shali', '29.20410000', '25.51960000', 'No refund for cancellation', 'Children under 8 stay free', 'No pets allowed', 'Smoking allowed in outdoor areas', NULL, 1, NULL, NULL),
(26, 'Palma Bay Rotana Resort', 'Alamein, Matrouh', 'A luxurious resort located on the shores of the Mediterranean Sea in Alamein, offering stunning views of the sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.', '/assets/images/hotels/alamein-marina.jpg', '9.2', 164, '900.00', '2025-05-19 20:45:37', 'alamein-marina', '30.85750000', '28.95500000', 'Free cancellation up to 48 hours before arrival', 'Children up to 6 years old stay free', 'Pets are not allowed', 'Non-smoking rooms available', NULL, 1, NULL, NULL),
(28, 'Hotel appartment alexandria sea view', 'Alexandria, Egypt', 'A luxurious hotel located on the shores of the Mediterranean Sea in Alexandria, offering stunning views of the sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.', '/assets/images/hotels/alex-royal.jpg', '8.2', 65, '400.00', '2025-05-19 20:45:37', 'alex-royal', '31.21560000', '29.95530000', 'Partial refund for cancellation within 24 hours', 'Children under 12 stay free', 'Pets allowed with prior approval', 'Non-smoking rooms only', NULL, 1, NULL, NULL),
(29, 'Reef Oasis Beach Aqua Park Resort', 'Sharm El Sheikh, South Sinai', 'A luxurious desert resort located on the shores of the Gulf of Aqaba in Sharm El Sheikh, offering stunning views of the desert and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.', '/assets/images/hotels/sharm-reef.jpg', '9.1', 3254, '800.00', '2025-05-19 20:45:37', 'sharm-reef', '27.91580000', '34.32990000', 'Free cancellation up to 72 hours before arrival', 'Children under 9 stay free', 'No pets allowed', 'Smoking allowed in outdoor areas', NULL, 1, NULL, NULL),
(30, 'Golden Beach Resort', 'Hurghada, Egypt', 'A luxurious resort located on the shores of the Red Sea in Hurghada, offering stunning views of the sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.', '/assets/images/hotels/hurghada-golden.jpg', '8.5', 2951, '700.00', '2025-05-19 20:45:37', 'hurghada-golden', '27.25780000', '33.81160000', 'Full refund for cancellation within 12 hours', 'Children under 8 stay free', 'Pets allowed with deposit', 'Non-smoking rooms only', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_amenities`
--

CREATE TABLE `hotel_amenities` (
  `hotel_id` int(11) NOT NULL,
  `amenity_id` int(11) NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hours` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_amenities`
--

INSERT INTO `hotel_amenities` (`hotel_id`, `amenity_id`, `details`, `features`, `hours`, `price`) VALUES
(1, 2, 'Enjoy a temperature-controlled luxury pool with a stunning Nile view and poolside service.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(1, 3, 'Unlimited high-speed Wi-Fi available in all rooms and public areas for seamless connectivity.', '[\"Available in all rooms\", \"No password required\", \"24/7 support\"]', '24/7', 'Free'),
(1, 4, 'A variety of international cuisines served in elegant settings by top chefs.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(1, 5, 'Relax and rejuvenate with world-class spa treatments and wellness therapies.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(1, 6, 'State-of-the-art gym with personal trainers and modern fitness equipment.', '[\"Cardio & weights\", \"Personal trainers\", \"Yoga classes\"]', '24/7', 'Free for hotel guests'),
(1, 9, 'Convenient valet parking available 24/7 for all guests and visitors.', '[\"Covered parking\", \"Security monitored\", \"Accessible parking\"]', '24/7', 'Free for guests, visitors EGP 50/day'),
(1, 10, 'Order food, drinks, and amenities to your room at any hour of the day.', '[\"Full menu\", \"Express delivery\", \"Special requests\"]', '24/7', 'Service charge may apply'),
(1, 12, 'Fully equipped business center with meeting rooms, printers, and high-speed internet.', '[\"Meeting rooms\", \"Printing & scanning\", \"Secretarial services\"]', '8:00 AM - 8:00 PM', 'Some services extra'),
(2, 2, 'Stunning infinity pool overlooking the Mediterranean Sea.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(2, 3, 'Complimentary Wi-Fi throughout the hotel premises.', '[\"Available in all rooms\", \"No password required\", \"24/7 support\"]', '24/7', 'Free'),
(2, 4, 'Multiple restaurants offering a range of local and international dishes.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(2, 5, 'Indulge in luxurious spa treatments and wellness programs.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(2, 6, 'Modern fitness center with the latest equipment and personal trainers.', '[\"Cardio & weights\", \"Personal trainers\", \"Yoga classes\"]', '24/7', 'Free for hotel guests'),
(2, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(2, 10, 'Prompt and courteous room service available around the clock.', '[\"Full menu\", \"Express delivery\", \"Special requests\"]', '24/7', 'Service charge may apply'),
(2, 18, 'Organized sea cruises and boat trips for guests.', '[\"PADI courses\", \"Equipment rental\", \"Guided dives\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(3, 2, 'Enjoy multiple pools for swimming, lounging, and water sports.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(3, 4, 'A variety of restaurants serving local and international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(3, 5, 'Relax and rejuvenate with spa treatments and massages.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(3, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(3, 8, 'Kid-friendly activities and a supervised play area.', '[\"Supervised play area\", \"Daily activities\", \"Kids pool\"]', '10:00 AM - 6:00 PM', 'Free for hotel guests'),
(3, 15, 'Relax with a refreshing drink at the beach bar overlooking the Red Sea.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(3, 17, 'Outdoor sports courts for beach volleyball, tennis, and more.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(3, 19, 'Professional diving center offering PADI certification courses and underwater adventures.', '[\"Snorkeling\", \"Diving\", \"Kayaking\", \"Equipment rental\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(4, 2, 'Enjoy multiple pools for swimming, lounging, and water sports.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(4, 4, 'Five restaurants offering a variety of local and international dishes.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(4, 5, 'Relax and rejuvenate with spa treatments and wellness therapies.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(4, 6, 'Modern gym with state-of-the-art equipment and personal trainers.', '[\"Cardio & weights\", \"Personal trainers\", \"Yoga classes\"]', '24/7', 'Free for hotel guests'),
(4, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(4, 8, 'Kid-friendly activities and a supervised play area.', '[\"Supervised play area\", \"Daily activities\", \"Kids pool\"]', '10:00 AM - 6:00 PM', 'Free for hotel guests'),
(4, 15, 'Live music and entertainment in the evenings.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(4, 19, 'Water sports activities such as snorkeling, diving, and kayaking.', '[\"Snorkeling\", \"Diving\", \"Kayaking\", \"Equipment rental\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(5, 2, 'Stunning outdoor pool overlooking the Nile with poolside service.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(5, 3, 'Unlimited Wi-Fi available throughout the hotel premises.', '[\"Available in all rooms\", \"No password required\", \"24/7 support\"]', '24/7', 'Free'),
(5, 4, 'Fine dining experiences with a variety of international cuisines.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(5, 5, 'Relax and rejuvenate with spa treatments and massages.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(5, 9, 'Convenient car rental service for exploring Luxor.', '[\"Covered parking\", \"Security monitored\", \"Accessible parking\"]', '24/7', 'Free for guests, visitors EGP 50/day'),
(5, 10, 'Room service available 24/7 for all your needs.', '[\"Full menu\", \"Express delivery\", \"Special requests\"]', '24/7', 'Service charge may apply'),
(5, 16, 'Guided tours of Luxor\'s historical sites and landmarks.', '[\"Local plants\", \"Walking paths\", \"Relaxation areas\"]', '24/7', 'Included'),
(5, 18, 'Organized Nile cruises for exploring the river and its landmarks.', '[\"PADI courses\", \"Equipment rental\", \"Guided dives\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(6, 2, 'Infinity pool with stunning views of the Nile and poolside service.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(6, 3, 'Unlimited Wi-Fi available throughout the hotel premises.', '[\"Available in all rooms\", \"No password required\", \"24/7 support\"]', '24/7', 'Free'),
(6, 4, 'A variety of local and international dining options.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(6, 5, 'World-class spa treatments and wellness therapies.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(6, 15, 'Relax with a drink at the Nile Lounge overlooking the Nile.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(6, 16, 'Guided tours of Aswan\'s historical sites and landmarks.', '[\"Local plants\", \"Walking paths\", \"Relaxation areas\"]', '24/7', 'Included'),
(6, 18, 'Private marina for boat trips and water sports.', '[\"PADI courses\", \"Equipment rental\", \"Guided dives\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(6, 20, 'Free shuttle service to and from the Aswan Airport.', '[\"Airport pickup\", \"Scheduled times\", \"Luggage assistance\"]', '6:00 AM - 11:00 PM', 'Free for hotel guests'),
(7, 3, 'Free Wi-Fi available throughout the hotel premises.', '[\"Available in all rooms\", \"No password required\", \"24/7 support\"]', '24/7', 'Free'),
(7, 4, 'A variety of local and international cuisine served in elegant settings.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(7, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(7, 11, 'Relax with a coffee at the beach café overlooking the Red Sea.', NULL, NULL, NULL),
(7, 17, 'Bicycle rental service for exploring Dahab.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(7, 19, 'Professional diving center offering PADI certification courses and underwater adventures.', '[\"Snorkeling\", \"Diving\", \"Kayaking\", \"Equipment rental\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(7, 20, 'Shuttle service to and from Dahab Airport.', '[\"Airport pickup\", \"Scheduled times\", \"Luggage assistance\"]', '6:00 AM - 11:00 PM', 'Free for hotel guests'),
(7, 21, 'Bedouin-style seating areas for relaxation and socializing.', NULL, NULL, NULL),
(8, 2, 'Luxury pool with stunning views of the Nile and poolside service.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(8, 4, 'Eight restaurants offering a variety of international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(8, 5, 'Spa and beauty salon offering a range of treatments and services.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(8, 6, 'Fully equipped gym with personal trainers and modern fitness equipment.', '[\"Cardio & weights\", \"Personal trainers\", \"Yoga classes\"]', '24/7', 'Free for hotel guests'),
(8, 9, 'Valet parking service available 24/7 for all guests and visitors.', '[\"Covered parking\", \"Security monitored\", \"Accessible parking\"]', '24/7', 'Free for guests, visitors EGP 50/day'),
(8, 10, 'Order food, drinks, and amenities to your room at any hour of the day.', '[\"Full menu\", \"Express delivery\", \"Special requests\"]', '24/7', 'Service charge may apply'),
(8, 12, 'Fully equipped business center with meeting rooms, printers, and high-speed internet.', '[\"Meeting rooms\", \"Printing & scanning\", \"Secretarial services\"]', '8:00 AM - 8:00 PM', 'Some services extra'),
(8, 15, 'Relax with a drink at the Nile View Lounge overlooking the Nile.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(9, 2, 'Four pools for swimming, lounging, and water sports.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(9, 4, 'Five restaurants offering a variety of local and international dishes.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(9, 5, 'Relax and rejuvenate with spa treatments and massages.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(9, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(9, 8, 'Kid-friendly activities and a supervised play area.', '[\"Supervised play area\", \"Daily activities\", \"Kids pool\"]', '10:00 AM - 6:00 PM', 'Free for hotel guests'),
(9, 15, 'Relax with a refreshing drink at the beach bar overlooking the Red Sea.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(9, 17, 'Water sports activities such as snorkeling, diving, and kayaking.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(9, 19, 'PADI diving center offering diving courses and underwater adventures.', '[\"Snorkeling\", \"Diving\", \"Kayaking\", \"Equipment rental\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(10, 2, 'Stunning infinity pool overlooking the Mediterranean Sea.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(10, 4, 'Three restaurants offering a variety of local and international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(10, 5, 'Relax and rejuvenate with spa treatments and wellness therapies.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(10, 17, 'Outdoor sports courts for beach volleyball, tennis, and more.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(10, 20, 'Shuttle service to and from Taba Airport.', '[\"Airport pickup\", \"Scheduled times\", \"Luggage assistance\"]', '6:00 AM - 11:00 PM', 'Free for hotel guests'),
(10, 22, 'Stunning views of the mountains and the Gulf of Aqaba.', NULL, NULL, NULL),
(10, 23, 'Guided hikes through the beautiful Sinai Mountains.', NULL, NULL, NULL),
(10, 24, 'Experience Bedouin culture with nightly entertainment and storytelling.', NULL, NULL, NULL),
(11, 4, 'Local cuisine served in a traditional Bedouin tent.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(11, 5, 'Relaxation sessions in the spa with traditional treatments.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(11, 7, 'Private beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(11, 15, 'BBQ nights under the stars with Bedouin storytelling.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(11, 17, 'Safari trips for exploring the desert on camelback or bicycle.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(11, 21, 'Bedouin-style camp with traditional activities and overnight stays.', NULL, NULL, NULL),
(11, 22, 'Guided horseback riding tours through the desert.', NULL, NULL, NULL),
(11, 24, 'Evenings spent stargazing under the clear Egyptian skies.', NULL, NULL, NULL),
(12, 1, 'Eco-friendly design with local materials and sustainable practices.', NULL, NULL, NULL),
(12, 2, 'Natural pool with stunning views of the oasis and surrounding desert.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(12, 4, 'Organic cuisine served in a traditional Bedouin tent.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(12, 5, 'Natural spa treatments using local ingredients and techniques.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(12, 16, 'Beautiful gardens with local plants and trees.', '[\"Local plants\", \"Walking paths\", \"Relaxation areas\"]', '24/7', 'Included'),
(12, 17, 'Desert tours on camelback or bicycle for exploring the surrounding landscape.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(12, 20, 'Solar panels providing sustainable energy for the resort.', '[\"Airport pickup\", \"Scheduled times\", \"Luggage assistance\"]', '6:00 AM - 11:00 PM', 'Free for hotel guests'),
(12, 24, 'Traditional Bedouin storytelling and music in the evenings.', NULL, NULL, NULL),
(13, 2, 'Three pools for swimming, lounging, and water sports.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(13, 4, 'Four restaurants offering a variety of local and international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(13, 5, 'Luxury spa with a range of treatments and massages.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(13, 6, 'Modern gym with state-of-the-art equipment and personal trainers.', '[\"Cardio & weights\", \"Personal trainers\", \"Yoga classes\"]', '24/7', 'Free for hotel guests'),
(13, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(13, 8, 'Kid-friendly activities and a supervised play area.', '[\"Supervised play area\", \"Daily activities\", \"Kids pool\"]', '10:00 AM - 6:00 PM', 'Free for hotel guests'),
(13, 15, 'Relax with a refreshing drink at the beach bar overlooking the Mediterranean Sea.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(13, 17, 'Water sports activities such as snorkeling, diving, and kayaking.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(14, 3, 'Unlimited Wi-Fi available throughout the hotel premises.', '[\"Available in all rooms\", \"No password required\", \"24/7 support\"]', '24/7', 'Free'),
(14, 4, 'Three restaurants offering a variety of local and international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(14, 5, 'Spa offering a range of treatments and services.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(14, 6, 'Modern gym with state-of-the-art equipment and personal trainers.', '[\"Cardio & weights\", \"Personal trainers\", \"Yoga classes\"]', '24/7', 'Free for hotel guests'),
(14, 9, 'Valet parking service available 24/7 for all guests and visitors.', '[\"Covered parking\", \"Security monitored\", \"Accessible parking\"]', '24/7', 'Free for guests, visitors EGP 50/day'),
(14, 10, 'Order food, drinks, and amenities to your room at any hour of the day.', '[\"Full menu\", \"Express delivery\", \"Special requests\"]', '24/7', 'Service charge may apply'),
(14, 12, 'Fully equipped business center with meeting rooms, printers, and high-speed internet.', '[\"Meeting rooms\", \"Printing & scanning\", \"Secretarial services\"]', '8:00 AM - 8:00 PM', 'Some services extra'),
(14, 15, 'Relax with a drink at the rooftop bar overlooking the city skyline.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(15, 2, 'Infinity pool overlooking the Mediterranean Sea with poolside service.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(15, 4, 'Four restaurants offering a variety of international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(15, 5, 'World-class spa treatments and wellness therapies.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(15, 6, 'Modern gym with personal trainers and state-of-the-art equipment.', '[\"Cardio & weights\", \"Personal trainers\", \"Yoga classes\"]', '24/7', 'Free for hotel guests'),
(15, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(15, 10, 'Room service available 24/7 for all your needs.', '[\"Full menu\", \"Express delivery\", \"Special requests\"]', '24/7', 'Service charge may apply'),
(15, 15, 'Relax with a drink at the sea bar overlooking the Mediterranean Sea.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(15, 18, 'Organized sea cruises for exploring the Mediterranean.', '[\"PADI courses\", \"Equipment rental\", \"Guided dives\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(16, 2, 'Four pools for swimming, lounging, and water sports.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(16, 4, 'Five restaurants offering a variety of local and international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(16, 5, 'World-class spa treatments and wellness therapies.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(16, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(16, 8, 'Kid-friendly activities and a supervised play area.', '[\"Supervised play area\", \"Daily activities\", \"Kids pool\"]', '10:00 AM - 6:00 PM', 'Free for hotel guests'),
(16, 15, 'Relax with a refreshing drink at the beach bar overlooking the Mediterranean Sea.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(16, 17, 'Water sports activities such as snorkeling, diving, and kayaking.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(16, 19, 'PADI diving center offering diving courses and underwater adventures.', '[\"Snorkeling\", \"Diving\", \"Kayaking\", \"Equipment rental\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(17, 2, 'Three pools for swimming, lounging, and water sports.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(17, 4, 'Four restaurants offering a variety of local and international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(17, 5, 'Spa offering a range of treatments and massages.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(17, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(17, 8, 'Kid-friendly activities and a supervised play area.', '[\"Supervised play area\", \"Daily activities\", \"Kids pool\"]', '10:00 AM - 6:00 PM', 'Free for hotel guests'),
(17, 15, 'Relax with a refreshing drink at the beach bar overlooking the Red Sea.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(17, 17, 'Water sports activities such as snorkeling, diving, and kayaking.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(17, 19, 'Professional diving center offering PADI certification courses and underwater adventures.', '[\"Snorkeling\", \"Diving\", \"Kayaking\", \"Equipment rental\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(18, 2, 'Stunning outdoor pool overlooking the Nile with poolside service.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(18, 4, 'Three restaurants offering a variety of local and international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(18, 5, 'Spa offering a range of treatments and massages.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(18, 6, 'Modern gym with state-of-the-art equipment and personal trainers.', '[\"Cardio & weights\", \"Personal trainers\", \"Yoga classes\"]', '24/7', 'Free for hotel guests'),
(18, 10, 'Order food, drinks, and amenities to your room at any hour of the day.', '[\"Full menu\", \"Express delivery\", \"Special requests\"]', '24/7', 'Service charge may apply'),
(18, 15, 'Relax with a drink at the Nile View Lounge overlooking the Nile.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(18, 16, 'Guided tours of Luxor\'s historical sites and landmarks.', '[\"Local plants\", \"Walking paths\", \"Relaxation areas\"]', '24/7', 'Included'),
(18, 18, 'Organized Nile cruises for exploring the river and its landmarks.', '[\"PADI courses\", \"Equipment rental\", \"Guided dives\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(19, 2, 'Infinity pool overlooking the Nile with poolside service.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(19, 4, 'Three restaurants offering a variety of local and international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(19, 5, 'Nubian spa treatments and wellness therapies.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(19, 6, 'Modern gym with personal trainers and state-of-the-art equipment.', '[\"Cardio & weights\", \"Personal trainers\", \"Yoga classes\"]', '24/7', 'Free for hotel guests'),
(19, 10, 'Room service available 24/7 for all your needs.', '[\"Full menu\", \"Express delivery\", \"Special requests\"]', '24/7', 'Service charge may apply'),
(19, 15, 'Relax with a drink at the Nile View Lounge overlooking the Nile.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(19, 16, 'Guided tours of Aswan\'s historical sites and landmarks.', '[\"Local plants\", \"Walking paths\", \"Relaxation areas\"]', '24/7', 'Included'),
(19, 18, 'Organized Nile cruises for exploring the river and its landmarks.', '[\"PADI courses\", \"Equipment rental\", \"Guided dives\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(20, 3, 'Free Wi-Fi available throughout the hotel premises.', '[\"Available in all rooms\", \"No password required\", \"24/7 support\"]', '24/7', 'Free'),
(20, 4, 'Local cuisine served in a traditional Bedouin tent.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(20, 5, 'Bedouin spa treatments and wellness therapies.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(20, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(20, 15, 'Relax with a refreshing drink at the beach bar overlooking the Red Sea.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(20, 17, 'Water sports activities such as snorkeling, diving, and kayaking.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(20, 19, 'PADI diving center offering diving courses and underwater adventures.', '[\"Snorkeling\", \"Diving\", \"Kayaking\", \"Equipment rental\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(20, 21, 'Bedouin-style seating areas for relaxation and socializing.', NULL, NULL, NULL),
(21, 2, 'Luxury pool with panoramic Nile views and poolside service.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(21, 3, 'Unlimited high-speed Wi-Fi available in all rooms and public areas.', '[\"Available in all rooms\", \"No password required\", \"24/7 support\"]', '24/7', 'Free'),
(21, 4, 'International cuisine served in a modern setting.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(21, 6, 'Modern gym with personal trainers and state-of-the-art equipment.', '[\"Cardio & weights\", \"Personal trainers\", \"Yoga classes\"]', '24/7', 'Free for hotel guests'),
(21, 9, 'Valet parking service available 24/7 for all guests and visitors.', '[\"Covered parking\", \"Security monitored\", \"Accessible parking\"]', '24/7', 'Free for guests, visitors EGP 50/day'),
(21, 10, 'Order food, drinks, and amenities to your room at any hour of the day.', '[\"Full menu\", \"Express delivery\", \"Special requests\"]', '24/7', 'Service charge may apply'),
(21, 12, 'Business center with meeting rooms and office services.', '[\"Meeting rooms\", \"Printing & scanning\", \"Secretarial services\"]', '8:00 AM - 8:00 PM', 'Some services extra'),
(21, 15, 'Relax with a drink at the Nile View Lounge overlooking the Nile.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(22, 2, 'Three pools for swimming, lounging, and water sports.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(22, 4, 'Four restaurants offering a variety of local and international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(22, 5, 'Luxury spa with a range of treatments and massages.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(22, 6, 'Modern gym with state-of-the-art equipment and personal trainers.', '[\"Cardio & weights\", \"Personal trainers\", \"Yoga classes\"]', '24/7', 'Free for hotel guests'),
(22, 7, 'Private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(22, 8, 'Kid-friendly activities and a supervised play area.', '[\"Supervised play area\", \"Daily activities\", \"Kids pool\"]', '10:00 AM - 6:00 PM', 'Free for hotel guests'),
(22, 15, 'Relax with a refreshing drink at the beach bar overlooking the Red Sea.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(22, 17, 'Water sports activities such as snorkeling, diving, and kayaking.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(23, 2, 'Infinity pool overlooking the Red Sea with poolside service.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(23, 4, 'Three restaurants offering a variety of local and international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(23, 5, 'World-class spa treatments and wellness therapies.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(23, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(23, 10, 'Room service available 24/7 for all your needs.', '[\"Full menu\", \"Express delivery\", \"Special requests\"]', '24/7', 'Service charge may apply'),
(23, 15, 'Relax with a drink at the casino lounge.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(23, 17, 'Outdoor sports courts for beach volleyball, tennis, and more.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(23, 24, 'Tour desk for organizing excursions and sightseeing.', NULL, NULL, NULL),
(24, 2, 'Natural pool with stunning views of Lake Qarun.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(24, 4, 'Organic cuisine served in a traditional setting.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(24, 5, 'Natural spa treatments using local ingredients and techniques.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(24, 7, 'Private beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(24, 16, 'Beautiful gardens with local plants and trees.', '[\"Local plants\", \"Walking paths\", \"Relaxation areas\"]', '24/7', 'Included'),
(24, 17, 'Desert tours on camelback or bicycle for exploring the surrounding landscape.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(24, 20, 'Shuttle service to and from Fayoum city.', '[\"Airport pickup\", \"Scheduled times\", \"Luggage assistance\"]', '6:00 AM - 11:00 PM', 'Free for hotel guests'),
(24, 24, 'Traditional Bedouin storytelling and music in the evenings.', NULL, NULL, NULL),
(25, 1, 'Eco-friendly design with local materials and sustainable practices.', NULL, NULL, NULL),
(25, 2, 'Natural pool with stunning views of the oasis and surrounding desert.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(25, 4, 'Organic cuisine served in a traditional Bedouin tent.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(25, 5, 'Natural spa treatments using local ingredients and techniques.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(25, 16, 'Beautiful gardens with local plants and trees.', '[\"Local plants\", \"Walking paths\", \"Relaxation areas\"]', '24/7', 'Included'),
(25, 17, 'Desert tours on camelback or bicycle for exploring the surrounding landscape.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(25, 20, 'Solar panels providing sustainable energy for the resort.', '[\"Airport pickup\", \"Scheduled times\", \"Luggage assistance\"]', '6:00 AM - 11:00 PM', 'Free for hotel guests'),
(25, 24, 'Traditional Bedouin storytelling and music in the evenings.', NULL, NULL, NULL),
(26, 2, 'Four pools for swimming, lounging, and water sports.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(26, 4, 'Five restaurants offering a variety of local and international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(26, 5, 'World-class spa treatments and wellness therapies.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(26, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(26, 8, 'Kid-friendly activities and a supervised play area.', '[\"Supervised play area\", \"Daily activities\", \"Kids pool\"]', '10:00 AM - 6:00 PM', 'Free for hotel guests'),
(26, 15, 'Relax with a refreshing drink at the beach bar overlooking the Mediterranean Sea.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(26, 17, 'Water sports activities such as snorkeling, diving, and kayaking.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(26, 19, 'PADI diving center offering diving courses and underwater adventures.', '[\"Snorkeling\", \"Diving\", \"Kayaking\", \"Equipment rental\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(28, 2, 'Infinity pool overlooking the Mediterranean Sea with poolside service.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(28, 4, 'Four restaurants offering a variety of international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(28, 5, 'World-class spa treatments and wellness therapies.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(28, 6, 'Modern gym with personal trainers and state-of-the-art equipment.', '[\"Cardio & weights\", \"Personal trainers\", \"Yoga classes\"]', '24/7', 'Free for hotel guests'),
(28, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(28, 10, 'Room service available 24/7 for all your needs.', '[\"Full menu\", \"Express delivery\", \"Special requests\"]', '24/7', 'Service charge may apply'),
(28, 15, 'Relax with a drink at the sea bar overlooking the Mediterranean Sea.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(28, 18, 'Organized sea cruises for exploring the Mediterranean.', '[\"PADI courses\", \"Equipment rental\", \"Guided dives\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(29, 2, 'Four pools for swimming, lounging, and water sports.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(29, 4, 'Five restaurants offering a variety of local and international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(29, 5, 'World-class spa treatments and wellness therapies.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(29, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(29, 8, 'Kid-friendly activities and a supervised play area.', '[\"Supervised play area\", \"Daily activities\", \"Kids pool\"]', '10:00 AM - 6:00 PM', 'Free for hotel guests'),
(29, 15, 'Relax with a refreshing drink at the beach bar overlooking the Red Sea.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(29, 17, 'Water sports activities such as snorkeling, diving, and kayaking.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests'),
(29, 19, 'PADI diving center offering diving courses and underwater adventures.', '[\"Snorkeling\", \"Diving\", \"Kayaking\", \"Equipment rental\"]', '9:00 AM - 6:00 PM', 'Extra charge'),
(30, 2, 'Three pools for swimming, lounging, and water sports.', '[\"Heated in winter\", \"Lifeguard on duty\", \"Pool bar available\", \"Towels provided\"]', '6:00 AM - 10:00 PM', 'Free for hotel guests'),
(30, 4, 'Four restaurants offering a variety of local and international cuisine.', '[\"Buffet and à la carte\", \"Vegetarian options\", \"Kids menu available\"]', '7:00 AM - 11:00 PM', 'Charges apply'),
(30, 5, 'Luxury spa with a range of treatments and massages.', '[\"Sauna & steam room\", \"Massage therapies\", \"Beauty treatments\"]', '9:00 AM - 9:00 PM', 'Extra charge'),
(30, 6, 'Modern gym with state-of-the-art equipment and personal trainers.', '[\"Cardio & weights\", \"Personal trainers\", \"Yoga classes\"]', '24/7', 'Free for hotel guests'),
(30, 7, 'Exclusive access to a private sandy beach with sunbeds and umbrellas.', '[\"Sunbeds and umbrellas included\", \"Beach towels provided\", \"Lifeguard on duty\"]', '7:00 AM - Sunset', 'Free for hotel guests'),
(30, 8, 'Kid-friendly activities and a supervised play area.', '[\"Supervised play area\", \"Daily activities\", \"Kids pool\"]', '10:00 AM - 6:00 PM', 'Free for hotel guests'),
(30, 15, 'Relax with a refreshing drink at the beach bar overlooking the Red Sea.', '[\"Live music\", \"Signature cocktails\", \"Snacks available\"]', '4:00 PM - 1:00 AM', 'Charges apply'),
(30, 17, 'Water sports activities such as snorkeling, diving, and kayaking.', '[\"Professional courts\", \"Equipment rental\", \"Lessons available\"]', '8:00 AM - 10:00 PM', 'Free for hotel guests');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_categories`
--

CREATE TABLE `hotel_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hotel_categories`
--

INSERT INTO `hotel_categories` (`id`, `name`, `description`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Luxury', 'High-end hotels with premium amenities', 'fa-star', '2025-05-22 17:08:28', '2025-05-22 17:08:28'),
(2, 'Business', 'Hotels catering to business travelers', 'fa-briefcase', '2025-05-22 17:08:28', '2025-05-22 17:08:28'),
(3, 'Resort', 'Vacation resorts and spa hotels', 'fa-umbrella-beach', '2025-05-22 17:08:28', '2025-05-22 17:08:28'),
(4, 'Boutique', 'Unique, stylish, and intimate hotels', 'fa-gem', '2025-05-22 17:08:28', '2025-05-22 17:08:28'),
(5, 'Budget', 'Affordable accommodations', 'fa-money-bill', '2025-05-22 17:08:28', '2025-05-22 17:08:28');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_features`
--

CREATE TABLE `hotel_features` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_gallery`
--

CREATE TABLE `hotel_gallery` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_images`
--

CREATE TABLE `hotel_images` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_main` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_featured` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_images`
--

INSERT INTO `hotel_images` (`id`, `hotel_id`, `image`, `is_main`, `created_at`, `is_featured`) VALUES
(345, 1, '/Booking-Hotel-Project/pages/images/hotels/ritz-cairo/1.jpg', 0, '2025-05-20 22:59:10', 0),
(346, 1, '/Booking-Hotel-Project/pages/images/hotels/ritz-cairo/2.jpg', 0, '2025-05-20 22:59:10', 0),
(347, 1, '/Booking-Hotel-Project/pages/images/hotels/ritz-cairo/3.jpg', 0, '2025-05-20 22:59:10', 0),
(348, 1, '/Booking-Hotel-Project/pages/images/hotels/ritz-cairo/4.jpg', 0, '2025-05-20 22:59:10', 0),
(349, 1, '/Booking-Hotel-Project/pages/images/hotels/ritz-cairo/5.jpg', 0, '2025-05-20 22:59:10', 0),
(350, 1, '/Booking-Hotel-Project/pages/images/hotels/ritz-cairo/6.jpg', 0, '2025-05-20 22:59:10', 0),
(351, 1, '/Booking-Hotel-Project/pages/images/hotels/ritz-cairo/7.jpg', 0, '2025-05-20 22:59:10', 0),
(352, 1, '/Booking-Hotel-Project/pages/images/hotels/ritz-cairo/8.jpg', 0, '2025-05-20 22:59:10', 0),
(353, 1, '/Booking-Hotel-Project/pages/images/hotels/ritz-cairo/9.jpg', 0, '2025-05-20 22:59:10', 0),
(354, 1, '/Booking-Hotel-Project/pages/images/hotels/ritz-cairo/room1.jpg', 0, '2025-05-20 22:59:10', 0),
(355, 1, '/Booking-Hotel-Project/pages/images/hotels/ritz-cairo/room2.jpg', 0, '2025-05-20 22:59:10', 0),
(356, 1, '/Booking-Hotel-Project/pages/images/hotels/ritz-cairo/room3.jpeg', 0, '2025-05-20 22:59:10', 0),
(357, 2, '/Booking-Hotel-Project/pages/images/hotels/fourseasons-alex/1.jpg', 0, '2025-05-20 22:59:10', 0),
(358, 2, '/Booking-Hotel-Project/pages/images/hotels/fourseasons-alex/2.jpg', 0, '2025-05-20 22:59:10', 0),
(359, 2, '/Booking-Hotel-Project/pages/images/hotels/fourseasons-alex/3.jpg', 0, '2025-05-20 22:59:10', 0),
(360, 2, '/Booking-Hotel-Project/pages/images/hotels/fourseasons-alex/4.jpg', 0, '2025-05-20 22:59:10', 0),
(361, 2, '/Booking-Hotel-Project/pages/images/hotels/fourseasons-alex/5.jpg', 0, '2025-05-20 22:59:10', 0),
(362, 2, '/Booking-Hotel-Project/pages/images/hotels/fourseasons-alex/6.jpg', 0, '2025-05-20 22:59:10', 0),
(363, 2, '/Booking-Hotel-Project/pages/images/hotels/fourseasons-alex/7.jpg', 0, '2025-05-20 22:59:10', 0),
(364, 2, '/Booking-Hotel-Project/pages/images/hotels/fourseasons-alex/8.jpg', 0, '2025-05-20 22:59:10', 0),
(365, 2, '/Booking-Hotel-Project/pages/images/hotels/fourseasons-alex/9.jpg', 0, '2025-05-20 22:59:10', 0),
(366, 2, '/Booking-Hotel-Project/pages/images/hotels/fourseasons-alex/room1.jpg', 0, '2025-05-20 22:59:10', 0),
(367, 2, '/Booking-Hotel-Project/pages/images/hotels/fourseasons-alex/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(368, 2, '/Booking-Hotel-Project/pages/images/hotels/fourseasons-alex/room3.jpeg', 0, '2025-05-20 22:59:11', 0),
(369, 3, '/Booking-Hotel-Project/pages/images/hotels/sharm-royal/1.jpg', 0, '2025-05-20 22:59:11', 0),
(370, 3, '/Booking-Hotel-Project/pages/images/hotels/sharm-royal/2.jpg', 0, '2025-05-20 22:59:11', 0),
(371, 3, '/Booking-Hotel-Project/pages/images/hotels/sharm-royal/3.jpg', 0, '2025-05-20 22:59:11', 0),
(372, 3, '/Booking-Hotel-Project/pages/images/hotels/sharm-royal/4.jpg', 0, '2025-05-20 22:59:11', 0),
(373, 3, '/Booking-Hotel-Project/pages/images/hotels/sharm-royal/5.jpg', 0, '2025-05-20 22:59:11', 0),
(374, 3, '/Booking-Hotel-Project/pages/images/hotels/sharm-royal/6.jpg', 0, '2025-05-20 22:59:11', 0),
(375, 3, '/Booking-Hotel-Project/pages/images/hotels/sharm-royal/7.jpg', 0, '2025-05-20 22:59:11', 0),
(376, 3, '/Booking-Hotel-Project/pages/images/hotels/sharm-royal/8.jpg', 0, '2025-05-20 22:59:11', 0),
(377, 3, '/Booking-Hotel-Project/pages/images/hotels/sharm-royal/9.jpg', 0, '2025-05-20 22:59:11', 0),
(378, 3, '/Booking-Hotel-Project/pages/images/hotels/sharm-royal/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(379, 3, '/Booking-Hotel-Project/pages/images/hotels/sharm-royal/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(380, 4, '/Booking-Hotel-Project/pages/images/hotels/hurghada-beach/1.jpg', 0, '2025-05-20 22:59:11', 0),
(381, 4, '/Booking-Hotel-Project/pages/images/hotels/hurghada-beach/2.jpg', 0, '2025-05-20 22:59:11', 0),
(382, 4, '/Booking-Hotel-Project/pages/images/hotels/hurghada-beach/3.jpg', 0, '2025-05-20 22:59:11', 0),
(383, 4, '/Booking-Hotel-Project/pages/images/hotels/hurghada-beach/4.jpg', 0, '2025-05-20 22:59:11', 0),
(384, 4, '/Booking-Hotel-Project/pages/images/hotels/hurghada-beach/5.jpg', 0, '2025-05-20 22:59:11', 0),
(385, 4, '/Booking-Hotel-Project/pages/images/hotels/hurghada-beach/6.jpg', 0, '2025-05-20 22:59:11', 0),
(386, 4, '/Booking-Hotel-Project/pages/images/hotels/hurghada-beach/7.jpg', 0, '2025-05-20 22:59:11', 0),
(387, 4, '/Booking-Hotel-Project/pages/images/hotels/hurghada-beach/8.jpg', 0, '2025-05-20 22:59:11', 0),
(388, 4, '/Booking-Hotel-Project/pages/images/hotels/hurghada-beach/9.jpg', 0, '2025-05-20 22:59:11', 0),
(389, 4, '/Booking-Hotel-Project/pages/images/hotels/hurghada-beach/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(390, 4, '/Booking-Hotel-Project/pages/images/hotels/hurghada-beach/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(391, 5, '/Booking-Hotel-Project/pages/images/hotels/luxor-palace/1.jpg', 0, '2025-05-20 22:59:11', 0),
(392, 5, '/Booking-Hotel-Project/pages/images/hotels/luxor-palace/2.jpg', 0, '2025-05-20 22:59:11', 0),
(393, 5, '/Booking-Hotel-Project/pages/images/hotels/luxor-palace/3.jpg', 0, '2025-05-20 22:59:11', 0),
(394, 5, '/Booking-Hotel-Project/pages/images/hotels/luxor-palace/4.jpg', 0, '2025-05-20 22:59:11', 0),
(395, 5, '/Booking-Hotel-Project/pages/images/hotels/luxor-palace/5.jpg', 0, '2025-05-20 22:59:11', 0),
(396, 5, '/Booking-Hotel-Project/pages/images/hotels/luxor-palace/6.jpg', 0, '2025-05-20 22:59:11', 0),
(397, 5, '/Booking-Hotel-Project/pages/images/hotels/luxor-palace/7.jpg', 0, '2025-05-20 22:59:11', 0),
(398, 5, '/Booking-Hotel-Project/pages/images/hotels/luxor-palace/8.jpg', 0, '2025-05-20 22:59:11', 0),
(399, 5, '/Booking-Hotel-Project/pages/images/hotels/luxor-palace/9.jpg', 0, '2025-05-20 22:59:11', 0),
(400, 5, '/Booking-Hotel-Project/pages/images/hotels/luxor-palace/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(401, 5, '/Booking-Hotel-Project/pages/images/hotels/luxor-palace/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(402, 5, '/Booking-Hotel-Project/pages/images/hotels/luxor-palace/room3.jpg', 0, '2025-05-20 22:59:11', 0),
(403, 6, '/Booking-Hotel-Project/pages/images/hotels/movenpick-aswan/1.jpg', 0, '2025-05-20 22:59:11', 0),
(404, 6, '/Booking-Hotel-Project/pages/images/hotels/movenpick-aswan/2.jpg', 0, '2025-05-20 22:59:11', 0),
(405, 6, '/Booking-Hotel-Project/pages/images/hotels/movenpick-aswan/3.jpg', 0, '2025-05-20 22:59:11', 0),
(406, 6, '/Booking-Hotel-Project/pages/images/hotels/movenpick-aswan/4.jpg', 0, '2025-05-20 22:59:11', 0),
(407, 6, '/Booking-Hotel-Project/pages/images/hotels/movenpick-aswan/5.jpg', 0, '2025-05-20 22:59:11', 0),
(408, 6, '/Booking-Hotel-Project/pages/images/hotels/movenpick-aswan/6.jpg', 0, '2025-05-20 22:59:11', 0),
(409, 6, '/Booking-Hotel-Project/pages/images/hotels/movenpick-aswan/7.jpg', 0, '2025-05-20 22:59:11', 0),
(410, 6, '/Booking-Hotel-Project/pages/images/hotels/movenpick-aswan/8.jpg', 0, '2025-05-20 22:59:11', 0),
(411, 6, '/Booking-Hotel-Project/pages/images/hotels/movenpick-aswan/9.jpg', 0, '2025-05-20 22:59:11', 0),
(412, 6, '/Booking-Hotel-Project/pages/images/hotels/movenpick-aswan/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(413, 6, '/Booking-Hotel-Project/pages/images/hotels/movenpick-aswan/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(414, 6, '/Booking-Hotel-Project/pages/images/hotels/movenpick-aswan/room3.jpg', 0, '2025-05-20 22:59:11', 0),
(415, 7, '/Booking-Hotel-Project/pages/images/hotels/dahab-lodge/1.jpg', 0, '2025-05-20 22:59:11', 0),
(416, 7, '/Booking-Hotel-Project/pages/images/hotels/dahab-lodge/2.jpg', 0, '2025-05-20 22:59:11', 0),
(417, 7, '/Booking-Hotel-Project/pages/images/hotels/dahab-lodge/3.jpg', 0, '2025-05-20 22:59:11', 0),
(418, 7, '/Booking-Hotel-Project/pages/images/hotels/dahab-lodge/4.jpg', 0, '2025-05-20 22:59:11', 0),
(419, 7, '/Booking-Hotel-Project/pages/images/hotels/dahab-lodge/5.jpg', 0, '2025-05-20 22:59:11', 0),
(420, 7, '/Booking-Hotel-Project/pages/images/hotels/dahab-lodge/6.jpg', 0, '2025-05-20 22:59:11', 0),
(421, 7, '/Booking-Hotel-Project/pages/images/hotels/dahab-lodge/7.jpg', 0, '2025-05-20 22:59:11', 0),
(422, 7, '/Booking-Hotel-Project/pages/images/hotels/dahab-lodge/8.jpg', 0, '2025-05-20 22:59:11', 0),
(423, 7, '/Booking-Hotel-Project/pages/images/hotels/dahab-lodge/9.jpg', 0, '2025-05-20 22:59:11', 0),
(424, 7, '/Booking-Hotel-Project/pages/images/hotels/dahab-lodge/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(425, 7, '/Booking-Hotel-Project/pages/images/hotels/dahab-lodge/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(426, 8, '/Booking-Hotel-Project/pages/images/hotels/cairo-marriott/1.jpg', 0, '2025-05-20 22:59:11', 0),
(427, 8, '/Booking-Hotel-Project/pages/images/hotels/cairo-marriott/2.jpg', 0, '2025-05-20 22:59:11', 0),
(428, 8, '/Booking-Hotel-Project/pages/images/hotels/cairo-marriott/3.jpg', 0, '2025-05-20 22:59:11', 0),
(429, 8, '/Booking-Hotel-Project/pages/images/hotels/cairo-marriott/4.jpg', 0, '2025-05-20 22:59:11', 0),
(430, 8, '/Booking-Hotel-Project/pages/images/hotels/cairo-marriott/5.jpg', 0, '2025-05-20 22:59:11', 0),
(431, 8, '/Booking-Hotel-Project/pages/images/hotels/cairo-marriott/6.jpg', 0, '2025-05-20 22:59:11', 0),
(432, 8, '/Booking-Hotel-Project/pages/images/hotels/cairo-marriott/7.jpg', 0, '2025-05-20 22:59:11', 0),
(433, 8, '/Booking-Hotel-Project/pages/images/hotels/cairo-marriott/8.jpg', 0, '2025-05-20 22:59:11', 0),
(434, 8, '/Booking-Hotel-Project/pages/images/hotels/cairo-marriott/9.jpg', 0, '2025-05-20 22:59:11', 0),
(435, 8, '/Booking-Hotel-Project/pages/images/hotels/cairo-marriott/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(436, 8, '/Booking-Hotel-Project/pages/images/hotels/cairo-marriott/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(437, 9, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-blue/1.jpg', 0, '2025-05-20 22:59:11', 0),
(438, 9, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-blue/2.jpg', 0, '2025-05-20 22:59:11', 0),
(439, 9, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-blue/3.jpg', 0, '2025-05-20 22:59:11', 0),
(440, 9, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-blue/4.jpg', 0, '2025-05-20 22:59:11', 0),
(441, 9, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-blue/5.jpg', 0, '2025-05-20 22:59:11', 0),
(442, 9, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-blue/6.jpg', 0, '2025-05-20 22:59:11', 0),
(443, 9, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-blue/7.jpg', 0, '2025-05-20 22:59:11', 0),
(444, 9, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-blue/8.jpg', 0, '2025-05-20 22:59:11', 0),
(445, 9, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-blue/9.jpg', 0, '2025-05-20 22:59:11', 0),
(446, 9, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-blue/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(447, 9, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-blue/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(448, 9, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-blue/room3.jpg', 0, '2025-05-20 22:59:11', 0),
(449, 10, '/Booking-Hotel-Project/pages/images/hotels/taba-heights/1.jpg', 0, '2025-05-20 22:59:11', 0),
(450, 10, '/Booking-Hotel-Project/pages/images/hotels/taba-heights/2.jpg', 0, '2025-05-20 22:59:11', 0),
(451, 10, '/Booking-Hotel-Project/pages/images/hotels/taba-heights/3.jpg', 0, '2025-05-20 22:59:11', 0),
(452, 10, '/Booking-Hotel-Project/pages/images/hotels/taba-heights/4.jpg', 0, '2025-05-20 22:59:11', 0),
(453, 10, '/Booking-Hotel-Project/pages/images/hotels/taba-heights/5.jpg', 0, '2025-05-20 22:59:11', 0),
(454, 10, '/Booking-Hotel-Project/pages/images/hotels/taba-heights/6.jpg', 0, '2025-05-20 22:59:11', 0),
(455, 10, '/Booking-Hotel-Project/pages/images/hotels/taba-heights/7.jpg', 0, '2025-05-20 22:59:11', 0),
(456, 10, '/Booking-Hotel-Project/pages/images/hotels/taba-heights/8.jpg', 0, '2025-05-20 22:59:11', 0),
(457, 10, '/Booking-Hotel-Project/pages/images/hotels/taba-heights/9.jpg', 0, '2025-05-20 22:59:11', 0),
(458, 10, '/Booking-Hotel-Project/pages/images/hotels/taba-heights/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(459, 10, '/Booking-Hotel-Project/pages/images/hotels/taba-heights/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(460, 10, '/Booking-Hotel-Project/pages/images/hotels/taba-heights/room3.jpg', 0, '2025-05-20 22:59:11', 0),
(461, 11, '/Booking-Hotel-Project/pages/images/hotels/fayoum-desert/1.jpg', 0, '2025-05-20 22:59:11', 0),
(462, 11, '/Booking-Hotel-Project/pages/images/hotels/fayoum-desert/2.jpg', 0, '2025-05-20 22:59:11', 0),
(463, 11, '/Booking-Hotel-Project/pages/images/hotels/fayoum-desert/3.jpg', 0, '2025-05-20 22:59:11', 0),
(464, 11, '/Booking-Hotel-Project/pages/images/hotels/fayoum-desert/4.jpg', 0, '2025-05-20 22:59:11', 0),
(465, 11, '/Booking-Hotel-Project/pages/images/hotels/fayoum-desert/5.jpg', 0, '2025-05-20 22:59:11', 0),
(466, 11, '/Booking-Hotel-Project/pages/images/hotels/fayoum-desert/6.jpg', 0, '2025-05-20 22:59:11', 0),
(467, 11, '/Booking-Hotel-Project/pages/images/hotels/fayoum-desert/7.jpg', 0, '2025-05-20 22:59:11', 0),
(468, 11, '/Booking-Hotel-Project/pages/images/hotels/fayoum-desert/8.jpg', 0, '2025-05-20 22:59:11', 0),
(469, 11, '/Booking-Hotel-Project/pages/images/hotels/fayoum-desert/9.jpg', 0, '2025-05-20 22:59:11', 0),
(470, 11, '/Booking-Hotel-Project/pages/images/hotels/fayoum-desert/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(471, 11, '/Booking-Hotel-Project/pages/images/hotels/fayoum-desert/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(472, 12, '/Booking-Hotel-Project/pages/images/hotels/siwa-eco/1.jpg', 0, '2025-05-20 22:59:11', 0),
(473, 12, '/Booking-Hotel-Project/pages/images/hotels/siwa-eco/2.jpg', 0, '2025-05-20 22:59:11', 0),
(474, 12, '/Booking-Hotel-Project/pages/images/hotels/siwa-eco/3.jpg', 0, '2025-05-20 22:59:11', 0),
(475, 12, '/Booking-Hotel-Project/pages/images/hotels/siwa-eco/4.jpg', 0, '2025-05-20 22:59:11', 0),
(476, 12, '/Booking-Hotel-Project/pages/images/hotels/siwa-eco/5.jpg', 0, '2025-05-20 22:59:11', 0),
(477, 12, '/Booking-Hotel-Project/pages/images/hotels/siwa-eco/6.jpg', 0, '2025-05-20 22:59:11', 0),
(478, 12, '/Booking-Hotel-Project/pages/images/hotels/siwa-eco/7.jpg', 0, '2025-05-20 22:59:11', 0),
(479, 12, '/Booking-Hotel-Project/pages/images/hotels/siwa-eco/8.jpg', 0, '2025-05-20 22:59:11', 0),
(480, 12, '/Booking-Hotel-Project/pages/images/hotels/siwa-eco/9.jpg', 0, '2025-05-20 22:59:11', 0),
(481, 12, '/Booking-Hotel-Project/pages/images/hotels/siwa-eco/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(482, 12, '/Booking-Hotel-Project/pages/images/hotels/siwa-eco/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(483, 13, '/Booking-Hotel-Project/pages/images/hotels/alamein-beach/1.jpg', 0, '2025-05-20 22:59:11', 0),
(484, 13, '/Booking-Hotel-Project/pages/images/hotels/alamein-beach/2.jpg', 0, '2025-05-20 22:59:11', 0),
(485, 13, '/Booking-Hotel-Project/pages/images/hotels/alamein-beach/3.jpg', 0, '2025-05-20 22:59:11', 0),
(486, 13, '/Booking-Hotel-Project/pages/images/hotels/alamein-beach/4.jpg', 0, '2025-05-20 22:59:11', 0),
(487, 13, '/Booking-Hotel-Project/pages/images/hotels/alamein-beach/5.jpg', 0, '2025-05-20 22:59:11', 0),
(488, 13, '/Booking-Hotel-Project/pages/images/hotels/alamein-beach/6.jpg', 0, '2025-05-20 22:59:11', 0),
(489, 13, '/Booking-Hotel-Project/pages/images/hotels/alamein-beach/7.jpg', 0, '2025-05-20 22:59:11', 0),
(490, 13, '/Booking-Hotel-Project/pages/images/hotels/alamein-beach/8.jpg', 0, '2025-05-20 22:59:11', 0),
(491, 13, '/Booking-Hotel-Project/pages/images/hotels/alamein-beach/9.jpg', 0, '2025-05-20 22:59:11', 0),
(492, 13, '/Booking-Hotel-Project/pages/images/hotels/alamein-beach/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(493, 13, '/Booking-Hotel-Project/pages/images/hotels/alamein-beach/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(494, 14, '/Booking-Hotel-Project/pages/images/hotels/cairo-citystars/1.jpg', 0, '2025-05-20 22:59:11', 0),
(495, 14, '/Booking-Hotel-Project/pages/images/hotels/cairo-citystars/2.jpg', 0, '2025-05-20 22:59:11', 0),
(496, 14, '/Booking-Hotel-Project/pages/images/hotels/cairo-citystars/3.jpg', 0, '2025-05-20 22:59:11', 0),
(497, 14, '/Booking-Hotel-Project/pages/images/hotels/cairo-citystars/4.jpg', 0, '2025-05-20 22:59:11', 0),
(498, 14, '/Booking-Hotel-Project/pages/images/hotels/cairo-citystars/5.jpg', 0, '2025-05-20 22:59:11', 0),
(499, 14, '/Booking-Hotel-Project/pages/images/hotels/cairo-citystars/6.jpg', 0, '2025-05-20 22:59:11', 0),
(500, 14, '/Booking-Hotel-Project/pages/images/hotels/cairo-citystars/7.jpg', 0, '2025-05-20 22:59:11', 0),
(501, 14, '/Booking-Hotel-Project/pages/images/hotels/cairo-citystars/8.jpg', 0, '2025-05-20 22:59:11', 0),
(502, 14, '/Booking-Hotel-Project/pages/images/hotels/cairo-citystars/9.jpg', 0, '2025-05-20 22:59:11', 0),
(503, 14, '/Booking-Hotel-Project/pages/images/hotels/cairo-citystars/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(504, 14, '/Booking-Hotel-Project/pages/images/hotels/cairo-citystars/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(505, 14, '/Booking-Hotel-Project/pages/images/hotels/cairo-citystars/room3.jpg', 0, '2025-05-20 22:59:11', 0),
(506, 15, '/Booking-Hotel-Project/pages/images/hotels/alex-seaview/1.jpg', 0, '2025-05-20 22:59:11', 0),
(507, 15, '/Booking-Hotel-Project/pages/images/hotels/alex-seaview/2.jpg', 0, '2025-05-20 22:59:11', 0),
(508, 15, '/Booking-Hotel-Project/pages/images/hotels/alex-seaview/3.jpg', 0, '2025-05-20 22:59:11', 0),
(509, 15, '/Booking-Hotel-Project/pages/images/hotels/alex-seaview/4.jpg', 0, '2025-05-20 22:59:11', 0),
(510, 15, '/Booking-Hotel-Project/pages/images/hotels/alex-seaview/5.jpg', 0, '2025-05-20 22:59:11', 0),
(511, 15, '/Booking-Hotel-Project/pages/images/hotels/alex-seaview/6.jpg', 0, '2025-05-20 22:59:11', 0),
(512, 15, '/Booking-Hotel-Project/pages/images/hotels/alex-seaview/7.jpg', 0, '2025-05-20 22:59:11', 0),
(513, 15, '/Booking-Hotel-Project/pages/images/hotels/alex-seaview/8.jpg', 0, '2025-05-20 22:59:11', 0),
(514, 15, '/Booking-Hotel-Project/pages/images/hotels/alex-seaview/9.jpg', 0, '2025-05-20 22:59:11', 0),
(515, 15, '/Booking-Hotel-Project/pages/images/hotels/alex-seaview/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(516, 15, '/Booking-Hotel-Project/pages/images/hotels/alex-seaview/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(517, 16, '/Booking-Hotel-Project/pages/images/hotels/sharm-coral/1.jpg', 0, '2025-05-20 22:59:11', 0),
(518, 16, '/Booking-Hotel-Project/pages/images/hotels/sharm-coral/2.jpg', 0, '2025-05-20 22:59:11', 0),
(519, 16, '/Booking-Hotel-Project/pages/images/hotels/sharm-coral/3.jpg', 0, '2025-05-20 22:59:11', 0),
(520, 16, '/Booking-Hotel-Project/pages/images/hotels/sharm-coral/4.jpg', 0, '2025-05-20 22:59:11', 0),
(521, 16, '/Booking-Hotel-Project/pages/images/hotels/sharm-coral/5.jpg', 0, '2025-05-20 22:59:11', 0),
(522, 16, '/Booking-Hotel-Project/pages/images/hotels/sharm-coral/6.jpg', 0, '2025-05-20 22:59:11', 0),
(523, 16, '/Booking-Hotel-Project/pages/images/hotels/sharm-coral/7.jpg', 0, '2025-05-20 22:59:11', 0),
(524, 16, '/Booking-Hotel-Project/pages/images/hotels/sharm-coral/8.jpg', 0, '2025-05-20 22:59:11', 0),
(525, 16, '/Booking-Hotel-Project/pages/images/hotels/sharm-coral/9.jpg', 0, '2025-05-20 22:59:11', 0),
(526, 16, '/Booking-Hotel-Project/pages/images/hotels/sharm-coral/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(527, 16, '/Booking-Hotel-Project/pages/images/hotels/sharm-coral/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(528, 17, '/Booking-Hotel-Project/pages/images/hotels/hurghada-sunrise/1.jpg', 0, '2025-05-20 22:59:11', 0),
(529, 17, '/Booking-Hotel-Project/pages/images/hotels/hurghada-sunrise/2.jpg', 0, '2025-05-20 22:59:11', 0),
(530, 17, '/Booking-Hotel-Project/pages/images/hotels/hurghada-sunrise/3.jpg', 0, '2025-05-20 22:59:11', 0),
(531, 17, '/Booking-Hotel-Project/pages/images/hotels/hurghada-sunrise/4.jpg', 0, '2025-05-20 22:59:11', 0),
(532, 17, '/Booking-Hotel-Project/pages/images/hotels/hurghada-sunrise/5.jpg', 0, '2025-05-20 22:59:11', 0),
(533, 17, '/Booking-Hotel-Project/pages/images/hotels/hurghada-sunrise/6.jpg', 0, '2025-05-20 22:59:11', 0),
(534, 17, '/Booking-Hotel-Project/pages/images/hotels/hurghada-sunrise/7.jpg', 0, '2025-05-20 22:59:11', 0),
(535, 17, '/Booking-Hotel-Project/pages/images/hotels/hurghada-sunrise/8.jpg', 0, '2025-05-20 22:59:11', 0),
(536, 17, '/Booking-Hotel-Project/pages/images/hotels/hurghada-sunrise/9.jpg', 0, '2025-05-20 22:59:11', 0),
(537, 17, '/Booking-Hotel-Project/pages/images/hotels/hurghada-sunrise/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(538, 17, '/Booking-Hotel-Project/pages/images/hotels/hurghada-sunrise/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(539, 17, '/Booking-Hotel-Project/pages/images/hotels/hurghada-sunrise/room3.jpg', 0, '2025-05-20 22:59:11', 0),
(540, 18, '/Booking-Hotel-Project/pages/images/hotels/luxor-nile/1.jpg', 0, '2025-05-20 22:59:11', 0),
(541, 18, '/Booking-Hotel-Project/pages/images/hotels/luxor-nile/2.jpg', 0, '2025-05-20 22:59:11', 0),
(542, 18, '/Booking-Hotel-Project/pages/images/hotels/luxor-nile/3.jpg', 0, '2025-05-20 22:59:11', 0),
(543, 18, '/Booking-Hotel-Project/pages/images/hotels/luxor-nile/4.jpg', 0, '2025-05-20 22:59:11', 0),
(544, 18, '/Booking-Hotel-Project/pages/images/hotels/luxor-nile/5.jpg', 0, '2025-05-20 22:59:11', 0),
(545, 18, '/Booking-Hotel-Project/pages/images/hotels/luxor-nile/6.jpg', 0, '2025-05-20 22:59:11', 0),
(546, 18, '/Booking-Hotel-Project/pages/images/hotels/luxor-nile/7.jpg', 0, '2025-05-20 22:59:11', 0),
(547, 18, '/Booking-Hotel-Project/pages/images/hotels/luxor-nile/8.jpg', 0, '2025-05-20 22:59:11', 0),
(548, 18, '/Booking-Hotel-Project/pages/images/hotels/luxor-nile/9.jpg', 0, '2025-05-20 22:59:11', 0),
(549, 18, '/Booking-Hotel-Project/pages/images/hotels/luxor-nile/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(550, 18, '/Booking-Hotel-Project/pages/images/hotels/luxor-nile/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(551, 19, '/Booking-Hotel-Project/pages/images/hotels/aswan-cataract/1.jpg', 0, '2025-05-20 22:59:11', 0),
(552, 19, '/Booking-Hotel-Project/pages/images/hotels/aswan-cataract/2.jpg', 0, '2025-05-20 22:59:11', 0),
(553, 19, '/Booking-Hotel-Project/pages/images/hotels/aswan-cataract/3.jpg', 0, '2025-05-20 22:59:11', 0),
(554, 19, '/Booking-Hotel-Project/pages/images/hotels/aswan-cataract/4.jpg', 0, '2025-05-20 22:59:11', 0),
(555, 19, '/Booking-Hotel-Project/pages/images/hotels/aswan-cataract/5.jpg', 0, '2025-05-20 22:59:11', 0),
(556, 19, '/Booking-Hotel-Project/pages/images/hotels/aswan-cataract/6.jpg', 0, '2025-05-20 22:59:11', 0),
(557, 19, '/Booking-Hotel-Project/pages/images/hotels/aswan-cataract/7.jpg', 0, '2025-05-20 22:59:11', 0),
(558, 19, '/Booking-Hotel-Project/pages/images/hotels/aswan-cataract/8.jpg', 0, '2025-05-20 22:59:11', 0),
(559, 19, '/Booking-Hotel-Project/pages/images/hotels/aswan-cataract/9.jpg', 0, '2025-05-20 22:59:11', 0),
(560, 19, '/Booking-Hotel-Project/pages/images/hotels/aswan-cataract/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(561, 19, '/Booking-Hotel-Project/pages/images/hotels/aswan-cataract/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(562, 19, '/Booking-Hotel-Project/pages/images/hotels/aswan-cataract/room3.jpg', 0, '2025-05-20 22:59:11', 0),
(563, 20, '/Booking-Hotel-Project/pages/images/hotels/dahab-blue/1.jpg', 0, '2025-05-20 22:59:11', 0),
(564, 20, '/Booking-Hotel-Project/pages/images/hotels/dahab-blue/2.jpg', 0, '2025-05-20 22:59:11', 0),
(565, 20, '/Booking-Hotel-Project/pages/images/hotels/dahab-blue/3.jpg', 0, '2025-05-20 22:59:11', 0),
(566, 20, '/Booking-Hotel-Project/pages/images/hotels/dahab-blue/4.jpg', 0, '2025-05-20 22:59:11', 0),
(567, 20, '/Booking-Hotel-Project/pages/images/hotels/dahab-blue/5.jpg', 0, '2025-05-20 22:59:11', 0),
(568, 20, '/Booking-Hotel-Project/pages/images/hotels/dahab-blue/6.jpg', 0, '2025-05-20 22:59:11', 0),
(569, 20, '/Booking-Hotel-Project/pages/images/hotels/dahab-blue/7.jpg', 0, '2025-05-20 22:59:11', 1),
(570, 20, '/Booking-Hotel-Project/pages/images/hotels/dahab-blue/8.jpg', 0, '2025-05-20 22:59:11', 0),
(571, 20, '/Booking-Hotel-Project/pages/images/hotels/dahab-blue/9.jpg', 0, '2025-05-20 22:59:11', 0),
(572, 20, '/Booking-Hotel-Project/pages/images/hotels/dahab-blue/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(573, 20, '/Booking-Hotel-Project/pages/images/hotels/dahab-blue/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(574, 21, '/Booking-Hotel-Project/pages/images/hotels/cairo-nile/1.jpg', 0, '2025-05-20 22:59:11', 0),
(575, 21, '/Booking-Hotel-Project/pages/images/hotels/cairo-nile/2.jpg', 0, '2025-05-20 22:59:11', 0),
(576, 21, '/Booking-Hotel-Project/pages/images/hotels/cairo-nile/3.jpg', 0, '2025-05-20 22:59:11', 0),
(577, 21, '/Booking-Hotel-Project/pages/images/hotels/cairo-nile/4.jpg', 0, '2025-05-20 22:59:11', 0),
(578, 21, '/Booking-Hotel-Project/pages/images/hotels/cairo-nile/5.jpg', 0, '2025-05-20 22:59:11', 0),
(579, 21, '/Booking-Hotel-Project/pages/images/hotels/cairo-nile/6.jpg', 0, '2025-05-20 22:59:11', 0),
(580, 21, '/Booking-Hotel-Project/pages/images/hotels/cairo-nile/7.jpg', 0, '2025-05-20 22:59:11', 0),
(581, 21, '/Booking-Hotel-Project/pages/images/hotels/cairo-nile/8.jpg', 0, '2025-05-20 22:59:11', 0),
(582, 21, '/Booking-Hotel-Project/pages/images/hotels/cairo-nile/9.jpg', 0, '2025-05-20 22:59:11', 0),
(583, 21, '/Booking-Hotel-Project/pages/images/hotels/cairo-nile/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(584, 21, '/Booking-Hotel-Project/pages/images/hotels/cairo-nile/room3.jpg', 0, '2025-05-20 22:59:11', 0),
(585, 22, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-coral/1.jpg', 0, '2025-05-20 22:59:11', 0),
(586, 22, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-coral/2.jpg', 0, '2025-05-20 22:59:11', 0),
(587, 22, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-coral/3.jpg', 0, '2025-05-20 22:59:11', 0),
(588, 22, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-coral/4.jpg', 0, '2025-05-20 22:59:11', 0),
(589, 22, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-coral/5.jpg', 0, '2025-05-20 22:59:11', 0),
(590, 22, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-coral/6.jpg', 0, '2025-05-20 22:59:11', 0),
(591, 22, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-coral/7.jpg', 0, '2025-05-20 22:59:11', 0),
(592, 22, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-coral/8.jpg', 0, '2025-05-20 22:59:11', 0),
(593, 22, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-coral/9.jpg', 0, '2025-05-20 22:59:11', 0),
(594, 22, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-coral/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(595, 22, '/Booking-Hotel-Project/pages/images/hotels/marsa-alam-coral/room3.jpg', 0, '2025-05-20 22:59:11', 0),
(596, 23, '/Booking-Hotel-Project/pages/images/hotels/taba-sands/1.jpg', 0, '2025-05-20 22:59:11', 0),
(597, 23, '/Booking-Hotel-Project/pages/images/hotels/taba-sands/2.jpg', 0, '2025-05-20 22:59:11', 0),
(598, 23, '/Booking-Hotel-Project/pages/images/hotels/taba-sands/3.jpg', 0, '2025-05-20 22:59:11', 0),
(599, 23, '/Booking-Hotel-Project/pages/images/hotels/taba-sands/4.jpg', 0, '2025-05-20 22:59:11', 0),
(600, 23, '/Booking-Hotel-Project/pages/images/hotels/taba-sands/5.jpg', 0, '2025-05-20 22:59:11', 0),
(601, 23, '/Booking-Hotel-Project/pages/images/hotels/taba-sands/6.jpg', 0, '2025-05-20 22:59:11', 0),
(602, 23, '/Booking-Hotel-Project/pages/images/hotels/taba-sands/7.jpg', 0, '2025-05-20 22:59:11', 0),
(603, 23, '/Booking-Hotel-Project/pages/images/hotels/taba-sands/8.jpg', 0, '2025-05-20 22:59:11', 0),
(604, 23, '/Booking-Hotel-Project/pages/images/hotels/taba-sands/9.jpg', 0, '2025-05-20 22:59:11', 0),
(605, 23, '/Booking-Hotel-Project/pages/images/hotels/taba-sands/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(606, 23, '/Booking-Hotel-Project/pages/images/hotels/taba-sands/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(607, 23, '/Booking-Hotel-Project/pages/images/hotels/taba-sands/room3.jpg', 0, '2025-05-20 22:59:11', 0),
(608, 24, '/Booking-Hotel-Project/pages/images/hotels/fayoum-tunis/1.jpg', 0, '2025-05-20 22:59:11', 0),
(609, 24, '/Booking-Hotel-Project/pages/images/hotels/fayoum-tunis/2.jpg', 0, '2025-05-20 22:59:11', 0),
(610, 24, '/Booking-Hotel-Project/pages/images/hotels/fayoum-tunis/3.jpg', 0, '2025-05-20 22:59:11', 0),
(611, 24, '/Booking-Hotel-Project/pages/images/hotels/fayoum-tunis/4.jpg', 0, '2025-05-20 22:59:11', 0),
(612, 24, '/Booking-Hotel-Project/pages/images/hotels/fayoum-tunis/5.jpg', 0, '2025-05-20 22:59:11', 0),
(613, 24, '/Booking-Hotel-Project/pages/images/hotels/fayoum-tunis/6.jpg', 0, '2025-05-20 22:59:11', 0),
(614, 24, '/Booking-Hotel-Project/pages/images/hotels/fayoum-tunis/7.jpg', 0, '2025-05-20 22:59:11', 0),
(615, 24, '/Booking-Hotel-Project/pages/images/hotels/fayoum-tunis/8.jpg', 0, '2025-05-20 22:59:11', 0),
(616, 24, '/Booking-Hotel-Project/pages/images/hotels/fayoum-tunis/9.jpg', 0, '2025-05-20 22:59:11', 0),
(617, 24, '/Booking-Hotel-Project/pages/images/hotels/fayoum-tunis/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(618, 24, '/Booking-Hotel-Project/pages/images/hotels/fayoum-tunis/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(619, 25, '/Booking-Hotel-Project/pages/images/hotels/siwa-shali/1.jpg', 0, '2025-05-20 22:59:11', 0),
(620, 25, '/Booking-Hotel-Project/pages/images/hotels/siwa-shali/2.jpg', 0, '2025-05-20 22:59:11', 0),
(621, 25, '/Booking-Hotel-Project/pages/images/hotels/siwa-shali/3.jpg', 0, '2025-05-20 22:59:11', 0),
(622, 25, '/Booking-Hotel-Project/pages/images/hotels/siwa-shali/4.jpg', 0, '2025-05-20 22:59:11', 0),
(623, 25, '/Booking-Hotel-Project/pages/images/hotels/siwa-shali/5.jpg', 0, '2025-05-20 22:59:11', 0),
(624, 25, '/Booking-Hotel-Project/pages/images/hotels/siwa-shali/6.jpg', 0, '2025-05-20 22:59:11', 0),
(625, 25, '/Booking-Hotel-Project/pages/images/hotels/siwa-shali/7.jpg', 0, '2025-05-20 22:59:11', 0),
(626, 25, '/Booking-Hotel-Project/pages/images/hotels/siwa-shali/8.jpg', 0, '2025-05-20 22:59:11', 0),
(627, 25, '/Booking-Hotel-Project/pages/images/hotels/siwa-shali/9.jpg', 0, '2025-05-20 22:59:11', 0),
(628, 25, '/Booking-Hotel-Project/pages/images/hotels/siwa-shali/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(629, 25, '/Booking-Hotel-Project/pages/images/hotels/siwa-shali/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(630, 26, '/Booking-Hotel-Project/pages/images/hotels/alamein-marina/1.jpg', 0, '2025-05-20 22:59:11', 0),
(631, 26, '/Booking-Hotel-Project/pages/images/hotels/alamein-marina/2.jpg', 0, '2025-05-20 22:59:11', 0),
(632, 26, '/Booking-Hotel-Project/pages/images/hotels/alamein-marina/3.jpg', 0, '2025-05-20 22:59:11', 0),
(633, 26, '/Booking-Hotel-Project/pages/images/hotels/alamein-marina/4.jpg', 0, '2025-05-20 22:59:11', 0),
(634, 26, '/Booking-Hotel-Project/pages/images/hotels/alamein-marina/5.jpg', 0, '2025-05-20 22:59:11', 0),
(635, 26, '/Booking-Hotel-Project/pages/images/hotels/alamein-marina/6.jpg', 0, '2025-05-20 22:59:11', 0),
(636, 26, '/Booking-Hotel-Project/pages/images/hotels/alamein-marina/7.jpg', 0, '2025-05-20 22:59:11', 0),
(637, 26, '/Booking-Hotel-Project/pages/images/hotels/alamein-marina/8.jpg', 0, '2025-05-20 22:59:11', 0),
(638, 26, '/Booking-Hotel-Project/pages/images/hotels/alamein-marina/9.jpg', 0, '2025-05-20 22:59:11', 0),
(639, 26, '/Booking-Hotel-Project/pages/images/hotels/alamein-marina/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(640, 26, '/Booking-Hotel-Project/pages/images/hotels/alamein-marina/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(641, 26, '/Booking-Hotel-Project/pages/images/hotels/alamein-marina/room3.jpg', 0, '2025-05-20 22:59:11', 0),
(653, 28, '/Booking-Hotel-Project/pages/images/hotels/alex-royal/1.jpg', 0, '2025-05-20 22:59:11', 0),
(654, 28, '/Booking-Hotel-Project/pages/images/hotels/alex-royal/2.jpg', 0, '2025-05-20 22:59:11', 0),
(655, 28, '/Booking-Hotel-Project/pages/images/hotels/alex-royal/3.jpg', 0, '2025-05-20 22:59:11', 0),
(656, 28, '/Booking-Hotel-Project/pages/images/hotels/alex-royal/4.jpg', 0, '2025-05-20 22:59:11', 0),
(657, 28, '/Booking-Hotel-Project/pages/images/hotels/alex-royal/5.jpg', 0, '2025-05-20 22:59:11', 0),
(658, 28, '/Booking-Hotel-Project/pages/images/hotels/alex-royal/6.jpg', 0, '2025-05-20 22:59:11', 0),
(659, 28, '/Booking-Hotel-Project/pages/images/hotels/alex-royal/7.jpg', 0, '2025-05-20 22:59:11', 0),
(660, 28, '/Booking-Hotel-Project/pages/images/hotels/alex-royal/8.jpg', 0, '2025-05-20 22:59:11', 0),
(661, 28, '/Booking-Hotel-Project/pages/images/hotels/alex-royal/9.jpg', 0, '2025-05-20 22:59:11', 0),
(662, 28, '/Booking-Hotel-Project/pages/images/hotels/alex-royal/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(663, 28, '/Booking-Hotel-Project/pages/images/hotels/alex-royal/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(664, 29, '/Booking-Hotel-Project/pages/images/hotels/sharm-reef/1.jpg', 0, '2025-05-20 22:59:11', 0),
(665, 29, '/Booking-Hotel-Project/pages/images/hotels/sharm-reef/2.jpg', 0, '2025-05-20 22:59:11', 0),
(666, 29, '/Booking-Hotel-Project/pages/images/hotels/sharm-reef/3.jpg', 0, '2025-05-20 22:59:11', 0),
(667, 29, '/Booking-Hotel-Project/pages/images/hotels/sharm-reef/4.jpg', 0, '2025-05-20 22:59:11', 0),
(668, 29, '/Booking-Hotel-Project/pages/images/hotels/sharm-reef/5.jpg', 0, '2025-05-20 22:59:11', 0),
(669, 29, '/Booking-Hotel-Project/pages/images/hotels/sharm-reef/6.jpg', 0, '2025-05-20 22:59:11', 0),
(670, 29, '/Booking-Hotel-Project/pages/images/hotels/sharm-reef/7.jpg', 0, '2025-05-20 22:59:11', 0),
(671, 29, '/Booking-Hotel-Project/pages/images/hotels/sharm-reef/8.jpg', 0, '2025-05-20 22:59:11', 0),
(672, 29, '/Booking-Hotel-Project/pages/images/hotels/sharm-reef/9.jpg', 0, '2025-05-20 22:59:11', 0),
(673, 29, '/Booking-Hotel-Project/pages/images/hotels/sharm-reef/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(674, 29, '/Booking-Hotel-Project/pages/images/hotels/sharm-reef/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(675, 29, '/Booking-Hotel-Project/pages/images/hotels/sharm-reef/room3.jpg', 0, '2025-05-20 22:59:11', 0),
(676, 30, '/Booking-Hotel-Project/pages/images/hotels/hurghada-golden/1.jpg', 0, '2025-05-20 22:59:11', 0),
(677, 30, '/Booking-Hotel-Project/pages/images/hotels/hurghada-golden/2.jpg', 0, '2025-05-20 22:59:11', 0),
(678, 30, '/Booking-Hotel-Project/pages/images/hotels/hurghada-golden/3.jpg', 0, '2025-05-20 22:59:11', 0),
(679, 30, '/Booking-Hotel-Project/pages/images/hotels/hurghada-golden/4.jpg', 0, '2025-05-20 22:59:11', 0),
(680, 30, '/Booking-Hotel-Project/pages/images/hotels/hurghada-golden/5.jpg', 0, '2025-05-20 22:59:11', 0),
(681, 30, '/Booking-Hotel-Project/pages/images/hotels/hurghada-golden/6.jpg', 0, '2025-05-20 22:59:11', 0),
(682, 30, '/Booking-Hotel-Project/pages/images/hotels/hurghada-golden/7.jpg', 0, '2025-05-20 22:59:11', 0),
(683, 30, '/Booking-Hotel-Project/pages/images/hotels/hurghada-golden/8.jpg', 0, '2025-05-20 22:59:11', 0),
(684, 30, '/Booking-Hotel-Project/pages/images/hotels/hurghada-golden/9.jpg', 0, '2025-05-20 22:59:11', 0),
(685, 30, '/Booking-Hotel-Project/pages/images/hotels/hurghada-golden/room1.jpg', 0, '2025-05-20 22:59:11', 0),
(686, 30, '/Booking-Hotel-Project/pages/images/hotels/hurghada-golden/room2.jpg', 0, '2025-05-20 22:59:11', 0),
(687, 30, '/Booking-Hotel-Project/pages/images/hotels/hurghada-golden/room3.jpg', 0, '2025-05-20 22:59:11', 0);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_policies`
--

CREATE TABLE `hotel_policies` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('check-in','check-out','cancellation','payment','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_ratings`
--

CREATE TABLE `hotel_ratings` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_ratings`
--

INSERT INTO `hotel_ratings` (`id`, `hotel_id`, `user_id`, `rating`, `created_at`) VALUES
(1, 1, NULL, 4, '2025-05-20 09:44:37'),
(2, 8, NULL, 3, '2025-05-20 11:42:11'),
(3, 21, NULL, 3, '2025-05-20 12:54:38'),
(4, 27, NULL, 4, '2025-05-20 15:27:51'),
(5, 29, NULL, 4, '2025-05-20 16:22:53'),
(6, 29, NULL, 4, '2025-05-20 17:13:19'),
(7, 24, NULL, 4, '2025-05-20 21:29:31'),
(8, 26, NULL, 4, '2025-05-21 08:49:32'),
(9, 30, NULL, 4, '2025-05-21 17:19:22'),
(10, 18, NULL, 4, '2025-05-23 11:51:18'),
(11, 30, NULL, 4, '2025-05-24 11:40:55'),
(12, 19, NULL, 2, '2025-05-31 10:46:00'),
(13, 3, NULL, 4, '2025-06-02 09:25:51');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `used` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`, `used`) VALUES
(1, '01069787819mohamed@gmail.com', '534d4876643c5dbddb6cbef73caae667df46e8ec9c48cb56bc473247b1abe8a9', '2025-05-30 01:36:27', '2025-05-29 22:36:27', 0),
(2, '01069787819mohamed@gmail.com', '4a49345823f1cbc8042542771f0f68df18b38a4c6368921e41bc33549d0542bc', '2025-05-30 01:37:08', '2025-05-29 22:37:08', 0),
(3, '01069787819mohamed@gmail.com', '7bdbb8ee7cbcf3775869ecc0af122ea015a8d38ad005ec4e343ebc007a68aaa2', '2025-05-30 01:39:59', '2025-05-29 22:39:59', 0),
(4, '01069787819mohamed@gmail.com', '4735dd019d26b025eadbadf6362e7d5b1544371e8ccece9f2e8c31cc88246ac9', '2025-05-30 01:39:59', '2025-05-29 22:39:59', 0),
(5, '01069787819mohamed@gmail.com', 'a1d2429e5a327330d035c99aabb8790692b68840e42fa0afbdebac30111f8bfe', '2025-05-30 01:42:16', '2025-05-29 22:42:16', 0),
(6, '01069787819mohamed@gmail.com', 'b432705a620d44ee0955becb29d4fe1f24653fe3a31374c160316c8958a10541', '2025-05-30 01:44:15', '2025-05-29 22:44:15', 0),
(7, '01069787819mohamed@gmail.com', 'ee27173762b4e1bdb335ff510262af39b8d4f2ab225d6d5ed4891510dbdd50e0', '2025-05-30 01:54:16', '2025-05-29 22:54:16', 0),
(8, '01069787819mohamed@gmail.com', '615bc94bb6cffa2f4d4734c2ae42adbe9552e866cb42570e8dff21da34964de1', '2025-05-30 01:59:27', '2025-05-29 22:59:27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'manage_users', 'Create, edit, and delete users', '2025-05-22 16:51:30'),
(2, 'manage_roles', 'Manage user roles and permissions', '2025-05-22 16:51:30'),
(3, 'manage_hotels', 'Create, edit, and delete hotels', '2025-05-22 16:51:30'),
(4, 'manage_bookings', 'Manage hotel bookings', '2025-05-22 16:51:30'),
(5, 'view_reports', 'View system reports and statistics', '2025-05-22 16:51:30'),
(6, 'edit_content', 'Edit website content', '2025-05-22 16:51:30'),
(14, 'view_dashboard', 'Access to admin dashboard', '2025-05-22 23:02:35'),
(15, 'manage_settings', 'Modify system settings', '2025-05-22 23:02:35'),
(18, 'manage_gallery', 'Manage hotel and user gallery', '2025-05-23 12:50:09'),
(22, 'manage_reviews', 'Manage hotel reviews', '2025-05-26 15:37:55');

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
(2, 3, '247c5be1f40a87d7682bb85ee5c78ebcbc5f7fc8b05b723e26efc140c5777736', '2025-06-16 21:26:29', '2025-05-17 22:26:29'),
(4, 3, '189fec39530cb3384256c1dd31c65f693fda03203c8f617038d058571e7ba1a7', '2025-06-19 14:52:42', '2025-05-20 15:52:42'),
(5, 3, '8fee19423d091b5d62f2df61c29810fef9f1160bb7da709695428628ed474fa6', '2025-06-23 22:30:28', '2025-05-24 23:30:28'),
(6, 3, '4fdfb59292dd1c63a95382b9d216fd04e54a384c439d9123363a18b94f78de34', '2025-06-24 08:46:25', '2025-05-25 09:46:25'),
(7, 3, '61cec5df395c93bb9fbed2867566ffa11929882c621bea403964bd364b19603f', '2025-06-26 14:54:41', '2025-05-27 15:54:41'),
(8, 3, 'e5dffaf1963d28e4625c17ca3c0ac8808fe9a839f99c5de4f2e30d7218c9c5de', '2025-07-01 07:55:55', '2025-06-01 08:55:55');

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
  `approved` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `hotel_id`, `room_name`, `rating`, `comment`, `review_date`, `approved`, `created_at`) VALUES
(2, 3, 30, 'Deluxe Suite', 4, 'very good', '2025-05-21 16:32:47', 1, '2025-05-21 13:32:47'),
(3, 3, 30, 'Deluxe Suite', 3, 'good', '2025-05-21 19:34:33', 1, '2025-05-21 16:34:33'),
(4, 3, 30, 'Family Suite', 4, 'Hi Im Mohamed', '2025-05-30 00:23:26', 1, '2025-05-29 21:23:26'),
(5, 3, 10, 'Deluxe Suite', 5, 'Hi its great Hotel', '2025-05-30 00:28:12', 1, '2025-05-29 21:28:12'),
(6, 3, 24, 'Standard Room', 2, 'its not bad', '2025-05-30 00:31:00', 1, '2025-05-29 21:31:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'admin', 'Full system administrator', '2025-05-22 16:51:30'),
(2, 'manager', 'Hotel management and moderate user access', '2025-05-22 16:51:30'),
(3, 'editor', 'Basic content editing privileges', '2025-05-22 16:51:30'),
(24, 'staff', 'Basic staff access', '2025-05-22 23:00:49');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES
(1, 1, '2025-05-22 16:51:30'),
(1, 2, '2025-05-22 16:51:30'),
(1, 3, '2025-05-22 16:51:30'),
(1, 4, '2025-05-22 16:51:30'),
(1, 5, '2025-05-22 16:51:30'),
(1, 6, '2025-05-22 16:51:30'),
(1, 14, '2025-05-22 23:07:50'),
(1, 15, '2025-05-22 23:07:50'),
(1, 18, '2025-05-23 12:51:32'),
(1, 22, '2025-05-26 16:05:50'),
(2, 3, '2025-05-22 16:51:30'),
(2, 4, '2025-05-22 16:51:30'),
(2, 5, '2025-05-22 16:51:30'),
(2, 14, '2025-05-22 23:06:42'),
(2, 18, '2025-05-23 12:51:32'),
(3, 6, '2025-05-22 16:51:30'),
(3, 18, '2025-05-26 14:27:39'),
(24, 5, '2025-05-22 23:07:26'),
(24, 14, '2025-05-22 23:07:26');

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
  `max_guests` int(11) DEFAULT NULL,
  `room_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Standard Room',
  `bed_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'King Size',
  `room_size` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '40 m²',
  `amenities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `capacity` int(11) DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `hotel_id`, `name`, `description`, `image`, `price`, `max_guests`, `room_type`, `bed_type`, `room_size`, `amenities`, `created_at`, `capacity`) VALUES
(1, 1, 'Standard Room', 'Comfortable room with all essentials and a beautiful view.', 'images/hotels/ritz-cairo/room1.jpg', '600.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(2, 1, 'Deluxe Suite', 'Spacious suite with a separate living area and luxury amenities.', 'images/hotels/ritz-cairo/room2.jpg', '1200.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(3, 1, 'Panoramic Room', 'Room with panoramic views and premium comfort.', 'images/hotels/ritz-cairo/room3.jpeg', '1800.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(4, 2, 'Standard Room', 'Elegant room with sea view and modern amenities.', 'images/hotels/fourseasons-alex/room1.jpg', '1000.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(5, 2, 'Deluxe Suite', 'Spacious suite with private balcony and luxury features.', 'images/hotels/fourseasons-alex/room2.jpg', '2300.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(6, 2, 'Panoramic Room', 'Room with panoramic sea views and premium comfort.', 'images/hotels/fourseasons-alex/room3.jpeg', '3000.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(7, 3, 'Standard Room', 'Luxury room with balcony overlooking the Red Sea.', 'images/hotels/sharm-royal/room1.jpg', '800.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(8, 3, 'Deluxe Suite', 'Luxury suite with private pool and panoramic view.', 'images/hotels/sharm-royal/room2.jpg', '1400.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(9, 3, 'Family Room', 'Spacious room for families with extra beds and amenities.', 'images/hotels/sharm-royal/3.jpg', '1800.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(10, 4, 'Superior Room', 'Elegant room with balcony and sea view.', 'images/hotels/hurghada-beach/room1.jpg', '500.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(11, 4, 'Beachfront Suite', 'Luxury suite directly on the beach.', 'images/hotels/hurghada-beach/room2.jpg', '1000.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(12, 5, 'Standard Room', 'Elegant room with a view of the Nile.', 'images/hotels/luxor-palace/room1.jpg', '600.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(13, 5, 'Deluxe Suite', 'Luxury suite with balcony and panoramic view.', 'images/hotels/luxor-palace/room2.jpg', '1200.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(14, 5, 'Panoramic Room', 'Room with panoramic views and premium comfort.', 'images/hotels/luxor-palace/room3.jpg', '1800.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(15, 6, 'Standard Room', 'Elegant room with direct view of the Nile.', 'images/hotels/movenpick-aswan/room1.jpg', '800.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(16, 6, 'Deluxe Suite', 'Luxury suite with balcony and 360° view.', 'images/hotels/movenpick-aswan/room2.jpg', '1500.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(17, 6, 'Panoramic Room', 'Room with panoramic views and premium comfort.', 'images/hotels/movenpick-aswan/3.jpg', '2000.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(18, 7, 'Beach Chalet', 'Cozy chalet with direct sea view and balcony.', 'images/hotels/dahab-lodge/room1.jpg', '200.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(19, 7, 'Family Suite', 'Spacious suite with sitting area and large balcony.', 'images/hotels/dahab-lodge/room2.jpg', '400.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(20, 8, 'Standard Room', 'Elegant room with panoramic view of the Nile.', 'images/hotels/cairo-marriott/room1.jpg', '900.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(21, 8, 'Deluxe Suite', 'Luxury suite with lounge and premium view.', 'images/hotels/cairo-marriott/room2.jpg', '1700.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(22, 8, 'Panoramic Room', 'Room with panoramic views and premium comfort.', 'images/hotels/cairo-marriott/3.jpg', '2200.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(23, 9, 'Superior Lagoon Room', 'Room overlooking turquoise lagoon and gardens.', 'images/hotels/marsa-alam-blue/room1.jpg', '800.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(24, 9, 'Beachfront Suite', 'Luxury suite with private terrace overlooking the sea.', 'images/hotels/marsa-alam-blue/room2.jpg', '1500.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(25, 10, 'Standard Room', 'Elegant room with mountain and gulf view.', 'images/hotels/taba-heights/room1.jpg', '400.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(26, 10, 'Deluxe Suite', 'Spacious suite with 180° view of the gulf and mountains.', 'images/hotels/taba-heights/room2.jpg', '700.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(27, 10, 'Panoramic Room', 'Room with panoramic views and premium comfort.', 'images/hotels/taba-heights/room3.jpg', '1000.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(28, 11, 'Standard Room', 'Private chalet surrounded by gardens with lake view.', 'images/hotels/fayoum-desert/room1.jpg', '600.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(29, 11, 'Deluxe Suite', 'Luxury suite with terrace overlooking the desert and lake.', 'images/hotels/fayoum-desert/room2.jpg', '1100.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(30, 11, 'Panoramic Room', 'Room with panoramic views and premium comfort.', 'images/hotels/fayoum-desert/3.jpg', '1500.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(31, 12, 'Standard Room', 'Chalet built from local mud with oasis view.', 'images/hotels/siwa-eco/room1.jpg', '500.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(32, 12, 'Deluxe Suite', 'Luxury suite with terrace overlooking the oasis and gardens.', 'images/hotels/siwa-eco/room2.jpg', '800.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(33, 13, 'Mud Chalet', 'Chalet built from local mud with oasis view and terrace.', 'images/hotels/alamein-beach/room1.jpg', '450.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(34, 13, 'Oasis Suite', 'Luxury suite with terrace overlooking the oasis and gardens.', 'images/hotels/alamein-beach/room2.jpg', '1000.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(35, 13, 'Family Suite', 'Spacious suite perfect for families with oasis views.', 'images/hotels/alamein-beach/3.jpg', '1500.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(36, 14, 'Standard Room', 'Modern room with city view and essential amenities.', 'images/hotels/cairo-citystars/room1.jpg', '700.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(37, 14, 'Deluxe Suite', 'Spacious suite with separate living area and premium amenities.', 'images/hotels/cairo-citystars/room2.jpg', '1400.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(38, 14, 'Executive Suite', 'Luxury suite with panoramic city views and premium services.', 'images/hotels/cairo-citystars/room3.jpg', '2000.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(39, 15, 'Standard Room', 'Elegant room with sea view and modern amenities.', 'images/hotels/alex-seaview/room1.jpg', '500.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(40, 15, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/alex-seaview/room2.jpg', '1000.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(42, 16, 'Standard Room', 'Comfortable room with Red Sea view.', 'images/hotels/sharm-reef/room1.jpg', '1100.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(43, 16, 'Deluxe Suite', 'Luxury suite with private balcony and sea view.', 'images/hotels/sharm-reef/room2.jpg', '2000.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(44, 16, 'Family Suite', 'Spacious suite with separate bedrooms and sea view.', 'images/hotels/sharm-reef/room3.jpg', '3000.00', 6, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(45, 17, 'Standard Room', 'Modern room with sea view and essential amenities.', 'images/hotels/hurghada-sunrise/room1.jpg', '750.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(46, 17, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/hurghada-sunrise/room2.jpg', '1500.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(47, 17, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/hurghada-sunrise/room3.jpg', '2000.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(48, 18, 'Standard Room', 'Comfortable room with Nile view.', 'images/hotels/luxor-nile/room1.jpg', '400.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(49, 18, 'Deluxe Suite', 'Spacious suite with private balcony and Nile view.', 'images/hotels/luxor-nile/room2.jpg', '800.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(51, 19, 'Standard Room', 'Elegant room with Nile view and premium amenities.', 'images/hotels/aswan-cataract/room1.jpg', '1000.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(52, 19, 'Deluxe Suite', 'Luxury suite with private balcony and panoramic Nile view.', 'images/hotels/aswan-cataract/room2.jpg', '2000.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(53, 19, 'Royal Suite', 'Exclusive suite with separate living area and premium services.', 'images/hotels/aswan-cataract/room3.jpg', '3000.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(54, 20, 'Standard Room', 'Cozy room with sea view and essential amenities.', 'images/hotels/dahab-blue/room1.jpg', '1100.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(55, 20, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/dahab-blue/room2.jpg', '2000.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(58, 21, 'Deluxe Suite', 'Spacious suite with separate living area.', 'images/hotels/cairo-nile/room2.jpg', '400.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(59, 21, 'Family Suite', 'Large suite perfect for families.', 'images/hotels/cairo-nile/room3.jpg', '600.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(61, 22, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/marsa-alam-coral/room2.jpg', '500.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(62, 22, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/marsa-alam-coral/room3.jpg', '750.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(63, 23, 'Standard Room', 'Modern room with sea view and essential amenities.', 'images/hotels/taba-sands/room1.jpg', '600.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(64, 23, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/taba-sands/room2.jpg', '1200.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(65, 23, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/taba-sands/room3.jpg', '1800.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(66, 24, 'Standard Room', 'Cozy room with lake view.', 'images/hotels/fayoum-tunis/room1.jpg', '200.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(67, 24, 'Deluxe Suite', 'Spacious suite with private balcony and lake view.', 'images/hotels/fayoum-tunis/room2.jpg', '400.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(69, 25, 'Standard Room', 'Traditional room with oasis view.', 'images/hotels/siwa-shali/room1.jpg', '450.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(70, 25, 'Deluxe Suite', 'Spacious suite with private terrace and oasis view.', 'images/hotels/siwa-shali/room2.jpg', '900.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(72, 26, 'Standard Room', 'Modern room with sea view and essential amenities.', 'images/hotels/alamein-marina/room1.jpg', '900.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(73, 26, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/alamein-marina/room2.jpg', '1800.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(74, 26, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/alamein-marina/room3.jpg', '2700.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(78, 28, 'Standard Room', 'Modern room with sea view and essential amenities.', 'images/hotels/alex-royal/room1.jpg', '400.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(81, 29, 'Standard Room', 'Comfortable room with sea view.', 'images/hotels/sharm-reef/room1.jpg', '800.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(82, 29, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/sharm-reef/room2.jpg', '1600.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(83, 29, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/sharm-reef/room3.jpg', '2400.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(84, 30, 'Standard Room', 'Modern room with sea view and essential amenities.', 'images/hotels/hurghada-golden/room1.jpg', '700.00', 2, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(85, 30, 'Deluxe Suite', 'Spacious suite with private balcony and sea view.', 'images/hotels/hurghada-golden/room2.jpg', '1400.00', 3, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2),
(86, 30, 'Family Suite', 'Large suite perfect for families with sea views.', 'images/hotels/hurghada-golden/room3.jpg', '2100.00', 4, 'Standard Room', 'King Size', '40 m²', NULL, '2025-05-20 22:11:17', 2);

-- --------------------------------------------------------

--
-- Table structure for table `room_features`
--

CREATE TABLE `room_features` (
  `room_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_features`
--

INSERT INTO `room_features` (`room_id`, `feature_id`) VALUES
(1, 2),
(1, 4),
(1, 6),
(1, 7),
(1, 10),
(1, 18),
(1, 19),
(1, 20),
(2, 1),
(2, 4),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 17),
(2, 18),
(2, 19),
(2, 20),
(3, 1),
(3, 4),
(3, 6),
(3, 7),
(3, 8),
(3, 9),
(3, 10),
(3, 11),
(3, 12),
(3, 14),
(3, 17),
(3, 18),
(3, 19),
(3, 20),
(4, 2),
(4, 4),
(4, 6),
(4, 7),
(4, 10),
(4, 18),
(4, 19),
(4, 20),
(5, 1),
(5, 4),
(5, 6),
(5, 7),
(5, 8),
(5, 9),
(5, 10),
(5, 11),
(5, 12),
(5, 17),
(5, 18),
(5, 19),
(5, 20),
(6, 1),
(6, 4),
(6, 6),
(6, 7),
(6, 8),
(6, 9),
(6, 10),
(6, 11),
(6, 12),
(6, 14),
(6, 17),
(6, 18),
(6, 19),
(6, 20),
(7, 2),
(7, 4),
(7, 6),
(7, 7),
(7, 10),
(7, 18),
(7, 19),
(7, 20),
(8, 1),
(8, 4),
(8, 6),
(8, 7),
(8, 8),
(8, 9),
(8, 10),
(8, 11),
(8, 12),
(8, 17),
(8, 18),
(8, 19),
(8, 20),
(11, 1),
(11, 4),
(11, 6),
(11, 7),
(11, 8),
(11, 9),
(11, 10),
(11, 11),
(11, 12),
(11, 13),
(11, 17),
(11, 18),
(11, 19),
(11, 20),
(12, 2),
(12, 4),
(12, 6),
(12, 7),
(12, 10),
(12, 18),
(12, 19),
(12, 20),
(13, 1),
(13, 4),
(13, 6),
(13, 7),
(13, 8),
(13, 9),
(13, 10),
(13, 11),
(13, 12),
(13, 17),
(13, 18),
(13, 19),
(13, 20),
(14, 1),
(14, 4),
(14, 6),
(14, 7),
(14, 8),
(14, 9),
(14, 10),
(14, 11),
(14, 12),
(14, 14),
(14, 17),
(14, 18),
(14, 19),
(14, 20),
(15, 2),
(15, 4),
(15, 6),
(15, 7),
(15, 10),
(15, 18),
(15, 19),
(15, 20),
(16, 1),
(16, 4),
(16, 6),
(16, 7),
(16, 8),
(16, 9),
(16, 10),
(16, 11),
(16, 12),
(16, 17),
(16, 18),
(16, 19),
(16, 20),
(17, 1),
(17, 4),
(17, 6),
(17, 7),
(17, 8),
(17, 9),
(17, 10),
(17, 11),
(17, 12),
(17, 14),
(17, 17),
(17, 18),
(17, 19),
(17, 20),
(18, 1),
(18, 4),
(18, 6),
(18, 7),
(18, 8),
(18, 9),
(18, 10),
(18, 11),
(18, 12),
(18, 13),
(18, 17),
(18, 18),
(18, 19),
(18, 20),
(19, 1),
(19, 4),
(19, 6),
(19, 7),
(19, 8),
(19, 9),
(19, 10),
(19, 11),
(19, 12),
(19, 17),
(19, 18),
(19, 19),
(19, 20),
(20, 2),
(20, 4),
(20, 6),
(20, 7),
(20, 10),
(20, 18),
(20, 19),
(20, 20),
(21, 1),
(21, 4),
(21, 6),
(21, 7),
(21, 8),
(21, 9),
(21, 10),
(21, 11),
(21, 12),
(21, 17),
(21, 18),
(21, 19),
(21, 20),
(22, 1),
(22, 4),
(22, 6),
(22, 7),
(22, 8),
(22, 9),
(22, 10),
(22, 11),
(22, 12),
(22, 14),
(22, 17),
(22, 18),
(22, 19),
(22, 20),
(24, 1),
(24, 4),
(24, 6),
(24, 7),
(24, 8),
(24, 9),
(24, 10),
(24, 11),
(24, 12),
(24, 13),
(24, 17),
(24, 18),
(24, 19),
(24, 20),
(25, 2),
(25, 4),
(25, 6),
(25, 7),
(25, 10),
(25, 18),
(25, 19),
(25, 20),
(26, 1),
(26, 4),
(26, 6),
(26, 7),
(26, 8),
(26, 9),
(26, 10),
(26, 11),
(26, 12),
(26, 17),
(26, 18),
(26, 19),
(26, 20),
(27, 1),
(27, 4),
(27, 6),
(27, 7),
(27, 8),
(27, 9),
(27, 10),
(27, 11),
(27, 12),
(27, 14),
(27, 17),
(27, 18),
(27, 19),
(27, 20),
(28, 2),
(28, 4),
(28, 6),
(28, 7),
(28, 10),
(28, 18),
(28, 19),
(28, 20),
(29, 1),
(29, 4),
(29, 6),
(29, 7),
(29, 8),
(29, 9),
(29, 10),
(29, 11),
(29, 12),
(29, 17),
(29, 18),
(29, 19),
(29, 20),
(30, 1),
(30, 4),
(30, 6),
(30, 7),
(30, 8),
(30, 9),
(30, 10),
(30, 11),
(30, 12),
(30, 14),
(30, 17),
(30, 18),
(30, 19),
(30, 20),
(31, 2),
(31, 4),
(31, 6),
(31, 7),
(31, 10),
(31, 18),
(31, 19),
(31, 20),
(32, 1),
(32, 4),
(32, 6),
(32, 7),
(32, 8),
(32, 9),
(32, 10),
(32, 11),
(32, 12),
(32, 17),
(32, 18),
(32, 19),
(32, 20),
(34, 1),
(34, 4),
(34, 6),
(34, 7),
(34, 8),
(34, 9),
(34, 10),
(34, 11),
(34, 12),
(34, 17),
(34, 18),
(34, 19),
(34, 20),
(35, 1),
(35, 4),
(35, 6),
(35, 7),
(35, 8),
(35, 9),
(35, 10),
(35, 11),
(35, 12),
(35, 17),
(35, 18),
(35, 19),
(35, 20),
(36, 2),
(36, 4),
(36, 6),
(36, 7),
(36, 10),
(36, 18),
(36, 19),
(36, 20),
(37, 1),
(37, 4),
(37, 6),
(37, 7),
(37, 8),
(37, 9),
(37, 10),
(37, 11),
(37, 12),
(37, 17),
(37, 18),
(37, 19),
(37, 20),
(38, 1),
(38, 4),
(38, 6),
(38, 7),
(38, 8),
(38, 9),
(38, 10),
(38, 11),
(38, 12),
(38, 17),
(38, 18),
(38, 19),
(38, 20),
(39, 2),
(39, 4),
(39, 6),
(39, 7),
(39, 10),
(39, 18),
(39, 19),
(39, 20),
(40, 1),
(40, 4),
(40, 6),
(40, 7),
(40, 8),
(40, 9),
(40, 10),
(40, 11),
(40, 12),
(40, 17),
(40, 18),
(40, 19),
(40, 20),
(42, 2),
(42, 4),
(42, 6),
(42, 7),
(42, 10),
(42, 18),
(42, 19),
(42, 20),
(43, 1),
(43, 4),
(43, 6),
(43, 7),
(43, 8),
(43, 9),
(43, 10),
(43, 11),
(43, 12),
(43, 17),
(43, 18),
(43, 19),
(43, 20),
(44, 1),
(44, 4),
(44, 6),
(44, 7),
(44, 8),
(44, 9),
(44, 10),
(44, 11),
(44, 12),
(44, 17),
(44, 18),
(44, 19),
(44, 20),
(45, 2),
(45, 4),
(45, 6),
(45, 7),
(45, 10),
(45, 18),
(45, 19),
(45, 20),
(46, 1),
(46, 4),
(46, 6),
(46, 7),
(46, 8),
(46, 9),
(46, 10),
(46, 11),
(46, 12),
(46, 17),
(46, 18),
(46, 19),
(46, 20),
(47, 1),
(47, 4),
(47, 6),
(47, 7),
(47, 8),
(47, 9),
(47, 10),
(47, 11),
(47, 12),
(47, 17),
(47, 18),
(47, 19),
(47, 20),
(48, 2),
(48, 4),
(48, 6),
(48, 7),
(48, 10),
(48, 18),
(48, 19),
(48, 20),
(49, 1),
(49, 4),
(49, 6),
(49, 7),
(49, 8),
(49, 9),
(49, 10),
(49, 11),
(49, 12),
(49, 17),
(49, 18),
(49, 19),
(49, 20),
(51, 2),
(51, 4),
(51, 6),
(51, 7),
(51, 10),
(51, 18),
(51, 19),
(51, 20),
(52, 1),
(52, 4),
(52, 6),
(52, 7),
(52, 8),
(52, 9),
(52, 10),
(52, 11),
(52, 12),
(52, 17),
(52, 18),
(52, 19),
(52, 20),
(53, 1),
(53, 4),
(53, 6),
(53, 7),
(53, 8),
(53, 9),
(53, 10),
(53, 11),
(53, 12),
(53, 17),
(53, 18),
(53, 19),
(53, 20),
(54, 2),
(54, 4),
(54, 6),
(54, 7),
(54, 10),
(54, 18),
(54, 19),
(54, 20),
(55, 1),
(55, 4),
(55, 6),
(55, 7),
(55, 8),
(55, 9),
(55, 10),
(55, 11),
(55, 12),
(55, 17),
(55, 18),
(55, 19),
(55, 20),
(58, 1),
(58, 4),
(58, 6),
(58, 7),
(58, 8),
(58, 9),
(58, 10),
(58, 11),
(58, 12),
(58, 17),
(58, 18),
(58, 19),
(58, 20),
(59, 1),
(59, 4),
(59, 6),
(59, 7),
(59, 8),
(59, 9),
(59, 10),
(59, 11),
(59, 12),
(59, 17),
(59, 18),
(59, 19),
(59, 20),
(61, 1),
(61, 4),
(61, 6),
(61, 7),
(61, 8),
(61, 9),
(61, 10),
(61, 11),
(61, 12),
(61, 17),
(61, 18),
(61, 19),
(61, 20),
(62, 1),
(62, 4),
(62, 6),
(62, 7),
(62, 8),
(62, 9),
(62, 10),
(62, 11),
(62, 12),
(62, 17),
(62, 18),
(62, 19),
(62, 20),
(63, 2),
(63, 4),
(63, 6),
(63, 7),
(63, 10),
(63, 18),
(63, 19),
(63, 20),
(64, 1),
(64, 4),
(64, 6),
(64, 7),
(64, 8),
(64, 9),
(64, 10),
(64, 11),
(64, 12),
(64, 17),
(64, 18),
(64, 19),
(64, 20),
(65, 1),
(65, 4),
(65, 6),
(65, 7),
(65, 8),
(65, 9),
(65, 10),
(65, 11),
(65, 12),
(65, 17),
(65, 18),
(65, 19),
(65, 20),
(66, 2),
(66, 4),
(66, 6),
(66, 7),
(66, 10),
(66, 18),
(66, 19),
(66, 20),
(67, 1),
(67, 4),
(67, 6),
(67, 7),
(67, 8),
(67, 9),
(67, 10),
(67, 11),
(67, 12),
(67, 17),
(67, 18),
(67, 19),
(67, 20),
(69, 2),
(69, 4),
(69, 6),
(69, 7),
(69, 10),
(69, 18),
(69, 19),
(69, 20),
(70, 1),
(70, 4),
(70, 6),
(70, 7),
(70, 8),
(70, 9),
(70, 10),
(70, 11),
(70, 12),
(70, 17),
(70, 18),
(70, 19),
(70, 20),
(72, 2),
(72, 4),
(72, 6),
(72, 7),
(72, 10),
(72, 18),
(72, 19),
(72, 20),
(73, 1),
(73, 4),
(73, 6),
(73, 7),
(73, 8),
(73, 9),
(73, 10),
(73, 11),
(73, 12),
(73, 17),
(73, 18),
(73, 19),
(73, 20),
(74, 1),
(74, 4),
(74, 6),
(74, 7),
(74, 8),
(74, 9),
(74, 10),
(74, 11),
(74, 12),
(74, 17),
(74, 18),
(74, 19),
(74, 20),
(78, 2),
(78, 4),
(78, 6),
(78, 7),
(78, 10),
(78, 18),
(78, 19),
(78, 20),
(81, 2),
(81, 4),
(81, 6),
(81, 7),
(81, 10),
(81, 18),
(81, 19),
(81, 20),
(82, 1),
(82, 4),
(82, 6),
(82, 7),
(82, 8),
(82, 9),
(82, 10),
(82, 11),
(82, 12),
(82, 17),
(82, 18),
(82, 19),
(82, 20),
(83, 1),
(83, 4),
(83, 6),
(83, 7),
(83, 8),
(83, 9),
(83, 10),
(83, 11),
(83, 12),
(83, 17),
(83, 18),
(83, 19),
(83, 20),
(84, 2),
(84, 4),
(84, 6),
(84, 7),
(84, 10),
(84, 18),
(84, 19),
(84, 20),
(85, 1),
(85, 4),
(85, 6),
(85, 7),
(85, 8),
(85, 9),
(85, 10),
(85, 11),
(85, 12),
(85, 17),
(85, 18),
(85, 19),
(85, 20),
(86, 1),
(86, 4),
(86, 6),
(86, 7),
(86, 8),
(86, 9),
(86, 10),
(86, 11),
(86, 12),
(86, 17),
(86, 18),
(86, 19),
(86, 20);

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
  `is_active` tinyint(1) DEFAULT 1,
  `status` enum('active','disabled') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skills` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `name`, `email`, `phone`, `date_of_birth`, `gender`, `address`, `profile_image`, `email_notifications`, `sms_notifications`, `marketing_emails`, `password`, `created_at`, `updated_at`, `is_active`, `status`, `last_login`, `cover_image`, `facebook_url`, `twitter_url`, `instagram_url`, `linkedin_url`, `website_url`, `skills`, `bio`) VALUES
(3, 'mhmd_rmdn_1', 'Mohamed R', 'Ibrahim', 'Mohamed R Ibrahim', '01069787819mohammed@gmail.com', '01069787819', '2003-09-16', 'male', 'banisuef', 'assets/images/profiles/profiles_3_1748822706.jpg', 1, 1, 0, '$2y$10$mfNdGhWzX.jr2OA9fDCQ3unr0yX.CXQjq86.b.LSo4n1g0XPgNgPq', '2025-05-12 13:41:43', '2025-06-02 08:42:35', 1, 'active', '2025-06-02 07:42:35', 'assets/images/profiles/covers_3_1748822706.jpg', '', '', '', '', '', '', 'جيت متأخر وبقيت الأول'),
(39, 'emk4', NULL, NULL, 'Mohamed Ramdan', 'emk422003@gmail.com', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '$2y$10$4yqFJenJncqPNB573wYsxeEDuUZll7y/QNznvvk/SKytb1AwGH6Yy', '2025-05-26 08:37:57', '2025-05-26 08:37:57', 1, 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 'mhmd_rmdn', 'M', 'R', 'Egypt Hotels', '01069787819mohamed@gmail.com', '01069787819', '0000-00-00', 'male', 'banisuef', 'assets/images/profiles/profile_43_1748559739.jpg', 1, 1, 0, '$2y$10$mFM3w3ZPBNvF7zNZ.WCIh.wFWrHaACAXyAX90hbf.qOo8N.6r4GB2', '2025-05-26 14:08:33', '2025-05-29 23:02:19', 1, 'active', NULL, 'assets/images/covers/cover_43_1748559739.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 'mr_123', NULL, NULL, 'Mohamed Ramdan', 'mr1692003@gmail.com', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '$2y$10$awr.Zk1PmBMW6cPMD6mYDOvdoOviAt/77VPnRMHaX7uV4DXqhRRMu', '2025-05-26 14:25:34', '2025-05-26 14:25:34', 1, 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 'hazemy14', 'Hazem', 'Yahia', 'Hazem Yahia', 'hazamyahya85@gmail.com', '+201143949700', '2003-05-01', 'male', 'banisuef', 'assets/images/profiles/profile_46_1748612099.jpg', 1, 1, 0, '$2y$10$vvkHsUVdoCvrhgrQuq3mkOtkCE6TK3rB.as3A2/.NSo6SEADgcrue', '2025-05-30 13:25:18', '2025-05-30 13:35:00', 1, 'active', NULL, 'assets/images/covers/cover_46_1748612099.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 'ezatahmed1', NULL, NULL, 'Ezat Ahmed', 'ezatahmed123@gmail.com', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '$2y$10$a9IZQ8yh4OixKW6dpq8Gz.gR3HFisA8SFFs.r7W2.j0WgjukIfJqu', '2025-05-30 13:38:49', '2025-05-30 13:38:49', 1, 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
(33, 3, '683ce9623f30a_hotel2.jpg', 'view\r\n', 0, '2025-06-01 23:59:30'),
(34, 3, '683ce98401909_hotel3.jpg', 'hi\r\n', 0, '2025-06-02 00:00:04'),
(35, 3, '683cec103747f_EREN JAEGER WALLPAPER_LE_upscale_balanced_x4.jpg', 'hi', 2, '2025-06-02 00:10:56');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`, `created_at`) VALUES
(3, 1, '2025-05-24 17:14:05'),
(39, 24, '2025-05-26 10:44:42'),
(43, 2, '2025-05-26 14:08:33'),
(44, 3, '2025-05-26 14:25:34');

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
  ADD KEY `idx_bookings_email` (`email`),
  ADD KEY `idx_bookings_cancelled` (`cancelled`),
  ADD KEY `fk_bookings_hotel` (`hotel_id`),
  ADD KEY `fk_bookings_room` (`room_id`),
  ADD KEY `fk_bookings_user` (`user_id`);

--
-- Indexes for table `booking_notes`
--
ALTER TABLE `booking_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `created_at`
--
ALTER TABLE `created_at`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_hotel_category` (`category_id`);

--
-- Indexes for table `hotel_amenities`
--
ALTER TABLE `hotel_amenities`
  ADD PRIMARY KEY (`hotel_id`,`amenity_id`),
  ADD KEY `amenity_id` (`amenity_id`);

--
-- Indexes for table `hotel_categories`
--
ALTER TABLE `hotel_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_features`
--
ALTER TABLE `hotel_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `hotel_gallery`
--
ALTER TABLE `hotel_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `hotel_images`
--
ALTER TABLE `hotel_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_hotel_images_hotel_id` (`hotel_id`),
  ADD KEY `idx_hotel_images_is_featured` (`is_featured`),
  ADD KEY `idx_hotel_featured` (`hotel_id`,`is_featured`);

--
-- Indexes for table `hotel_policies`
--
ALTER TABLE `hotel_policies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `hotel_ratings`
--
ALTER TABLE `hotel_ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

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
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `room_features`
--
ALTER TABLE `room_features`
  ADD PRIMARY KEY (`room_id`,`feature_id`),
  ADD KEY `feature_id` (`feature_id`);

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
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`user_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `booking_notes`
--
ALTER TABLE `booking_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `created_at`
--
ALTER TABLE `created_at`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `hotel_categories`
--
ALTER TABLE `hotel_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hotel_features`
--
ALTER TABLE `hotel_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotel_gallery`
--
ALTER TABLE `hotel_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hotel_images`
--
ALTER TABLE `hotel_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=689;

--
-- AUTO_INCREMENT for table `hotel_policies`
--
ALTER TABLE `hotel_policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotel_ratings`
--
ALTER TABLE `hotel_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `policies`
--
ALTER TABLE `policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `user_gallery`
--
ALTER TABLE `user_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_notes`
--
ALTER TABLE `booking_notes`
  ADD CONSTRAINT `booking_notes_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_notes_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `created_at`
--
ALTER TABLE `created_at`
  ADD CONSTRAINT `created_at_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`),
  ADD CONSTRAINT `created_at_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `fk_hotel_category` FOREIGN KEY (`category_id`) REFERENCES `hotel_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hotel_amenities`
--
ALTER TABLE `hotel_amenities`
  ADD CONSTRAINT `hotel_amenities_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotel_amenities_ibfk_2` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_features`
--
ALTER TABLE `hotel_features`
  ADD CONSTRAINT `hotel_features_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_gallery`
--
ALTER TABLE `hotel_gallery`
  ADD CONSTRAINT `hotel_gallery_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_images`
--
ALTER TABLE `hotel_images`
  ADD CONSTRAINT `hotel_images_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_policies`
--
ALTER TABLE `hotel_policies`
  ADD CONSTRAINT `hotel_policies_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_features`
--
ALTER TABLE `room_features`
  ADD CONSTRAINT `room_features_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_features_ibfk_2` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `user_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
