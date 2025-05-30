<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egypt Hotels</title>
    <link rel="shortcut icon" href="../assets/images/icons/web-icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../assets/css/styles.css">
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

    <section class="home-hero">
        <div class="home-hero-overlay"></div>
        <div class="home-hero-content animate__animated animate__fadeInDown">
            <h1>Egypt Hotels</h1>
            <p>Discover Egypt's Finest Hotels</p>
            <a href="explore.php" class="home-hero-cta animate__animated animate__fadeInUp animate__delay-0.7s">Explore</a>
            <img  src="../pages/images/background.png" alt="Egypt Hotels Background" class="home-hero-background animate__animated animate__fadeInUp animate__delay-0.7s">
        </div>
    </section>

    <section class="featured-hotels section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Featured Hotels</h2>
            <p class="section-subtitle" data-aos="fade-up">Discover our handpicked selection of luxury accommodations</p>
            <div class="grid grid-cols-3 gap-lg">
                <div class="card hotel-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="hotel-badge">Best Seller</div>
                    <img src="../assets/images/Featured/1.jpg" alt="Nile Palace" class="card-img">
                    <div class="card-content">
                        <div class="hotel-header">
                            <h3>The Nile Ritz-Carlton</h3>
                            <div class="hotel-rating">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                                <span class="rating-count">(4.4)</span>
                            </div>
                        </div>
                        <p class="hotel-location"><i class="fas fa-map-marker-alt"></i> Cairo, Egypt</p>
                        <div class="hotel-features">
                            <span><i class="fas fa-wifi"></i> Free WiFi</span>
                            <span><i class="fas fa-swimming-pool"></i> Pool</span>
                            <span><i class="fas fa-utensils"></i> Restaurant</span>
                        </div>
                        <div class="hotel-price">
                            <span class="price">600 EGP</span>
                            <span class="per-night">per night</span>
                        </div>
                        <a href="hotel-details.php?id=1" class="button button-primary">View Details</a>
                    </div>
                </div>
                <div class="card hotel-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="hotel-badge">Popular</div>
                    <img src="../assets/images/Featured/2.jpg" alt="Red Sea Resort" class="card-img">
                    <div class="card-content">
                        <div class="hotel-header">
                            <h3>Serry Beach Resort</h3>
                            <div class="hotel-rating">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                                <span class="rating-count">(4.2)</span>
                            </div>
                        </div>
                        <p class="hotel-location"><i class="fas fa-map-marker-alt"></i> Hurghada, Egypt</p>
                        <div class="hotel-features">
                            <span><i class="fas fa-wifi"></i> Free WiFi</span>
                            <span><i class="fas fa-umbrella-beach"></i> Beach</span>
                            <span><i class="fas fa-spa"></i> Spa</span>
                        </div>
                        <div class="hotel-price">
                            <span class="price">500 EGP</span>
                            <span class="per-night">per night</span>
                        </div>
                        <a href="hotel-details.php?id=4" class="button button-primary">View Details</a>
                    </div>
                </div>
                <div class="card hotel-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="hotel-badge">Luxury</div>
                    <img src="../assets/images/Featured/3.jpg" alt="Alexandria Grand" class="card-img">
                    <div class="card-content">
                        <div class="hotel-header">
                            <h3>Four Seasons San Stefano</h3>
                            <div class="hotel-rating">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                <span class="rating-count">(4.8)</span>
                            </div>
                        </div>
                        <p class="hotel-location"><i class="fas fa-map-marker-alt"></i> Alexandria, Egypt</p>
                        <div class="hotel-features">
                            <span><i class="fas fa-wifi"></i> Free WiFi</span>
                            <span><i class="fas fa-concierge-bell"></i> Room Service</span>
                            <span><i class="fas fa-dumbbell"></i> Gym</span>
                        </div>
                        <div class="hotel-price">
                            <span class="price">1000 EGP</span>
                            <span class="per-night">per night</span>
                        </div>
                        <a href="hotel-details.php?id=2" class="button button-primary">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="destinations section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Popular Destinations</h2>
            <div class="grid grid-cols-4 gap-lg">
                <div class="destination-card card" data-aos="fade-up" data-aos-delay="100" data-city="Cairo">
                    <img src="../assets/images/destinations/cairo.jpeg" alt="Cairo" class="card-img">
                    <div class="card-content">
                        <h3>Cairo</h3>
                        <p>City of a Thousand Minarets</p>
                        <a href="explore.php?city=Cairo" class="button button-outline">Explore</a>
                    </div>
                </div>
                <div class="destination-card card" data-aos="fade-up" data-aos-delay="200" data-city="Luxor">
                    <img src="../assets/images/destinations/luxor.jpeg" alt="Luxor" class="card-img">
                    <div class="card-content">
                        <h3>Luxor</h3>
                        <p>Ancient wonders and temples</p>
                        <a href="explore.php?city=Luxor" class="button button-outline">Explore</a>
                    </div>
                </div>
                <div class="destination-card card" data-aos="fade-up" data-aos-delay="300" data-city="Sharm El-Sheikh">
                    <img src="../assets/images/destinations/sharm.jpeg" alt="Sharm El Sheikh" class="card-img">
                    <div class="card-content">
                        <h3>Sharm El-Sheikh</h3>
                        <p>Red Sea paradise</p>
                        <a href="explore.php?city=Sharm El-Sheikh" class="button button-outline">Explore</a>
                    </div>
                </div>
                <div class="destination-card card" data-aos="fade-up" data-aos-delay="400" data-city="Aswan">
                    <img src="../assets/images/destinations/aswan.jpeg" alt="Aswan" class="card-img">
                    <div class="card-content">
                        <h3>Aswan</h3>
                        <p>Nubian culture & Nile views</p>
                        <a href="explore.php?city=Aswan" class="button button-outline">Explore</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="why-us section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Why Choose Egypt Hotels?</h2>
            <div class="grid grid-cols-4 gap-lg">
                <div class="feature-card card" data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-award fa-2x accent"></i>
                    <h3>Top Rated</h3>
                    <p>Handpicked hotels with the best reviews</p>
                </div>
                <div class="feature-card card" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-headset fa-2x accent"></i>
                    <h3>24/7 Support</h3>
                    <p>Always here to help you</p>
                </div>
                <div class="feature-card card" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-lock fa-2x accent"></i>
                    <h3>Secure Booking</h3>
                    <p>Safe and easy online reservations</p>
                </div>
                <div class="feature-card card" data-aos="fade-up" data-aos-delay="400">
                    <i class="fas fa-tags fa-2x accent"></i>
                    <h3>Best Price</h3>
                    <p>Unbeatable deals and offers</p>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonials section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">What Our Guests Say</h2>
            <div class="grid grid-cols-3 gap-lg">
                <div class="testimonial-card card" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-text">"The best hotel experience I've ever had. Everything was perfect!"</div>
                    <div class="testimonial-author">
                        <img src="../assets/images/testimonials/1.jpeg" alt="Guest" class="author-image">
                        <div>
                            <div class="author-name">James Carter</div>
                            <div class="author-title">London, UK</div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card card" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-text">"Booking was so easy and the staff were incredibly helpful."</div>
                    <div class="testimonial-author">
                        <img src="../assets/images/testimonials/3.jpeg" alt="Guest" class="author-image">
                        <div>
                            <div class="author-name">Sarah Ahmed</div>
                            <div class="author-title">Dubai, UAE</div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card card" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-text">"Amazing locations and great value. Highly recommended!"</div>
                    <div class="testimonial-author">
                        <img src="../assets/images/testimonials/2.jpeg" alt="Guest" class="author-image">
                        <div>
                            <div class="author-name">Mohamed El Sayed</div>
                            <div class="author-title">Cairo, Egypt</div>
                        </div>
                    </div>
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