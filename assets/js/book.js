AOS.init({
    duration: 800,
    easing: 'ease-in-out'
});

const bookingForm = document.getElementById('booking-form');
const hotelSelect = document.getElementById('hotel');
const roomTypeSelect = document.getElementById('room-type');
const checkInInput = document.getElementById('check-in');
const checkOutInput = document.getElementById('check-out');
const guestsInput = document.getElementById('guests');
const roomsInput = document.getElementById('rooms');

const summaryHotel = document.querySelector('.hotel-name');
const summaryRoomType = document.querySelector('.room-type');
const summaryCheckIn = document.querySelector('.check-in');
const summaryCheckOut = document.querySelector('.check-out');
const summaryGuests = document.querySelector('.guests');
const summaryRooms = document.querySelector('.rooms');
const summaryTotal = document.querySelector('.total-price');

const hotelPrices = {
    'The Nile Ritz-Carlton': { standard: 600, deluxe: 1200, panoramic: 1800 },
    'Four Seasons At San Stefano': { standard: 1000, deluxe: 2300, panoramic: 3000 },
    'Royal Savoy Sharm El Sheikh' : { standard: 800, deluxe: 1400, family: 1800 },
    'Serry Beach Resort' : { superior: 500, beachfront: 1000},
    'Sofitel Winter Palace Luxor' : { standard: 600, deluxe: 1200, suite: 1800},
    'Mövenpick Resort Aswan' : { standard: 800, deluxe: 1500, panoramic: 2000},
    'Dahab Lodge' : {chalet: 200, family: 400},
    'Marriott Mena House' : { standard: 900, deluxe: 1700, panoramic: 2200},
    'Casa Blue Resort' : { superior: 800, beachfront: 1500},
    'Steigenberger Hotel & Nelson Village' : { standard: 400, deluxe: 700, panoramic: 1000},
    'Helnan Auberge Fayoum' : { standard: 600, deluxe: 1100, panoramic: 1500},
    'Siwa Tarriott eco lodge hotel' : { standard: 500, deluxe: 800},
    'Chalet in Marassi Marina, Canal view with luxurious furniture' : { standard: 500, deluxe: 1000},
    'Intercontinental Cairo Citystars' : { standard: 700, deluxe: 1200, panoramic: 1600},
    'Rhactus House San Stefano' : { standard: 500, deluxe: 900},
    'Domina Coral Bay Resort' : { deluxe: 1100, Beachfront: 2000, family: 2800},
    'Sunrise Aqua Joy Resort' : { deluxe: 750, family: 1500, executive: 2200},
    'Luxor Nile View Flats' : { nileview: 400, executive: 800},
    'Sofitel Legend Old Cataract' : { nileview: 1000, nubian: 2000, royal: 3000},
    'Beit Theresa' : { beachchalet: 1100, deluxe: 2800},
    'New Cairo Nyoum Porto New Cairo, Elite Apartments' : { nileview: 200, executive: 550, family: 800},
    'Marsa CoralCoral Hills Resort & SPA' : { deluxe: 250, beachfront: 450},
    'Taba Sands Hotel & Casino' : { deluxe: 600, family: 1500, executive: 2000},
    'Tache Boutique Hotel Fayoum' : { oasis: 200, desert: 500},
    'Siwa Shali Resort' : { mudchalet: 450, oasis: 1000, family: 1500},
    'Palma Bay Rotana Resort' : { marinaview: 350, lagoon: 700, family: 1100},
    'Concorde El Salam Cairo Hotel & Casino' : { cityview: 600, executive: 1200},
    'Hotel appartment alexandria sea view' : { deluxe: 400, royal: 1000},
    'Reef Oasis Beach Aqua Park Resort' : { luxurytent: 800, desert: 1700, family: 2200},
    'Golden Beach Resort' : { deluxe: 700, beachfront: 1400, family: 1800},
};

