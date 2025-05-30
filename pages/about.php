<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egypt Hotels | About Us</title>
    <link rel="shortcut icon" href="../assets/images/icons/web-icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
</head>
<body>
    <?php include 'navbar_sidebar.php'; ?>

    <!-- Hero Section -->
    <section class="about-hero">
        <div class="about-hero-overlay"></div>
        <div class="about-hero-content" >
            <h1>ABOUT US</h1>
        </div>
    </section>

    <!-- Intro Section -->
    <section class="about-intro">
        <div class="intro-container">
            <div class="intro-images">
                <div class="intro-img-sm-col">
                    <div class="intro-img-small"><img src="../assets/images/icons/team.jpg" alt="Meeting"></div>
                </div>
            </div>
            <div class="intro-content">
                <h2><span class="accent">Let tomorrow</span> begin today.</h2>
                <p>Welcome to Your Gateway to Egypt's Finest Hotels. Discover the best of Egyptian hospitality with our dedicated platform, offering you access to a handpicked selection of top hotels across Egypt — from luxurious Red Sea resorts to historic boutique hotels in the heart of Cairo and Alexandria. We provide a seamless booking experience that combines comfort, elegance, and exclusive offers.</p>
                <div class="intro-blocks">
                    <div class="intro-block">
                        <h4>Our Mission</h4>
                    </div>
                    <div class="intro-block">
                        <p>To deliver an unparalleled hotel booking experience, helping travelers explore the beauty of Egypt through premium stays, exclusive deals, and outstanding customer support — creating lasting memories for every guest.</p>
                    </div>
                    <div class="intro-block">                        
                        <h4>Our Strategy</h4>
                    </div>
                    <div class="intro-block">                        
                        <p>We provide tailored travel and hotel deals, group reservations, and curated local recommendations to ensure every guest enjoys a unique and memorable stay.</p>
                    </div>
                    <div class="intro-block">
                        <h4>Our Vision</h4>
                    </div>
                    <div class="intro-block">
                        <p>To be recognized as the leading online destination for hotel bookings in Egypt, setting new standards in customer experience while promoting transparency, innovation, local tourism, and showcasing the rich diversity of Egyptian hospitality to the world.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="about-stats">
        <div class="about-stats-overlay">
        <div class="stats-grid">
            <div class="stat-item">
                <i class="fas fa-briefcase"></i>
                <div class="stat-number">10</div>
                <div class="stat-label">Cases Completed</div>
            </div>
            <div class="stat-item">
                <i class="fas fa-user-friends"></i>
                <div class="stat-number">5</div>
                <div class="stat-label">Consultants</div>
            </div>
            <div class="stat-item">
                <i class="fas fa-calendar-alt"></i>
                <div class="stat-number">1</div>
                <div class="stat-label">Years of Experiences</div>
            </div>
        </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="about-why">
        <div class="why-header">
            <div class="why-accent"></div>
            <h2>Why to choose us</h2>
            <p class="why-subtitle">As integrity, partners grow with you!</p>
        </div>
        <div class="why-grid">
            <div class="why-item"><span class="why-icon"><i class="fas fa-user-tie"></i></span><h4>Professional</h4><p>We are passionate about creating exceptional experiences for our guests.</p></div>
            <div class="why-item"><span class="why-icon"><i class="fas fa-briefcase"></i></span><h4>Experienced</h4><p>We strive for excellence in every aspect of our service and operations.</p></div>
            <div class="why-item"><span class="why-icon"><i class="fas fa-bolt"></i></span><h4>Energy</h4><p>We are committed to environmental responsibility and sustainable practices.</p></div>
            <div class="why-item"><span class="why-icon"><i class="fas fa-handshake"></i></span><h4>Honesty</h4><p>We conduct our business with honesty, transparency, and ethical practices.</p></div>
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

    <script src="../assets/js/about.js"></script>
    <script src="../assets/js/script.js"></script>
    <script>
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
    <script>
        var header = document.getElementById('header');
if (header) {
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
        if (currentScroll <= 0) {
            header.classList.remove('scroll-up');
            return;
        }

        if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
            header.classList.remove('scroll-up');
            header.classList.add('scroll-down');
        } else if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
            header.classList.remove('scroll-down');
            header.classList.add('scroll-up');
    }
    
    lastScroll = currentScroll;
});
}
</script>
</body>
</html> 