<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/icons/web-icon.png" type="image/x-icon">
    <title>Egypt Hotel - Welcome</title>
    <link rel="stylesheet" href="assets/css/welcome.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <nav class="navbar container">
            <a class="logo">
                <img src="pages/images/Logo.png" alt="Egypt Hotels Logo" class="logo-img">
            </a>
            <div class="nav-icons">
                <ul class="nav-links">
                    <li><a href="pages/login/login.html" class="sign-in-btn">Sign In</a></li>
                    <li><a href="pages/login/login.html" class="sign-up-btn">Sign Up</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <section class="hero-container">
        <div class="hero-left">
            <h1 class="hero-headline">
                <span class="bold">Egypt</span> <span class="italic">Hotels</span><br>
                <span class="italic">Anytime,</span> <span class="bold">Anywhere</span>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <a href="pages/login/login.html" class="cta-btn">Get Started</a>
                    <span class="arrow-animate">
                        <svg width="35" height="35" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 12H6" stroke="#2563eb" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 18L6 12L12 6" stroke="#2563eb" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </h1>
        </div>
        <div class="scroll-down-arrow" onclick="document.getElementById('aboutSection').scrollIntoView({behavior: 'smooth'});">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 5v14M19 12l-7 7-7-7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </section>
    <!-- About Section (Dark Glassmorphism) -->
    <section class="about-section dark-glass aos-fade-up" id="aboutSection">
        <div class="about-bg-shapes">
            <span class="shape shape1"></span>
            <span class="shape shape2"></span>
        </div>
        <div class="about-content">
            <div class="about-left">
                <h2 class="section-title">ABOUT</h2>
                <p class="about-desc">
                    Egypt Hotels is your gateway to booking the best hotels across Egypt — whether<br> you're planning a business trip to Cairo, a beach getaway in Hurghada,<br> or a cultural retreat in Luxor. We bring you verified hotel options,<br> seamless booking, and 24/7 support, all in one modern platform.
                </p>
                <a href="index.php" class="btn learn-more">Learn More</a>
            </div>
            <div class="about-right">
                <div class="stat"><span class="stat-number">30+</span> Hotels Listed</div>
                <div class="stat"><span class="stat-number">10+</span> Cities Covered</div>
                <div class="stat"><span class="stat-number">24/7</span> Support</div>
            </div>
        </div>
        <div class="scroll-down-arrow" onclick="document.getElementById('servicesSection').scrollIntoView({behavior: 'smooth'});">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 5v14M19 12l-7 7-7-7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </section>
    <!-- Our Services Section -->
    <section class="services-section dark-glass aos-fade-up" id="servicesSection">
        <div class="container">
            <h2 class="section-title">OUR SERVICES</h2>
            <p class="section-subtitle">Everything you need to book the perfect stay, your way.</p>
            <div class="services-grid">
                <div class="service-card highlight">
                    <div class="service-icon"><i class="fas fa-hotel"></i></div>
                    <h3 class="service-title">Hotel Booking</h3>
                    <p class="service-desc">Easily find and reserve hotels across Egypt with verified reviews and best rates.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-gift"></i></div>
                    <h3 class="service-title">Custom Packages</h3>
                    <p class="service-desc">Get tailored travel and hotel deals for your unique needs and preferences.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-users"></i></div>
                    <h3 class="service-title">Group Reservations</h3>
                    <p class="service-desc">Book for events, conferences, or families with special group rates and support.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-map-marked-alt"></i></div>
                    <h3 class="service-title">Local Recommendations</h3>
                    <p class="service-desc">Explore top restaurants, attractions, and experiences curated for you.</p>
                </div>
            </div>
        </div>
    </section>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.Swiper) {
            new Swiper('.hotels-slider', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                pagination: { el: '.swiper-pagination', clickable: true },
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
            });
        }
    });
    </script>

<script src="assets/js/welcome-animations.js"></script>
    <footer class="footer-section">
        <div class="footer-glass">
            <div class="footer-content">
                <div class="footer-brand">
                    <img src="pages/images/Logo.png" alt="Egypt Hotels Logo" class="footer-logo">
                </div>
                <div class="footer-links">
                    <a href="index.php">Home</a>
                    <a href="index.php">Explore</a>
                    <a href="index.php">About</a>
                    <a href="index.php">Contact</a>
                </div>
                <div class="footer-social">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="footer-bottom">
                <span>&copy; 2025 Egypt Hotels. All rights reserved.</span>
            </div>
        </div>
    </footer>
</body>
</html> 