const hotelsData = {
    'The Nile Ritz-Carlton': 1,
    'Four Seasons At San Stefano': 2,
    'Royal Savoy Sharm El Sheikh': 3,
    'Serry Beach Resort': 4,
    'Sofitel Winter Palace Luxor': 5,
    'Mövenpick Resort Aswan': 6,
    'Dahab Lodge': 7,
    'Marriott Mena House': 8,
    'Casa Blue Resort': 9,
    'Steigenberger Hotel & Nelson Village': 10,
    'Helnan Auberge Fayoum': 11,
    'Siwa Tarriott eco lodge hotel': 12,
    'Chalet in Marassi Marina, Canal view with luxurious furniture': 13,
    'Intercontinental Cairo Citystars': 14,
    'Rhactus House San Stefano': 15,
    'Domina Coral Bay Resort': 16,
    'Sunrise Aqua Joy Resort': 17,
    'Luxor Nile View Flats': 18,
    'Sofitel Legend Old Cataract': 19,
    'Beit Theresa': 20,
    'New Cairo Nyoum Porto New Cairo, Elite Apartments': 21,
    'Marsa CoralCoral Hills Resort & SPA': 22,
    'Taba Sands Hotel & Casino': 23,
    'Tache Boutique Hotel Fayoum': 24,
    'Siwa Shali Resort': 25,
    'Palma Bay Rotana Resort': 26,
    'Concorde El Salam Cairo Hotel & Casino': 27,
    'Hotel appartment alexandria sea view': 28,
    'Reef Oasis Beach Aqua Park Resort': 29,
    'Golden Beach Resort': 30
};

const bookingMessage = document.getElementById('booking-message');

const roomsData = {
    'Beit Theresa|Deluxe Suite': 55,
    'Beit Theresa|Standard Room': 54,
    'Casa Blue Resort|Beachfront Suite': 24,
    'Casa Blue Resort|Superior Lagoon Room': 23,
    'Chalet in Marassi Marina, Canal view with luxurious furniture|Family Suite': 35,
    'Chalet in Marassi Marina, Canal view with luxurious furniture|Mud Chalet': 33,
    'Chalet in Marassi Marina, Canal view with luxurious furniture|Oasis Suite': 34,
    'Dahab Lodge|Beach Chalet': 18,
    'Dahab Lodge|Family Suite': 19,
    'Domina Coral Bay Resort|Deluxe Suite': 43,
    'Domina Coral Bay Resort|Family Suite': 44,
    'Domina Coral Bay Resort|Standard Room': 42,
    'Four Seasons At San Stefano|Deluxe Suite': 5,
    'Four Seasons At San Stefano|Panoramic Room': 6,
    'Four Seasons At San Stefano|Standard Room': 4,
    'Golden Beach Resort|Deluxe Suite': 85,
    'Golden Beach Resort|Family Suite': 86,
    'Golden Beach Resort|Standard Room': 84,
    'Helnan Auberge Fayoum|Deluxe Suite': 29,
    'Helnan Auberge Fayoum|Panoramic Room': 30,
    'Helnan Auberge Fayoum|Standard Room': 28,
    'Hotel appartment alexandria sea view|Standard Room': 78,
    'Intercontinental Cairo Citystars|Deluxe Suite': 37,
    'Intercontinental Cairo Citystars|Executive Suite': 38,
    'Intercontinental Cairo Citystars|Standard Room': 36,
    'Luxor Nile View Flats|Deluxe Suite': 49,
    'Luxor Nile View Flats|Standard Room': 48,
    'Marriott Mena House|Deluxe Suite': 21,
    'Marriott Mena House|Panoramic Room': 22,
    'Marriott Mena House|Standard Room': 20,
    'Marsa CoralCoral Hills Resort & SPA|Deluxe Suite': 61,
    'Marsa CoralCoral Hills Resort & SPA|Family Suite': 62,
    'Mövenpick Resort Aswan|Deluxe Suite': 16,
    'Mövenpick Resort Aswan|Panoramic Room': 17,
    'Mövenpick Resort Aswan|Standard Room': 15,
    'New Cairo Nyoum Porto New Cairo, Elite Apartments|Deluxe Suite': 58,
    'New Cairo Nyoum Porto New Cairo, Elite Apartments|Family Suite': 59,
    'Palma Bay Rotana Resort|Deluxe Suite': 73,
    'Palma Bay Rotana Resort|Family Suite': 74,
    'Palma Bay Rotana Resort|Standard Room': 72,
    'Reef Oasis Beach Aqua Park Resort|Deluxe Suite': 82,
    'Reef Oasis Beach Aqua Park Resort|Family Suite': 83,
    'Reef Oasis Beach Aqua Park Resort|Standard Room': 81,
    'Rhactus House San Stefano|Deluxe Suite': 40,
    'Rhactus House San Stefano|Standard Room': 39,
    'Royal Savoy Sharm El Sheikh|Deluxe Suite': 8,
    'Royal Savoy Sharm El Sheikh|Family Room': 9,
    'Royal Savoy Sharm El Sheikh|Standard Room': 7,
    'Serry Beach Resort|Beachfront Suite': 11,
    'Serry Beach Resort|Superior Room': 10,
    'Siwa Shali Resort|Deluxe Suite': 70,
    'Siwa Shali Resort|Standard Room': 69,
    'Siwa Tarriott eco lodge hotel|Deluxe Suite': 32,
    'Siwa Tarriott eco lodge hotel|Standard Room': 31,
    'Sofitel Legend Old Cataract|Deluxe Suite': 52,
    'Sofitel Legend Old Cataract|Royal Suite': 53,
    'Sofitel Legend Old Cataract|Standard Room': 51,
    'Sofitel Winter Palace Luxor|Deluxe Suite': 13,
    'Sofitel Winter Palace Luxor|Panoramic Room': 14,
    'Sofitel Winter Palace Luxor|Standard Room': 12,
    'Steigenberger Hotel & Nelson Village|Deluxe Suite': 26,
    'Steigenberger Hotel & Nelson Village|Panoramic Room': 27,
    'Steigenberger Hotel & Nelson Village|Standard Room': 25,
    'Sunrise Aqua Joy Resort|Deluxe Suite': 46,
    'Sunrise Aqua Joy Resort|Family Suite': 47,
    'Sunrise Aqua Joy Resort|Standard Room': 45,
    'Taba Sands Hotel & Casino|Deluxe Suite': 64,
    'Taba Sands Hotel & Casino|Family Suite': 65,
    'Taba Sands Hotel & Casino|Standard Room': 63,
    'Tache Boutique Hotel Fayoum|Deluxe Suite': 67,
    'Tache Boutique Hotel Fayoum|Standard Room': 66,
    'The Nile Ritz-Carlton|Deluxe Suite': 2,
    'The Nile Ritz-Carlton|Panoramic Room': 3,
    'The Nile Ritz-Carlton|Standard Room': 1,
};

