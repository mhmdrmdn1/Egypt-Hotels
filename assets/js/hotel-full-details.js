async function getHotelDetails(hotelId) {
    console.log('Fetching hotel details for ID:', hotelId); // Debug log
    try {
        console.log('Making API request...'); // Debug log
        const response = await fetch(`/Booking-Hotel-Project/pages/api/hotels_api.php?id=${hotelId}`);
        console.log('API Response status:', response.status); // Debug log
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        console.log('Parsing JSON response...'); // Debug log
        const data = await response.json();
        console.log('API Response data:', data); // Debug log
        
        if (!data) {
            throw new Error('No data received from API');
        }
        return data;
    } catch (error) {
        console.error('Error fetching hotel details:', error);
        document.getElementById('full-details-main').innerHTML = `
            <div class="alert alert-danger">
                <h4 class="alert-heading">Error Loading Hotel Details</h4>
                <p>There was a problem loading the hotel information. Please try again later.</p>
                <hr>
                <p class="mb-0">Error: ${error.message}</p>
                <p class="mb-0">Hotel ID: ${hotelId}</p>
            </div>
        `;
        return null;
    }
}

async function renderFullHotelDetails(hotelId) {
    if (!hotelId) {
        console.error('No hotel ID provided');
        document.getElementById('full-details-main').innerHTML = `
            <div class="alert alert-danger">
                <h4 class="alert-heading">Error</h4>
                <p>No hotel ID provided in the URL.</p>
            </div>
        `;
        return;
    }

    console.log('Starting to render hotel details for ID:', hotelId); // Debug log
    
    // Show loading state immediately
    document.getElementById('full-details-main').innerHTML = `
        <div class="loading-container text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="mt-3">Loading hotel details...</div>
        </div>
    `;

    try {
        console.log('Fetching hotel data...'); // Debug log
        const hotel = await getHotelDetails(hotelId);
        
        if (!hotel) {
            console.error('No hotel data returned');
            return; // Error already handled in getHotelDetails
        }

        console.log('Hotel data received:', hotel); // Debug log

        // Validate required data
        if (!hotel.name || !hotel.location) {
            document.getElementById('full-details-main').innerHTML = `
                <div class="alert alert-warning">
                    <h4 class="alert-heading">Incomplete Hotel Information</h4>
                    <p>Some required hotel information is missing.</p>
                    <hr>
                    <p class="mb-0">Missing fields: ${!hotel.name ? 'name' : ''} ${!hotel.location ? 'location' : ''}</p>
                </div>
            `;
            return;
        }

        console.log('Starting to render sections...'); // Debug log

        // --- Hero Section with Parallax Effect ---
        let heroImg = (hotel.images && Array.isArray(hotel.images) && hotel.images.length) ? hotel.images[0] : (hotel.image || '../pages/images/hotels/default.jpg');
        let heroHtml = `
            <div class="hero-section position-relative mb-5" style="min-height: 70vh;">
                <div class="parallax-bg" style="background-image: url('${heroImg}');"></div>
                <div class="hero-content position-absolute start-0 end-0 bottom-0 p-4 text-white" 
                     style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                    <div class="container">
                        <div class="row align-items-end">
                            <div class="col-lg-8">
                                <h1 class="display-4 fw-bold mb-2 animate-up">${hotel.name}</h1>
                                <div class="d-flex align-items-center gap-3 mb-3 animate-up" style="animation-delay: 0.2s">
                                    <div class="location">
                                        <i class="fas fa-map-marker-alt me-2"></i>${hotel.location || 'Location not available'}
                                    </div>
                                    <div class="rating">
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-star text-warning me-1"></i>${hotel.rating || '0'}
                                        </span>
                                        <span class="ms-2 text-white-50">(${hotel.reviews ? hotel.reviews.length : 0} reviews)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 text-lg-end animate-up" style="animation-delay: 0.4s">
                                <div class="price-badge bg-primary p-3 d-inline-block rounded-3">
                                    <div class="fs-6 text-white-50">Starting from</div>
                                    <div class="fs-3 fw-bold">EGP ${(hotel.price || 0).toLocaleString()}</div>
                                    <div class="fs-6 text-white-50">per night</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // --- Quick Info Section ---
        let quickInfoHtml = `
            <div class="quick-info-section py-4 mb-5 bg-light">
                <div class="container">
                    <div class="row g-4">
                        <div class="col-md-3 col-6">
                            <div class="quick-info-item text-center p-3 rounded-3 bg-white shadow-sm h-100">
                                <i class="fas fa-bed fs-3 text-primary mb-2"></i>
                                <div class="fw-bold">${hotel.rooms ? hotel.rooms.length : 0}</div>
                                <div class="text-muted">Room Types</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="quick-info-item text-center p-3 rounded-3 bg-white shadow-sm h-100">
                                <i class="fas fa-concierge-bell fs-3 text-primary mb-2"></i>
                                <div class="fw-bold">${hotel.amenities ? hotel.amenities.length : 0}</div>
                                <div class="text-muted">Amenities</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="quick-info-item text-center p-3 rounded-3 bg-white shadow-sm h-100">
                                <i class="fas fa-star fs-3 text-primary mb-2"></i>
                                <div class="fw-bold">${hotel.rating || '0'}/10</div>
                                <div class="text-muted">Rating</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="quick-info-item text-center p-3 rounded-3 bg-white shadow-sm h-100">
                                <i class="fas fa-map-marked-alt fs-3 text-primary mb-2"></i>
                                <div class="fw-bold">View</div>
                                <div class="text-muted">Location</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // --- Gallery Section with Masonry Layout ---
        let galleryHtml = '';
        if (hotel.images && Array.isArray(hotel.images) && hotel.images.length > 0) {
            galleryHtml = `
                <div class="gallery-section mb-5">
                    <div class="container">
                        <h2 class="section-title mb-4">Gallery</h2>
                        <div class="gallery-masonry row g-3">
                            ${hotel.images.map((img, idx) => `
                                <div class="col-lg-4 col-md-6 gallery-item">
                                    <div class="position-relative overflow-hidden rounded-3 shadow-sm">
                                        <img src="${img}" 
                                             class="img-fluid w-100" 
                                             alt="${hotel.name} image ${idx + 1}"
                                             loading="lazy"
                                             style="aspect-ratio: 16/9; object-fit: cover;"
                                             data-src="${img}"
                                             onerror="this.onerror=null;this.src='../pages/images/hotels/default.jpg';">
                                        <div class="gallery-overlay position-absolute start-0 end-0 bottom-0 p-3 text-white"
                                             style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>View Image</div>
                                                <i class="fas fa-expand-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
        } else {
            galleryHtml = `
                <div class="gallery-section mb-5">
                    <div class="container">
                        <h2 class="section-title mb-4">Gallery</h2>
                        <div class="gallery-masonry row g-3">
                            <div class="col-12 text-center">
                                <img src="../pages/images/hotels/default.jpg" 
                                     class="img-fluid rounded-3" 
                                     alt="${hotel.name || 'Hotel'}"
                                     style="max-height: 400px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // --- Amenities Section with Grid Layout ---
        const amenityIcons = {
            // Beach Amenities
            'Private Beach': 'fas fa-umbrella-beach',
            'Beach': 'fas fa-water',
            'Beach Access': 'fas fa-umbrella-beach',
            'Beach Service': 'fas fa-umbrella-beach',
            
            // Kids Amenities
            'Kids Club': 'fas fa-child',
            'Kids Area': 'fas fa-baby',
            'Kids Pool': 'fas fa-swimming-pool',
            'Kids Activities': 'fas fa-puzzle-piece',
            'Kids Entertainment': 'fas fa-gamepad',
            'Playground': 'fas fa-child',
            
            // Bar & Dining
            'Bar/Lounge': 'fas fa-glass-martini-alt',
            'Bar': 'fas fa-glass-cheers',
            'Lounge': 'fas fa-couch',
            'Pool Bar': 'fas fa-cocktail',
            'Restaurant': 'fas fa-utensils',
            'Cafe': 'fas fa-coffee',
            'Dining': 'fas fa-concierge-bell',
            
            // Existing amenities
            'Swimming Pool': 'fas fa-swimming-pool',
            'Spa': 'fas fa-spa',
            'Gym': 'fas fa-dumbbell',
            'WiFi': 'fas fa-wifi',
            'Parking': 'fas fa-parking',
            'Room Service': 'fas fa-concierge-bell',
            'Beach': 'fas fa-umbrella-beach',
            'Air Conditioning': 'fas fa-snowflake',
            'Conference Room': 'fas fa-chalkboard-teacher',
            'Laundry': 'fas fa-tshirt',
            'Security': 'fas fa-shield-alt',
            'Garden': 'fas fa-leaf',
            'Tennis Court': 'fas fa-table-tennis',
            'Shuttle Service': 'fas fa-shuttle-van',
            'Business Center': 'fas fa-briefcase',
            'Eco Design': 'fas fa-seedling',
            'Pet Friendly': 'fas fa-paw',
            'Elevator': 'fas fa-elevator',
            'Coffee Shop': 'fas fa-coffee',
            'Fitness Center': 'fas fa-running',
            '24/7 Front Desk': 'fas fa-clock',
            'Airport Transfer': 'fas fa-plane-departure',
            'Medical Service': 'fas fa-first-aid',
            'Housekeeping': 'fas fa-broom',
            'Meeting Rooms': 'fas fa-handshake',
            'Smoking Area': 'fas fa-smoking',
            'Non-Smoking': 'fas fa-smoking-ban',
            'Wheelchair Access': 'fas fa-wheelchair',
            'ATM': 'fas fa-money-bill-wave',
            'Currency Exchange': 'fas fa-exchange-alt',
            'Gift Shop': 'fas fa-gift',
            'Luggage Storage': 'fas fa-suitcase',
            'Wake-up Service': 'fas fa-bell',
            'Massage': 'fas fa-hands',
            'Sauna': 'fas fa-hot-tub',
            'Library': 'fas fa-book-reader',
            'Game Room': 'fas fa-gamepad'
        };

        let amenitiesHtml = `
            <div class="amenities-section mb-5 py-5 bg-light">
                <div class="container">
                    <h2 class="section-title mb-4">Amenities & Services</h2>
                    <div class="row g-4">
                        ${Array.isArray(hotel.amenities) && hotel.amenities.length > 0 ? hotel.amenities.map(am => {
                            // Debug logging
                            console.log('Processing amenity:', am);
                            console.log('Amenity name:', am.name);
                            console.log('Icon from amenityIcons:', amenityIcons[am.name]);
                            
                            // Get the icon based on the amenity name, or use a default icon
                            const icon = amenityIcons[am.name] || amenityIcons[am.name?.toLowerCase()] || 'fas fa-utensils';
                            console.log('Final icon class:', icon);
                            
                            // Add animation delay based on index
                            const animationDelay = 0.1;
                            return `
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="amenity-card h-100 p-4 bg-white rounded-4 shadow-sm hover-lift">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="amenity-icon">
                                                <div class="icon-circle bg-primary bg-opacity-10 p-3 rounded-circle">
                                                    <i class="${icon} fs-4 text-primary" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="amenity-content">
                                                <h5 class="mb-2 fs-6 fw-bold text-dark">${am.name || 'Amenity'}</h5>
                                                ${am.description ? `
                                                    <p class="mb-0 text-muted small">
                                                        ${am.description}
                                                    </p>
                                                ` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('') : `
                            <div class="col-12">
                                <div class="alert alert-info mb-0 rounded-4 shadow-sm">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle fs-4 text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-1">Amenities Information</h6>
                                            <p class="mb-0">The amenities information will be updated soon.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `}
                    </div>
                </div>
            </div>
        `;

        // Add custom styles for amenities hover effects
        const amenityStyles = document.createElement('style');
        amenityStyles.innerHTML = `
            .amenity-card {
                transition: all 0.3s ease;
                border: 1px solid rgba(0,0,0,0.1);
            }

            .hover-lift:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
            }

            .icon-circle {
                width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }

            .amenity-card:hover .icon-circle {
                background-color: var(--bs-primary) !important;
            }

            .amenity-card:hover .icon-circle i {
                color: white !important;
            }

            .amenity-content {
                flex: 1;
            }

            @media (max-width: 576px) {
                .amenity-card {
                    padding: 1rem !important;
                }
                
                .icon-circle {
                    width: 40px;
                    height: 40px;
                }
                
                .icon-circle i {
                    font-size: 1rem !important;
                }
            }
        `;
        document.head.appendChild(amenityStyles);

        // --- Policies Section with Modern Cards ---
        let policiesHtml = `
            <div class="policies-section mb-5">
                <div class="container">
                    <h2 class="section-title mb-4">Hotel Policies</h2>
                    <div class="row g-4">
                        ${Array.isArray(hotel.policies) ? hotel.policies.map(policy => `
                            <div class="col-md-6">
                                <div class="policy-card h-100 p-4 bg-white rounded-3 shadow-sm border-start border-4 border-primary">
                                    <div class="d-flex align-items-start">
                                        <div class="policy-icon me-3">
                                            <i class="${policy.icon || 'fas fa-info-circle'} fs-4 text-primary"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-2">${policy.title || 'Hotel Policy'}</h5>
                                            <p class="mb-0 text-muted">${policy.text || 'Policy details not available.'}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).join('') : `
                            <div class="col-12">
                                <div class="policy-card h-100 p-4 bg-white rounded-3 shadow-sm border-start border-4 border-primary">
                                    <div class="d-flex align-items-start">
                                        <div class="policy-icon me-3">
                                            <i class="fas fa-info-circle fs-4 text-primary"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-2">Standard Hotel Policies</h5>
                                            <p class="mb-0 text-muted">
                                                • Check-in time: 2:00 PM<br>
                                                • Check-out time: 12:00 PM<br>
                                                • Pets are not allowed<br>
                                                • Non-smoking property<br>
                                                • Credit card or cash deposit required<br>
                                                • Government-issued photo ID required
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `}
                    </div>
                </div>
            </div>
        `;

        // --- Location Section with Interactive Map ---
        let mapHtml = '';
        if (hotel.coordinates && typeof hotel.coordinates === 'object' && hotel.coordinates.lat && hotel.coordinates.lng) {
            mapHtml = `
                <div class="location-section mb-5">
                    <div class="container">
                        <h2 class="section-title mb-4">Location</h2>
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-0">
                                <div class="ratio ratio-21x9">
                                    <iframe 
                                        src="https://maps.google.com/maps?q=${hotel.coordinates.lat},${hotel.coordinates.lng}&z=15&output=embed" 
                                        class="rounded-3"
                                        style="border:0;" 
                                        allowfullscreen="" 
                                        loading="lazy">
                                    </iframe>
                                </div>
                                <div class="p-4">
                                    <h5 class="mb-3">Address</h5>
                                    <p class="mb-0">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        ${hotel.location || 'Location information not available'}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Add custom styles
        const customStyles = document.createElement('style');
        customStyles.innerHTML = `
            .parallax-bg {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                transform: translateZ(0);
                will-change: transform;
            }

            .animate-up {
                opacity: 0;
                transform: translateY(20px);
                animation: fadeInUp 0.6s ease forwards;
                will-change: transform, opacity;
            }

            @keyframes fadeInUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .section-title {
                position: relative;
                padding-bottom: 1rem;
                margin-bottom: 2rem;
                font-weight: 700;
                color: #1a2942;
            }

            .section-title::after {
                content: '';
                position: absolute;
                left: 0;
                bottom: 0;
                width: 50px;
                height: 3px;
                background: #1e5dd1;
            }

            .gallery-item {
                transition: transform 0.2s ease;
                cursor: pointer;
                will-change: transform;
                transform: translateZ(0);
            }

            .gallery-item:hover {
                transform: translateY(-5px);
            }

            .gallery-overlay {
                opacity: 0;
                transition: opacity 0.2s ease;
                will-change: opacity;
            }

            .gallery-item:hover .gallery-overlay {
                opacity: 1;
            }

            .policy-card {
                transition: transform 0.2s ease;
                will-change: transform;
                transform: translateZ(0);
            }

            .policy-card:hover {
                transform: translateY(-5px);
            }

            .gallery-masonry img {
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .gallery-masonry img.loaded {
                opacity: 1;
            }

            @media (max-width: 768px) {
                .parallax-bg {
                    background-attachment: scroll;
                }
            }
        `;
        document.head.appendChild(customStyles);

        // Initialize Intersection Observer for lazy loading images
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.1
        });

        // Combine all sections and add to DOM
        document.getElementById('full-details-main').innerHTML = 
            heroHtml + 
            quickInfoHtml + 
            galleryHtml + 
            amenitiesHtml + 
            policiesHtml + 
            mapHtml;

        // Initialize lazy loading for all images
        document.querySelectorAll('.gallery-masonry img').forEach(img => {
            const src = img.src;
            img.src = '';
            img.dataset.src = src;
            imageObserver.observe(img);
        });

        // Initialize gallery lightbox with improved performance
        const galleryItems = document.querySelectorAll('.gallery-item');
        const modal = document.getElementById('galleryModal');
        const modalImg = document.getElementById('galleryModalImage');
        
        if (!modal) {
            document.body.insertAdjacentHTML('beforeend', `
                <div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content bg-transparent border-0">
                            <div class="modal-body p-0">
                                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                                <img id="galleryModalImage" src="" class="img-fluid w-100 rounded-3" alt="Gallery image">
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }

        galleryItems.forEach(item => {
            item.addEventListener('click', function() {
                const img = this.querySelector('img');
                const modal = new bootstrap.Modal(document.getElementById('galleryModal'));
                const modalImg = document.getElementById('galleryModalImage');
                
                // Pre-load image before showing modal
                const tempImg = new Image();
                tempImg.onload = function() {
                    modalImg.src = this.src;
          modal.show();
                };
                tempImg.src = img.dataset.src || img.src;
            });
        });

        // Add scroll animations with improved performance
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-up');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.section-title, .amenity-card, .policy-card, .gallery-item').forEach(el => {
            observer.observe(el);
        });
    } catch (error) {
        console.error('Error rendering hotel details:', error);
        document.getElementById('full-details-main').innerHTML = `
            <div class="alert alert-danger">
                <h4 class="alert-heading">Error Rendering Hotel Details</h4>
                <p>There was a problem rendering the hotel details. Please try again later.</p>
                <hr>
                <p class="mb-0">Error: ${error.message}</p>
            </div>
        `;
    }
}

function getHotelIdFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return params.get('id');
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    const hotelId = getHotelIdFromUrl();
    if (hotelId) {
    renderFullHotelDetails(hotelId);
    }
});

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