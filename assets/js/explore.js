// Initialize AOS
AOS.init({
    duration: 1000,
    once: true
});

// Global variables for map functionality
let map = null;
let markers = [];
let isMapVisible = false;

function getUrlParameter(name) {
    name = name.replace(/[[\]]/g, '\\$&');
    const regex = new RegExp('[?&]' + name + '=([^&#]*)');
    const results = regex.exec(window.location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

// Essential UI functions only
function getHotelsContainer() {
    const container = document.querySelector('.listings-grid') || 
                     document.querySelector('.hotels-container');
    
    if (container) return container;
    
    console.log("No hotel container found, attempting to create one");
    const possibleParents = [
        document.querySelector('.explore-section'),
        document.querySelector('.hotel-listings'),
        document.querySelector('.listings-section'),
        document.querySelector('main'),
        document.querySelector('body')
    ];
    
    const parent = possibleParents.find(el => el !== null);
    
    if (parent) {
        console.log("Creating hotel container in parent element");
        const newContainer = document.createElement('div');
        newContainer.className = 'listings-grid hotels-container';
        newContainer.style.display = 'grid';
        newContainer.style.gridTemplateColumns = 'repeat(auto-fill, minmax(300px, 1fr))';
        newContainer.style.gap = '20px';
        newContainer.style.margin = '20px auto';
        newContainer.style.maxWidth = '1200px';
        parent.appendChild(newContainer);
        return newContainer;
    }
    
    return null;
}

function getHotelCards() {
    return Array.from(document.querySelectorAll('.hotel-card'));
}

// Modern hotel card generation function
window.generateHotelCard = function(hotel) {
    let mainImg = hotel.image && hotel.image !== 'null' && hotel.image !== '' ? hotel.image : '/Booking-Hotel-Project/pages/images/hotels/default.jpg';
    let images = hotel.images && hotel.images.length > 0 ? hotel.images : [mainImg];
    const mainImgWithCacheBreak = mainImg ? `${mainImg}?v=${hotel.id}` : '/Booking-Hotel-Project/pages/images/hotels/default.jpg';
    const priceUSD = Math.round(hotel.price / 51);
    const rating = parseFloat(hotel.rating) || 0;
    const reviewCount = parseInt(hotel.reviews) || 0;
    const reviewText = reviewCount === 1 ? 'review' : 'reviews';
    const description = hotel.description ? hotel.description.slice(0, 180) + (hotel.description.length > 180 ? '...' : '') : '';
    return `
    <div class=\"hotel-card-modern-horizontal big improved\" data-id=\"${hotel.id}\" data-latitude=\"${hotel.latitude}\" data-longitude=\"${hotel.longitude}\">
        <div class=\"hotel-img-horizontal-wrap big improved\">
            <img src=\"${mainImgWithCacheBreak}\" alt=\"${hotel.name}\" class=\"hotel-main-img-horizontal big improved\" loading=\"lazy\" onerror=\"this.src='../../assets/images/hotels/default.jpg'\">
            <div class=\"hotel-rating-badge-horizontal big improved\">${rating.toFixed(1)} <span class=\"hotel-reviews-horizontal improved\"><i class=\"fa fa-comment-dots\"></i> ${reviewCount} ${reviewText}</span></div>
        </div>
        <div class=\"hotel-card-body-horizontal big improved\">
            <h3 class=\"hotel-name-horizontal big improved\">${hotel.name}</h3>
            <div class=\"hotel-location-horizontal improved\"><i class=\"fas fa-map-marker-alt\"></i> ${hotel.location}</div>
            <div class=\"hotel-price-horizontal improved\"><i class=\"fa fa-money-bill-wave\"></i> <span style=\"color:#27ae60;\">${hotel.price} EGP</span> <span class=\"usd-price big improved\">(~$${priceUSD} USD)</span> <span class=\"night-label\">/night</span></div>
            <div class=\"hotel-description-horizontal big improved\">${description}</div>
            <button class=\"view-details-btn-modern-horizontal big improved\" onclick=\"viewHotelDetails(${hotel.id})\">View Details</button>
        </div>
    </div>
    `;
};

function viewHotelDetails(hotelId) {
    window.location.href = `hotel-details.php?id=${hotelId}`;
}

const categoryButtons = document.querySelectorAll('.filter-tag');
let activeCategory = 'All';

categoryButtons.forEach(btn => {
    btn.addEventListener('click', function() {
        categoryButtons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        activeCategory = this.textContent.trim();
        filterAndSortHotels();
    });
});

// Handle image thumbnails
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('hotel-thumb')) {
        const thumb = e.target;
        const parent = thumb.closest('.hotel-image');
        if (parent) {
            const mainImg = parent.querySelector('.main-hotel-img');
            if (mainImg) {
                mainImg.src = thumb.src;
            }
        }
    }
});