function updateSummary() {
    const params = new URLSearchParams(window.location.search);
    const hotel = params.get('hotel') ? decodeURIComponent(params.get('hotel')) : 
                 (hotelSelect ? hotelSelect.options[hotelSelect.selectedIndex].text : '-');
    const roomType = params.get('room') ? decodeURIComponent(params.get('room')) : 
                    (roomTypeSelect ? roomTypeSelect.options[roomTypeSelect.selectedIndex].text : '-');
    const checkIn = checkInInput.value;
    const checkOut = checkOutInput.value;
    const guests = guestsInput.value;
    const rooms = roomsInput.value;

    if (summaryHotel) summaryHotel.textContent = hotel;
    if (summaryRoomType) summaryRoomType.textContent = roomType;
    if (summaryCheckIn) summaryCheckIn.textContent = checkIn || '-';
    if (summaryCheckOut) summaryCheckOut.textContent = checkOut || '-';
    if (summaryGuests) summaryGuests.textContent = guests;
    if (summaryRooms) summaryRooms.textContent = rooms;

    let price = params.get('price') ? params.get('price') : 0;
    if (!price && hotelSelect && roomTypeSelect && hotelSelect.value && roomTypeSelect.value) {
        const hotel = hotelSelect.value;
        const room = roomTypeSelect.value;
        if (hotelPrices[hotel] && hotelPrices[hotel][room]) {
            price = hotelPrices[hotel][room];
        } else {
            price = 0;
        }
    }
    const total = price * parseInt(rooms);
    if (summaryTotal) summaryTotal.textContent = price ? `${total} EGP` : '0 EGP';

    const totalPriceInput = document.getElementById('total_price');
    const hotelNameInput = document.getElementById('hotel_name');
    const roomNameInput = document.getElementById('room_name');
    if (totalPriceInput) totalPriceInput.value = total;
    if (hotelNameInput && summaryHotel) hotelNameInput.value = summaryHotel.textContent;
    if (roomNameInput && summaryRoomType) roomNameInput.value = summaryRoomType.textContent;
}

