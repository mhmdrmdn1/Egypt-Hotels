AOS.init({
    duration: 800,
    easing: 'ease-in-out'
});

const hotelsSlider = document.querySelector('.hotels-slider');
if (hotelsSlider) {
const swiper = new Swiper('.hotels-slider', {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        640: {
            slidesPerView: 2,
        },
        1024: {
            slidesPerView: 3,
        },
    }
});
}

var header = document.getElementById('header');
if (header) {
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
        if (currentScroll <= 0) {
            header.classList.remove('scroll-up');
            return;
        }

        if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
            header.classList.remove('scroll-up');
            header.classList.add('scroll-down');
        } else if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
            header.classList.remove('scroll-down');
            header.classList.add('scroll-up');
    }
    
    lastScroll = currentScroll;
});
}

const searchForm = document.getElementById('hotel-search');
if (searchForm) {
searchForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const location = document.getElementById('location').value;
    const checkIn = document.getElementById('check-in').value;
    const checkOut = document.getElementById('check-out').value;
    const guests = document.getElementById('guests').value;
    
    if (!location || !checkIn || !checkOut || !guests) {
        alert('Please fill in all required fields');
        return;
    }
    
    if (new Date(checkOut) <= new Date(checkIn)) {
        alert('Check-out date must be after check-in date');
        return;
    }
    
    console.log('Search submitted:', {
        location,
        checkIn,
        checkOut,
        guests
    });
    
    alert('Search request submitted successfully!');
});

const today = new Date().toISOString().split('T')[0];
document.getElementById('check-in').min = today;
document.getElementById('check-out').min = today;

document.getElementById('check-in').addEventListener('change', function() {
    document.getElementById('check-out').min = this.value;
});
}

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

document.querySelectorAll('.book-now').forEach(button => {
    button.addEventListener('click', function() {
        const hotelName = this.closest('.hotel-info').querySelector('h3').textContent;
        alert(`You will be redirected to book ${hotelName}`);
    });
});

const newsletterForm = document.querySelector('.newsletter-form');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        
        alert('Thank you for subscribing to our newsletter!');
        this.reset();
    });
}

document.querySelectorAll('.explore-btn').forEach(button => {
    button.addEventListener('click', function() {
        const destinationCard = this.closest('.destination-card');
        if (destinationCard) {
            const destination = destinationCard.querySelector('h3').textContent;
        console.log(`Exploring hotels in ${destination}`);
        alert(`Discovering hotels in ${destination}`);
        }
    });
});

// Special Offers Button Click Handler
document.querySelectorAll('.book-offer').forEach(button => {
    button.addEventListener('click', function() {
        const offerTitle = this.closest('.offer-content').querySelector('h3').textContent;
        console.log(`Booking special offer: ${offerTitle}`);
        alert(`Processing your booking for ${offerTitle}`);
    });
}); 

const swiperElement = document.querySelector('.swiper');
if (swiperElement) {
    new Swiper('.swiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const lazyImages = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));
});

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^\+?[\d\s-]{10,}$/;
    return re.test(phone);
}

const navigationLinks = document.querySelectorAll('.nav-links a');
if (navigationLinks.length > 0) {
    navigationLinks.forEach(link => {
        if (link.getAttribute('href') === window.location.pathname.split('/').pop()) {
            link.classList.add('active');
        }
    });
}

const hotelSearchForm = document.getElementById('hotel-search');
if (hotelSearchForm) {
    hotelSearchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const location = document.getElementById('location').value;
        const checkIn = document.getElementById('check-in').value;
        const checkOut = document.getElementById('check-out').value;
        const guests = document.getElementById('guests').value;

        const params = new URLSearchParams(window.location.search);
        if (location) params.set('location', location);
        if (checkIn) params.set('checkIn', checkIn);
        if (checkOut) params.set('checkOut', checkOut);
        if (guests) params.set('guests', guests);
        window.history.replaceState({}, '', `${window.location.pathname}?${params}`);

        const searchEvent = new CustomEvent('searchUpdated', {
            detail: { location, checkIn, checkOut, guests }
        });
        document.dispatchEvent(searchEvent);

        if (typeof window.applyAllFilters === 'function') {
            window.applyAllFilters();
        } else if (typeof window.filterAndSortHotels === 'function') {
            window.filterAndSortHotels();
        }
    });
}

const menuDropdownBtn = document.getElementById('menuDropdownBtn');
const menuDropdownMenu = document.getElementById('menuDropdownMenu');
if(menuDropdownBtn && menuDropdownMenu) {
    menuDropdownBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        menuDropdownMenu.classList.toggle('show');
    });
    document.addEventListener('click', function(e) {
        if (!menuDropdownBtn.contains(e.target)) {
            menuDropdownMenu.classList.remove('show');
        }
    });
}

function updateSidebarAuthLinks() {
    const loginLink = document.querySelector('.sidebar-links a[href="login/login.html"]');
    const logoutLink = document.querySelector('.sidebar-links a[href="logout.php"]');
    if (!loginLink || !logoutLink) return;
    if (localStorage.getItem('isLoggedIn') === 'true') {
        loginLink.style.display = 'none';
        logoutLink.style.display = 'flex';
    } else {
        loginLink.style.display = 'flex';
        logoutLink.style.display = 'none';
    }
    logoutLink.addEventListener('click', function() {
        localStorage.removeItem('isLoggedIn');
        setTimeout(updateSidebarAuthLinks, 100);
    });
}
document.addEventListener('DOMContentLoaded', updateSidebarAuthLinks);