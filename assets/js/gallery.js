AOS.init({
    duration: 1000,
    once: true
});

function openLightbox(img, title, desc) {
    const lightbox = document.querySelector('.lightbox-modal');
    const lightboxImage = document.querySelector('.lightbox-image');
    const lightboxCaption = document.querySelector('.lightbox-caption');
    lightboxImage.src = img.src;
    lightboxImage.alt = title;
    lightboxCaption.innerHTML = `<h3>${title}</h3><p>${desc}</p>`;
    lightbox.classList.add('active');
}

function closeLightbox() {
    const lightbox = document.querySelector('.lightbox-modal');
    lightbox.classList.remove('active');
}

document.addEventListener('DOMContentLoaded', function() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    galleryItems.forEach(item => {
        item.addEventListener('click', function() {
            const img = this.querySelector('img');
            const title = this.querySelector('.overlay h3').textContent;
            const desc = this.querySelector('.overlay p').textContent;
            openLightbox(img, title, desc);
        });
    });

    const lightbox = document.querySelector('.lightbox-modal');
    const closeBtn = document.querySelector('.close-lightbox');
    
    closeBtn.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            closeLightbox();
        }
    });
});

const gallerySwiper = new Swiper('.gallery-slider', {
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
        }
    }
});

document.querySelectorAll('.category-card').forEach(card => {
    card.addEventListener('click', function() {
        const category = this.querySelector('h3').textContent;
        // Here you can add logic to filter gallery items by category
        console.log(`Filtering by category: ${category}`);
    });
});

document.querySelectorAll('.grid-item').forEach(item => {
    item.addEventListener('click', function() {
        const title = this.querySelector('h3').textContent;
        console.log(`Showing details for: ${title}`);
    });
});

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
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

const galleryHeader = document.getElementById('header');

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll <= 0) {
        galleryHeader.classList.remove('scroll-up');
        return;
    }
    
    if (currentScroll > lastScroll && !galleryHeader.classList.contains('scroll-down')) {
        galleryHeader.classList.remove('scroll-up');
        galleryHeader.classList.add('scroll-down');
    } else if (currentScroll < lastScroll && galleryHeader.classList.contains('scroll-down')) {
        galleryHeader.classList.remove('scroll-down');
        galleryHeader.classList.add('scroll-up');
    }
}); 