// Handle main search form
const mainSearchForm = document.getElementById('main-search-bar');
const filtersBar = document.getElementById('filters-bar');

if (mainSearchForm) {
    mainSearchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (filtersBar) {
            filtersBar.style.display = '';
            filtersBar.scrollIntoView({ behavior: 'smooth' });
        }
        // Use the unified filter function from filters.js
        if (typeof window.applyFilters === 'function') {
            window.applyFilters();
        }
    });
}

// Initialize filters when page loads
document.addEventListener('DOMContentLoaded', () => {
    console.log("Page loaded - initializing filters");
    if (typeof window.initializeFilters === 'function') {
        window.initializeFilters();
    }
});

function filterAndSortHotels() {
    const cards = getHotelCards();
    const searchTerm = document.getElementById('search-input')?.value.toLowerCase() || '';
    const minPrice = parseFloat(document.getElementById('min-price')?.value) || 0;
    const maxPrice = parseFloat(document.getElementById('max-price')?.value) || Infinity;
    const minRating = parseFloat(document.getElementById('min-rating')?.value) || 0;
    
    cards.forEach(card => {
        const name = card.querySelector('h3').textContent.toLowerCase();
        const location = card.getAttribute('data-category').toLowerCase();
        const price = parseFloat(card.getAttribute('data-price'));
        const rating = parseFloat(card.getAttribute('data-rating'));
        
        const matchesSearch = name.includes(searchTerm) || location.includes(searchTerm);
        const matchesPrice = price >= minPrice && price <= maxPrice;
        const matchesRating = rating >= minRating;
        const matchesCategory = activeCategory === 'All' || location === activeCategory.toLowerCase();
        
        card.style.display = matchesSearch && matchesPrice && matchesRating && matchesCategory ? '' : 'none';
    });
}

function generateCityFilters(hotels) {
    const cityGroup = document.getElementById('cityGroup');
    if (!cityGroup) return;
    const cities = [...new Set(hotels.map(h => h.location).filter(Boolean))];
    cityGroup.innerHTML = cities.map(city => `
        <label><input type="checkbox" value="${city}"> ${city}</label>
    `).join('');
}

