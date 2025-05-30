<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egypt Hotels | Gallery</title>
    <link rel="shortcut icon" href="../assets/images/icons/web-icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="../assets/css/navbar_sidebar.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/gallery.css">
    <link rel="stylesheet" href="../assets/css/images.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/notification.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'navbar_sidebar.php'; ?>

    <!-- Hero Section -->
    <section class="gallery-hero">
        <div class="hero-container">
            <div class="hero-content" data-aos="fade-right">
                <h1>Moments Captured For You</h1>
                <p class="hero-description">Share your unforgettable moments and experiences with us. Your memories become part of our story.</p>
            </div>
            <div class="hero-upload" data-aos="fade-left">
                <div class="upload-card">
                    <h2>Share Your Experience</h2>
                    <form id="userUploadForm" action="/Booking-Hotel-Project/upload_user_image.php" method="POST" enctype="multipart/form-data">
                        <div class="upload-input-group">
                            <label for="user_image" class="upload-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Choose Photo</span>
                            </label>
                            <input type="file" id="user_image" name="user_image" accept="image/*" required>
                            <img id="previewImage" src="" alt="Preview" style="display:none;max-width:100%;max-height:150px;margin-top:10px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);" />
                        </div>
                        <textarea name="caption" placeholder="Write a caption for your photo..." rows="2"></textarea>
                        <button type="submit" class="upload-btn">
                            <i class="fas fa-paper-plane"></i> Share Your Moment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php if (isset($_GET['upload'])): ?>
      <div class="upload-message <?php echo $_GET['upload'] === 'success' ? 'success' : 'error'; ?>" id="uploadMessage">
        <span class="close-upload-message" style="margin-left:10px;cursor:pointer;font-size:1.3rem;font-weight:bold;">&times;</span>
        <?php if ($_GET['upload'] === 'success'): ?>
          <i class="fas fa-check-circle"></i> Photo uploaded successfully! It will appear after review.
        <?php else: ?>
          <i class="fas fa-times-circle"></i> Error uploading photo. Please try again.
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <section class="section user-gallery">
      <h2 class="section-title">User Gallery</h2>
      <div class="gallery-grid">
        <?php
        require_once __DIR__ . '/../config/database.php';
        $images = $pdo->query("SELECT * FROM user_gallery WHERE approved = 1 ORDER BY id DESC")->fetchAll();
        foreach ($images as $img) {
            echo '<div class="gallery-item user">';
            echo '<img src="../assets/user_uploads/' . htmlspecialchars($img['image']) . '" alt="User Photo">';
            echo '<div class="overlay"><h3>' . htmlspecialchars($img['caption']) . '</h3></div>';
            echo '</div>';
        }
        ?>
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

    <div class="lightbox-modal">
        <div class="lightbox-content">
            <span class="close-lightbox">&times;</span>
            <img src="" alt="" class="lightbox-image">
            <div class="lightbox-caption"></div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/gallery.js"></script>
    <script src="../assets/js/lazy-load.js"></script>
    <script src="../assets/js/notification.js"></script>
    <script>
        AOS.init();
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
    <script>
    document.getElementById('user_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('previewImage');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.src = ev.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    });
    </script>
    <script>
    // Auto-hide upload message after 10 seconds
    const uploadMsg = document.getElementById('uploadMessage');
    if (uploadMsg) {
        setTimeout(() => {
            uploadMsg.style.display = 'none';
        }, 10000);
        // Hide on close button click
        uploadMsg.querySelector('.close-upload-message').onclick = function() {
            uploadMsg.style.display = 'none';
        };
    }
    </script>
</body>
</html> 