if (hotelSelect) hotelSelect.addEventListener('change', updateSummary);
if (roomTypeSelect) roomTypeSelect.addEventListener('change', updateSummary);
if (checkInInput) checkInInput.addEventListener('change', updateSummary);
if (checkOutInput) checkOutInput.addEventListener('change', updateSummary);
if (guestsInput) guestsInput.addEventListener('change', updateSummary);
if (roomsInput) roomsInput.addEventListener('change', updateSummary);

function fillHiddenIds() {
    const hotelIdInput = document.getElementById('hotel_id');
    const roomIdInput = document.getElementById('room_id');
    let hotelId = '';
    let roomId = '';

    let hotelName = '';
    let roomName = '';

    // جلب اسم الفندق واسم الغرفة من select أو ملخص أو URL
    const hotelSelect = document.getElementById('hotel');
    const roomSelect = document.getElementById('room-type');
    if (hotelSelect && hotelSelect.options[hotelSelect.selectedIndex]) {
        hotelName = hotelSelect.options[hotelSelect.selectedIndex].text;
        hotelId = hotelSelect.value;
    }
    if (roomSelect && roomSelect.options[roomSelect.selectedIndex]) {
        roomName = roomSelect.options[roomSelect.selectedIndex].text;
    }
    if (!hotelName) {
        const summaryHotel = document.querySelector('.hotel-name');
        if (summaryHotel && summaryHotel.textContent && summaryHotel.textContent !== '-') {
            hotelName = summaryHotel.textContent.trim();
        }
    }
    if (!roomName) {
        const summaryRoom = document.querySelector('.room-type');
        if (summaryRoom && summaryRoom.textContent && summaryRoom.textContent !== '-') {
            roomName = summaryRoom.textContent.trim();
        }
    }
    if (!hotelName || !roomName) {
        const params = new URLSearchParams(window.location.search);
        if (!hotelName && params.get('hotel')) hotelName = decodeURIComponent(params.get('hotel'));
        if (!roomName && params.get('room')) roomName = decodeURIComponent(params.get('room'));
    }

    // جلب رقم الفندق من hotelsData إذا لم يوجد
    if (!hotelId && hotelName && hotelsData[hotelName]) {
        hotelId = hotelsData[hotelName];
    }

    // جلب رقم الغرفة من roomsData
    if (hotelName && roomName) {
        const key = hotelName + '|' + roomName;
        if (roomsData[key]) {
            roomId = roomsData[key];
        }
    }

    if (hotelIdInput) hotelIdInput.value = hotelId;
    if (roomIdInput) roomIdInput.value = roomId;
}

fillHiddenIds();
if (document.getElementById('hotel')) document.getElementById('hotel').addEventListener('change', fillHiddenIds);
if (document.getElementById('room-type')) document.getElementById('room-type').addEventListener('change', fillHiddenIds);

function showToast(message, type = 'success') {
    const oldToast = document.getElementById('custom-toast');
    if (oldToast) oldToast.remove();

    const toast = document.createElement('div');
    toast.id = 'custom-toast';
    toast.className = `custom-toast ${type}`;

    const icon = document.createElement('span');
    icon.className = 'toast-icon';
    if (type === 'success') {
        icon.innerHTML = '<span style="font-size:22px;">&#10003;</span>';
    } else {
        icon.innerHTML = '<span style="font-size:22px;">&#9888;</span>';
    }

    toast.appendChild(icon);
    const msgSpan = document.createElement('span');
    msgSpan.textContent = message;
    toast.appendChild(msgSpan);

    toast.style.position = 'fixed';
    toast.style.top = '30px';
    toast.style.left = '50%';
    toast.style.transform = 'translateX(-50%)';
    toast.style.zIndex = '9999';
    toast.style.padding = '16px 32px';
    toast.style.borderRadius = '8px';
    toast.style.background = type === 'success' ? '#4BB543' : '#E74C3C';
    toast.style.color = '#fff';
    toast.style.fontSize = '18px';
    toast.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
    toast.style.opacity = '0.97';

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}

