<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egypt Hotels | About Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="../assets/images/icons/web-icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/hotel-details.css">
    <style>
        /* Main Styles */
        body {
            background-color: #f8f9fa;
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            color: #1e5dd1;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 8px rgba(30, 93, 209, 0.1);
            border: 1px solid rgba(30, 93, 209, 0.1);
            margin-bottom: 2rem;
        }

        .back-button:hover {
            background: #f0f5ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 93, 209, 0.15);
        }

        .back-button i {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        /* Content Sections */
        .full-hero-img { 
            border-radius: 18px; 
            box-shadow: 0 8px 32px rgba(30, 93, 209, 0.1), 0 1.5px 8px rgba(30, 93, 209, 0.1);
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .full-gallery-thumb { 
            cursor: pointer; 
            border: 2px solid #ececec; 
            border-radius: 12px; 
            transition: all 0.3s ease;
            aspect-ratio: 16/9;
            object-fit: cover;
        }

        .full-gallery-thumb.active, 
        .full-gallery-thumb:hover { 
            border: 2px solid #1e5dd1;
            transform: translateY(-2px);
        }

        .full-section-title { 
            font-family: 'Playfair Display', serif; 
            font-size: 2rem; 
            font-weight: 700; 
            color: #1a2942; 
            margin-bottom: 1.5rem; 
            margin-top: 3rem;
            position: relative;
            padding-bottom: 1rem;
        }

        .full-section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: #1e5dd1;
            border-radius: 3px;
        }

        .full-amenity { 
            display: inline-flex; 
            align-items: center; 
            gap: 0.75rem; 
            background: #f7f6f2; 
            color: #1a2942; 
            border-radius: 2rem; 
            padding: 0.6rem 1.2rem; 
            margin: 0.3rem 0.5rem 0.3rem 0; 
            font-size: 1rem;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .full-amenity:hover {
            background: white;
            border-color: #1e5dd1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 93, 209, 0.1);
        }

        .full-amenity i { 
            color: #1e5dd1; 
            font-size: 1.2rem;
        }

        .full-policy-card { 
            background: #fff; 
            border-radius: 16px; 
            box-shadow: 0 4px 20px rgba(26, 41, 66, 0.08); 
            border: 1px solid #ececec; 
            padding: 1.5rem; 
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .full-policy-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(26, 41, 66, 0.12);
        }

        .full-shadow { 
            box-shadow: 0 8px 32px rgba(30, 93, 209, 0.1), 0 1.5px 8px rgba(30, 93, 209, 0.1);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .full-section-title {
                font-size: 1.75rem;
                margin-top: 2rem;
                margin-bottom: 1.2rem;
            }

            .full-amenity {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .full-policy-card {
                padding: 1.2rem;
            }
        }

        @media (max-width: 576px) {
            .full-section-title {
                font-size: 1.5rem;
            }

            .back-button {
                width: 100%;
                justify-content: center;
            }
        }

        /* Footer Styles */
        .footer {
            background: linear-gradient(120deg, #8fa9d3 60%, #21438b 100%);
            color: #fff;
            padding: 4rem 0 2rem 0;
            box-shadow: 0 -4px 32px rgba(37,99,235,0.10);
            margin-top: 4rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-section h3 {
            color: #fff;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.8rem;
        }

        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: rgba(255,255,255,0.3);
        }

        .footer-logo {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .footer-logo-img {
            height: 45px;
            width: auto;
            object-fit: contain;
            filter: brightness(0) invert(1);
            transition: all 0.3s ease;
        }

        .footer-logo:hover .footer-logo-img {
            transform: scale(1.05);
            opacity: 0.9;
        }

        .footer-section p, 
        .footer-section li {
            color: #e0e7ef;
            font-size: 1rem;
            margin-bottom: 0.8rem;
            line-height: 1.6;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links a {
            color: #e0e7ef;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-links a {
            color: #fff;
            font-size: 1.3rem;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .social-links a:hover {
            background: white;
            color: #1e5dd1;
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #e0e7ef;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="container my-5">
            <a href="hotel-details.php?id=<?php echo htmlspecialchars($_GET['id']); ?>" class="back-button">
                <i class="bi bi-arrow-left"></i> Back to Hotel
            </a>
            <main id="full-details-main">
                <!-- Content will be loaded dynamically -->
            </main>
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
                            <a href="https://www.facebook.com/profile.php?id=61576084713550" aria-label="Follow us on Facebook" target="_blank">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="https://x.com/egypt_hotels25" aria-label="Follow us on Twitter" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.instagram.com/egypt_hotels25/" aria-label="Follow us on Instagram" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.linkedin.com/in/egypt-hotels-404222365/" aria-label="Follow us on LinkedIn" target="_blank">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        </div>
                    </div>
                    <div class="footer-section">
                        <h3>Quick Links</h3>
                        <ul class="footer-links">
                            <li><a href="explore.php"><i class="bi bi-chevron-right me-2"></i>Explore Hotels</a></li>
                            <li><a href="gallery.php"><i class="bi bi-chevron-right me-2"></i>Photo Gallery</a></li>
                            <li><a href="about.php"><i class="bi bi-chevron-right me-2"></i>About Us</a></li>
                            <li><a href="contact.php"><i class="bi bi-chevron-right me-2"></i>Contact</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h3>Contact Info</h3>
                        <ul class="footer-links">
                            <li><i class="fas fa-map-marker-alt me-2"></i> Banisuef</li>
                            <li><i class="fas fa-phone me-2"></i> +20 1069787819</li>
                            <li><i class="fas fa-envelope me-2"></i> egypthotels25@gmail.com</li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; 2024 Egypt Hotels. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/hotel-full-details.js"></script>
</body>
</html> 