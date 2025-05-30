function getHotelIdFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return params.get('id');
}

async function getHotelDetails(hotelId) {
    try {
        const response = await fetch(`api/hotels_api.php?id=${hotelId}`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return await response.json();
    } catch (error) {
        console.error('Error fetching hotel details:', error);
        return null;
    }
}

document.addEventListener('DOMContentLoaded', async function() {
    const hotelId = getHotelIdFromUrl();
    const USD_RATE = 51;
    if (!hotelId) {
        document.querySelector('main.container').innerHTML = '<div class="alert alert-danger">Hotel not found. Please select a valid hotel.</div>';
        return;
    }

    const hotel = await getHotelDetails(hotelId);
    if (!hotel || hotel.error) {
        document.querySelector('main.container').innerHTML = '<div class="alert alert-danger">Hotel not found. Please select a valid hotel.</div>';
        return;
    }

    const carouselContainer = document.getElementById('carousel-container');
    let carouselInner = '';
    if (hotel.images && Array.isArray(hotel.images) && hotel.images.length > 0) {
        carouselInner = hotel.images.map((img, idx) => `
            <div class="carousel-item${idx === 0 ? ' active' : ''}">
                <img src="${img}" 
                     class="d-block w-100" 
                     alt="${hotel.name} image ${idx+1}" 
                     loading="lazy"
                     onerror="this.onerror=null;this.src='../pages/images/hotels/default.jpg';this.classList.add('img-error');"
                     onload="this.classList.add('img-loaded');"
                     style="min-height: 400px; object-fit: cover;">
            </div>
        `).join('');
    } else {
        carouselInner = `
            <div class="carousel-item active">
                <img src="${hotel.image || '../pages/images/hotels/default.jpg'}" 
                     class="d-block w-100" 
                     alt="${hotel.name}"
                     loading="lazy" 
                     onerror="this.onerror=null;this.src='../pages/images/hotels/default.jpg';this.classList.add('img-error');"
                     onload="this.classList.add('img-loaded');"
                     style="min-height: 400px; object-fit: cover;">
            </div>`;
    }
    carouselContainer.innerHTML = `
    <div id="hotelImagesCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        ${carouselInner}
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#hotelImagesCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#hotelImagesCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>`;

    const hotelInfo = document.getElementById('hotel-info-container');
    const priceUSD = (hotel.price / USD_RATE).toFixed(2);
    hotelInfo.innerHTML = `
      <div class="hotel-info-card p-4 rounded-4 shadow-sm bg-white position-relative">
        <div class="d-flex align-items-center mb-3 gap-3">
          <img src="${hotel.images && hotel.images.length ? hotel.images[0] : hotel.image}" 
               class="hotel-avatar rounded-circle border border-2" 
               style="width:64px;height:64px;object-fit:cover;" 
               loading="lazy"
               alt="${hotel.name}"
               onerror="this.onerror=null;this.src='../pages/images/hotels/default.jpg';">
          <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 mb-1">
              <h1 class="mb-0 h3 fw-bold">${hotel.name}</h1>
            </div>
            <div class="text-muted small"><i class="fa fa-map-marker-alt text-primary me-1"></i> ${hotel.location}</div>
          </div>
        </div>
        <div class="mb-2 d-flex align-items-center gap-2">
          <span class="badge bg-success fs-6 d-flex align-items-center hotel-rating-badge" style="min-width:56px;">
            <i class="fa fa-star me-1"></i> <span class="fw-bold">${hotel.rating || 0}</span>
          </span>
          <span class="text-muted">(${Array.isArray(hotel.reviews) ? hotel.reviews.length : (hotel.reviews || 0)} reviews)</span>
        </div>
        <div class="user-rating mb-2">
          <span class="me-2">Your Rating:</span>
          <span id="user-star-rating">
            <i class="fa-regular fa-star" data-value="1"></i>
            <i class="fa-regular fa-star" data-value="2"></i>
            <i class="fa-regular fa-star" data-value="3"></i>
            <i class="fa-regular fa-star" data-value="4"></i>
            <i class="fa-regular fa-star" data-value="5"></i>
          </span>
          <span id="user-rating-msg" class="ms-2 text-success"></span>
        </div>
        <div class="mb-3 d-flex align-items-center gap-3">
          <span class="fs-3 fw-bold"><i class="fa fa-money-bill-wave text-success me-1"></i> ${hotel.price.toLocaleString()} <span class="fs-6">EGP/night</span></span>
          <span class="text-secondary ms-2">(~$${priceUSD} USD/night)</span>
        </div>
      </div>
    `;

    const aboutSection = document.getElementById('about-section');
    aboutSection.innerHTML = `
      <div class="card about-hotel-card border-0 mb-4 d-flex flex-row align-items-center justify-content-between">
        <div class="flex-grow-1">
          <div class="about-hotel-title mb-2 mb-md-0">
            <i class="fa fa-crown me-2" style="color:#1e5dd1;"></i>
            About the Hotel
          </div>
          <div class="about-hotel-desc">${hotel.description}</div>
        </div>
        <div class="ms-3">
          <a href="hotel-full-details.php?id=${hotelId}" class="btn btn-outline-primary about-btn">About</a>
        </div>
      </div>`;

    const amenitiesSection = document.getElementById('amenities-cards');
    if (hotel.amenities && Array.isArray(hotel.amenities) && hotel.amenities.length > 0) {
        amenitiesSection.innerHTML = hotel.amenities.map((amenity, idx) => {
            let icon = amenity.icon || 'fa-check';
            return `
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card amenity-card h-100 text-center p-3" data-idx="${idx}" style="cursor: pointer;">
                    <div class="display-4 mb-2">
                        <i class="fa ${icon}"></i>
                    </div>
                    <div class="fw-bold mb-2">${amenity.name}</div>
                    <div class="small text-muted mb-2">${amenity.description || ''}</div>
                    ${amenity.hours ? `<div class="small text-primary"><i class="fa fa-clock me-1"></i>${amenity.hours}</div>` : ''}
                    ${amenity.price ? `<div class="small text-success mt-1"><i class="fa fa-tag me-1"></i>${amenity.price}</div>` : ''}
                </div>
            </div>
            `;
        }).join('');

        // Add click event for amenity details modal
        document.querySelectorAll('.amenity-card').forEach(card => {
            card.addEventListener('click', function() {
                const idx = +this.getAttribute('data-idx');
                const amenity = hotel.amenities[idx];
                const modal = new bootstrap.Modal(document.getElementById('amenityModal'));
                
                document.getElementById('amenityModalLabel').textContent = amenity.name;
                let modalContent = `
                    <div class="text-center mb-4">
                        <div class="amenity-icon mb-3">
                            <i class="fa ${amenity.icon || 'fa-check'} fa-2x"></i>
                        </div>
                        <h4 class="amenity-title">${amenity.name}</h4>
                    </div>
                    ${amenity.description ? `
                        <div class="amenity-section">
                            <i class="fa fa-info-circle text-primary"></i>
                            <div class="ms-3">
                                <strong>Description:</strong>
                                <p class="mb-0">${amenity.description}</p>
                            </div>
                        </div>
                    ` : ''}
                    ${amenity.features && amenity.features.length > 0 ? `
                        <div class="amenity-section">
                            <i class="fa fa-list-ul text-primary"></i>
                            <div class="ms-3">
                                <strong>Features:</strong>
                                <ul class="amenity-features-list">
                                    ${amenity.features.map(feature => `<li>${feature}</li>`).join('')}
                                </ul>
                            </div>
                        </div>
                    ` : ''}
                    ${amenity.hours ? `
                        <div class="amenity-section">
                            <i class="fa fa-clock text-primary"></i>
                            <div class="ms-3">
                                <strong>Hours:</strong>
                                <p class="mb-0">${amenity.hours}</p>
                            </div>
                        </div>
                    ` : ''}
                    ${amenity.price ? `
                        <div class="amenity-section">
                            <i class="fa fa-tag text-primary"></i>
                            <div class="ms-3">
                                <strong>Price:</strong>
                                <p class="mb-0">${amenity.price}</p>
                            </div>
                        </div>
                    ` : ''}
                `;
                document.getElementById('amenityModalBody').innerHTML = modalContent;
                modal.show();

                // عند إغلاق المودال، أزل التعتيم وكلاس modal-open يدويًا
                const amenityModalEl = document.getElementById('amenityModal');
                amenityModalEl.addEventListener('hidden.bs.modal', function() {
                  document.body.classList.remove('modal-open');
                  document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                  document.body.style.overflow = '';
                }, { once: true });
            });
        });
    } else {
        amenitiesSection.innerHTML = '<div class="col-12"><p class="text-muted">No amenities available.</p></div>';
    }

    const roomsSection = document.getElementById('rooms-cards');
    if (hotel.rooms && Array.isArray(hotel.rooms) && hotel.rooms.length > 0) {
        roomsSection.innerHTML = hotel.rooms.map((room, idx) => {
            const usdPrice = (room.price / USD_RATE).toFixed(2);
            // استخدم رقم الغرفة كاسم الصورة
            const imageNumber = (idx + 1).toString();
            let roomImg = `/Booking-Hotel-Project/pages/images/rooms/${hotel.folder}/${imageNumber}.jpg`;
            return `
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card room-card h-100">
                        <div class="room-image-container">
                            <img src="${roomImg}" 
                                 class="card-img-top room-image" 
                                 alt="${room.name}"
                                 loading="lazy"
                                 onerror="this.onerror=null;this.src='/Booking-Hotel-Project/pages/images/hotels/default-room.jpg';">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2">${room.name}</h5>
                            <div class="mb-2 text-primary fw-bold">
                                ${(room.price || 0).toLocaleString()} EGP
                                <span class="text-secondary ms-2">($${usdPrice} USD)</span>
                            </div>
                            <p class="card-text">${room.description || 'Experience luxury and comfort in our beautifully designed room.'}</p>
                            <div class="mt-auto">
                                <a href="room-details.php?hotel_id=${hotelId}&room=${encodeURIComponent(room.name)}" 
                                   class="btn btn-outline-primary w-100">Room Details</a>
                            </div>
                        </div>
                    </div>
                </div>`;
        }).join('');

        // تحديث أنماط CSS لتحسين عرض الصور
        const roomStyles = document.createElement('style');
        roomStyles.innerHTML = `
            .room-image-container {
                position: relative;
                overflow: hidden;
                border-radius: 12px 12px 0 0;
                height: 220px;
                background-color: #f8f9fa;
            }
            
            .room-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }
            
            .room-card:hover .room-image {
                transform: scale(1.05);
            }
            
            .room-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border: 1px solid rgba(0,0,0,0.1);
            }
            
            .room-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            }
            
            .card-title {
                font-size: 1.25rem;
                font-weight: 600;
                color: #2c3e50;
            }
            
            .card-text {
                color: #6c757d;
                font-size: 0.95rem;
                line-height: 1.5;
            }
            
            .btn-outline-primary {
                border-width: 2px;
                font-weight: 500;
                padding: 0.6rem 1.2rem;
            }
            
            @media (max-width: 768px) {
                .room-image-container {
                    height: 180px;
                }
                
                .card-title {
                    font-size: 1.1rem;
                }
                
                .card-text {
                    font-size: 0.9rem;
                }
            }
        `;
        document.head.appendChild(roomStyles);
    } else {
        roomsSection.innerHTML = `
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No rooms are currently available for this hotel.
                </div>
            </div>`;
    }

    const locationSection = document.getElementById('location-section');
    if (hotel.lat && hotel.lng) {
        locationSection.innerHTML = `
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h4 mb-3">Hotel Location</h2>
                    <div id="hotel-map" style="height: 350px; border-radius: 12px; overflow: hidden;"></div>
                </div>
            </div>`;
        
        const map = L.map('hotel-map').setView([hotel.lat, hotel.lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        L.marker([hotel.lat, hotel.lng]).addTo(map)
            .bindPopup(hotel.name)
            .openPopup();
    } else {
        locationSection.innerHTML = `<div class="card shadow-sm border-0"><div class="card-body"><h2 class="h4 mb-3">Hotel Location</h2><p class="mb-3"><i class="fa fa-map-marker-alt text-primary"></i> ${hotel.location}</p></div></div>`;
    }

    const policiesSection = document.getElementById('policies-cards');
    if (hotel.policies) {
        // رسم السياسات مع فيديو بشكل عصري
        const policies = [
            {
                key: 'cancellation',
                icon: 'fa-times-circle',
                title: 'Cancellation Policy',
                desc: hotel.policies.cancellation,
                video: 'https://www.youtube.com/embed/KQKerrZoMRo'
            },
            {
                key: 'children',
                icon: 'fa-child',
                title: 'Children Policy',
                desc: hotel.policies.children,
                video: 'https://www.youtube.com/embed/p1dK3kc-Q1M'
            },
            {
                key: 'pets',
                icon: 'fa-paw',
                title: 'Pets Policy',
                desc: hotel.policies.pets,
                video: 'https://www.youtube.com/embed/4iUCGAgeqCY'
            },
            {
                key: 'smoking',
                icon: 'fa-smoking',
                title: 'Smoking Policy',
                desc: hotel.policies.smoking,
                video: 'https://www.youtube.com/embed/9WOPtXMqE5E'
            }
        ];
        policiesSection.innerHTML = `
            <div class="col-12">
                <div class="policies-container">
                    ${policies.filter(p => p.desc).map(p => `
                        <div class="policy-item">
                            <span class="policy-icon"><i class="fa ${p.icon}"></i></span>
                            <span class="policy-title policy-link" data-video="${p.video}">${p.title}</span>
                            <div class="policy-desc">${p.desc}</div>
                            <button class="btn btn-outline-primary policy-video-btn" data-video="${p.video}">Watch Video</button>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    } else {
        policiesSection.innerHTML = '<div class="col-12"><p class="text-muted">No policies available.</p></div>';
    }

    if (!document.getElementById('amenityModal')) {
      const modalHtml = `
      <div class="modal fade" id="amenityModal" tabindex="-1" aria-labelledby="amenityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="amenityModalLabel"></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="amenityModalBody"></div>
          </div>
        </div>
      </div>`;
      document.body.insertAdjacentHTML('beforeend', modalHtml);
    }

    setTimeout(() => {
      document.querySelectorAll('.amenity-card').forEach(card => {
        card.addEventListener('click', function() {
          const idx = +this.getAttribute('data-idx');
          const amenity = hotel.amenities[idx];
          let icon = amenity.icon || 'fa-check';
          let html = `
            <div class=\"text-center mb-4\">\n              <span class=\"amenity-modal-icon\"><i class=\"fa ${icon}\"></i></span>\n            </div>\n            <div class=\"amenity-modal-name\">${amenity.name}</div>\n            <hr style=\"margin:1.2rem 0;opacity:.12;\">\n          `;
          if (amenity.description) {
            html += `<div class=\"amenity-section\"><i class=\"fa fa-info-circle text-primary\"></i><div><strong>Description:</strong> <span style=\"color:#232323;\">${amenity.description}</span></div></div>`;
          }
          if (amenity.details) {
            html += `<div class=\"amenity-section\"><i class=\"fa fa-list-alt text-primary\"></i><div><strong>Details:</strong> <span style=\"color:#232323;\">${amenity.details}</span></div></div>`;
          }
          let featuresArr = amenity.features;
          if (typeof featuresArr === 'string') {
            try { featuresArr = JSON.parse(featuresArr); } catch(e) { featuresArr = []; }
          }
          if (featuresArr && featuresArr.length) {
            html += `<div class=\"amenity-section\"><div class=\"d-flex align-items-center gap-2 mb-1\"><i class=\"fa fa-star text-primary\"></i><strong>Features:</strong></div><ul class=\"amenity-features-list\">${featuresArr.map(f=>`<li>${f}</li>`).join('')}</ul></div>`;
          }
          if (amenity.hours) {
            html += `<div class=\"amenity-section\"><i class=\"fa fa-clock text-primary\"></i><div><strong>Hours:</strong> <span style=\"color:#232323;\">${amenity.hours}</span></div></div>`;
          }
          if (amenity.price) {
            html += `<div class=\"amenity-section\"><i class=\"fa fa-money-bill text-success\"></i><div><strong>Price:</strong> <span class=\"amenity-price\">${amenity.price}</span></div></div>`;
          }
          if (!amenity.description && !amenity.details && !(featuresArr && featuresArr.length) && !amenity.hours && !amenity.price) {
            html += '<div class=\"text-muted text-center\">No details available.</div>';
          }

          document.getElementById('amenityModalLabel').textContent = amenity.name;
          document.getElementById('amenityModalBody').innerHTML = html;
          const modal = new bootstrap.Modal(document.getElementById('amenityModal'));
          modal.show();
        });
      });
    }, 500);

    const style = document.createElement('style');
    style.innerHTML = `
      .hotel-rating-row .hotel-rating-badge {
        background: #198754 !important;
        color: #fff !important;
        transition: none !important;
        opacity: 1 !important;
        z-index: 1;
      }
      .hotel-rating-row .hotel-rating-badge:hover,
      .hotel-rating-row .hotel-rating-badge:focus,
      .hotel-rating-row .hotel-rating-badge:active {
        background: #198754 !important;
        color: #fff !important;
        opacity: 1 !important;
        z-index: 1;
      }
      .carousel-item img {
        opacity: 0;
        transition: opacity 0.15s ease-in-out;
        will-change: opacity;
        transform: translateZ(0);
        backface-visibility: hidden;
      }
      .carousel-item img.img-loaded {
        opacity: 1;
      }
      .carousel-item img.img-error {
        opacity: 1;
        filter: grayscale(100%);
      }
    `;
    document.head.appendChild(style);

    const filterBarHtml = `
      <nav id="section-filter-bar" class="mb-4 sticky-top bg-white py-3" style="z-index:100;box-shadow:0 2px 12px #1e5dd111;border-radius:12px;">
        <div class="container">
          <div class="filter-bar-content d-flex flex-wrap gap-2 justify-content-center align-items-center">
            <a href="#about-section" class="filter-link active" data-section="about">
              <i class="fas fa-info-circle"></i>
              <span>About</span>
            </a>
            <a href="#amenities-section" class="filter-link" data-section="amenities">
              <i class="fas fa-concierge-bell"></i>
              <span>Amenities</span>
            </a>
            <a href="#rooms-section" class="filter-link" data-section="rooms">
              <i class="fas fa-bed"></i>
              <span>Rooms</span>
            </a>
            <a href="#location-section" class="filter-link" data-section="location">
              <i class="fas fa-map-marker-alt"></i>
              <span>Location</span>
            </a>
            <a href="#policies-section" class="filter-link" data-section="policies">
              <i class="fas fa-shield-alt"></i>
              <span>Policies</span>
            </a>
          </div>
        </div>
      </nav>
    `;
    if (hotelInfo) {
      hotelInfo.insertAdjacentHTML('afterend', filterBarHtml);
    }

    setTimeout(() => {
      const filterLinks = document.querySelectorAll('.filter-link');
      const sections = document.querySelectorAll('section[id]');
      
      function updateActiveSection() {
        const scrollPosition = window.scrollY;
        
        sections.forEach(section => {
          const sectionTop = section.offsetTop - 100;
          const sectionBottom = sectionTop + section.offsetHeight;
          const sectionId = section.getAttribute('id');
          
          if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
            filterLinks.forEach(link => {
              link.classList.remove('active');
              if (link.getAttribute('data-section') === sectionId.replace('-section', '')) {
                link.classList.add('active');
              }
            });
          }
        });
      }

      filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.getAttribute('href').replace('#', '');
          const target = document.getElementById(targetId);
          
          if (target) {
            filterLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            target.scrollIntoView({ 
              behavior: 'smooth', 
              block: 'start'
            });
          }
        });
      });

      window.addEventListener('scroll', updateActiveSection);
      updateActiveSection();
    }, 500);

    setTimeout(function() {
      console.log('Star rating activation after DOM update');
      const stars = document.querySelectorAll('#user-star-rating i');
      const msg = document.getElementById('user-rating-msg');
      let rating = 0;
      stars.forEach((star, idx) => {
        star.onclick = function() {
          rating = idx + 1;
          stars.forEach((s, i) => s.className = (i < rating ? 'fa-solid fa-star text-warning' : 'fa-regular fa-star'));
          msg.textContent = 'Thank you for your rating!';
          const hotelId = getHotelIdFromUrl();
          console.log('Sending rating:', hotelId, rating);
          fetch('api/rate_hotel.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ hotel_id: hotelId, rating: rating })
          })
          .then(res => res.json())
          .then(data => {
            console.log('API response:', data);
            if (data.success) {
              msg.textContent = 'Thank you for your rating!';
              msg.className = 'ms-2 text-success';
            } else {
              msg.textContent = 'An error occurred while saving the rating';
              msg.className = 'ms-2 text-danger';
            }
          })
          .catch(() => {
            msg.textContent = 'An error occurred while saving the rating';
            msg.className = 'ms-2 text-danger';
          });
        }
        star.onmouseenter = function() {
          stars.forEach((s, i) => s.className = (i <= idx ? 'fa-solid fa-star text-warning' : 'fa-regular fa-star'));
        }
        star.onmouseleave = function() {
          stars.forEach((s, i) => s.className = (i < rating ? 'fa-solid fa-star text-warning' : 'fa-regular fa-star'));
        }
      });
    }, 700);

    document.querySelectorAll('img').forEach(function(img) {
        img.onerror = function() {
            console.error('Image failed to load:', this.src);
        };
    });

    // --- سياسات الفندق مع فيديو ---
    const policyVideos = {
      cancellation: 'https://www.youtube.com/embed/KQKerrZoMRo',
      children: 'https://www.youtube.com/embed/p1dK3kc-Q1M',
      pets: 'https://www.youtube.com/embed/4iUCGAgeqCY',
      smoking: 'https://www.youtube.com/embed/9WOPtXMqE5E'
    };
    // بعد رسم السياسات
    setTimeout(() => {
      document.querySelectorAll('.policy-link, .policy-video-btn').forEach(el => {
        el.addEventListener('click', function() {
          const videoUrl = this.getAttribute('data-video');
          document.getElementById('policyVideoFrame').src = videoUrl;
          const modal = new bootstrap.Modal(document.getElementById('policyVideoModal'));
          modal.show();
        });
      });
      // عند إغلاق المودال، أفرغ الفيديو
      const policyModal = document.getElementById('policyVideoModal');
      if (policyModal) {
        policyModal.addEventListener('hidden.bs.modal', function() {
          document.getElementById('policyVideoFrame').src = '';
        });
      }
    }, 700);

    // أضف CSS عصري للقسم إذا لم يكن موجودًا
    (function addPoliciesCSS() {
      if (!document.getElementById('policies-modern-css')) {
        const style = document.createElement('style');
        style.id = 'policies-modern-css';
        style.innerHTML = `
.policies-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}
.policy-item {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 12px #1e5dd122;
    padding: 1.5rem 1.2rem 1.2rem 1.2rem;
    transition: box-shadow 0.2s, transform 0.2s;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    min-height: 180px;
    position: relative;
}
.policy-item:hover {
    box-shadow: 0 8px 24px #1e5dd144;
    transform: translateY(-4px) scale(1.02);
}
.policy-icon {
    font-size: 2.2rem;
    color: #1e5dd1;
    margin-bottom: 0.7rem;
    display: inline-block;
}
.policy-title {
    font-size: 1.18rem;
    font-weight: 700;
    color: #1e5dd1;
    margin-bottom: 0.3rem;
    cursor: pointer;
    transition: color 0.2s;
}
.policy-title:hover {
    color: #1746a0;
    text-decoration: underline;
}
.policy-desc {
    color: #444;
    font-size: 1.04rem;
    margin-bottom: 1.1rem;
}
.policy-video-btn {
    border-radius: 20px;
    font-size: 0.98rem;
    font-weight: 600;
    background: #f7faff;
    color: #1e5dd1;
    border: 1.5px solid #1e5dd1;
    transition: background 0.2s, color 0.2s;
    margin-top: auto;
}
.policy-video-btn:hover {
    background: #1e5dd1;
    color: #fff;
}
@media (max-width: 600px) {
    .policies-container {
        grid-template-columns: 1fr;
    }
    .policy-item {
        padding: 1.1rem 0.7rem;
    }
}
`;
        document.head.appendChild(style);
      }
    })();
});

const hotelHeader = document.getElementById('header');
if (hotelHeader) {
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
        if (currentScroll <= 0) {
            hotelHeader.classList.remove('scroll-up');
            return;
        }

        if (currentScroll > lastScroll && !hotelHeader.classList.contains('scroll-down')) {
            hotelHeader.classList.remove('scroll-up');
            hotelHeader.classList.add('scroll-down');
        } else if (currentScroll < lastScroll && hotelHeader.classList.contains('scroll-down')) {
            hotelHeader.classList.remove('scroll-down');
            hotelHeader.classList.add('scroll-up');
    }
    
    lastScroll = currentScroll;
});
}

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