window.applyAllFilters = function() {
    const cards = getHotelCards();
    const selectedCities = Array.from(document.querySelectorAll('#cityGroup input[type=checkbox]:checked')).map(cb => cb.value.toLowerCase());
    const selectedRatings = Array.from(document.querySelectorAll('.rating-group input[type=checkbox]:checked')).map(cb => parseFloat(cb.value));
    const selectedPopular = Array.from(document.querySelectorAll('#popularFiltersGroup input[type=checkbox]:checked')).map(cb => cb.value.toLowerCase());
    const selectedFacilities = Array.from(document.querySelectorAll('#facilitiesGroup input[type=checkbox]:checked')).map(cb => cb.value.toLowerCase());
    const selectedRoomFacilities = Array.from(document.querySelectorAll('#roomFacilitiesGroup input[type=checkbox]:checked')).map(cb => cb.value.toLowerCase());
    const selectedReviewScores = Array.from(document.querySelectorAll('#reviewScoreGroup input[type=checkbox]:checked')).map(cb => parseFloat(cb.value));
    const priceRange = document.getElementById('priceRange');
    const maxPrice = priceRange ? parseFloat(priceRange.value) : Infinity;

    let shownCount = 0;
    cards.forEach(card => {
        const city = card.getAttribute('data-category').toLowerCase();
        const price = parseFloat(card.getAttribute('data-price'));
        const rating = parseFloat(card.getAttribute('data-rating'));
        let show = (!selectedCities.length || selectedCities.includes(city));
        if (show && selectedRatings.length) {
            show = selectedRatings.some(r => rating >= (r === 5 ? 4.5 : r) && rating <= r);
        }
        if (show && price > maxPrice) show = false;
        if (show && (selectedPopular.length || selectedFacilities.length || selectedRoomFacilities.length)) {
            const features = (card.getAttribute('data-features') || '').toLowerCase();
            if (selectedPopular.length && !selectedPopular.every(f => features.includes(f))) show = false;
            if (selectedFacilities.length && !selectedFacilities.every(f => features.includes(f))) show = false;
            if (selectedRoomFacilities.length && !selectedRoomFacilities.every(f => features.includes(f))) show = false;
        }
        card.style.display = show ? '' : 'none';
        if (show) shownCount++;
    });
    const countEl = document.getElementById('hotelsCount');
    if (countEl) {
        countEl.textContent = shownCount + (shownCount === 1 ? ' property' : ' properties');
    }
}

function bindAllFilters() {
    // Bind all checkbox filters
    const filterGroups = [
        '#cityGroup input[type="checkbox"]',
        '.rating-group input[type="checkbox"]',
        '#popularFiltersGroup input[type="checkbox"]',
        '#facilitiesGroup input[type="checkbox"]',
        '#roomFacilitiesGroup input[type="checkbox"]',
        '#reviewScoreGroup input[type="checkbox"]'
    ];

    filterGroups.forEach(selector => {
        document.querySelectorAll(selector).forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                console.log(`Filter changed: ${checkbox.value}`);
                applyFilters();
            });
        });
    });

    // Bind price range filter
    const priceRange = document.getElementById('priceRange');
    if (priceRange) {
        priceRange.addEventListener('input', debounce(() => {
            console.log(`Price range changed: ${priceRange.value}`);
            applyFilters();
        }, 300));
    }

    // Bind sort select
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            console.log(`Sort changed: ${sortSelect.value}`);
            applyFilters();
        });
    }
}

// Debug System
const DEBUG = {
    enabled: true,
    logLevel: 'info', // 'debug', 'info', 'warn', 'error'
    logs: [],
    
    log: function(message, level = 'info', data = null) {
        if (!this.enabled) return;
        
        const timestamp = new Date().toISOString();
        const logEntry = {
            timestamp,
            level,
            message,
            data
        };
        
        this.logs.push(logEntry);
        
        // Console output based on level
        switch(level) {
            case 'debug':
                console.debug(`[DEBUG] ${message}`, data || '');
                break;
            case 'info':
                console.info(`[INFO] ${message}`, data || '');
                break;
            case 'warn':
                console.warn(`[WARN] ${message}`, data || '');
                break;
            case 'error':
                console.error(`[ERROR] ${message}`, data || '');
                break;
        }
    },
    
    getLogs: function() {
        return this.logs;
    },
    
    clearLogs: function() {
        this.logs = [];
    }
};

// Error handling wrapper
function handleError(error, context) {
    DEBUG.log(`Error in ${context}: ${error.message}`, 'error', {
        stack: error.stack,
        context: context
    });
    
    // Show user-friendly error message
    showErrorNotification(`An error occurred: ${error.message}`);
}

