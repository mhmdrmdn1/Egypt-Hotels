<?php session_start(); ?>
<?php
$first_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
$last_name = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';
$email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
$phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : '';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egypt Hotels | Book Your Stay</title>
    <link rel="shortcut icon" href="../assets/images/icons/web-icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/book.css">
    <link rel="stylesheet" href="../assets/css/images.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <nav class="navbar container">
            <a href="index.php" class="logo">
                <img src="../pages/images/Logo.png" alt="Egypt Hotels Logo" class="logo-img">
            </a>
            <ul class="nav-links">
                <li><a href="explore.php">Explore</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            <div class="nav-icons">
                <div class="nav-icon" id="openSidebarBtn" tabindex="0">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </nav>
    </header>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <aside class="sidebar-menu" id="sidebarMenu">
        <button class="sidebar-close" id="closeSidebarBtn" aria-label="Close Menu"><i class="fas fa-times"></i></button>
        <?php if(isset($_SESSION['user_id'])): ?>
        <div class="user-profile">
            <div class="user-info">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="profile-image-container">
                        <?php
                            $profile_image_path = isset($_SESSION['user_profile_image']) && !empty($_SESSION['user_profile_image']) 
                                ? (strpos($_SESSION['user_profile_image'], '../') === 0 ? htmlspecialchars($_SESSION['user_profile_image']) : '../' . htmlspecialchars($_SESSION['user_profile_image'])) 
                                : '../images/default-avatar.png';
                        ?>
                        <img src="<?php echo $profile_image_path; ?>" alt="Profile Image" class="profile-image">
                    </div>
                    <span class="username">
                        <h3><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'User'; ?></h3>
                        <p class="user-email"><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?></p>
                    </span>
                <?php endif; ?>
            </div>
            <div class="profile-actions">
                    <a href="profile.php" class="profile-link"><i class="fas fa-user"></i> My Profile</a>
            </div>
        </div>
        <hr class="sidebar-divider">
        <?php endif; ?>
        <nav class="sidebar-links">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="explore.php"><i class="fas fa-compass"></i> Explore</a>
            <a href="gallery.php"><i class="fas fa-images"></i> Gallery</a>
            <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
            <a href="contact.php"><i class="fas fa-envelope"></i> Contact</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <hr class="sidebar-divider">
            <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                <a href="admin_dashboard/index.php"><i class="fas fa-user-shield"></i>Dashboard</a>
            <?php endif; ?>
                <a href="login/logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <hr class="sidebar-divider">
                <a href="login/login.html" class="login-link"><i class="fas fa-sign-in-alt"></i> Login</a>
            <?php endif; ?>
        </nav>
    </aside>

    <section class="booking-form">
        <div class="form-container">
            <div id="booking-message" style="display:none; margin-bottom: 15px;"></div>
            <div class="form-content">
                <h2 data-aos="fade-up">Complete Your Booking</h2>
                <form id="booking-form" data-aos="fade-up" data-aos-delay="100">
                    <div class="form-section">
                        <h3>Personal Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="first-name">First Name</label>
                                <input type="text" id="first-name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="last-name">Last Name</label>
                                <input type="text" id="last-name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="check-in">Check-in Date</label>
                                <input type="date" id="check-in" name="check_in" required>
                            </div>
                            <div class="form-group">
                                <label for="check-out">Check-out Date</label>
                                <input type="date" id="check-out" name="check_out" required>
                            </div>
                            <div class="form-group">
                                <label for="guests">Number of Guests</label>
                                <input type="number" id="guests" name="guests" min="1" value="1" required>
                            </div>
                            <div class="form-group">
                                <label for="rooms">Number of Rooms</label>
                                <input type="number" id="rooms" name="rooms" min="1" value="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Special Requests</h3>
                        <div class="form-group">
                            <textarea id="requests" name="special_requests" placeholder="Any special requests or requirements?"></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Payment Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="card-name">Name on Card</label>
                                <input type="text" id="card-name" name="card_name" required>
                            </div>
                            <div class="form-group">
                                <label for="card-number">Card Number</label>
                                <input type="text" id="card-number" name="card_number" required>
                            </div>
                            <div class="form-group">
                                <label for="expiry">Expiry Date</label>
                                <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" name="cvv" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="terms-group">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">I agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a></label>
                        </div>
                    </div>

                    <input type="hidden" name="hotel_id" id="hotel_id" value="">
                    <input type="hidden" name="room_id" id="room_id" value="">
                    <input type="hidden" name="total_price" id="total_price" value="">
                    <input type="hidden" name="hotel_name" id="hotel_name" value="">
                    <input type="hidden" name="room_name" id="room_name" value="">
                    <button type="submit" class="submit-btn">Complete Booking</button>
                </form>
            </div>

            <div class="booking-summary" data-aos="fade-left">
                <h3>Booking Summary</h3>
                <div class="summary-content">
                    <div class="summary-item">
                        <span>Hotel:</span>
                        <span class="hotel-name">-</span>
                    </div>
                    <div class="summary-item">
                        <span>Room Type:</span>
                        <span class="room-type">-</span>
                    </div>
                    <div class="summary-item">
                        <span>Check-in:</span>
                        <span class="check-in">-</span>
                    </div>
                    <div class="summary-item">
                        <span>Check-out:</span>
                        <span class="check-out">-</span>
                    </div>
                    <div class="summary-item">
                        <span>Guests:</span>
                        <span class="guests">-</span>
                    </div>
                    <div class="summary-item">
                        <span>Rooms:</span>
                        <span class="rooms">-</span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-item total">
                        <span>Total:</span>
                        <span class="total-price">0 EGP</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="booking-benefits">
        <div class="benefits-container">
            <h2 data-aos="fade-up">Why Book With Us?</h2>
            <div class="benefits-grid">
                <div class="benefit-card" data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Secure Booking</h3>
                    <p>Your information is protected with industry-standard encryption</p>
                </div>
                <div class="benefit-card" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-clock"></i>
                    <h3>Instant Confirmation</h3>
                    <p>Receive your booking confirmation immediately</p>
                </div>
                <div class="benefit-card" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-undo"></i>
                    <h3>Flexible Cancellation</h3>
                    <p>Free cancellation up to 24 hours before check-in</p>
                </div>
                <div class="benefit-card" data-aos="fade-up" data-aos-delay="400">
                    <i class="fas fa-headset"></i>
                    <h3>24/7 Support</h3>
                    <p>Our team is always here to help you</p>
                </div>
            </div>
        </div>
    </section>

    <section class="faq">
        <div class="faq-container">
            <h2 data-aos="fade-up">Frequently Asked Questions</h2>
            <div class="faq-grid">
                <div class="faq-item" data-aos="fade-up" data-aos-delay="100">
                    <h3>What is the cancellation policy?</h3>
                    <p>You can cancel your booking free of charge up to 24 hours before check-in. After that, a cancellation fee may apply.</p>
                </div>
                <div class="faq-item" data-aos="fade-up" data-aos-delay="200">
                    <h3>When will I receive my booking confirmation?</h3>
                    <p>You'll receive your booking confirmation immediately after completing the payment process.</p>
                </div>
                <div class="faq-item" data-aos="fade-up" data-aos-delay="300">
                    <h3>Can I modify my booking?</h3>
                    <p>Yes, you can modify your booking through your account or by contacting our customer service.</p>
                </div>
                <div class="faq-item" data-aos="fade-up" data-aos-delay="400">
                    <h3>What payment methods do you accept?</h3>
                    <p>We accept all major credit cards, PayPal, and bank transfers.</p>
                </div>
            </div>
        </div>
    </section>

    


    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="../pages/images/Logo-footer.png" alt="Egypt Hotels Logo" class="footer-logo-img">
                    </div>
                    <p>Your trusted partner for memorable stays in Egypt. Discover luxury, comfort, and authentic hospitality.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/profile.php?id=61576084713550" aria-label="Follow us on Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="https://x.com/egypt_hotels25" aria-label="Follow us on Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/egypt_hotels25/" aria-label="Follow us on Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/in/egypt-hotels-404222365/" aria-label="Follow us on LinkedIn"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="explore.php">Explore Hotels</a></li>
                        <li><a href="gallery.php">Photo Gallery</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt"></i> Banisuef</li>
                        <li><i class="fas fa-phone"></i> +20 1069787819</li>
                        <li><i class="fas fa-envelope"></i> egypthotels25@gmail.com</li>
                    </ul>
                </div> 
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Egypt Hotels. All rights reserved.</p>
            </div>
        </div>
    </footer>


    <script src="https://kit.fontawesome.com/your-code.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/book.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const hotelsPreviewData = {
            1: {
        name: "The Nile Ritz-Carlton",
        location: "Cairo, Egypt",
        image: "images/filter/ritz.jpeg",
        coordinates: {
            lat: 30.0444,
            lng: 31.2357
        },
        rating: 8.7,
        reviews: 1363,
        price: 600,
        description: "The Nile Ritz-Carlton is a landmark in luxury hospitality, located in the heart of Egypt's capital with stunning views of the Nile River. The hotel blends modern luxury with authentic Egyptian hospitality.",
        images: [
            "images/filter/ritz.jpeg",
            "images/hotels/ritz-cairo/1.jpg",
            "images/hotels/ritz-cairo/2.jpg",
            "images/hotels/ritz-cairo/3.jpg",
            "images/hotels/ritz-cairo/4.jpg",
            "images/hotels/ritz-cairo/5.jpg",
            "images/hotels/ritz-cairo/6.jpg",
            "images/hotels/ritz-cairo/7.jpg",
            "images/hotels/ritz-cairo/8.jpg",
            "images/hotels/ritz-cairo/9.jpg",
        ],
        amenities: [
            { icon: "fa-swimming-pool", name: "Luxury Swimming Pool" },
            { icon: "fa-wifi", name: "High-Speed Internet" },
            { icon: "fa-utensils", name: "International Restaurants" },
            { icon: "fa-spa", name: "Luxury Spa" },
            { icon: "fa-dumbbell", name: "Fully Equipped Health Club" },
            { icon: "fa-car", name: "Valet Parking Service" },
            { icon: "fa-concierge-bell", name: "24/7 Room Service" },
            { icon: "fa-business-time", name: "Business Center" }
        ],
        rooms: [
            {
                name: "Standard Room",
                description: "Comfortable room with all essentials and a beautiful view.",
                image: "images/hotels/ritz-cairo/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "45 sqm" }
                ],
                price: 600
            },
            {
                name: "Deluxe Suite",
                description: "Spacious suite with a separate living area and luxury amenities.",
                image: "images/hotels/ritz-cairo/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-4 Persons" },
                    { icon: "fa-ruler-combined", text: "75 sqm" }
                ],
                price: 1200
            },
            {
                name: "Panoramic Room",
                description: "Room with panoramic views and premium comfort, perfect for families or longer stays.",
                image: "images/hotels/ritz-cairo/room3.jpeg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "90 sqm" }
                ],
                price: 1800
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free when using existing beds",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms with designated smoking areas"
        }
    },
    2: {
        name: "Four Seasons At San Stefano",
        location: "Alexandria, Egypt",
        image: "images/filter/Four Seasons Alexandria at San Stefano.jpeg",
        coordinates: {
            lat: 31.2001,
            lng: 29.9187
        },
        rating: 9.2,
        reviews: 1477,
        price: 1000,
        description: "Four Seasons Hotel Alexandria At San Stefano is located directly on the Mediterranean coast, offering an exceptional stay with panoramic sea views. The hotel features elegant design and modern facilities.",
        images: [
            "images/filter/Four Seasons Alexandria at San Stefano.jpeg",
            "images/hotels/fourseasons-alex/1.jpg",
            "images/hotels/fourseasons-alex/2.jpg",
            "images/hotels/fourseasons-alex/3.jpg",
            "images/hotels/fourseasons-alex/4.jpg",
            "images/hotels/fourseasons-alex/5.jpg",
            "images/hotels/fourseasons-alex/6.jpg",
            "images/hotels/fourseasons-alex/7.jpg",
            "images/hotels/fourseasons-alex/8.jpg",
            "images/hotels/fourseasons-alex/9.jpg",
            
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-swimming-pool", name: "Infinity Pool" },
            { icon: "fa-wifi", name: "Free Wi-Fi" },
            { icon: "fa-utensils", name: "Various Restaurants" },
            { icon: "fa-spa", name: "World-Class Spa" },
            { icon: "fa-dumbbell", name: "Fitness Center" },
            { icon: "fa-concierge-bell", name: "Room Service" },
            { icon: "fa-ship", name: "Sea Cruises" }
        ],
        rooms: [
            {
                name: "Standard Room",
                description: "Elegant room with sea view and modern amenities.",
                image: "images/hotels/fourseasons-alex/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "42 sqm" }
                ],
                price: 1000
            },
            {
                name: "Deluxe Suite",
                description: "Spacious suite with private balcony and luxury features.",
                image: "images/hotels/fourseasons-alex/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "65 sqm" }
                ],
                price: 2300
            },
            {
                name: "Panoramic Room",
                description: "Room with panoramic sea views and premium comfort.",
                image: "images/hotels/fourseasons-alex/room3.jpeg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "90 sqm" }
                ],
                price: 3000
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 48 hours before arrival",
            children: "Children up to 6 years old stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    3: {
        name: "Royal Savoy Sharm El Sheikh",
        location: "Sharm El Sheikh, South Sinai",
        image: "images/filter/sharm-royal.jpg",
        coordinates: {
            lat: 27.9158,
            lng: 34.3300
        },
        rating: 9.3,
        reviews: 1149,
        price: 800,
        description: "A luxurious resort located directly on the Red Sea beach in Sharm El Sheikh, offering stunning views of coral reefs and the sea. The resort features modern design and comprehensive leisure and relaxation services.",
        images: [
            "images/filter/sharm-royal.jpg",
            "images/hotels/sharm-royal/1.jpg",
            "images/hotels/sharm-royal/2.jpg",
            "images/hotels/sharm-royal/3.jpg",
            "images/hotels/sharm-royal/4.jpg",
            "images/hotels/sharm-royal/5.jpg",
            "images/hotels/sharm-royal/6.jpg",
            "images/hotels/sharm-royal/7.jpg",
            "images/hotels/sharm-royal/8.jpg",
            "images/hotels/sharm-royal/9.jpg",
            
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-swimming-pool", name: "Multiple Pools" },
            { icon: "fa-fish", name: "Diving Center" },
            { icon: "fa-utensils", name: "Various Restaurants" },
            { icon: "fa-spa", name: "Spa & Massage" },
            { icon: "fa-volleyball-ball", name: "Sports Courts" },
            { icon: "fa-child", name: "Kids Club" },
            { icon: "fa-glass-martini", name: "Beach Bar" }
        ],
        rooms: [
            {
                name: "Standard Room",
                description: "Luxury room with balcony overlooking the Red Sea.",
                image: "images/hotels/sharm-royal/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "40 sqm" }
                ],
                price: 800
            },
            {
                name: "Deluxe Suite",
                description: "Luxury suite with private pool and panoramic view.",
                image: "images/hotels/sharm-royal/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-4 Persons" },
                    { icon: "fa-ruler-combined", text: "80 sqm" }
                ],
                price: 1400
            },
            {
                name: "Family Room",
                description: "Spacious room for families with extra beds and amenities.",
                image: "images/hotels/sharm-royal/3.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "100 sqm" }
                ],
                price: 1800
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free when using existing beds",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms with designated smoking areas"
        }
    },    
    4: {
        name: "Serry Beach Resort",
        location: "Hurghada, Red Sea",
        image: "images/filter/hurghada-beach.jpg",
        coordinates: {
            lat: 27.2579,
            lng: 33.8116
        },
        rating: 8.8,
        reviews: 308,
        price: 500,
        description: "A luxurious beachfront resort in Hurghada offering a distinguished stay with stunning views of the Red Sea. The resort features a private beach, tropical gardens, and diverse entertainment facilities.",
        images: [
            "images/filter/hurghada-beach.jpg",
            "images/hotels/hurghada-beach/1.jpg",
            "images/hotels/hurghada-beach/2.jpg",
            "images/hotels/hurghada-beach/3.jpg",
            "images/hotels/hurghada-beach/4.jpg",
            "images/hotels/hurghada-beach/5.jpg",
            "images/hotels/hurghada-beach/6.jpg",
            "images/hotels/hurghada-beach/7.jpg",
            "images/hotels/hurghada-beach/8.jpg",
            "images/hotels/hurghada-beach/9.jpg",
            
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-swimming-pool", name: "Multiple Pools" },
            { icon: "fa-water", name: "Water Sports" },
            { icon: "fa-utensils", name: "5 Restaurants" },
            { icon: "fa-spa", name: "Spa and Wellness Center" },
            { icon: "fa-dumbbell", name: "Gym" },
            { icon: "fa-child", name: "Kids Club" },
            { icon: "fa-music", name: "Night Entertainment" }
        ],
        rooms: [
            {
                name: "Superior Room",
                description: "Elegant room with balcony and sea view",
                image: "images/hotels/hurghada-beach/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "38 sqm" }
                ],
                price: 500
            },
            {
                name: "Beachfront Suite",
                description: "Luxury suite directly on the beach",
                image: "images/hotels/hurghada-beach/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "65 sqm" }
                ],
                price: 1000
            },
        ],
        policies: {
            cancellation: "Free cancellation up to 48 hours before arrival",
            children: "Children up to 6 years old stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    5: {
        name: "Sofitel Winter Palace Luxor",
        location: "Luxor, Egypt",
        image: "images/filter/luxor-palace.jpg",
        coordinates: {
            lat: 25.6872,
            lng: 32.6396
        },
        rating: 8.9,
        reviews: 781,
        price: 600,
        description: "A historic luxury hotel located on the banks of the Nile in Luxor, combining ancient Egyptian authenticity with modern elegance. It offers charming views of the Luxor Temple and the Nile River.",
        images: [
            "images/filter/luxor-palace.jpg",
            "images/hotels/luxor-palace/1.jpg",
            "images/hotels/luxor-palace/2.jpg",
            "images/hotels/luxor-palace/3.jpg",
            "images/hotels/luxor-palace/4.jpg",
            "images/hotels/luxor-palace/5.jpg",
            "images/hotels/luxor-palace/6.jpg",
            "images/hotels/luxor-palace/7.jpg",
            "images/hotels/luxor-palace/8.jpg",
            "images/hotels/luxor-palace/9.jpg",
            
        ],
        amenities: [
            { icon: "fa-swimming-pool", name: "Outdoor Pool" },
            { icon: "fa-ship", name: "Nile Cruises" },
            { icon: "fa-utensils", name: "Fine Dining" },
            { icon: "fa-spa", name: "Spa & Massage" },
            { icon: "fa-wifi", name: "Free Internet" },
            { icon: "fa-concierge-bell", name: "Room Service" },
            { icon: "fa-car", name: "Car Rental" },
            { icon: "fa-monument", name: "Sightseeing Tours" }
        ],
        rooms: [
            {
                name: "Standard Room",
                description: "Elegant room with a view of the Nile",
                image: "images/hotels/luxor-palace/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "35 sqm" }
                ],
                price: 600
            },
            {
                name: "Deluxe Suite",
                description: "Luxury suite with balcony and panoramic view",
                image: "images/hotels/luxor-palace/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "70 sqm" }
                ],
                price: 1200
            },
            {
                name: "Panoramic Room",
                description: "Room with panoramic views and premium comfort, perfect for families or longer stays.",
                image: "images/hotels/luxor-palace/room3.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "100 sqm" }
                ],
                price: 1800
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 48 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },    
    6: {
        name: "Mövenpick Resort Aswan",
        location: "Aswan, Egypt",
        image: "images/filter/aswan-movenpick.jpg",
        coordinates: {
            lat: 24.0889,
            lng: 32.8998
        },
        rating: 8.7,
        reviews: 677,
        price: 800,
        description: "A luxury hotel located on Elephantine Island in the heart of the Nile River, offering panoramic views of the river and stunning natural scenery. The design blends authentic Nubian style with modern elegance.",
        images: [
            "images/filter/aswan-movenpick.jpg",
            "images/hotels/movenpick-aswan/1.jpg",
            "images/hotels/movenpick-aswan/2.jpg",
            "images/hotels/movenpick-aswan/3.jpg",
            "images/hotels/movenpick-aswan/4.jpg",
            "images/hotels/movenpick-aswan/5.jpg",
            "images/hotels/movenpick-aswan/6.jpg",
            "images/hotels/movenpick-aswan/7.jpg",
            "images/hotels/movenpick-aswan/8.jpg",
            "images/hotels/movenpick-aswan/9.jpg",
            
        ],
        amenities: [
            { icon: "fa-swimming-pool", name: "Infinity Pool" },
            { icon: "fa-ship", name: "Private Marina" },
            { icon: "fa-utensils", name: "Nubian & International Restaurants" },
            { icon: "fa-spa", name: "Luxury Spa" },
            { icon: "fa-wifi", name: "Free Internet" },
            { icon: "fa-glass-martini", name: "Nile Lounge" },
            { icon: "fa-shuttle-van", name: "Free Shuttle Service" },
            { icon: "fa-monument", name: "Sightseeing Tours" }
        ],
        rooms: [
            {
                name: "Standard Room",
                description: "Elegant room with direct view of the Nile.",
                image: "images/hotels/movenpick-aswan/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "38 sqm" }
                ],
                price: 800
            },
            {
                name: "Deluxe Suite",
                description: "Luxury suite with balcony and 360° view.",
                image: "images/hotels/movenpick-aswan/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "75 sqm" }
                ],
                price: 1500
            },
            {
                name: "Panoramic Room",
                description: "Room with panoramic views and premium comfort, perfect for families or longer stays.",
                image: "images/hotels/movenpick-aswan/3.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "100 sqm" }
                ],
                price: 2000
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms with designated smoking areas"
        }
    },
    7: {
        name: "Dahab Lodge",
        location: "Dahab, South Sinai",
        image: "images/filter/dahab-lodge.jpg",
        coordinates: {
            lat: 28.4982,
            lng: 34.5163
        },
        rating: 7.0,
        reviews: 80,
        price: 200,
        description: "A charming resort that combines simplicity and comfort, located directly on the Gulf of Aqaba in Dahab. It features authentic Bedouin design and a calm atmosphere ideal for diving lovers and relaxation.",
        images: [
            "images/filter/dahab-lodge.jpg",
            "images/hotels/dahab-lodge/1.jpg",
            "images/hotels/dahab-lodge/2.jpg",
            "images/hotels/dahab-lodge/3.jpg",
            "images/hotels/dahab-lodge/4.jpg",
            "images/hotels/dahab-lodge/5.jpg",
            "images/hotels/dahab-lodge/6.jpg",
            "images/hotels/dahab-lodge/7.jpg",
            "images/hotels/dahab-lodge/8.jpg",
            "images/hotels/dahab-lodge/9.jpg",
            
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-fish", name: "Professional Diving Center" },
            { icon: "fa-utensils", name: "Oriental & International Restaurant" },
            { icon: "fa-wifi", name: "Free Internet" },
            { icon: "fa-bicycle", name: "Bicycle Rental" },
            { icon: "fa-campground", name: "Bedouin Seating Areas" },
            { icon: "fa-shuttle-van", name: "Shuttle Service" },
            { icon: "fa-coffee", name: "Beach Café" }
        ],
        rooms: [
            {
                name: "Beach Chalet",
                description: "Cozy chalet with direct sea view and balcony",
                image: "images/hotels/dahab-lodge/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "Large Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "30 sqm" },
                    { icon: "fa-wind", text: "Air Conditioning" }
                ],
                price: 200
            },
            {
                name: "Family Suite",
                description: "Spacious suite with sitting area and large balcony",
                image: "images/hotels/dahab-lodge/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "2 Large Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "45 sqm" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 400
            },
        ],
        policies: {
            cancellation: "Free cancellation up to 24 hours before arrival",
            children: "Children up to 6 years old stay free",
            pets: "Pets are not allowed",
            smoking: "Smoking allowed in designated areas only"
        }
    },    
    8: {
        name: "Marriott Mena House",
        location: "Zamalek, Cairo",
        image: "images/filter/cairo-marriott.jpg",
        coordinates: {
            lat: 30.0571,
            lng: 31.2272
        },
        rating: 8.8,
        reviews: 2050,
        price: 900,
        description: "A luxury hotel located in the heart of upscale Zamalek, offering breathtaking views of the Nile River and Cairo skyline. It combines global luxury with authentic Egyptian hospitality.",
        images: [
            "images/filter/cairo-marriott.jpg",
            "images/hotels/cairo-marriott/1.jpg",
            "images/hotels/cairo-marriott/2.jpg",
            "images/hotels/cairo-marriott/3.jpg",
            "images/hotels/cairo-marriott/4.jpg",
            "images/hotels/cairo-marriott/5.jpg",
            "images/hotels/cairo-marriott/6.jpg",
            "images/hotels/cairo-marriott/7.jpg",
            "images/hotels/cairo-marriott/8.jpg",
            "images/hotels/cairo-marriott/9.jpg",
            
        ],
        amenities: [
            { icon: "fa-swimming-pool", name: "Luxury Pool" },
            { icon: "fa-spa", name: "Spa & Beauty Salon" },
            { icon: "fa-utensils", name: "8 International Restaurants" },
            { icon: "fa-dumbbell", name: "Full-Service Health Club" },
            { icon: "fa-business-time", name: "Business Center" },
            { icon: "fa-car", name: "Valet Parking" },
            { icon: "fa-concierge-bell", name: "24/7 Room Service" },
            { icon: "fa-glass-martini", name: "Nile View Lounge" }
        ],
        rooms: [
            {
                name: "Standard Room",
                description: "Elegant room with panoramic view of the Nile",
                image: "images/hotels/cairo-marriott/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "48 sqm" }
                ],
                price: 900
            },
            {
                name: "Deluxe Suite",
                description: "Luxury suite with lounge and premium view of Nile & city",
                image: "images/hotels/cairo-marriott/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "75 sqm" }
                ],
                price: 1700
            },
            {
                name: "Panoramic Room",
                description: "Room with panoramic views and premium comfort, perfect for families or longer stays.",
                image: "images/hotels/cairo-marriott/3.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "100 sqm" }
                ],
                price: 2200
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 48 hours before arrival",
            children: "Children up to 12 years stay free when using existing beds",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    9: {
        name: "Casa Blue Resort",
        location: "Marsa Alam, Red Sea",
        image: "images/filter/marsa-alam-blue.jpg",
        coordinates: {
            lat: 25.0676,
            lng: 34.8990
        },
        rating: 10,
        reviews: 15,
        price: 800,
        description: "A luxury beachfront resort located on a private beach in Marsa Alam, surrounded by stunning coral reefs and turquoise waters. It offers an exceptional diving experience and a relaxing stay.",
        images: [
            "images/filter/marsa-alam-blue.jpg",
            "images/hotels/marsa-alam-blue/1.jpg",
            "images/hotels/marsa-alam-blue/2.jpg",
            "images/hotels/marsa-alam-blue/3.jpg",
            "images/hotels/marsa-alam-blue/4.jpg",
            "images/hotels/marsa-alam-blue/5.jpg",
            "images/hotels/marsa-alam-blue/6.jpg",
            "images/hotels/marsa-alam-blue/7.jpg",
            "images/hotels/marsa-alam-blue/8.jpg",
            "images/hotels/marsa-alam-blue/9.jpg",
            
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-fish", name: "PADI Diving Center" },
            { icon: "fa-swimming-pool", name: "4 Pools" },
            { icon: "fa-utensils", name: "5 Restaurants" },
            { icon: "fa-spa", name: "Spa & Massage" },
            { icon: "fa-volleyball-ball", name: "Water Sports" },
            { icon: "fa-child", name: "Kids Club" },
            { icon: "fa-glass-martini", name: "Beach Bar" }
        ],
        rooms: [
            {
                name: "Superior Lagoon Room",
                description: "Room overlooking turquoise lagoon and gardens",
                image: "images/hotels/marsa-alam-blue/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "42 sqm" },
                    { icon: "fa-umbrella-beach", text: "Private Balcony" }
                ],
                price: 800
            },
            {
                name: "Beachfront Suite",
                description: "Luxury suite with private terrace overlooking the sea",
                image: "images/hotels/marsa-alam-blue/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-4 Persons" },
                    { icon: "fa-ruler-combined", text: "65 sqm" },
                    { icon: "fa-cocktail", text: "VIP Room Service" }
                ],
                price: 1500
            },
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 6 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },    
    10: {
        name: "Steigenberger Hotel & Nelson Village",
        location: "Taba, South Sinai",
        image: "images/filter/taba-heights.jpg",
        coordinates: {
            lat: 29.4927,
            lng: 34.8967
        },
        rating: 9.8,
        reviews: 9895,
        price: 400,
        description: "A unique mountain resort overlooking the Gulf of Aqaba and four countries at once. It features a strategic location and a design harmonizing with the surrounding mountains. Offers a distinctive stay with panoramic views of the Red Sea and nearby mountains.",
        images: [
            "images/filter/taba-heights.jpg",
            "images/hotels/taba-heights/1.jpg",
            "images/hotels/taba-heights/2.jpg",
            "images/hotels/taba-heights/3.jpg",
            "images/hotels/taba-heights/4.jpg",
            "images/hotels/taba-heights/5.jpg",
            "images/hotels/taba-heights/6.jpg",
            "images/hotels/taba-heights/7.jpg",
            "images/hotels/taba-heights/8.jpg",
            "images/hotels/taba-heights/9.jpg",
            
        ],
        amenities: [
            { icon: "fa-mountain", name: "Mountain Views" },
            { icon: "fa-swimming-pool", name: "Infinity Pool" },
            { icon: "fa-utensils", name: "3 Restaurants" },
            { icon: "fa-hiking", name: "Mountain Hikes" },
            { icon: "fa-spa", name: "Wellness Spa" },
            { icon: "fa-shuttle-van", name: "Shuttle Service" },
            { icon: "fa-volleyball-ball", name: "Sports Courts" },
            { icon: "fa-star", name: "Bedouin Nights" }
        ],
        rooms: [
            {
                name: "Standard Room",
                description: "Elegant room with mountain and gulf view.",
                image: "images/hotels/taba-heights/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "35 sqm" }
                ],
                price: 400
            },
            {
                name: "Deluxe Suite",
                description: "Spacious suite with 180° view of the gulf and mountains.",
                image: "images/hotels/taba-heights/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "60 sqm" }
                ],
                price: 700
            },
            {
                name: "Panoramic Room",
                description: "Room with panoramic views and premium comfort, perfect for families or longer stays.",
                image: "images/hotels/taba-heights/room3.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "100 sqm" }
                ],
                price: 1000
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 48 hours before arrival",
            children: "Children up to 6 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    11: {
        name: "Helnan Auberge Fayoum",
        location: "Fayoum, Egypt",
        image: "images/filter/fayoum-desert.jpg",
        coordinates: {
            lat: 29.3084,
            lng: 30.8428
        },
        rating: 7.8,
        reviews: 704,
        price: 600,
        description: "A unique desert resort located on the edge of the Fayoum desert, offering a special stay combining desert charm and modern comfort. Inspired by local architecture, it offers stunning views of Lake Qarun and activities like horseback riding and desert safari.",
        images: [
            "images/filter/fayoum-desert.jpg",
            "images/hotels/fayoum-desert/1.jpg",
            "images/hotels/fayoum-desert/2.jpg",
            "images/hotels/fayoum-desert/3.jpg",
            "images/hotels/fayoum-desert/4.jpg",
            "images/hotels/fayoum-desert/5.jpg",
            "images/hotels/fayoum-desert/6.jpg",
            "images/hotels/fayoum-desert/7.jpg",
            "images/hotels/fayoum-desert/8.jpg",
            "images/hotels/fayoum-desert/9.jpg",
            
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Lake Beach" },
            { icon: "fa-horse", name: "Horseback Riding" },
            { icon: "fa-utensils", name: "Local Restaurant" },
            { icon: "fa-campground", name: "Desert Camp" },
            { icon: "fa-bicycle", name: "Safari Trips" },
            { icon: "fa-fire", name: "BBQ Nights" },
            { icon: "fa-spa", name: "Relaxation Sessions" },
            { icon: "fa-star-and-crescent", name: "Stargazing Evenings" }
        ],
        rooms: [
            {
                name: "Standard Room",
                description: "Private chalet surrounded by gardens with lake view.",
                image: "images/hotels/fayoum-desert/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "Large Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "40 sqm" }
                ],
                price: 600
            },
            {
                name: "Deluxe Suite",
                description: "Luxury suite with terrace overlooking the desert and lake.",
                image: "images/hotels/fayoum-desert/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-4 Persons" },
                    { icon: "fa-ruler-combined", text: "65 sqm" }
                ],
                price: 1100
            },
            {
                name: "Panoramic Room",
                description: "Room with panoramic views and premium comfort, perfect for families or longer stays.",
                image: "images/hotels/fayoum-desert/3.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "100 sqm" }
                ],
                price: 1500
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets allowed with prior approval",
            smoking: "Smoking allowed in designated areas"
        }
    },    
    12: {
        name: "Siwa Tarriott eco lodge hotel",
        location: "Siwa, Egypt",
        image: "images/filter/siwa-eco.jpg",
        coordinates: {
            lat: 29.2041,
            lng: 25.5195
        },
        rating: 7.0,
        reviews: 60,
        price: 500,
        description: "A unique eco-resort located in the enchanting Siwa Oasis, combining local heritage with environmental sustainability. It features a design inspired by traditional Siwan architecture and the use of local materials. It offers a unique accommodation experience in the heart of the desert while preserving the environment.",
        images: [
            "images/filter/siwa-eco.jpg",
            "images/hotels/siwa-eco/1.jpg",
            "images/hotels/siwa-eco/2.jpg",
            "images/hotels/siwa-eco/3.jpg",
            "images/hotels/siwa-eco/4.jpg",
            "images/hotels/siwa-eco/5.jpg",
            "images/hotels/siwa-eco/6.jpg",
            "images/hotels/siwa-eco/7.jpg",
            "images/hotels/siwa-eco/8.jpg",
            "images/hotels/siwa-eco/9.jpg",
            
        ],
        amenities: [
            { icon: "fa-leaf", name: "Eco Design" },
            { icon: "fa-swimming-pool", name: "Natural Pool" },
            { icon: "fa-utensils", name: "Organic Restaurant" },
            { icon: "fa-spa", name: "Natural Spa" },
            { icon: "fa-bicycle", name: "Desert Tours" },
            { icon: "fa-tree", name: "Organic Gardens" },
            { icon: "fa-solar-panel", name: "Solar Energy" },
            { icon: "fa-star", name: "Traditional Evenings" }
        ],
        rooms: [
            {
                name: "Standard Room",
                description: "Chalet built from local mud with oasis view.",
                image: "images/hotels/siwa-eco/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "35 m²" }
                ],
                price: 500
            },
            {
                name: "Deluxe Suite",
                description: "Luxury suite with terrace overlooking the oasis and gardens.",
                image: "images/hotels/siwa-eco/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "55 m²" }
                ],
                price: 800
            },
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Smoking allowed in designated areas only"
        }
    },
    13: {
        name: "Chalet in Marassi Marina, Canal view with luxurious furniture",
        location: "New Alamein, Matrouh",
        image: "images/filter/alamein-beach.jpg",
        coordinates: {
            lat: 30.8300,
            lng: 28.9500
        },
        rating: 0,
        reviews: 0,
        price: 500,
        description: "A luxurious coastal resort overlooking the Mediterranean Sea in New Alamein city. It features modern design and a prime location on a golden sandy beach. It offers a unique stay experience with stunning sea views.",
        images: [
            "images/filter/alamein-beach.jpg",
            "images/hotels/alamein-beach/1.jpg",
            "images/hotels/alamein-beach/2.jpg",
            "images/hotels/alamein-beach/3.jpg",
            "images/hotels/alamein-beach/4.jpg",
            "images/hotels/alamein-beach/5.jpg",
            "images/hotels/alamein-beach/6.jpg",
            "images/hotels/alamein-beach/7.jpg",
            "images/hotels/alamein-beach/8.jpg",
            "images/hotels/alamein-beach/9.jpg",
            
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-swimming-pool", name: "3 Pools" },
            { icon: "fa-utensils", name: "4 Restaurants" },
            { icon: "fa-spa", name: "Luxury Spa" },
            { icon: "fa-volleyball-ball", name: "Water Sports" },
            { icon: "fa-child", name: "Kids Club" },
            { icon: "fa-dumbbell", name: "Fitness Center" },
            { icon: "fa-glass-martini", name: "Beach Bar" }
        ],
        rooms: [
            {
                name: "Standard Room",
                description: "Elegant room with direct sea view.",
                image: "images/hotels/alamein-beach/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "40 m²" }
                ],
                price: 500
            },
            {
                name: "Deluxe Suite",
                description: "Luxury suite with private terrace overlooking the sea.",
                image: "images/hotels/alamein-beach/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "65 m²" }
                ],
                price: 1000
            },
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    14: {
        name: "Intercontinental Cairo Citystars",
        location: "Downtown, Cairo",
        image: "images/filter/cairo-citystars.jpg",
        coordinates: {
            lat: 30.0444,
            lng: 31.2357
        },
        rating: 8.8,
        reviews: 2261,
        price: 700,
        description: "A modern hotel located in the heart of Cairo, featuring contemporary design and a strategic location near major tourist and commercial landmarks. It offers stunning city views and modern services.",
        images: [
            "images/filter/cairo-citystars.jpg",
            "images/hotels/cairo-city/1.jpg",
            "images/hotels/cairo-city/2.jpg",
            "images/hotels/cairo-city/3.jpg",
            "images/hotels/cairo-city/4.jpg",
            "images/hotels/cairo-city/5.jpg",
            "images/hotels/cairo-city/6.jpg",
            "images/hotels/cairo-city/7.jpg",
            "images/hotels/cairo-city/8.jpg",
            "images/hotels/cairo-city/9.jpg",
            
        ],
        amenities: [
            { icon: "fa-wifi", name: "Free WiFi" },
            { icon: "fa-utensils", name: "3 Restaurants" },
            { icon: "fa-dumbbell", name: "Fitness Center" },
            { icon: "fa-business-time", name: "Business Center" },
            { icon: "fa-spa", name: "Spa" },
            { icon: "fa-car", name: "Valet Service" },
            { icon: "fa-concierge-bell", name: "24/7 Room Service" },
            { icon: "fa-glass-martini", name: "Rooftop Bar" }
        ],
        rooms: [
            {
                name: "City View Room",
                description: "Comfortable room with city view.",
                image: "images/hotels/cairo-city/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "35 m²" }
                ],
                price: 700
            },
            {
                name: "Deluxe Suite",
                description: "Luxury suite with separate living room.",
                image: "images/hotels/cairo-city/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "55 m²" }
                ],
                price: 1200
            },
            {
                name: "Panoramic Room",
                description: "Room with panoramic city views and premium comfort.",
                image: "images/hotels/cairo-city/room3.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "80 m²" }
                ],
                price: 1600
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 48 hours before arrival",
            children: "Children up to 6 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    15: {
        name: "Rhactus House San Stefano",
        location: "Alexandria",
        image: "images/filter/alex-seaview.jpg",
        coordinates: {
            lat: 31.2001,
            lng: 29.9187
        },
        rating: 9.4,
        reviews: 157,
        price: 500,
        description: "A luxurious hotel overlooking the Mediterranean Sea in the heart of Alexandria. It features modern design and panoramic sea views. It offers a unique stay experience with premium services.",
        images: [
            "images/filter/alex-seaview.jpg",
            "images/hotels/alex-sea/1.jpg",
            "images/hotels/alex-sea/2.jpg",
            "images/hotels/alex-sea/3.jpg",
            "images/hotels/alex-sea/4.jpg",
            "images/hotels/alex-sea/5.jpg",
            "images/hotels/alex-sea/6.jpg",
            "images/hotels/alex-sea/7.jpg",
            "images/hotels/alex-sea/8.jpg",
            "images/hotels/alex-sea/9.jpg",
            
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-swimming-pool", name: "Infinity Pool" },
            { icon: "fa-utensils", name: "4 Restaurants" },
            { icon: "fa-spa", name: "Luxury Spa" },
            { icon: "fa-dumbbell", name: "Fitness Center" },
            { icon: "fa-ship", name: "Sea Cruises" },
            { icon: "fa-concierge-bell", name: "24/7 Room Service" },
            { icon: "fa-glass-martini", name: "Sea Bar" }
        ],
        rooms: [
            {
                name: "Standard Room",
                description: "Elegant room with direct sea view.",
                image: "images/hotels/alex-sea/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "38 m²" }
                ],
                price: 500
            },
            {
                name: "Deluxe Suite",
                description: "Luxury suite with 180-degree sea view.",
                image: "images/hotels/alex-sea/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "60 m²" }
                ],
                price: 900
            },
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    16: {
        name: "Domina Coral Bay Resort",
        location: "Sharm El Sheikh",
        image: "images/filter/sharm-reef.jpg",
        coordinates: {
            lat: 27.9158,
            lng: 34.3300
        },
        rating: 9.5,
        reviews: 2,
        price: 1100,
        description: "A luxurious resort overlooking Naama Bay in Sharm El Sheikh, featuring a prime beachfront location and stunning views of the Red Sea. It offers a unique stay experience with comprehensive entertainment and relaxation facilities.",
        images: [
            "images/filter/sharm-reef.jpg",
            "images/hotels/sharm-coral/1.jpg",
            "images/hotels/sharm-coral/2.jpg",
            "images/hotels/sharm-coral/3.jpg",
            "images/hotels/sharm-coral/4.jpg",
            "images/hotels/sharm-coral/5.jpg",
            "images/hotels/sharm-coral/6.jpg",
            "images/hotels/sharm-coral/7.jpg",
            "images/hotels/sharm-coral/8.jpg",
            "images/hotels/sharm-coral/9.jpg"
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-swimming-pool", name: "4 Pools" },
            { icon: "fa-utensils", name: "5 Restaurants" },
            { icon: "fa-fish", name: "PADI Diving Center" },
            { icon: "fa-spa", name: "Luxury Spa" },
            { icon: "fa-volleyball-ball", name: "Water Sports" },
            { icon: "fa-child", name: "Kids Club" },
            { icon: "fa-glass-martini", name: "Beach Bar" }
        ],
        rooms: [
            {
                name: "Deluxe Sea View Room",
                description: "Elegant room with direct sea view and private balcony",
                image: "images/hotels/sharm-coral/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "42 m²" },
                    { icon: "fa-umbrella-beach", text: "Sea View Balcony" }
                ],
                price: 1100
            },
            {
                name: "Beachfront Suite",
                description: "Luxury suite with private terrace and panoramic sea views",
                image: "images/hotels/sharm-coral/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "70 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 2000
            },
            {
                name: "Family Suite",
                description: "Spacious suite perfect for families with separate living area",
                image: "images/hotels/sharm-coral/3.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "90 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 2800
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    17: {
        name: "Sunrise Aqua Joy Resort",
        location: "Hurghada, Red Sea",
        image: "images/filter/hurghada-sunrise.jpg",
        coordinates: {
            lat: 27.2578,
            lng: 33.8116
        },
        rating: 9.1,
        reviews: 5206,
        price: 750,
        description: "A luxurious beachfront resort overlooking the Red Sea in Hurghada, featuring modern design and a prime location on a golden sandy beach. It offers a comfortable stay experience with comprehensive entertainment and relaxation facilities.",
        images: [
            "images/filter/hurghada-sunrise.jpg",
            "images/hotels/hurghada-sun/1.jpg",
            "images/hotels/hurghada-sun/2.jpg",
            "images/hotels/hurghada-sun/3.jpg",
            "images/hotels/hurghada-sun/4.jpg",
            "images/hotels/hurghada-sun/5.jpg",
            "images/hotels/hurghada-sun/6.jpg",
            "images/hotels/hurghada-sun/7.jpg",
            "images/hotels/hurghada-sun/8.jpg",
            "images/hotels/hurghada-sun/9.jpg"
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-swimming-pool", name: "3 Pools" },
            { icon: "fa-utensils", name: "4 Restaurants" },
            { icon: "fa-fish", name: "Diving Center" },
            { icon: "fa-spa", name: "Spa" },
            { icon: "fa-volleyball-ball", name: "Water Sports" },
            { icon: "fa-child", name: "Kids Club" },
            { icon: "fa-glass-martini", name: "Beach Bar" }
        ],
        rooms: [
            {
                name: "Deluxe Sea View Room",
                description: "Elegant room with direct sea view and private balcony",
                image: "images/hotels/hurghada-sun/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "38 m²" },
                    { icon: "fa-umbrella-beach", text: "Sea View Balcony" }
                ],
                price: 750
            },
            {
                name: "Family Suite",
                description: "Spacious suite with separate living area and sea view",
                image: "images/hotels/hurghada-sun/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "55 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 1500
            },
            {
                name: "Executive Suite",
                description: "Luxury suite with panoramic sea views and premium amenities",
                image: "images/hotels/hurghada-sun/room3.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "75 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 2200
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 48 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    18: {
        name: "Luxor Nile View Flats",
        location: "Nile Corniche, Luxor",
        image: "images/filter/luxor-nile.jpg",
        coordinates: {
            lat: 25.6872,
            lng: 32.6396
        },
        rating: 8.1,
        reviews: 53,
        price: 400,
        description: "A luxurious hotel overlooking the Nile River in the heart of Luxor, featuring design inspired by ancient Egyptian architecture and a prime location near archaeological sites. It offers a unique stay experience with stunning Nile views.",
        images: [
            "images/filter/luxor-nile.jpg",
            "images/hotels/luxor-nile/1.jpg",
            "images/hotels/luxor-nile/2.jpg",
            "images/hotels/luxor-nile/3.jpg",
            "images/hotels/luxor-nile/4.jpg",
            "images/hotels/luxor-nile/5.jpg",
            "images/hotels/luxor-nile/6.jpg",
            "images/hotels/luxor-nile/7.jpg",
            "images/hotels/luxor-nile/8.jpg",
            "images/hotels/luxor-nile/9.jpg"
        ],
        amenities: [
            { icon: "fa-swimming-pool", name: "Outdoor Pool" },
            { icon: "fa-utensils", name: "3 Restaurants" },
            { icon: "fa-ship", name: "Nile Cruises" },
            { icon: "fa-spa", name: "Spa" },
            { icon: "fa-monument", name: "Sightseeing Tours" },
            { icon: "fa-dumbbell", name: "Fitness Center" },
            { icon: "fa-concierge-bell", name: "24/7 Room Service" },
            { icon: "fa-glass-martini", name: "Nile View Lounge" }
        ],
        rooms: [
            {
                name: "Nile View Room",
                description: "Elegant room with panoramic Nile views and balcony",
                image: "images/hotels/luxor-nile/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "40 m²" },
                    { icon: "fa-water", text: "Nile View Balcony" }
                ],
                price: 400
            },
            {
                name: "Executive Suite",
                description: "Luxury suite with separate living area and Nile views",
                image: "images/hotels/luxor-nile/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "60 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 800
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    19: {
        name: "Sofitel Legend Old Cataract",
        location: "Aswan Island, Aswan",
        image: "images/filter/aswan-cataract.jpg",
        coordinates: {
            lat: 24.0889,
            lng: 32.8998
        },
        rating: 9.3,
        reviews: 931,
        price: 1000,
        description: "A unique resort located on Aswan Island in the heart of the Nile River, offering panoramic views of the river and stunning natural scenery. The design blends authentic Nubian style with modern elegance.",
        images: [
            "images/filter/aswan-cataract.jpg",
            "images/hotels/aswan-cataract/1.jpg",
            "images/hotels/aswan-cataract/2.jpg",
            "images/hotels/aswan-cataract/3.jpg",
            "images/hotels/aswan-cataract/4.jpg",
            "images/hotels/aswan-cataract/5.jpg",
            "images/hotels/aswan-cataract/6.jpg",
            "images/hotels/aswan-cataract/7.jpg",
            "images/hotels/aswan-cataract/8.jpg",
            "images/hotels/aswan-cataract/9.jpg"
        ],
        amenities: [
            { icon: "fa-swimming-pool", name: "Infinity Pool" },
            { icon: "fa-utensils", name: "3 Restaurants" },
            { icon: "fa-ship", name: "Nile Cruises" },
            { icon: "fa-spa", name: "Nubian Spa" },
            { icon: "fa-monument", name: "Sightseeing Tours" },
            { icon: "fa-dumbbell", name: "Fitness Center" },
            { icon: "fa-concierge-bell", name: "24/7 Room Service" },
            { icon: "fa-glass-martini", name: "Nile View Lounge" }
        ],
        rooms: [
            {
                name: "Nile View Room",
                description: "Elegant room with panoramic Nile views and balcony",
                image: "images/hotels/aswan-cataract/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "40 m²" },
                    { icon: "fa-water", text: "Nile View Balcony" }
                ],
                price: 1000
            },
            {
                name: "Nubian Suite",
                description: "Luxury suite with 360-degree Nile views and living area",
                image: "images/hotels/aswan-cataract/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "60 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 2000
            },
            {
                name: "Royal Suite",
                description: "Exclusive suite with private terrace and panoramic views",
                image: "images/hotels/aswan-cataract/room3.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-4 Persons" },
                    { icon: "fa-ruler-combined", text: "90 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 3000
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    20: {
        name: "Beit Theresa",
        location: "Dahab, South Sinai",
        image: "images/filter/dahab-blue.jpg",
        coordinates: {
            lat: 28.4982,
            lng: 34.5163
        },
        rating: 9.7,
        reviews: 223,
        price: 1100,
        description: "A charming resort located directly on the Gulf of Aqaba in Dahab, offering a unique blend of Bedouin charm and modern comfort. It features authentic design and a calm atmosphere ideal for diving lovers and relaxation.",
        images: [
            "images/filter/dahab-blue.jpg",
            "images/hotels/dahab-blue/1.jpg",
            "images/hotels/dahab-blue/2.jpg",
            "images/hotels/dahab-blue/3.jpg",
            "images/hotels/dahab-blue/4.jpg",
            "images/hotels/dahab-blue/5.jpg",
            "images/hotels/dahab-blue/6.jpg",
            "images/hotels/dahab-blue/7.jpg",
            "images/hotels/dahab-blue/8.jpg",
            "images/hotels/dahab-blue/9.jpg"
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-fish", name: "PADI Diving Center" },
            { icon: "fa-utensils", name: "Local Cuisine" },
            { icon: "fa-spa", name: "Bedouin Spa" },
            { icon: "fa-volleyball-ball", name: "Water Sports" },
            { icon: "fa-campground", name: "Bedouin Seating Areas" },
            { icon: "fa-wifi", name: "Free Wi-Fi" },
            { icon: "fa-glass-martini", name: "Beach Bar" }
        ],
        rooms: [
            {
                name: "Beach Chalet",
                description: "Cozy chalet with direct sea view and private balcony",
                image: "images/hotels/dahab-blue/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "Large Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "35 m²" },
                    { icon: "fa-wind", text: "Air Conditioning" }
                ],
                price: 1100
            },
            {
                name: "Deluxe Suite",
                description: "Luxury suite with panoramic sea views and premium amenities",
                image: "images/hotels/dahab-blue/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "65 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 2800
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 24 hours before arrival",
            children: "Children up to 6 years old stay free",
            pets: "Pets are not allowed",
            smoking: "Smoking allowed in designated areas only"
        }
    },
    21: {
        name: "New Cairo Nyoum Porto New Cairo, Elite Apartments",
        location: "Cairo, Egypt",
        image: "images/filter/cairo-nile.jpg",
        coordinates: {
            lat: 30.0444,
            lng: 31.2357
        },
        rating: 7.1,
        reviews: 7,
        price: 200,
        description: "A luxurious hotel located on the banks of the Nile in Cairo, combining modern elegance with ancient Egyptian charm. It offers panoramic views of the Nile River and a prime location near major tourist attractions.",
        images: [
            "images/filter/cairo-nile.jpg",
            "images/hotels/cairo-nile/1.jpg",
            "images/hotels/cairo-nile/2.jpg",
            "images/hotels/cairo-nile/3.jpg",
            "images/hotels/cairo-nile/4.jpg",
            "images/hotels/cairo-nile/5.jpg",
            "images/hotels/cairo-nile/6.jpg",
            "images/hotels/cairo-nile/7.jpg",
            "images/hotels/cairo-nile/8.jpg",
            "images/hotels/cairo-nile/9.jpg"
        ],
        amenities: [
            { icon: "fa-swimming-pool", name: "Infinity Pool" },
            { icon: "fa-utensils", name: "5 Restaurants" },
            { icon: "fa-spa", name: "Spa & Massage" },
            { icon: "fa-dumbbell", name: "Fitness Center" },
            { icon: "fa-business-time", name: "Business Center" },
            { icon: "fa-car", name: "Car Rental" },
            { icon: "fa-concierge-bell", name: "24/7 Room Service" },
            { icon: "fa-glass-martini", name: "Nile View Lounge" }
        ],
        rooms: [
            {
                name: "Nile View Room",
                description: "Elegant room with panoramic Nile views and private balcony",
                image: "images/hotels/cairo-nile/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "45 m²" },
                    { icon: "fa-water", text: "Nile View Balcony" }
                ],
                price: 200
            },
            {
                name: "Executive Suite",
                description: "Luxury suite with 360-degree views of the Nile and city",
                image: "images/hotels/cairo-nile/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "75 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 550
            },
            {
                name: "Family Suite",
                description: "Spacious suite perfect for families with Nile views",
                image: "images/hotels/cairo-nile/3.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "90 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 800
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    22: {
        name: "Marsa CoralCoral Hills Resort & SPA",
        location: "Marsa Matrouh, Egypt",
        image: "images/filter/marsa-alam-coral.jpg",
        coordinates: {
            lat: 25.0676,
            lng: 34.8990
        },
        rating: 7.4,
        reviews: 149,
        price: 250,
        description: "A luxurious resort located on the coast of Marsa Matrouh, offering stunning views of the Red Sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.",
        images: [
            "images/filter/marsa-alam-coral.jpg",
            "images/hotels/marsa-coral/1.jpg",
            "images/hotels/marsa-coral/2.jpg",
            "images/hotels/marsa-coral/3.jpg",
            "images/hotels/marsa-coral/4.jpg",
            "images/hotels/marsa-coral/5.jpg",
            "images/hotels/marsa-coral/6.jpg",
            "images/hotels/marsa-coral/7.jpg",
            "images/hotels/marsa-coral/8.jpg",
            "images/hotels/marsa-coral/9.jpg"
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-swimming-pool", name: "3 Pools" },
            { icon: "fa-utensils", name: "4 Restaurants" },
            { icon: "fa-fish", name: "PADI Diving Center" },
            { icon: "fa-spa", name: "Luxury Spa" },
            { icon: "fa-volleyball-ball", name: "Water Sports" },
            { icon: "fa-child", name: "Kids Club" },
            { icon: "fa-glass-martini", name: "Beach Bar" }
        ],
        rooms: [
            {
                name: "Deluxe Sea View Room",
                description: "Elegant room with direct sea view and private balcony",
                image: "images/hotels/marsa-coral/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "42 m²" },
                    { icon: "fa-umbrella-beach", text: "Sea View Balcony" }
                ],
                price: 250
            },
            {
                name: "Beachfront Suite",
                description: "Luxury suite with private terrace and panoramic sea views",
                image: "images/hotels/marsa-coral/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "70 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 450
            },
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    23: {
        name: "Taba Sands Hotel & Casino",
        location: "Taba, Egypt",
        image: "images/filter/taba-sands.jpg",
        coordinates: {
            lat: 29.4927,
            lng: 34.8967
        },
        rating: 8.7,
        reviews: 351,
        price: 600,
        description: "A luxurious resort located on the shores of the Red Sea in Taba, offering stunning views of the sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.",
        images: [
            "images/filter/taba-sands.jpg",
            "images/hotels/taba-sands/1.jpg",
            "images/hotels/taba-sands/2.jpg",
            "images/hotels/taba-sands/3.jpg",
            "images/hotels/taba-sands/4.jpg",
            "images/hotels/taba-sands/5.jpg",
            "images/hotels/taba-sands/6.jpg",
            "images/hotels/taba-sands/7.jpg",
            "images/hotels/taba-sands/8.jpg",
            "images/hotels/taba-sands/9.jpg"
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-swimming-pool", name: "Infinity Pool" },
            { icon: "fa-utensils", name: "3 Restaurants" },
            { icon: "fa-fish", name: "Diving Center" },
            { icon: "fa-spa", name: "Spa & Massage" },
            { icon: "fa-volleyball-ball", name: "Water Sports" },
            { icon: "fa-child", name: "Kids Club" },
            { icon: "fa-glass-martini", name: "Beach Bar" }
        ],
        rooms: [
            {
                name: "Deluxe Sea View Room",
                description: "Elegant room with direct sea view and private balcony",
                image: "images/hotels/taba-sands/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "40 m²" },
                    { icon: "fa-umbrella-beach", text: "Sea View Balcony" }
                ],
                price: 600
            },
            {
                name: "Family Suite",
                description: "Spacious suite with private pool and garden view",
                image: "images/hotels/taba-sands/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "65 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 1500
            },
            {
                name: "Executive Suite",
                description: "Luxury suite with panoramic sea views and premium amenities",
                image: "images/hotels/taba-sands/3.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "75 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 2000
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    24: {
        name: "Tache Boutique Hotel Fayoum",
        location: "Fayoum, Egypt",
        image: "images/filter/fayoum-tunis.jpg",
        coordinates: {
            lat: 29.3084,
            lng: 30.8428
        },
        rating: 7.3,
        reviews: 51,
        price: 200,
        description: "A unique resort located on the shores of Lake Qarun in Fayoum, offering stunning views of the lake and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.",
        images: [
            "images/filter/fayoum-tunis.jpg",
            "images/hotels/fayoum-tunzi/1.jpg",
            "images/hotels/fayoum-tunzi/2.jpg",
            "images/hotels/fayoum-tunzi/3.jpg",
            "images/hotels/fayoum-tunzi/4.jpg",
            "images/hotels/fayoum-tunzi/5.jpg",
            "images/hotels/fayoum-tunzi/6.jpg",
            "images/hotels/fayoum-tunzi/7.jpg",
            "images/hotels/fayoum-tunzi/8.jpg",
            "images/hotels/fayoum-tunzi/9.jpg"
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Lake Beach" },
            { icon: "fa-swimming-pool", name: "Infinity Pool" },
            { icon: "fa-utensils", name: "Local Restaurant" },
            { icon: "fa-bicycle", name: "Bicycle Rental" },
            { icon: "fa-campground", name: "Desert Camp" },
            { icon: "fa-fire", name: "BBQ Nights" },
            { icon: "fa-spa", name: "Relaxation Sessions" },
            { icon: "fa-star-and-crescent", name: "Stargazing Evenings" }
        ],
        rooms: [
            {
                name: "Oasis Chalet",
                description: "Private chalet surrounded by gardens with lake view",
                image: "images/hotels/fayoum-tunzi/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "Large Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "40 m²" },
                    { icon: "fa-tree", text: "Private Garden" }
                ],
                price: 200
            },
            {
                name: "Desert Suite",
                description: "Luxury suite with terrace overlooking the desert and lake",
                image: "images/hotels/fayoum-tunzi/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-4 Persons" },
                    { icon: "fa-ruler-combined", text: "65 m²" },
                    { icon: "fa-moon", text: "Outdoor Sitting Area" }
                ],
                price: 500
            },
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets allowed with prior approval",
            smoking: "Smoking allowed in designated areas"
        }
    },
    25: {
        name: "Siwa Shali Resort",
        location: "Siwa Oasis, Egypt",
        image: "images/filter/siwa-shali.jpg",
        coordinates: {
            lat: 29.2041,
            lng: 25.5195
        },
        rating: 8.3,
        reviews: 152,
        price: 450,
        description: "A unique eco-resort located in the enchanting Siwa Oasis, combining local heritage with environmental sustainability. It features a design inspired by traditional Siwan architecture and the use of local materials.",
        images: [
            "images/filter/siwa-shali.jpg",
            "images/hotels/siwa-shali/1.jpg",
            "images/hotels/siwa-shali/2.jpg",
            "images/hotels/siwa-shali/3.jpg",
            "images/hotels/siwa-shali/4.jpg",
            "images/hotels/siwa-shali/5.jpg",
            "images/hotels/siwa-shali/6.jpg",
            "images/hotels/siwa-shali/7.jpg",
            "images/hotels/siwa-shali/8.jpg",
            "images/hotels/siwa-shali/9.jpg"
        ],
        amenities: [
            { icon: "fa-leaf", name: "Eco Design" },
            { icon: "fa-swimming-pool", name: "Natural Pool" },
            { icon: "fa-utensils", name: "Organic Restaurant" },
            { icon: "fa-spa", name: "Natural Spa" },
            { icon: "fa-bicycle", name: "Desert Tours" },
            { icon: "fa-tree", name: "Organic Gardens" },
            { icon: "fa-solar-panel", name: "Solar Energy" },
            { icon: "fa-star", name: "Traditional Evenings" }
        ],
        rooms: [
            {
                name: "Mud Chalet",
                description: "Chalet built from local mud with oasis view and terrace",
                image: "images/hotels/siwa-shali/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "35 m²" },
                    { icon: "fa-tree", text: "Private Terrace" }
                ],
                price: 450
            },
            {
                name: "Oasis Suite",
                description: "Luxury suite with terrace overlooking the oasis and gardens",
                image: "images/hotels/siwa-shali/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "55 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 1000
            },
            {
                name: "Family Suite",
                description: "Spacious suite perfect for families with oasis views",
                image: "images/hotels/siwa-shali/3.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "75 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 1500
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Smoking allowed in designated areas only"
        }
    },
    26: {
        name: "Palma Bay Rotana Resort",
        location: "Alamein, Matrouh",
        image: "images/filter/alamein-marina.jpg",
        coordinates: {
            lat: 30.8300,
            lng: 28.9500
        },
        rating: 9.2,
        reviews: 164,
        price: 900,
        description: "A luxurious resort located on the shores of the Mediterranean Sea in Alamein, offering stunning views of the sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.",
        images: [
            "images/filter/alamein-marina.jpg",
            "images/hotels/alamein-marina/1.jpg",
            "images/hotels/alamein-marina/2.jpg",
            "images/hotels/alamein-marina/3.jpg",
            "images/hotels/alamein-marina/4.jpg",
            "images/hotels/alamein-marina/5.jpg",
            "images/hotels/alamein-marina/6.jpg",
            "images/hotels/alamein-marina/7.jpg",
            "images/hotels/alamein-marina/8.jpg",
            "images/hotels/alamein-marina/9.jpg"
        ],
        amenities: [
            { icon: "fa-anchor", name: "Marina Access" },
            { icon: "fa-swimming-pool", name: "Lagoon Pool" },
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-utensils", name: "International Dining" },
            { icon: "fa-dumbbell", name: "Fitness Center" },
            { icon: "fa-spa", name: "Spa & Wellness" },
            { icon: "fa-child", name: "Kids Club" },
            { icon: "fa-glass-martini", name: "Beach Bar" }
        ],
        rooms: [
            {
                name: "Marina View Room",
                description: "Modern room with balcony overlooking the marina.",
                image: "images/hotels/alamein-marina/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "38 m²" },
                    { icon: "fa-anchor", text: "Marina View" }
                ],
                price: 350
            },
            {
                name: "Lagoon Suite",
                description: "Spacious suite with direct access to the lagoon pool.",
                image: "images/hotels/alamein-marina/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "60 m²" },
                    { icon: "fa-swimming-pool", text: "Pool Access" }
                ],
                price: 700
            },
            {
                name: "Family Suite",
                description: "Large suite ideal for families, with two bedrooms and a living area.",
                image: "images/hotels/alamein-marina/room3.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "85 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 1100
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    27: {
        name: "Concorde El Salam Cairo Hotel & Casino",
        location: "Downtown, Cairo",
        image: "images/filter/cairo-concorde.jpg",
        coordinates: {
            lat: 30.0444,
            lng: 31.2357
        },
        rating: 8.5,
        reviews: 2843,
        price: 600,
        description: "A modern hotel located in the heart of Cairo, featuring contemporary design and a strategic location near major tourist and commercial landmarks. It offers stunning city views and modern services.",
        images: [
            "images/filter/cairo-concorde.jpg",
            "images/hotels/cairo-concorde/1.jpg",
            "images/hotels/cairo-concorde/2.jpg",
            "images/hotels/cairo-concorde/3.jpg",
            "images/hotels/cairo-concorde/4.jpg",
            "images/hotels/cairo-concorde/5.jpg",
            "images/hotels/cairo-concorde/6.jpg",
            "images/hotels/cairo-concorde/7.jpg",
            "images/hotels/cairo-concorde/8.jpg",
            "images/hotels/cairo-concorde/9.jpg"
        ],
        amenities: [
            { icon: "fa-wifi", name: "Free Wi-Fi" },
            { icon: "fa-utensils", name: "3 Restaurants" },
            { icon: "fa-dumbbell", name: "Fitness Center" },
            { icon: "fa-business-time", name: "Business Center" },
            { icon: "fa-spa", name: "Spa" },
            { icon: "fa-car", name: "Car Rental" },
            { icon: "fa-concierge-bell", name: "24/7 Room Service" },
            { icon: "fa-glass-martini", name: "Rooftop Bar" }
        ],
        rooms: [
            {
                name: "City View Room",
                description: "Comfortable room with city view",
                image: "images/hotels/cairo-concorde/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "35 m²" },
                    { icon: "fa-city", text: "City View Balcony" }
                ],
                price: 600
            },
            {
                name: "Executive Suite",
                description: "Luxury suite with separate living room",
                image: "images/hotels/cairo-concorde/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "55 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 1200
            },
        ],
        policies: {
            cancellation: "Free cancellation up to 48 hours before arrival",
            children: "Children up to 6 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    28: {
        name: "Hotel appartment alexandria sea view",
        location: "Alexandria, Egypt",
        image: "images/filter/alex-royal.jpg",
        coordinates: {
            lat: 31.2001,
            lng: 29.9187
        },
        rating: 8.2,
        reviews: 65,
        price: 400,
        description: "A luxurious hotel located on the shores of the Mediterranean Sea in Alexandria, offering stunning views of the sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.",
        images: [
            "images/filter/alex-royal.jpg",
            "images/hotels/alexandria-royal/1.jpg",
            "images/hotels/alexandria-royal/2.jpg",
            "images/hotels/alexandria-royal/3.jpg",
            "images/hotels/alexandria-royal/4.jpg",
            "images/hotels/alexandria-royal/5.jpg",
            "images/hotels/alexandria-royal/6.jpg",
            "images/hotels/alexandria-royal/7.jpg",
            "images/hotels/alexandria-royal/8.jpg",
            "images/hotels/alexandria-royal/9.jpg"
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-swimming-pool", name: "Infinity Pool" },
            { icon: "fa-utensils", name: "3 Restaurants" },
            { icon: "fa-spa", name: "Luxury Spa" },
            { icon: "fa-volleyball-ball", name: "Water Sports" },
            { icon: "fa-child", name: "Kids Club" },
            { icon: "fa-dumbbell", name: "Fitness Center" },
            { icon: "fa-glass-martini", name: "Beach Bar" }
        ],
        rooms: [
            {
                name: "Deluxe Sea View Room",
                description: "Elegant room with direct sea view",
                image: "images/hotels/alexandria-royal/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "40 m²" },
                    { icon: "fa-umbrella-beach", text: "Sea View Balcony" }
                ],
                price: 400
            },
            {
                name: "Royal Suite",
                description: "Luxury suite with private pool and panoramic view",
                image: "images/hotels/alexandria-royal/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "60 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 1000
            },
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    29: {
        name: "Reef Oasis Beach Aqua Park Resort",
        location: "Sharm El Sheikh, South Sinai",
        image: "images/filter/sharm-reef.jpg",
        coordinates: {
            lat: 27.9158,
            lng: 34.3300
        },
        rating: 9.1,
        reviews: 3254,
        price: 800,
        description: "A luxurious desert resort located on the shores of the Gulf of Aqaba in Sharm El Sheikh, offering stunning views of the desert and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.",
        images: [
            "images/filter/sharm-reef.jpg",
            "images/hotels/sharm-riv-oasis/1.jpg",
            "images/hotels/sharm-riv-oasis/2.jpg",
            "images/hotels/sharm-riv-oasis/3.jpg",
            "images/hotels/sharm-riv-oasis/4.jpg",
            "images/hotels/sharm-riv-oasis/5.jpg",
            "images/hotels/sharm-riv-oasis/6.jpg",
            "images/hotels/sharm-riv-oasis/7.jpg",
            "images/hotels/sharm-riv-oasis/8.jpg",
            "images/hotels/sharm-riv-oasis/9.jpg"
        ],
        amenities: [
            { icon: "fa-tree", name: "Desert Gardens" },
            { icon: "fa-swimming-pool", name: "Infinity Pool" },
            { icon: "fa-utensils", name: "Local Cuisine" },
            { icon: "fa-spa", name: "Bedouin Spa" },
            { icon: "fa-horse", name: "Camel Rides" },
            { icon: "fa-star", name: "Bedouin Nights" },
            { icon: "fa-fire", name: "Campfire Nights" },
            { icon: "fa-moon", name: "Star Gazing" }
        ],
        rooms: [
            {
                name: "Luxury Tent",
                description: "Luxurious Bedouin tent with private pool and garden view",
                image: "images/hotels/sharm-riv-oasis/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "45 m²" },
                    { icon: "fa-tree", text: "Private Garden" }
                ],
                price: 800
            },
            {
                name: "Desert Suite",
                description: "Luxury suite with private pool and garden view",
                image: "images/hotels/sharm-riv-oasis/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "70 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 1700
            },
            {
                name: "Family Suite",
                description: "Spacious suite for families, with two bedrooms and a living area.",
                image: "images/hotels/sharm-riv-oasis/3.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "80 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 2200
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    },
    30: {
        name: "Golden Beach Resort",
        location: "Hurghada, Egypt",
        image: "images/filter/hurghada-golden.jpg",
        coordinates: {
            lat: 27.2579,
            lng: 33.8116
        },
        rating: 8.5,
        reviews: 2951,
        price: 700,
        description: "A luxurious resort located on the shores of the Red Sea in Hurghada, offering stunning views of the sea and a prime location on a sandy beach. It features modern design and comprehensive leisure and relaxation facilities.",
        images: [
            "images/filter/hurghada-golden.jpg",
            "images/hotels/hurghada-golden/1.jpg",
            "images/hotels/hurghada-golden/2.jpg",
            "images/hotels/hurghada-golden/3.jpg",
            "images/hotels/hurghada-golden/4.jpg",
            "images/hotels/hurghada-golden/5.jpg",
            "images/hotels/hurghada-golden/6.jpg",
            "images/hotels/hurghada-golden/7.jpg",
            "images/hotels/hurghada-golden/8.jpg",
            "images/hotels/hurghada-golden/9.jpg"
        ],
        amenities: [
            { icon: "fa-umbrella-beach", name: "Private Beach" },
            { icon: "fa-swimming-pool", name: "Infinity Pool" },
            { icon: "fa-utensils", name: "4 Restaurants" },
            { icon: "fa-water", name: "Water Sports" },
            { icon: "fa-volleyball-ball", name: "Water Sports" },
            { icon: "fa-spa", name: "Spa & Massage" },
            { icon: "fa-child", name: "Kids Club" },
            { icon: "fa-glass-martini", name: "Beach Bar" }
        ],
        rooms: [
            {
                name: "Deluxe Sea View Room",
                description: "Elegant room with direct sea view",
                image: "images/hotels/hurghada-golden/room1.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2 Persons" },
                    { icon: "fa-ruler-combined", text: "40 m²" },
                    { icon: "fa-umbrella-beach", text: "Sea View Balcony" }
                ],
                price: 700
            },
            {
                name: "Beachfront Suite",
                description: "Luxury suite with private terrace overlooking the sea",
                image: "images/hotels/hurghada-golden/room2.jpg",
                features: [
                    { icon: "fa-bed", text: "King Bed" },
                    { icon: "fa-user", text: "2-3 Persons" },
                    { icon: "fa-ruler-combined", text: "65 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 1400
            },
            {
                name: "Family Suite",
                description: "Large suite for families, with two bedrooms and a living area.",
                image: "images/hotels/hurghada-golden/room3.jpg",
                features: [
                    { icon: "fa-bed", text: "Two King Beds" },
                    { icon: "fa-user", text: "4 Persons" },
                    { icon: "fa-ruler-combined", text: "100 m²" },
                    { icon: "fa-couch", text: "Living Room" }
                ],
                price: 1800
            }
        ],
        policies: {
            cancellation: "Free cancellation up to 72 hours before arrival",
            children: "Children up to 12 years stay free",
            pets: "Pets are not allowed",
            smoking: "Non-smoking rooms available"
        }
    }
};

        const params = new URLSearchParams(window.location.search);
        const hotel = params.get('hotel');
        const room = params.get('room');
        const price = params.get('price');

        if (hotel) {
            document.querySelector('.hotel-name').textContent = decodeURIComponent(hotel);
            const previewTitle = document.querySelector('.hotel-preview-title');
            if (previewTitle) previewTitle.childNodes[0].textContent = decodeURIComponent(hotel) + ' ';
        }
        if (room) {
            document.querySelector('.room-type').textContent = decodeURIComponent(room);
        }
        if (price) {
            document.querySelector('.total-price').textContent = price + ' EGP';
        }

        if (hotel && hotelsPreviewData[decodeURIComponent(hotel)]) {
            const data = hotelsPreviewData[decodeURIComponent(hotel)];
            document.querySelector('.hotel-preview-desc').textContent = data.desc;
            document.querySelector('.preview-main-image').src = data.images[0];
            document.querySelectorAll('.preview-thumb').forEach((img, idx) => {
                img.src = data.images[idx] || data.images[0];
            });
            const amenitiesDiv = document.querySelector('.hotel-amenities');
            if (amenitiesDiv) {
                amenitiesDiv.innerHTML = data.amenities.map(a =>
                    `<span class="amenity-badge"><i class="fas ${a.icon}"></i> ${a.text}</span>`
                ).join('');
            }
            const ratingSpan = document.querySelector('.hotel-rating');
            if (ratingSpan) {
                ratingSpan.innerHTML = '★'.repeat(data.rating);
            }
        }

        const hotelSelect = document.getElementById('hotel');
        if (hotelSelect) {
            hotelSelect.addEventListener('change', function() {
                const selectedHotel = this.value;
                if (selectedHotel && hotelsPreviewData[selectedHotel]) {
                    const data = hotelsPreviewData[selectedHotel];
                    
                    document.querySelector('.hotel-name').textContent = selectedHotel;
                    
                    const previewTitle = document.querySelector('.hotel-preview-title');
                    if (previewTitle) previewTitle.childNodes[0].textContent = selectedHotel + ' ';
                    
                    document.querySelector('.hotel-preview-desc').textContent = data.desc;
                    document.querySelector('.preview-main-image').src = data.images[0];
                    
                    document.querySelectorAll('.preview-thumb').forEach((img, idx) => {
                        img.src = data.images[idx] || data.images[0];
                    });
                    
                    const amenitiesDiv = document.querySelector('.hotel-amenities');
                    if (amenitiesDiv) {
                        amenitiesDiv.innerHTML = data.amenities.map(a =>
                            `<span class="amenity-badge"><i class="fas ${a.icon}"></i> ${a.text}</span>`
                        ).join('');
                    }
                    
                    const ratingSpan = document.querySelector('.hotel-rating');
                    if (ratingSpan) {
                        ratingSpan.innerHTML = '★'.repeat(data.rating);
                    }
                }
            });
        }

        const roomSelect = document.getElementById('room-type');
        if (roomSelect) {
            roomSelect.addEventListener('change', function() {
                document.querySelector('.room-type').textContent = this.value;
            });
        }

        const checkInInput = document.getElementById('check-in');
        if (checkInInput) {
            checkInInput.addEventListener('change', function() {
                document.querySelector('.check-in').textContent = this.value;
            });
        }

        const checkOutInput = document.getElementById('check-out');
        if (checkOutInput) {
            checkOutInput.addEventListener('change', function() {
                document.querySelector('.check-out').textContent = this.value;
            });
        }

        const guestsInput = document.getElementById('guests');
        if (guestsInput) {
            guestsInput.addEventListener('change', function() {
                document.querySelector('.guests').textContent = this.value;
            });
        }

        const roomsInput = document.getElementById('rooms');
        if (roomsInput) {
            roomsInput.addEventListener('change', function() {
                document.querySelector('.rooms').textContent = this.value;
            });
        }
    });
    </script>
    <script>
        AOS.init();
        const openSidebarBtn = document.getElementById('openSidebarBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');
        const sidebarMenu = document.getElementById('sidebarMenu');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebarMenu.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebarMenu.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        openSidebarBtn.addEventListener('click', openSidebar);
        closeSidebarBtn.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSidebar();
        });
    </script>
</body>
</html> 