<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egypt Hotels | Explore</title>
    <link rel="shortcut icon" href="../assets/images/icons/web-icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/navbar_sidebar.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/explore.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
</head>
<body>
    <?php include 'navbar_sidebar.php'; ?>

    <section class="explore-hero">
        <div class="explore-hero-overlay"></div>
        <div class="explore-hero-content" data-aos="fade-up">
            <div class="explore-hero-text">
                <h1>Discover Your Perfect Stay</h1>
                <p>Explore Egypt's finest hotels and resorts</p>
            </div>
            <div class="search-container">
                <form class="search-form" id="searchForm">
                    <div class="search-group location-group">
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" id="locationInput" placeholder="Where do you want to stay?" class="location-input" autocomplete="off">
                        <div class="autocomplete-results" id="autocompleteResults"></div>
                    </div>
                    <div class="search-group date-group">
                        <i class="fas fa-calendar"></i>
                        <input type="date" id="checkInInput" placeholder="Check-in date" class="date-input">
                        <div class="date-picker" id="checkInPicker"></div>
                    </div>
                    <div class="search-group date-group">
                        <i class="fas fa-calendar"></i>
                        <input type="date" id="checkOutInput" placeholder="Check-out date" class="date-input">
                        <div class="date-picker" id="checkOutPicker"></div>
                    </div>
                    <div class="search-group guests-group">
                        <i class="fas fa-user-friends"></i>
                        <input type="text" id="guestsInput" placeholder="Guests & Rooms" class="guests-input" readonly>
                        <div class="guests-picker" id="guestsPicker">
                            <div class="guests-row">
                                <div class="guests-label">Adults</div>
                                <div class="guests-counter">
                                    <button type="button" class="counter-btn" data-type="adults" data-op="dec">-</button>
                                    <span class="counter-value" id="adultsValue">1</span>
                                    <button type="button" class="counter-btn" data-type="adults" data-op="inc">+</button>
                                </div>
                            </div>
                            <div class="guests-row">
                                <div class="guests-label">Children</div>
                                <div class="guests-counter">
                                    <button type="button" class="counter-btn" data-type="children" data-op="dec">-</button>
                                    <span class="counter-value" id="childrenValue">0</span>
                                    <button type="button" class="counter-btn" data-type="children" data-op="inc">+</button>
                                </div>
                            </div>
                            <div class="guests-row">
                                <div class="guests-label">Rooms</div>
                                <div class="guests-counter">
                                    <button type="button" class="counter-btn" data-type="rooms" data-op="dec">-</button>
                                    <span class="counter-value" id="roomsValue">1</span>
                                    <button type="button" class="counter-btn" data-type="rooms" data-op="inc">+</button>
                                </div>
                            </div>
                            <div class="guests-picker-footer">
                                <button type="button" class="done-btn">Done</button>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i>Search Hotels</button>
                </form>
            </div>
        </div>
    </section>
    
    <div class="hotels-page-layout" style="display: flex; gap: 32px; align-items: flex-start;">
        <div class="filters-sidebar" style="display: flex; flex-direction: column; gap: 18px; min-width: 260px;">
            <!-- Cities Filter Container -->
            <aside class="sidebar-filter">
                <h3>Filter by City</h3>
                <div class="filter-group">
                    <div class="checkbox-group" id="cityGroup">
                        <!-- Cities will be dynamically populated -->
                    </div>
                </div>
            </aside>
        </div>
        <div class="hotels-container listings-grid">
            <div class="hotels-toolbar">
                <div class="hotels-count" id="hotelsCount">300+ properties</div>
                <div class="sort-by">
                    <label for="sortSelect">Sort by</label>
                    <select id="sortSelect">
                        <option value="recommended">Recommended</option>
                        <option value="price_low_high">Price (low to high)</option>
                        <option value="price_high_low">Price (high to low)</option>
                        <option value="rating_high_low">Rating (high to low)</option>
                        <option value="rating_low_high">Rating (low to high)</option>
                    </select>
                </div>
            </div>
            <div id="hotelsCards" class="listings-grid"></div>
        </div>
    </div>

    <!-- Map Section: always visible below hotels -->
    <div class="map-section" style="display: block; margin-top: 30px;">
        <div class="map-header">
            <h2>Explore Hotels on Map</h2>
        </div>
        <div id="hotels-map" style="height: 600px; width: 100%; border-radius: 12px;"></div>
    </div>

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

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="../assets/js/filters.js"></script>
    <script src="../assets/js/search.js"></script>
    <script src="../assets/js/explore.js"></script>
    <script src="../assets/js/script.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
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
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
</body>
</html>
