document.addEventListener('DOMContentLoaded', function() {
    const thumbnails = document.querySelectorAll('.thumbnails img');
    const mainImage = document.querySelector('.main-image img');
    const lightboxModal = document.getElementById('imageLightbox');
    const lightboxImage = lightboxModal.querySelector('.lightbox-content img');
    const closeButton = lightboxModal.querySelector('.lightbox-close');
    const prevButton = lightboxModal.querySelector('.lightbox-prev');
    const nextButton = lightboxModal.querySelector('.lightbox-next');
    const counter = lightboxModal.querySelector('.lightbox-counter');
    
    let images = [];
    let currentIndex = 0;
    if (mainImage) {
        images.push(mainImage.src);
    }
    
    thumbnails.forEach(thumb => {
        if (!images.includes(thumb.src)) {
            images.push(thumb.src);
        }
    });

    const updateCounter = () => {
        counter.textContent = `${currentIndex + 1} / ${images.length}`;
    };

    const showImage = () => {
        lightboxImage.src = images[currentIndex];
        updateCounter();
    };
    const openLightbox = (index) => {
        currentIndex = index;
        showImage();
        lightboxModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    const closeLightbox = () => {
        lightboxModal.classList.remove('active');
        document.body.style.overflow = '';
    };

    const showPrevImage = () => {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        showImage();
    };

    const showNextImage = () => {
        currentIndex = (currentIndex + 1) % images.length;
        showImage();
    };

    if (mainImage) {
        mainImage.addEventListener('click', () => openLightbox(0));
    }

    thumbnails.forEach((thumb, index) => {
        thumb.addEventListener('click', () => openLightbox(index + 1));
    });

    if (closeButton) closeButton.addEventListener('click', closeLightbox);
    if (prevButton) prevButton.addEventListener('click', showPrevImage);
    if (nextButton) nextButton.addEventListener('click', showNextImage);    

    if (lightboxModal) {
        lightboxModal.addEventListener('click', (e) => {
            if (e.target === lightboxModal) {
                closeLightbox();
            }
        });
    }

    document.addEventListener('keydown', (e) => {
        if (!lightboxModal || !lightboxModal.classList.contains('active')) return;
        switch (e.key) {
            case 'Escape':
                closeLightbox();
                break;
            case 'ArrowLeft':
                showPrevImage();
                break;
            case 'ArrowRight':
                showNextImage();
                break;
        }
    });

    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const checkIn = document.getElementById('checkIn').value;
            const checkOut = document.getElementById('checkOut').value;
            const guests = document.getElementById('guests').value;
            alert('Availability check request sent successfully!');
        });
    }

    const searchBox = document.querySelector('.search-box input');
    const searchBtn = document.querySelector('.search-btn');
    const cityFilter = document.getElementById('city');
    const ratingFilter = document.getElementById('rating');
    const priceFilter = document.getElementById('price');
    const sortFilter = document.getElementById('sort');

    let hotelData = window.hotelsData || [];
    let hotelCards = document.querySelectorAll('.hotel-card');

    hotelCards.forEach(card => {
        const hotelId = card.dataset.hotelId;
        if (!hotelId) {
            const hotelName = card.querySelector('h3')?.textContent;
            const hotel = hotelData.find(h => h.name === hotelName);
            if (hotel) {
                card.dataset.hotelId = hotel.id;
                card.dataset.city = hotel.city;
                card.dataset.price = hotel.price;
                card.dataset.rating = hotel.rating;
            }
        }
    });

    const urlParams = new URLSearchParams(window.location.search);
    if (searchBox && urlParams.has('location')) {
        searchBox.value = urlParams.get('location');
    }
    if (cityFilter && urlParams.has('city')) {
        cityFilter.value = urlParams.get('city');
    }

    document.addEventListener('searchUpdated', function(e) {
        const searchData = e.detail;
        if (searchData && searchData.location) {
            if (searchBox) {
                searchBox.value = searchData.location;
            }
            applyFilters();
        }
    });

    if (searchBox && searchBtn) {
        const handleSearch = () => {
            const searchTerm = searchBox.value.trim();
            if (searchTerm) {
                const params = new URLSearchParams(window.location.search);
                params.set('location', searchTerm);
                window.history.replaceState({}, '', `${window.location.pathname}?${params}`);
                
                const searchEvent = new CustomEvent('searchUpdated', {
                    detail: { location: searchTerm }
                });
                document.dispatchEvent(searchEvent);
                
                applyFilters();
            }
        };

        searchBtn.addEventListener('click', handleSearch);
        searchBox.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        });
    }

    function applyFilters() {
        // Get all filter values
        const searchLocation = (searchBox?.value || '').toLowerCase();
        const selectedCities = Array.from(document.querySelectorAll('#cityGroup input:checked')).map(input => input.value);
        const selectedRatings = Array.from(document.querySelectorAll('.rating-group input:checked')).map(input => parseFloat(input.value));
        const selectedPopular = Array.from(document.querySelectorAll('#popularFiltersGroup input:checked')).map(input => input.value);
        const selectedFacilities = Array.from(document.querySelectorAll('#facilitiesGroup input:checked')).map(input => input.value);
        const selectedRoomFacilities = Array.from(document.querySelectorAll('#roomFacilitiesGroup input:checked')).map(input => input.value);
        const selectedReviewScores = Array.from(document.querySelectorAll('#reviewScoreGroup input:checked')).map(input => parseFloat(input.value));
        const maxPrice = document.getElementById('priceRange')?.value || 5000;
        const sortBy = document.getElementById('sortSelect')?.value || 'recommended';

        // Get all hotel cards
        hotelCards = document.querySelectorAll('.hotel-card');
        let filteredHotels = hotelData.slice();

        // Apply text search filter
        if (searchLocation) {
            filteredHotels = filteredHotels.filter(hotel =>
                (hotel.name + ' ' + hotel.city + ' ' + (hotel.location || '') + ' ' + (hotel.description || '')).toLowerCase().includes(searchLocation)
            );
        }

        // Apply city filter
        if (selectedCities.length > 0) {
            filteredHotels = filteredHotels.filter(hotel => selectedCities.includes(hotel.city));
        }

        // Apply rating filter
        if (selectedRatings.length > 0) {
            filteredHotels = filteredHotels.filter(hotel => 
                selectedRatings.some(rating => Math.floor(hotel.rating) === Math.floor(rating))
            );
        }

        // Apply popular filters
        if (selectedPopular.length > 0) {
            filteredHotels = filteredHotels.filter(hotel => 
                selectedPopular.every(filter => hotel.features?.includes(filter))
            );
        }

        // Apply facilities filter
        if (selectedFacilities.length > 0) {
            filteredHotels = filteredHotels.filter(hotel => 
                selectedFacilities.every(facility => hotel.facilities?.includes(facility))
            );
        }

        // Apply room facilities filter
        if (selectedRoomFacilities.length > 0) {
            filteredHotels = filteredHotels.filter(hotel => 
                selectedRoomFacilities.every(facility => hotel.roomFacilities?.includes(facility))
            );
        }

        // Apply review score filter
        if (selectedReviewScores.length > 0) {
            filteredHotels = filteredHotels.filter(hotel => 
                selectedReviewScores.some(score => hotel.reviewScore >= score)
            );
        }

        // Apply price filter
        filteredHotels = filteredHotels.filter(hotel => hotel.price <= maxPrice);

        // Apply sorting
        switch (sortBy) {
            case 'price_low_high':
                filteredHotels.sort((a, b) => a.price - b.price);
                break;
            case 'price_high_low':
                filteredHotels.sort((a, b) => b.price - a.price);
                break;
            case 'rating_high_low':
                filteredHotels.sort((a, b) => b.rating - a.rating);
                break;
            case 'rating_low_high':
                filteredHotels.sort((a, b) => a.rating - b.rating);
                break;
            default: // recommended
                filteredHotels.sort((a, b) => b.rating - a.rating);
        }

        // Show/hide cards based on filtering
        hotelCards.forEach(card => {
            const hotelId = parseInt(card.dataset.hotelId);
            if (filteredHotels.some(h => h.id === hotelId)) {
                card.style.display = 'block';
                card.style.opacity = '0';
                setTimeout(() => {
                    card.style.opacity = '1';
                }, 50);
            } else {
                card.style.display = 'none';
            }
        });

        // Update hotels count
        const visibleCards = Array.from(hotelCards).filter(card => card.style.display !== 'none');
        const hotelsCount = document.getElementById('hotelsCount');
        if (hotelsCount) {
            hotelsCount.textContent = `${visibleCards.length}+ properties`;
        }

        // Show/hide no results message
        const noResultsMsg = document.querySelector('.no-results-message');
        if (noResultsMsg) {
            noResultsMsg.style.display = visibleCards.length === 0 ? 'block' : 'none';
        }
    }

    // Add event listeners for all filter inputs
    document.querySelectorAll('.filter-group input[type="checkbox"], #priceRange, #sortSelect').forEach(input => {
        input.addEventListener('change', applyFilters);
    });

    // Add event listener for price range input
    const priceRange = document.getElementById('priceRange');
    if (priceRange) {
        priceRange.addEventListener('input', () => {
            const priceOutput = document.getElementById('priceOutput');
            if (priceOutput) {
                priceOutput.value = priceRange.value;
            }
        });
    }

    window.applyAllFilters = applyFilters;
    window.filterAndSortHotels = applyFilters;

    [cityFilter, ratingFilter, priceFilter, sortFilter].forEach(filter => {
        if (filter) {
            filter.addEventListener('change', applyFilters);
        }
    });

    if (urlParams.toString()) {
        applyFilters();
    }

    const viewDetailsButtons = document.querySelectorAll('.view-details');
    viewDetailsButtons.forEach(button => {
        button.addEventListener('click', function() {
            const hotelCard = this.closest('.hotel-card');
            const hotelName = hotelCard.querySelector('h3')?.textContent;
            const hotelId = hotelCard.dataset.hotelId;
            
            if (hotelId) {
                window.location.href = `hotel-details.php?id=${hotelId}`;
            } else {
                console.warn('Hotel ID not found for:', hotelName);
            }
        });
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId !== '#') {
                document.querySelector(targetId).scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    const navLinks = document.querySelectorAll('nav ul li a');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetHref = this.getAttribute('href');
            if (targetHref && targetHref.startsWith('#')) {
                e.preventDefault();
                if (targetHref !== '#') {
                    document.querySelector(targetHref).scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    const roomCards = document.querySelectorAll('.room-card');
    
    roomCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    hotelCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    const userIcon = document.querySelector('.user-icon');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    userIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('active');
    });

    document.addEventListener('click', function(e) {
        if (!dropdownMenu.contains(e.target) && !userIcon.contains(e.target)) {
            dropdownMenu.classList.remove('active');
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            dropdownMenu.classList.remove('active');
        }
    });
}); 