// Always initialize the map after hotel cards are rendered
function initializeMapAfterHotels() {
    const tryInit = () => {
        // Use the new horizontal card class
        const hotelCards = document.querySelectorAll('.hotel-card-modern-horizontal');
        console.log('[MAP] Checking for hotel cards:', hotelCards.length);
        if (hotelCards.length > 0) {
            // Remove any previous map instance
            if (window.hotelsMap) {
                console.log('[MAP] Removing previous map instance');
                window.hotelsMap.remove();
                window.hotelsMap = null;
            }
            // Initialize map
            const mapContainer = document.getElementById('hotels-map');
            if (!mapContainer) {
                console.error('[MAP] Map container not found!');
                return false;
            }
            const map = L.map('hotels-map').setView([26.8206, 30.8025], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            window.hotelsMap = map;
            console.log('[MAP] Map initialized, adding markers...');
            addHotelMarkers(map);
            return true;
        } else {
            console.warn('[MAP] No hotel cards found for map initialization.');
        }
        return false;
    };
    if (!tryInit()) {
        setTimeout(tryInit, 500);
    }
}

// عند تحميل الصفحة أو بعد رسم الفنادق
window.renderHotelsWithMap = function() {
    initializeMapAfterHotels();
};

document.addEventListener('DOMContentLoaded', function() {
    initializeMapAfterHotels();
});

function addHotelMarkers(map) {
    // Get hotel cards (new horizontal class)
    const hotelCards = document.querySelectorAll('.hotel-card-modern-horizontal');
    console.log('Found hotel cards:', hotelCards.length);
    
    if (hotelCards.length === 0) {
        console.error('No hotel cards found');
        return;
    }
    
    // Use marker cluster group for better visualization
    const markers = L.markerClusterGroup();
    
    // Add markers for each hotel
    hotelCards.forEach(card => {
        // Safely get required elements from new card structure
        const nameEl = card.querySelector('.hotel-name-horizontal');
        const priceEl = card.querySelector('.hotel-price-horizontal');
        const ratingBadge = card.querySelector('.hotel-rating-badge-horizontal');
        const imageEl = card.querySelector('.hotel-main-img-horizontal');
        if (!nameEl || !priceEl || !ratingBadge || !imageEl) {
            console.warn('Skipping hotel card missing required elements:', card);
            return;
        }
        const lat = parseFloat(card.dataset.latitude);
        const lng = parseFloat(card.dataset.longitude);
        const name = nameEl.textContent;
        const price = priceEl.textContent;
        // Extract rating number from badge
        const rating = ratingBadge.textContent.split(' ')[0];
        const image = imageEl.src;
        
        if (isNaN(lat) || isNaN(lng)) {
            console.error(`Invalid coordinates for hotel ${name}: lat=${lat}, lng=${lng}`);
            return;
        }
        
        // Create custom marker icon
        const markerIcon = L.divIcon({
            className: 'hotel-marker',
            html: '<i class="fas fa-hotel"></i>',
            iconSize: [30, 30]
        });
        
        // Create marker with custom icon
        const marker = L.marker([lat, lng], { icon: markerIcon });
        
        // Create popup content
        const popupContent = `
            <div class="hotel-popup">
                <img src="${image}" alt="${name}" class="hotel-popup-img">
                <h3>${name}</h3>
                <div class="hotel-popup-rating">Rating: ${rating}</div>
                <div class="hotel-popup-price">${price}</div>
                <button onclick="viewHotelDetails(${card.dataset.id})" class="view-details-btn">View Details</button>
            </div>
        `;
        
        // Add popup to marker (default: only on click)
        marker.bindPopup(popupContent);
        
        // Add marker to cluster group
        markers.addLayer(marker);
    });
    
    // Add cluster group to map
    map.addLayer(markers);
    
    // Fit map bounds to show all markers
    if (markers.getLayers().length > 0) {
        map.fitBounds(markers.getBounds());
    }
}


