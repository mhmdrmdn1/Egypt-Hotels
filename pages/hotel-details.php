<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egypt Hotels | Hotel Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="../assets/images/icons/web-icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/hotel-details.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
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
                            $profile_image_path = isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image']) 
                                ? (strpos($_SESSION['profile_image'], '../') === 0 ? htmlspecialchars($_SESSION['profile_image']) : '../' . htmlspecialchars($_SESSION['profile_image'])) 
                                : '../images/default-avatar.png';
                        ?>
                        <img src="<?php echo $profile_image_path; ?>" alt="Profile Image" class="profile-image">
                    </div>
                    <span class="username" style="display: flex; flex-direction: column; align-items: center; text-align: center; width: 100%;">
                        <h3 style="margin-bottom: 0;">
                            <?php
                            if (isset($_SESSION['first_name']) && isset($_SESSION['last_name']) && $_SESSION['first_name'] && $_SESSION['last_name']) {
                                echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']);
                            } elseif (isset($_SESSION['username'])) {
                                echo htmlspecialchars($_SESSION['username']);
                            } else {
                                echo 'User';
                            }
                            ?>
                        </h3>
                        <p class="user-email" style="margin-top: 0; font-size: 0.95em; color: #888;">
                            <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>
                        </p>
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

    <main class="container my-4">
        <div class="row g-4 align-items-stretch" id="hero-section">
            <div class="col-lg-7" id="carousel-container">
            </div>
            <div class="col-lg-5 d-flex flex-column justify-content-between" id="hotel-info-container">
            </div>
        </div>
        <section class="my-5" id="about-section">
        </section>
        <section class="my-5" id="amenities-section">
            <h2 class="mb-4">Amenities & Services</h2>
            <div class="row" id="amenities-cards">
            </div>
        </section>
        <section class="my-5" id="rooms-section">
            <h2 class="mb-4">Available Rooms</h2>
            <div class="row" id="rooms-cards">
            </div>
        </section>
        <section class="my-5" id="location-section">
            <h2 class="mb-4">Hotel Location</h2>
            <div id="hotel-map" style="height: 350px; border-radius: 12px; overflow: hidden;"></div>
        </section>
        <section class="my-5" id="policies-section">
            <h2 class="mb-4">Hotel Policies</h2>
            <div class="row" id="policies-cards">
                <div class="col-12">
                    <div class="policies-container">
                        <div class="policy-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <h5 class="mb-0 policy-link" data-video="https://www.youtube.com/watch?v=KQKerrZoMRo" style="cursor:pointer;color:#1e5dd1;"><i class="fa fa-times-circle text-primary me-2"></i> Cancellation Policy</h5>
                                <button class="btn btn-sm btn-outline-primary ms-3 policy-video-btn" data-video="https://www.youtube.com/watch?v=KQKerrZoMRo">watch video</button>
                            </div>
                            <p>Full refund for cancellation within 12 hours</p>
                        </div>
                        <div class="policy-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <h5 class="mb-0 policy-link" data-video="https://www.youtube.com/watch?v=p1dK3kc-Q1M" style="cursor:pointer;color:#1e5dd1;"><i class="fa fa-child text-primary me-2"></i> Children Policy</h5>
                                <button class="btn btn-sm btn-outline-primary ms-3 policy-video-btn" data-video="https://www.youtube.com/watch?v=p1dK3kc-Q1M">watch video</button>
                            </div>
                            <p>Children under 8 stay free</p>
                        </div>
                        <div class="policy-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <h5 class="mb-0 policy-link" data-video="https://www.youtube.com/watch?v=cKw8H6C2eZw" style="cursor:pointer;color:#1e5dd1;"><i class="fa fa-paw text-primary me-2"></i> Pets Policy</h5>
                                <button class="btn btn-sm btn-outline-primary ms-3 policy-video-btn" data-video="https://www.youtube.com/watch?v=cKw8H6C2eZw">watch video</button>
                            </div>
                            <p>Pets allowed with deposit</p>
                        </div>
                        <div class="policy-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <h5 class="mb-0 policy-link" data-video="https://www.youtube.com/watch?v=9WOPtXMqE5E" style="cursor:pointer;color:#1e5dd1;"><i class="fa fa-smoking text-primary me-2"></i> Smoking Policy</h5>
                                <button class="btn btn-sm btn-outline-primary ms-3 policy-video-btn" data-video="https://www.youtube.com/watch?v=9WOPtXMqE5E">watch video</button>
                            </div>
                            <p>Non-smoking rooms only</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
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
    <div class="modal fade" id="policyVideoModal" tabindex="-1" aria-labelledby="policyVideoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="policyVideoModalLabel">Policy Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="ratio ratio-16x9">
                        <iframe id="policyVideoFrame" src="" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade amenity-modal" id="amenityModal" tabindex="-1" aria-labelledby="amenityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="amenityModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="amenityModalBody">
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/hotel-details.js"></script>
    <script src="../assets/js/script.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var openSidebarBtn = document.getElementById('openSidebarBtn');
        var closeSidebarBtn = document.getElementById('closeSidebarBtn');
        var sidebarMenu = document.getElementById('sidebarMenu');
        var sidebarOverlay = document.getElementById('sidebarOverlay');
        if(openSidebarBtn && closeSidebarBtn && sidebarMenu && sidebarOverlay) {
            openSidebarBtn.addEventListener('click', function() {
                sidebarMenu.classList.add('active');
                sidebarOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
            closeSidebarBtn.addEventListener('click', function() {
                sidebarMenu.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
            sidebarOverlay.addEventListener('click', function() {
                sidebarMenu.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
            window.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    sidebarMenu.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }
        document.querySelectorAll('img').forEach(function(img) {
            img.onerror = function() {
                console.error('Image failed to load:', this.src);
            };
        });
    });
    </script>
    <script>
    window.onerror = function(message, source, lineno, colno, error) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/Booking-Hotel-Project/pages/api/js_error_logger.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify({
            message: message,
            source: source,
            lineno: lineno,
            colno: colno,
            error: error ? error.stack : null,
            url: window.location.href
        }));
        console.error('JS Error:', message, source, lineno, colno, error);
    };
    </script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.AOS) {
            AOS.init();
        }
    });
    </script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</body>
</html>