function showBookingSuccessCard(details) {
    const oldCard = document.getElementById('booking-success-card');
    if (oldCard) oldCard.remove();

    const card = document.createElement('div');
    card.id = 'booking-success-card';
    card.className = 'booking-success-card booking-summary';

    card.innerHTML = `
        <span class="close-success-card" title="Close" style="position:absolute;top:10px;right:18px;cursor:pointer;font-size:26px;color:#4BB543;font-weight:bold;line-height:1;z-index:2;">&times;</span>
        <h3 style="text-align:center;color:#4BB543;margin-bottom:1.5rem;">Booking Details</h3>
        <div class="summary-content">
            <div><i class="fa fa-user"></i><strong>Name:</strong> ${details.first_name} ${details.last_name}</div>
            <div><i class="fa fa-envelope"></i><strong>Email:</strong> ${details.email}</div>
            <div><i class="fa fa-phone"></i><strong>Phone:</strong> ${details.phone}</div>
            <div><i class="fa fa-hotel"></i><strong>Hotel:</strong> ${details.hotel_name}</div>
            <div><i class="fa fa-bed"></i><strong>Room:</strong> ${details.room_name}</div>
            <div><i class="fa fa-calendar-plus"></i><strong>Check-in:</strong> ${details.check_in}</div>
            <div><i class="fa fa-calendar-minus"></i><strong>Check-out:</strong> ${details.check_out}</div>
            <div><i class="fa fa-users"></i><strong>Guests:</strong> ${details.guests}</div>
            <div><i class="fa fa-door-closed"></i><strong>Rooms:</strong> ${details.rooms}</div>
            <div><i class="fa fa-money-bill-wave"></i><strong>Total Price:</strong> ${details.total_price} EGP</div>
        </div>
        <div class="booking-success-message">
            <i class="fa fa-check-circle"></i>
            Booking successful! You will receive a confirmation email shortly.
        </div>
    `;
    card.querySelector('.close-success-card').onclick = () => card.remove();

    document.body.appendChild(card);

    setTimeout(() => {
        if (card.parentNode) card.remove();
    }, 10000);
}

if (bookingForm) {
    bookingForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = bookingForm.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        if (bookingMessage) {
            bookingMessage.style.display = 'none';
            bookingMessage.textContent = '';
            bookingMessage.className = '';
        }

        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (checkIn < today) {
            showToast('Check-in date cannot be in the past', 'error');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }

        if (checkOut <= checkIn) {
            showToast('Check-out date must be after check-in date', 'error');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }

        if (parseInt(guestsInput.value) < 1) {
            showToast('Number of guests must be at least 1', 'error');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }

        if (parseInt(roomsInput.value) < 1) {
            showToast('Number of rooms must be at least 1', 'error');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }

        const cardNumber = document.getElementById('card-number').value.replace(/\s/g, '');
        if (!/^\d{16}$/.test(cardNumber)) {
            showToast('Card number must be exactly 16 digits', 'error');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }
        if (!isValidCardNumber(cardNumber)) {
            showToast('Please enter a valid card number', 'error');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }

        const expiry = document.getElementById('expiry').value;
        if (!isValidExpiryDate(expiry)) {
            showToast('Please enter a valid expiry date', 'error');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }

        const cvv = document.getElementById('cvv').value;
        if (!isValidCVV(cvv)) {
            showToast('Please enter a valid CVV', 'error');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }

        fillHiddenIds();
        const hotelIdInput = document.getElementById('hotel_id');
        const roomIdInput = document.getElementById('room_id');
        if (!hotelIdInput.value || !roomIdInput.value) {
            showToast('Hotel and room selection is required.', 'error');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }

        try {
            const formData = new FormData(this);
            const response = await fetch('book/book.php', {
                method: 'POST',
                body: formData
            });

            let data;
            try {
                data = await response.json();
            } catch (jsonError) {
                const responseText = await response.text();
                if (responseText.includes('You must be logged in to book')) {
                    window.location.href = 'login/login.html';
                    return;
                }
                throw jsonError;
            }
            
            if (data.status === 'success') {
                const details = {
                    first_name: document.getElementById('first-name').value,
                    last_name: document.getElementById('last-name').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    hotel_name: document.getElementById('hotel_name').value,
                    room_name: document.getElementById('room_name').value,
                    check_in: document.getElementById('check-in').value,
                    check_out: document.getElementById('check-out').value,
                    guests: document.getElementById('guests').value,
                    rooms: document.getElementById('rooms').value,
                    total_price: document.getElementById('total_price').value
                };
                showBookingSuccessCard(details);
                bookingForm.reset();
                updateSummary();
            } else {
                if (data.message && data.message === 'You must be logged in to book.') {
                    window.location.href = 'login/login.html';
                } else {
                    showToast(data.message || 'An error occurred during booking. Please try again.', 'error');
                }
            }
        } catch (error) {
            console.error('Booking error:', error);
            showToast('An error occurred while processing your booking. Please try again.', 'error');
        } finally {
            if (submitBtn) submitBtn.disabled = false;
        }
    });
}

