(() => {
  // Only run if the search bar exists
  const searchForm = document.getElementById('searchForm');
  if (!searchForm) return;

  // --- Autocomplete Data (use hotel locations from hotelsData if available) ---
  let locations = [];
  if (window.hotelsData) {
    locations = Object.values(window.hotelsData).map(h => h.location);
  } else {
    locations = [
      'Cairo', 'Alexandria', 'Sharm El-Sheikh', 'Hurghada', 'Luxor', 'Aswan',
      'Dahab', 'Marsa Alam', 'Siwa', 'Fayoum','Taba','New Alamein'
    ];
  }
  locations = [...new Set(locations)];

  // --- DOM Elements ---
  const locationInput = document.getElementById('locationInput');
  const autocompleteResults = document.getElementById('autocompleteResults');
  const checkInInput = document.getElementById('checkInInput');
  const checkOutInput = document.getElementById('checkOutInput');
  const guestsInput = document.getElementById('guestsInput');
  const guestsPicker = document.getElementById('guestsPicker');
  const adultsValue = document.getElementById('adultsValue');
  const childrenValue = document.getElementById('childrenValue');
  const roomsValue = document.getElementById('roomsValue');

  if (locationInput && autocompleteResults) {
    locationInput.addEventListener('input', () => {
      const val = locationInput.value.trim().toLowerCase();
      autocompleteResults.innerHTML = '';
      if (!val) {
        autocompleteResults.classList.remove('active');
        return;
      }
      const matches = locations.filter(loc => loc.toLowerCase().includes(val));
      if (matches.length === 0) {
        autocompleteResults.classList.remove('active');
        return;
      }
      matches.forEach(loc => {
        const item = document.createElement('div');
        item.className = 'autocomplete-item';
        item.innerHTML = `<i class='fas fa-map-marker-alt'></i><span class='item-name'>${loc}</span>`;
        item.addEventListener('click', () => {
          locationInput.value = loc;
          autocompleteResults.classList.remove('active');
          const searchEvent = new CustomEvent('searchUpdated', {
            detail: { location: loc }
          });
          document.dispatchEvent(searchEvent);
        });
        autocompleteResults.appendChild(item);
      });
      autocompleteResults.classList.add('active');
    });
    document.addEventListener('click', e => {
      if (!autocompleteResults.contains(e.target) && e.target !== locationInput) {
        autocompleteResults.classList.remove('active');
      }
    });
  }

  function setupDatePicker(input, pickerId) {
    if (!input) return;
    
    const today = new Date().toISOString().split('T')[0];
    input.min = today;
    
    input.addEventListener('click', (e) => {
      e.preventDefault();
      input.showPicker(); 
    });

    input.addEventListener('change', () => {
      if (input.id === 'checkInInput' && checkOutInput) {
        checkOutInput.min = input.value;
        if (checkOutInput.value && checkOutInput.value < input.value) {
          checkOutInput.value = '';
        }
      }
      
      if (typeof window.applyAllFilters === 'function') {
        window.applyAllFilters();
      }
    });
  }

  setupDatePicker(checkInInput, 'checkInPicker');
  setupDatePicker(checkOutInput, 'checkOutPicker');

  if (guestsInput && guestsPicker) {
    guestsInput.addEventListener('focus', () => {
      guestsPicker.classList.add('active');
    });
    guestsInput.addEventListener('click', () => {
      guestsPicker.classList.add('active');
    });
    document.addEventListener('click', e => {
      if (!guestsPicker.contains(e.target) && e.target !== guestsInput) {
        guestsPicker.classList.remove('active');
      }
    });
    guestsPicker.querySelectorAll('.counter-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const type = btn.dataset.type;
        const op = btn.dataset.op;
        let val = parseInt(document.getElementById(type + 'Value').textContent);
        if (op === 'inc') val++;
        if (op === 'dec' && val > (type === 'adults' ? 1 : 0)) val--;
        document.getElementById(type + 'Value').textContent = val;
        updateGuestsInput();
        if (typeof window.applyAllFilters === 'function') {
          window.applyAllFilters();
        }
      });
    });
    function updateGuestsInput() {
      guestsInput.value = `${adultsValue.textContent} Adults, ${childrenValue.textContent} Children, ${roomsValue.textContent} Room${roomsValue.textContent > 1 ? 's' : ''}`;
    }
    guestsPicker.querySelector('.done-btn').addEventListener('click', () => {
      guestsPicker.classList.remove('active');
      updateGuestsInput();
      if (typeof window.applyAllFilters === 'function') {
        window.applyAllFilters();
      }
    });
    updateGuestsInput();
  }

  async function searchHotels(query) {
    try {
      const response = await fetch('/Booking-Hotel-Project/pages/api/hotels_api.php');
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const hotels = await response.json();
      
      // البحث في الفنادق
      return hotels.filter(hotel => 
        hotel.name.toLowerCase().includes(query.toLowerCase()) ||
        hotel.city.toLowerCase().includes(query.toLowerCase()) ||
        hotel.description.toLowerCase().includes(query.toLowerCase())
      );
    } catch (error) {
      console.error('Error searching hotels:', error);
      return [];
    }
  }

  function displaySearchResults(hotels) {
    const container = document.querySelector('.hotels-container');
    if (!container) return;

    if (!hotels || hotels.length === 0) {
      container.innerHTML = `
        <div class="no-results">
          <i class="fas fa-search"></i>
          <p>No hotels found for your search criteria.</p>
        </div>
      `;
      return;
    }

    // تجهيز بيانات الفنادق لتكون متوافقة مع الكارت الحديث
    const defaultImage = '/Booking-Hotel-Project/pages/images/hotels/default.jpg';
    const safeHotels = hotels.map(hotel => {
      return {
        ...hotel,
        name: hotel.name || 'Unknown Hotel',
        image: hotel.image || defaultImage,
        images: Array.isArray(hotel.images) && hotel.images.length > 0 ? hotel.images : [hotel.image || defaultImage],
        rating: hotel.rating !== undefined && hotel.rating !== null ? hotel.rating : 4.5,
        location: hotel.location || 'Unknown Location',
        reviews: hotel.reviews_count || hotel.reviews || 0,
        price: hotel.price !== undefined && hotel.price !== null ? hotel.price : 0,
        latitude: hotel.latitude || hotel.lat || 26.8206,
        longitude: hotel.longitude || hotel.lng || 30.8025,
        features: hotel.amenities || hotel.features || []
      };
    });

    container.innerHTML = safeHotels.map(hotel => window.generateHotelCard(hotel)).join('');

    // إعادة تفعيل أي مكتبات أو أحداث مرتبطة بالكروت (مثل AOS أو الخرائط)
    if (typeof AOS !== 'undefined') {
      AOS.refresh();
    }
    if (window.renderHotelsWithMap) {
      window.renderHotelsWithMap();
    }
  }

  document.getElementById('searchForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const query = locationInput.value.trim().toLowerCase();
    const container = document.querySelector('.hotels-container');
    
    try {
      // Show loading state
      if (container) {
        container.innerHTML = `
          <div class="loading">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Searching for hotels...</p>
          </div>
        `;
      }

      const response = await fetch('/Booking-Hotel-Project/pages/api/hotels_api.php');
      let hotels;
      let rawText = await response.text();
      try {
        hotels = JSON.parse(rawText);
      } catch (jsonErr) {
        console.error('JSON Parse Error:', jsonErr, 'Raw response:', rawText);
        if (container) {
          container.innerHTML = `
            <div class="error-message">
              <i class="fas fa-exclamation-circle"></i>
              <p>Failed to parse hotel data. Please contact support.</p>
              <small>JSON Error: ${jsonErr.message}</small>
              <pre>${rawText}</pre>
            </div>
          `;
        }
        return;
      }
      console.log('Hotels API Response:', hotels);
      if (!Array.isArray(hotels)) {
        console.error('API did not return an array:', hotels);
        if (container) {
          container.innerHTML = `
            <div class="error-message">
              <i class="fas fa-exclamation-circle"></i>
              <p>API did not return a valid hotel list.</p>
              <pre>${JSON.stringify(hotels, null, 2)}</pre>
            </div>
          `;
        }
        return;
      }
      // فلترة الفنادق حسب المدينة والاسم والوصف
      const results = hotels.filter(hotel => {
        const searchFields = [
          hotel.location,
          hotel.name,
          hotel.description
        ].map(field => field ? field.toLowerCase() : '');
        return searchFields.some(field => field.includes(query));
      });
      displaySearchResults(results);
      // Update URL without reloading
      const searchParams = new URLSearchParams(window.location.search);
      searchParams.set('location', locationInput.value);
      window.history.pushState({}, '', `${window.location.pathname}?${searchParams.toString()}`);
    } catch (error) {
      console.error('Error searching hotels:', error);
      if (container) {
        container.innerHTML = `
          <div class="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <p>Failed to search hotels. Please try again.</p>
            <small>Error: ${error.message}</small>
          </div>
        `;
      }
    }
  });

  const params = new URLSearchParams(window.location.search);
  if (locationInput && params.has('location')) {
    locationInput.value = params.get('location');
  }
  if (checkInInput && params.has('checkIn')) {
    checkInInput.value = params.get('checkIn');
  }
  if (checkOutInput && params.has('checkOut')) {
    checkOutInput.value = params.get('checkOut');
  }
  if (adultsValue && params.has('adults')) {
    adultsValue.textContent = params.get('adults');
  }
  if (childrenValue && params.has('children')) {
    childrenValue.textContent = params.get('children');
  }
  if (roomsValue && params.has('rooms')) {
    roomsValue.textContent = params.get('rooms');
  }
  if (guestsInput) {
    updateGuestsInput();
  }

  function generateHotelCard(hotel) {
    // تأكد من وجود مصفوفة صور صحيحة
    const images = Array.isArray(hotel.images) && hotel.images.length > 0
      ? hotel.images
      : [hotel.image || '/Booking-Hotel-Project/pages/images/hotels/default.jpg'];

    return `
      <div class="hotel-card" data-id="${hotel.id}">
        <div class="hotel-image">
          <img src="${images[0]}" alt="${hotel.name}">
          <div class="hotel-rating">
            <span class="stars">${'★'.repeat(Math.floor(hotel.rating))}${hotel.rating % 1 ? '½' : ''}</span>
            <span class="reviews">${hotel.reviews_count || hotel.reviews || 0} reviews</span>
          </div>
        </div>
        <div class="hotel-info">
          <h3>${hotel.name}</h3>
          <div class="hotel-location">
            <i class="fas fa-map-marker-alt"></i>
            ${hotel.location}
          </div>
          <div class="hotel-amenities">
            ${(hotel.amenities || hotel.features || []).slice(0, 3).map(amenity => `
              <span class="amenity">
                <i class="fas ${getAmenityIcon(amenity)}"></i>
                ${typeof amenity === 'string' ? amenity : (amenity.name || '')}
              </span>
            `).join('')}
          </div>
          <div class="hotel-price">
            <span class="amount">${hotel.price_per_night || hotel.price} EGP</span>
            <span class="per-night">per night</span>
          </div>
          <button class="view-details-btn" onclick="window.location.href='hotel-details.php?id=${hotel.id}'">
            View Details
          </button>
        </div>
      </div>
    `;
  }
})(); 