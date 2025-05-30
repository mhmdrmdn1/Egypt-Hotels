const hotelsContainer = document.querySelector('.hotels-container');

const cityFilter = document.getElementById('cityFilter');
const ratingFilter = document.getElementById('ratingFilter');
const priceFilter = document.getElementById('priceFilter');
const sortFilter = document.getElementById('sortFilter');

let originalHotels = [];
let filteredHotels = [];
let citiesRendered = false;

function getUrlParameter(name) {
    name = name.replace(/[[\]]/g, '\\$&');
    const regex = new RegExp('[?&]' + name + '=([^&#]*)');
    const results = regex.exec(window.location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

async function initializeFilters() {
    console.log('Initializing filters and loading hotels...');
    
    try {
        // Clear any existing data
        originalHotels = [];
        filteredHotels = [];
        
        // Fetch hotels from API
        const hotels = await fetchHotels();
        if (!hotels || !Array.isArray(hotels)) {
            console.error('Invalid hotels data received');
            return;
        }

        console.log('Successfully fetched hotels from API:', hotels.length);

        // Store all hotels without any filtering
        originalHotels = [...hotels];
        filteredHotels = [...hotels];
        
        // ضبط شريط السعر على أعلى سعر موجود
        const priceRange = document.getElementById('priceRange');
        if (priceRange && originalHotels.length > 0) {
            const maxHotelPrice = Math.max(...originalHotels.map(h => h.price || 0));
            priceRange.max = Math.ceil(maxHotelPrice / 50) * 50; // تقريب للأعلى لأقرب 50
            priceRange.value = priceRange.max; // القيمة الافتراضية هي الحد الأقصى
            // تحديث النص المعروض
            const priceValue = document.getElementById('priceValue');
            if (priceValue) {
                priceValue.textContent = `${priceRange.value} EGP`;
            }
            // تحديث الحد الأقصى على اليمين
            const priceMaxValue = document.getElementById('priceMaxValue');
            if (priceMaxValue) {
                priceMaxValue.textContent = `${priceRange.max} EGP`;
            }
        }

        console.log('Stored hotels in originalHotels:', originalHotels.length);

        // Initialize UI
        renderCityCheckboxes();
        renderHotels(filteredHotels);
        bindFilterListeners();
        updateHotelCount(filteredHotels.length);

        // Apply URL parameters if present
        const cityFromUrl = getUrlParameter('city');
        if (cityFromUrl) {
            const cityCheckbox = document.querySelector(`#cityGroup input[value="${cityFromUrl}"]`);
            if (cityCheckbox) {
                cityCheckbox.checked = true;
                applyFilters();
            }
        }
    } catch (error) {
        console.error('Error initializing filters:', error);
        showErrorMessage('Error loading hotels. Please try again later.');
    }
}

function updateHotelCount(count) {
    const hotelsCount = document.getElementById('hotelsCount');
    if (hotelsCount) {
        hotelsCount.textContent = `${count} ${count === 1 ? 'property' : 'properties'}`;
        console.log('Updated hotel count display:', count);
    }
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

async function fetchHotels() {
    try {
        console.log('Fetching hotels from API...');
        const response = await fetch('api/hotels_api.php');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const hotels = await response.json();
        console.log('API returned hotels:', hotels.length);
        return hotels;
    } catch (error) {
        console.error('Error fetching hotels:', error);
        showErrorMessage('Error loading hotels. Please try again later.');
        return [];
    }
}

function sortHotels(hotels, sortBy) {
    let sorted = [...hotels];
    switch (sortBy) {
        case 'price_low_high':
            sorted.sort((a, b) => (parseFloat(a.price) || 0) - (parseFloat(b.price) || 0));
            break;
        case 'price_high_low':
            sorted.sort((a, b) => (parseFloat(b.price) || 0) - (parseFloat(a.price) || 0));
            break;
        case 'rating_high_low':
            sorted.sort((a, b) => (parseFloat(b.rating) || 0) - (parseFloat(a.rating) || 0));
            break;
        case 'rating_low_high':
            sorted.sort((a, b) => (parseFloat(a.rating) || 0) - (parseFloat(b.rating) || 0));
            break;
        default:
            break;
    }
    return sorted;
}

// دالة لتوحيد اسم المدينة (إزالة المسافات وتحويل إلى حروف صغيرة)
function normalizeCityName(name) {
    return name ? name.trim().toLowerCase().replace(/\s+/g, ' ') : '';
}

function applyFilters() {
    try {
        // Get filter values
        const checkedCities = Array.from(document.querySelectorAll('#cityGroup input[type="checkbox"]:checked')).map(cb => normalizeCityName(cb.value));

        // Apply city filter only
        filteredHotels = originalHotels.filter(hotel => {
            if (checkedCities.length > 0) {
                const hotelCityRaw = hotel.location ? hotel.location.split(',')[0].trim() : '';
                const hotelCityNorm = normalizeCityName(hotelCityRaw);
                const hotelLocationNorm = normalizeCityName(hotel.location || '');
                const matchesCity = checkedCities.some(city => hotelCityNorm === city || hotelLocationNorm.includes(city));
                if (!matchesCity) return false;
            }
            return true;
        });

        // Sort hotels if needed
        const sortSelect = document.getElementById('sortSelect');
        if (sortSelect) {
            const sortBy = sortSelect.value;
            filteredHotels = sortHotels(filteredHotels, sortBy);
        }

        // Render hotels and update count
        renderHotels(filteredHotels);
        updateHotelCount(filteredHotels.length);
    } catch (error) {
        console.error('Error applying filters:', error);
        showErrorMessage('Error filtering hotels. Please try again.');
    }
}

function renderHotels(hotels) {
    const cardsContainer = document.getElementById('hotelsCards');
    if (!cardsContainer) {
        console.error('Hotels container not found');
        return;
    }

    console.log('Rendering hotels:', hotels.length);

    if (hotels.length === 0) {
        cardsContainer.innerHTML = `
            <div class="no-results-message">
                <i class="fas fa-search"></i>
                <h3>No hotels found</h3>
                <p>Try adjusting your search criteria</p>
            </div>
        `;
        return;
    }

    // Fill missing hotel data with defaults
    const defaultImage = '/Booking-Hotel-Project/pages/images/hotels/default.jpg';
    const safeHotels = hotels.map(hotel => {
        return {
            ...hotel,
            name: hotel.name || 'Unknown Hotel',
            image: hotel.image || defaultImage,
            rating: hotel.rating !== undefined && hotel.rating !== null ? hotel.rating : 0,
            location: hotel.location || 'Unknown Location',
            reviews: hotel.reviews !== undefined && hotel.reviews !== null ? hotel.reviews : 0,
            price: hotel.price !== undefined && hotel.price !== null ? hotel.price : 0,
            latitude: hotel.latitude || 26.8206,
            longitude: hotel.longitude || 30.8025,
            images: Array.isArray(hotel.images) ? hotel.images : (hotel.image ? [hotel.image] : [defaultImage])
        };
    });

    // Use the unified card generator for all hotels
    cardsContainer.innerHTML = safeHotels.map(hotel => window.generateHotelCard(hotel)).join('');

    // Re-initialize AOS for new elements
    if (typeof AOS !== 'undefined') {
        AOS.refresh();
    }

    // Add click handlers
    document.querySelectorAll('.hotel-card').forEach(card => {
        card.addEventListener('click', (e) => {
            if (!e.target.classList.contains('view-details')) {
                const hotelId = card.dataset.id;
                window.location.href = `hotel-details.php?id=${hotelId}`;
            }
        });
    });

    // Re-initialize the map markers after rendering hotels
    if (window.renderHotelsWithMap) {
        window.renderHotelsWithMap();
    }
}

function renderCityCheckboxes() {
    const cityGroup = document.getElementById('cityGroup');
    if (!cityGroup || !originalHotels || originalHotels.length === 0) {
        console.error('Missing required elements for city rendering');
        return;
    }

    // List of cities to exclude from UI only (not from actual data)
    const excludedFromUI = [
        'Zamalek',
        'Siwa Oasis',
        'Nile Corniche',
        'Marsa Matrouh',
        'Downtown',
        'Aswan Island',
        'Alamein'
    ];

    // Get all unique cities from hotels (normalized)
    const citySet = new Map();
    originalHotels.forEach(hotel => {
        if (hotel.location) {
            const cityRaw = hotel.location.split(',')[0].trim();
            const cityNorm = normalizeCityName(cityRaw);
            if (cityRaw && !excludedFromUI.includes(cityRaw) && !citySet.has(cityNorm)) {
                citySet.set(cityNorm, cityRaw); // استخدم الاسم الأصلي للعرض
            }
        }
    });

    const cityList = Array.from(citySet.values()).sort((a, b) => a.localeCompare(b));
    
    // Generate checkboxes HTML (vertical list)
    cityGroup.innerHTML = cityList.map(city => `
        <div style="margin-bottom: 0px;">
            <label class="checkbox-label" style="display: flex; align-items: center;">
                <input type="checkbox" 
                       name="city[]" 
                       value="${city}">
                <span class="checkbox-text" style="margin-left: 8px;">${city}</span>
            </label>
        </div>
    `).join('');

    // Attach event listeners
    cityGroup.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', applyFilters);
    });
}

function bindFilterListeners() {
    // Bind only city filter inputs
    const filterInputs = document.querySelectorAll('#cityGroup input[type="checkbox"], #sortSelect');
    filterInputs.forEach(input => {
        input.addEventListener('change', applyFilters);
    });
    // Bind search input
    const searchInput = document.querySelector('#locationInput, .search-box input');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            applyFilters();
        });
    }
}