function isValidCardNumber(cardNumber) {
    let sum = 0;
    let isEven = false;

    for (let i = cardNumber.length - 1; i >= 0; i--) {
        let digit = parseInt(cardNumber.charAt(i));

        if (isEven) {
            digit *= 2;
            if (digit > 9) {
                digit -= 9;
            }
        }

        sum += digit;
        isEven = !isEven;
    }

    return sum % 10 === 0;
}

function isValidExpiryDate(expiry) {
    const [month, year] = expiry.split('/');
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear() % 100;
    const currentMonth = currentDate.getMonth() + 1;

    if (!month || !year || isNaN(month) || isNaN(year)) {
        return false;
    }

    const expMonth = parseInt(month);
    const expYear = parseInt(year);

    if (expMonth < 1 || expMonth > 12) {
        return false;
    }

    if (expYear < currentYear || (expYear === currentYear && expMonth < currentMonth)) {
        return false;
    }

    return true;
}

function isValidCVV(cvv) {
    return /^\d{3,4}$/.test(cvv);
}

const cardNumberInput = document.getElementById('card-number');
if (cardNumberInput) {
    cardNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        e.target.value = formattedValue;
    });
}

const expiryInput = document.getElementById('expiry');
if (expiryInput) {
    expiryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        e.target.value = value;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const hotel = params.get('hotel');
    const room = params.get('room');
    const price = params.get('price');

    if (hotel && summaryHotel) {
        const decodedHotel = decodeURIComponent(hotel);
        summaryHotel.textContent = decodedHotel;
        const previewTitle = document.querySelector('.hotel-preview-title');
        if (previewTitle) {
            previewTitle.childNodes[0].textContent = decodedHotel + ' ';
        }
    }
    if (room && summaryRoomType) {
        summaryRoomType.textContent = decodeURIComponent(room);
    }
    if (price && summaryTotal) {
        summaryTotal.textContent = price + ' EGP';
    }

    fillHiddenIds();
});

async function getHotelDetails(hotelId) {
    try {
        const response = await fetch(`pages/api/hotels_api.php?id=${hotelId}`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const hotels = await response.json();
        return hotels.find(hotel => hotel.id === parseInt(hotelId));
    } catch (error) {
        console.error('Error fetching hotel details:', error);
        return null;
    }
}

async function initializeBooking() {
    const params = new URLSearchParams(window.location.search);
    const hotelId = params.get('id');
    
    const bookingContainer = document.getElementById('booking-container');
    if (!hotelId) {
        if (bookingContainer) {
            bookingContainer.innerHTML = '<div class="alert alert-danger">No hotel selected. Please select a hotel first.</div>';
        }
        return;
    }

    const hotel = await getHotelDetails(hotelId);
    if (!hotel) {
        if (bookingContainer) {
            bookingContainer.innerHTML = '<div class="alert alert-danger">Hotel not found. Please select a valid hotel.</div>';
        }
        return;
    }

    // عرض تفاصيل الفندق
    if (document.getElementById('hotel-name')) document.getElementById('hotel-name').textContent = hotel.name;
    if (document.getElementById('hotel-location')) document.getElementById('hotel-location').textContent = hotel.city;
    if (document.getElementById('hotel-price')) document.getElementById('hotel-price').textContent = `$${hotel.price} per night`;

    // تحديث السعر الإجمالي عند تغيير التواريخ
    const checkInInput = document.getElementById('check-in');
    const checkOutInput = document.getElementById('check-out');
    const totalPriceElement = document.getElementById('total-price');

    function updateTotalPrice() {
        if (checkInInput && checkOutInput && totalPriceElement && checkInInput.value && checkOutInput.value) {
            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);
            const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            if (nights > 0) {
                const totalPrice = nights * hotel.price;
                totalPriceElement.textContent = `$${totalPrice}`;
            }
        }
    }

    if (checkInInput) checkInInput.addEventListener('change', updateTotalPrice);
    if (checkOutInput) checkOutInput.addEventListener('change', updateTotalPrice);
}

// تهيئة صفحة الحجز عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', initializeBooking);
