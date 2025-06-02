<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details | Egypt Hotels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="../assets/images/icons/web-icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            color: #1e5dd1;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .back-btn:hover {
    background: #1e5dd1;
    color: white;
    transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(30,93,209,0.2);
        }
        .room-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(44,62,80,0.08);
            padding: 2rem;
    margin: 0;
            width: 100vw;
            max-width: 100vw;
            min-height: 100vh;
            animation: fadeIn 1s;
        }
        .room-image { border-radius: 16px; box-shadow: 0 4px 16px rgba(30,93,209,0.08); width: 100%; height: 320px; object-fit: cover; }
        .room-title { font-size: 2rem; font-weight: 700; color: #1e5dd1; }
        .room-price { font-size: 1.5rem; font-weight: 600; color: #2ecc71; }
        .usd-price { color: #888; font-size: 1rem; margin-left: 8px; }
        .desc { color: #444; margin: 1rem 0 1.5rem 0; }
        .features-list, .policies-list { list-style: none; padding: 0; margin: 0; }
        .features-list li, .policies-list li { margin-bottom: 0.5rem; font-size: 1.05rem; }
        .features-list i, .policies-list i { color: #1e5dd1; margin-right: 8px; }
        .section-title { font-size: 1.25rem; font-weight: 600; color: #1e5dd1; margin-top: 2rem; margin-bottom: 1rem; }
        .review-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e9ecef;
            margin-right: 1rem;
        }
        .review-card {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .review-user { font-weight: 600; color: #1e5dd1; }
        .review-date { color: #888; font-size: 0.95rem; }
        .review-rating { color: #f1c40f; margin-right: 6px; }
        .book-btn {
            background: #fff !important;
            color: #1e5dd1 !important;
            font-weight: 600;
            border: 2px solid #fff;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            transition: background 0.3s, color 0.3s, border 0.3s, transform 0.2s;
            box-shadow: 0 2px 8px #1e5dd122;
        }
        .book-btn:hover {
            background: #1e5dd1 !important;
            color: #fff !important;
            border-color: #1e5dd1;
            transform: translateY(-2px) scale(1.03);
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(40px);} to { opacity: 1; transform: none; } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px);} to { opacity: 1; transform: none; } }
        body { background: #f7f8fa; margin: 0; padding: 0; }
        main.container { max-width: 100vw !important; padding: 0 !important; }
        .features-section-bg {
            background: #f7faff;
            border-radius: 18px;
            padding: 2.5rem 1.5rem 2rem 1.5rem;
            margin-bottom: 2.5rem;
        }
        .features-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e5dd1;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            margin-bottom: 2.2rem;
            justify-content: center;
        }
        .policies-section-bg {
            background: #f8f9fb;
            border-radius: 18px;
            padding: 2.5rem 1.5rem 2rem 1.5rem;
            margin-bottom: 2.5rem;
        }
        .policies-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e5dd1;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            margin-bottom: 2.2rem;
            justify-content: center;
        }
       .section-title#reviews-section {
            font-size: 2.3rem;
            font-weight: 800;
            color: #1e5dd1;
            text-align: center;
            margin-top: 2.5rem;
            margin-bottom: 2.2rem;
            letter-spacing: 1px;
            position: relative;
        }
        .section-title#reviews-section::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: #1e5dd1;
            margin: 12px auto 0 auto;
            border-radius: 2px;
            opacity: 0.15;
        }
        .reviews-section-bg {
            background: linear-gradient(135deg, #f7faff 80%, #eaf0fb 100%);
            border-radius: 18px;
            padding: 2.5rem 1.5rem 2rem 1.5rem;
            box-shadow: 0 8px 32px rgba(44,62,80,0.10);
            max-width: 900px;
            margin: 0 auto 3rem auto;
            border: 1px solid #e9ecef;
        }
        .review-form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        #starRating {
            display: flex;
            align-items: center;
            margin-bottom: 0;
        }
        .review-submit-btn {
            min-width: 160px;
            margin-left: 1rem;
            white-space: nowrap;
        }
        .marquee-rating {
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
            margin: 1.2rem 0 1.5rem 0;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 0.5rem 0.7rem;
            position: relative;
        }
        .marquee-inner {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 12s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
        .marquee-rating-content {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            font-size: 1.08rem;
            color: #1e5dd1;
            font-weight: 600;
        }
        .marquee-rating-content .review-rating {
            font-size: 1.2rem;
            color: #f1c40f;
            margin-right: 0.5rem;
        }
        .review-card {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            border-radius: 14px;
            box-shadow: 0 2px 8px #1e5dd122;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1.5rem;
            background: #fff;
            border: none;
            transition: box-shadow 0.2s;
        }
        .review-card:hover {
            box-shadow: 0 6px 18px #1e5dd144;
        }
        .review-avatar {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e9ecef;
            margin-right: 1.2rem;
            box-shadow: 0 2px 8px #1e5dd122;
        }
        .review-user {
            font-weight: 700;
            color: #1e5dd1;
            font-size: 1.15rem;
        }
        .review-rating {
            color: #f1c40f;
            font-size: 1.1rem;
            margin-right: 6px;
            letter-spacing: 1px;
        }
        .review-date {
            color: #888;
            font-size: 0.95rem;
            margin-left: auto;
        }
        .review-comment {
            margin-left: 4.2rem;
            font-size: 1.08rem;
            color: #333;
            margin-top: 0.2rem;
        }
        #reviewForm .btn {
            border-radius: 25px;
            font-weight: 600;
            background: #1e5dd1;
            border: none;
            transition: background 0.2s;
        }
        #reviewForm .btn:hover {
            background: #1746a0;
        }
        #reviews-list {
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <button class="back-btn" id="backToHotel">
        <i class="fas fa-arrow-left"></i>
        Back to Hotel
                </button>

    <main class="container" style="max-width:100vw; padding:0;">
        <div id="room-details" class="room-card mx-auto">
            <div class="text-center text-muted">Loading room details...</div>
        </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="../assets/js/script.js"></script>
<script>
    // Helper: get query param
function getParam(name) {
    const url = new URL(window.location.href);
    return url.searchParams.get(name);
}

    // Fetch and render room details
    async function loadRoomDetails() {
    const hotelId = getParam('hotel_id');
    const roomName = getParam('room');
    if (!hotelId || !roomName) {
            document.getElementById('room-details').innerHTML = '<div class="alert alert-danger">Missing hotel or room information in URL.</div>';
        return;
    }
        try {
            const res = await fetch(`api/hotels_api.php?id=${hotelId}`);
            const data = await res.json();
            if (!data.rooms) throw new Error('No rooms found');
            const room = data.rooms.find(r => r.name === roomName);
        if (!room) {
                document.getElementById('room-details').innerHTML = '<div class="alert alert-warning">Room not found in this hotel.</div>';
                return;
            }
            // Render
        const usdPrice = (room.price / 51).toFixed(2);
            document.getElementById('room-details').innerHTML = `
                <div class="room-hero-container position-relative mb-5" style="margin:4rem 5rem 0 5rem;max-width:calc(100vw - 10rem);">
                    <div class="room-hero-img-wrapper position-relative" style="border-radius:4px;overflow:hidden;">
                        <img src="/Booking-Hotel-Project/pages/${room.image}" alt="Room Image" class="w-100" style="height:500px;object-fit:cover;">
                        <div class="room-hero-overlay position-absolute top-0 start-0 w-100 h-100" style="background:linear-gradient(180deg,rgba(41, 88, 175, 0) 60%,rgb(109, 114, 121) 100%);z-index:2;"></div>
                        <div class="room-hero-content position-absolute bottom-0 start-0 w-100 p-4 d-flex flex-column flex-md-row align-items-end justify-content-between" style="z-index:3;">
                            <div class="text-white">
                                <h1 class="fw-bold mb-2" style="font-size:2.2rem;">${room.name}</h1>
                                <div class="mb-2" style="font-size:1.1rem;">${room.description}</div>
                                <button class="book-btn mt-2" id="bookNowBtn"><i class="fas fa-calendar-check me-2"></i>Book Now</button>
                            </div>
                            <div class="bg-primary text-white rounded-4 shadow-lg p-3 ms-md-4 mt-3 mt-md-0 text-center" style="min-width:170px;">
                                <div style="font-size:0.95rem;opacity:0.85;">Starting from</div>
                                <div class="fw-bold" style="font-size:1.7rem;">EGP ${room.price.toLocaleString()}</div>
                                <div style="font-size:0.95rem;opacity:0.85;">($${usdPrice} USD)</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="features-section-bg" data-aos="fade-up">
                    <div class="features-title">
                        <i class="fa-solid fa-layer-group"></i>
                        Room Features
                    </div>
                    <div class="row row-cols-2 row-cols-md-3 g-3 mb-4" id="features-grid"></div>
                </div>
                <div class="policies-section-bg" data-aos="fade-up">
                    <div class="policies-title">
                        <i class="fa-solid fa-file-contract"></i>
                        Hotel Policies
                    </div>
                    <div class="row row-cols-1 row-cols-md-2 g-3 mb-4">
                        <div class="col">
                            <div class="d-flex align-items-center bg-white rounded-3 shadow-sm p-3 h-100">
                                <i class="fa-solid fa-ban text-danger fs-4 me-3"></i>
                                <span><b>Cancellation:</b> ${data.policies.cancellation || 'N/A'}</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center bg-white rounded-3 shadow-sm p-3 h-100">
                                <i class="fa-solid fa-child text-primary fs-4 me-3"></i>
                                <span><b>Children:</b> ${data.policies.children || 'N/A'}</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center bg-white rounded-3 shadow-sm p-3 h-100">
                                <i class="fa-solid fa-dog text-warning fs-4 me-3"></i>
                                <span><b>Pets:</b> ${data.policies.pets || 'N/A'}</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center bg-white rounded-3 shadow-sm p-3 h-100">
                                <i class="fa-solid fa-smoking text-secondary fs-4 me-3"></i>
                                <span><b>Smoking:</b> ${data.policies.smoking || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mb-5">
                    <a href="#reviews-section" class="btn btn-outline-primary px-4 py-2" style="font-weight:600;font-size:1.1rem;">Go to Reviews <i class="fa-solid fa-arrow-down ms-2"></i></a>
                </div>
                <div class="section-title" id="reviews-section" data-aos="fade-up">Reviews</div>
                <div class="reviews-section-bg">
                    <form id="reviewForm" class="mb-4">
                        <div class="mb-3">
                            <label for="reviewText" class="form-label fw-bold">Your Review</label>
                            <textarea class="form-control" id="reviewText" rows="3" required placeholder="Write your feedback about this room..."></textarea>
                        </div>
                        <div class="review-form-row mb-3">
                            <div class="d-flex align-items-center">
                                <label class="form-label fw-bold me-3 mb-0">Your Rating</label>
                                <div id="starRating" class="d-flex align-items-center"></div>
                            </div>
                            <button type="submit" class="btn btn-primary px-4 review-submit-btn">Submit Review</button>
                        </div>
                    </form>
                    <div class="marquee-rating">
                        <div class="marquee-inner">
                            <span class="marquee-rating-content">
                                <span class="review-rating">★★★★★</span>
                                Top Rated by Our Guests! Enjoy your stay and share your experience.
                            </span>
                        </div>
                    </div>
                    <div id="reviews-list">
                        ${(data.reviews && data.reviews.length) ? data.reviews.map(r => `
                            <div class='review-card'>
                                <div class='d-flex align-items-center mb-2'>
                                    <img src='${r.profile_image && r.profile_image !== "" 
                                        ? r.profile_image 
                                        : "/Booking-Hotel-Project/pages/images/default-avatar.png"}' 
                                        class='review-avatar' alt='User'>
                                    <div>
                                        <span class='review-user'>${r.user && r.user !== '' ? r.user : 'User'}</span>
                                        <span class='review-rating ms-2'>${'★'.repeat(r.rating)}${'☆'.repeat(5 - r.rating)}</span>
                                    </div>
                                    <span class='review-date'>${r.date}</span>
                                </div>
                                <div class='review-comment'>${r.comment}</div>
                            </div>
                        `).join('') : '<div class="text-muted">No reviews yet.</div>'}
                    </div>
                </div>
            `;
            // Book button event
            document.getElementById('bookNowBtn').onclick = function() {
                const params = new URLSearchParams({
                    hotel: data.name,
                    room: room.name,
                    price: room.price
                });
                window.location.href = `book.php?${params.toString()}`;
            };
            // Room Features grid rendering
            const featuresGrid = document.getElementById('features-grid');
            if (featuresGrid && room.features && room.features.length) {
                const iconMap = {
                    bed: 'fa-bed',
                    wifi: 'fa-wifi',
                    tv: 'fa-tv',
                    air: 'fa-wind',
                    conditioning: 'fa-wind',
                    minibar: 'fa-wine-bottle',
                    bar: 'fa-wine-glass-alt',
                    safe: 'fa-lock',
                    balcony: 'fa-building',
                    shower: 'fa-shower',
                    bath: 'fa-bath',
                    desk: 'fa-briefcase',
                    coffee: 'fa-mug-hot',
                    maker: 'fa-mug-hot',
                    hair: 'fa-wind',
                    dryer: 'fa-wind',
                    smart: 'fa-tv',
                    view: 'fa-eye',
                    phone: 'fa-phone',
                    heating: 'fa-fire',
                    pool: 'fa-swimming-pool',
                    kitchen: 'fa-utensils',
                    lounge: 'fa-couch',
                    child: 'fa-child',
                    family: 'fa-users',
                    smoke: 'fa-smoking',
                    non: 'fa-ban',
                    parking: 'fa-car',
                    gym: 'fa-dumbbell',
                    spa: 'fa-spa',
                    sea: 'fa-water',
                    garden: 'fa-leaf',
                    city: 'fa-city',
                    room: 'fa-door-open',
                };
                featuresGrid.innerHTML = room.features.map(f => {
                    let icon = 'fa-circle-check';
                    const lower = f.toLowerCase();
                    for (const key in iconMap) {
                        if (lower.includes(key)) { icon = iconMap[key]; break; }
                    }
                    return `<div class='col'><div class='d-flex align-items-center bg-white rounded-3 shadow-sm p-3 h-100'><i class='fa-solid ${icon} text-primary fs-4 me-3'></i><span>${f}</span></div></div>`;
                }).join('');
            } else if (featuresGrid) {
                featuresGrid.innerHTML = `<div class='col text-muted'>No features listed.</div>`;
            }
            // Star rating widget
            const starRating = document.getElementById('starRating');
            let currentRating = 0;
            if (starRating) {
                for (let i = 1; i <= 5; i++) {
                    const star = document.createElement('i');
                    star.className = 'fa-star fa-regular fs-3 text-warning pointer';
                    star.style.cursor = 'pointer';
                    star.dataset.value = i;
                    star.addEventListener('mouseenter', function() {
                        for (let j = 1; j <= 5; j++) {
                            starRating.children[j-1].className = j <= i ? 'fa-solid fa-star fs-3 text-warning pointer' : 'fa-regular fa-star fs-3 text-warning pointer';
                        }
                    });
                    star.addEventListener('mouseleave', function() {
                        for (let j = 1; j <= 5; j++) {
                            starRating.children[j-1].className = j <= currentRating ? 'fa-solid fa-star fs-3 text-warning pointer' : 'fa-regular fa-star fs-3 text-warning pointer';
                        }
                    });
                    star.addEventListener('click', function() {
                        currentRating = i;
                        for (let j = 1; j <= 5; j++) {
                            starRating.children[j-1].className = j <= currentRating ? 'fa-solid fa-star fs-3 text-warning pointer' : 'fa-regular fa-star fs-3 text-warning pointer';
                        }
                    });
                    starRating.appendChild(star);
                }
            }
            // Helper: get user name from PHP session (if available)
            let userName = 'Anonymous';
            <?php if(isset($_SESSION['username'])): ?>
                userName = <?php echo json_encode($_SESSION['username']); ?>;
            <?php endif; ?>
            // Handle review form submit (AJAX)
            const reviewForm = document.getElementById('reviewForm');
            if (reviewForm) {
                reviewForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const text = document.getElementById('reviewText').value.trim();
                    let rating = currentRating;
                    if (!text || rating === 0) {
                        showReviewAlert('Please write a review and select a rating.', 'danger');
                        return;
                    }
                    const params = new URLSearchParams(window.location.search);
            const hotelId = params.get('hotel_id');
            const roomName = params.get('room');
            if (!hotelId || !roomName) {
                showReviewAlert('Room or hotel information missing.', 'danger');
                return;
            }
            // إرسال البيانات للـ API
            const formData = new FormData();
            formData.append('hotel_id', hotelId);
            formData.append('room_name', roomName);
            formData.append('rating', rating);
            formData.append('comment', text);
            try {
                const res = await fetch('api/reviews_api.php', { method: 'POST', body: formData });
                const result = await res.json();
                if (result.success) {
                    showReviewAlert('Thank you for your review!', 'success');
                    reviewForm.reset();
                    currentRating = 0;
                    for (let i = 0; i < 5; i++) starRating.children[i].className = 'fa-regular fa-star fs-3 text-warning pointer';
                    await loadReviewsAjax();
                } else {
                    showReviewAlert(result.error || 'Error submitting review.', 'danger');
                }
            } catch (err) {
                showReviewAlert('Error submitting review.', 'danger');
            }
                });
            }
            // تحميل التعليقات من الـ API
            async function loadReviewsAjax() {
                const params = new URLSearchParams(window.location.search);
                const hotelId = params.get('hotel_id');
                const roomName = params.get('room');
                if (!hotelId || !roomName) return;
                try {
                    const res = await fetch(`api/reviews_api.php?hotel_id=${hotelId}&room_name=${encodeURIComponent(roomName)}`);
                    const data = await res.json();
                    const reviewsList = document.getElementById('reviews-list');
                    if (data.reviews && data.reviews.length) {
                        reviewsList.innerHTML = data.reviews.map(r => `
                            <div class='review-card'>
                                <div class='d-flex align-items-center mb-2'>
                                    <img src='${r.profile_image && r.profile_image !== "" 
                                        ? r.profile_image 
                                        : "/Booking-Hotel-Project/pages/images/default-avatar.png"}' 
                                        class='review-avatar' alt='User'>
                                    <div>
                                        <span class='review-user'>${r.user && r.user !== '' ? r.user : 'User'}</span>
                                        <span class='review-rating ms-2'>${'★'.repeat(r.rating)}${'☆'.repeat(5 - r.rating)}</span>
                                    </div>
                                    <span class='review-date'>${r.date}</span>
                                </div>
                                <div class='review-comment'>${r.comment}</div>
                            </div>
                        `).join('');
                    } else {
                        reviewsList.innerHTML = `<div class='text-muted'>No reviews yet.</div>`;
                    }
                } catch (e) {
                    // ignore
                }
            }
            // إشعار أنيق
            function showReviewAlert(msg, type) {
                let alert = document.getElementById('review-alert');
                if (!alert) {
                    alert = document.createElement('div');
                    alert.id = 'review-alert';
                    alert.className = 'alert position-fixed top-0 start-50 translate-middle-x mt-4';
                    alert.style.zIndex = 9999;
                    document.body.appendChild(alert);
                }
                alert.className = `alert alert-${type} position-fixed top-0 start-50 translate-middle-x mt-4`;
                alert.textContent = msg;
                alert.style.display = 'block';
                setTimeout(() => { alert.style.display = 'none'; }, 2500);
            }
            // حفظ بيانات الغرفة في window لاستخدامها في جلب room_id
            window.roomDetailsData = data;
            // تحميل التعليقات عند تحميل الصفحة
            await loadReviewsAjax();
        } catch (e) {
            document.getElementById('room-details').innerHTML = `<div class="alert alert-danger">Error loading room details: ${e.message}</div>`;
        }
}

document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });

        // Load room details
        loadRoomDetails();

        // Back to hotel button
        document.getElementById('backToHotel').addEventListener('click', function() {
    const hotelId = getParam('hotel_id');
            window.location.href = `hotel-details.php?id=${hotelId}`;
        });
    });
</script>
</body>
</html> 