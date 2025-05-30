document.addEventListener('DOMContentLoaded', function() {
    const headlineSpans = document.querySelectorAll('.hero-headline span');
    headlineSpans.forEach(span => {
        span.style.opacity = 0;
        if (span.classList.contains('bold')) {
            span.style.transform = 'translateX(-100px)';
        } else if (span.classList.contains('italic')) {
            span.style.transform = 'translateX(100px)';
        }
    });
    headlineSpans.forEach((span, i) => {
        setTimeout(() => {
            span.style.transition = 'all 1s cubic-bezier(.4,0,.2,1)';
            span.style.opacity = 1;
            span.style.transform = 'translateX(0)';
        }, 200 + i * 180);
    });

    const anytime = document.querySelector('.hero-headline .italic');
    if (anytime) {
        anytime.style.opacity = 0;
        anytime.style.transform = 'translateX(60px)';
        setTimeout(() => {
            anytime.style.transition = 'all 1s cubic-bezier(.4,0,.2,1)';
            anytime.style.opacity = 1;
            anytime.style.transform = 'translateX(0)';
        }, 500);
    }

    const cta = document.querySelector('.cta-btn');
    if (cta) {
        cta.style.opacity = 0;
        cta.style.transform = 'scale(0.95)';
        setTimeout(() => {
            cta.style.transition = 'all 0.8s cubic-bezier(.4,0,.2,1)';
            cta.style.opacity = 1;
            cta.style.transform = 'scale(1)';
        }, 700);
    }

    const heroImg = document.querySelector('.hero-3d-img');
    if (heroImg) {
        heroImg.style.opacity = 0;
        heroImg.style.transform = 'translateY(30vh) scale(0.98)';
        heroImg.style.animation = 'none';
        setTimeout(() => {
            heroImg.style.transition = 'all 1s cubic-bezier(.4,0,.2,1)';
            heroImg.style.opacity = 1;
            heroImg.style.transform = 'translateY(0) scale(1)';
            setTimeout(() => {
                heroImg.style.transition = '';
                heroImg.style.animation = 'hero-float 1s ease-in-out infinite alternate';
            }, 1300);
        }, 400);
    }

    const features = document.querySelectorAll('.feature-card');
    features.forEach((card, i) => {
        card.style.opacity = 0;
        card.style.transform = 'translateY(40px)';
        setTimeout(() => {
            card.style.transition = 'all 0.7s cubic-bezier(.4,0,.2,1)';
            card.style.opacity = 1;
            card.style.transform = 'translateY(0)';
        }, 900 + i * 180);
    });

    const aboutSection = document.querySelector('.about-section');
    const aboutIcons = document.querySelectorAll('.about-icon');
    const featuresSection = document.querySelector('.features-section');
    const featureCards = document.querySelectorAll('.feature-card');

    if (aboutSection) aboutSection.classList.add('fade-in');
    if (featuresSection) featuresSection.classList.add('fade-in');
    revealOnScroll([aboutSection], 'visible');
    revealOnScroll(Array.from(aboutIcons), 'visible');
    revealOnScroll(Array.from(featureCards), 'visible');
});

const openSidebarBtn = document.getElementById('openSidebarBtn');
const closeSidebarBtn = document.getElementById('closeSidebarBtn');
const sidebarMenu = document.getElementById('sidebarMenu');
const sidebarOverlay = document.getElementById('sidebarOverlay');

if (openSidebarBtn && closeSidebarBtn && sidebarMenu && sidebarOverlay) {
    openSidebarBtn.addEventListener('click', function() {
        sidebarMenu.classList.add('active');
        sidebarOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
    closeSidebarBtn.addEventListener('click', function() {
        sidebarMenu.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    });
    sidebarOverlay.addEventListener('click', function() {
        sidebarMenu.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    });
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            sidebarMenu.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
}

function revealOnScroll(elements, className = 'visible') {
    if (!('IntersectionObserver' in window)) {
        elements.forEach(el => el.classList.add(className));
        return;
    }
    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add(className);
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });
    elements.forEach(el => observer.observe(el));
} 

const welcomeHeader = document.getElementById('header');
if (welcomeHeader) {
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
        if (currentScroll <= 0) {
            welcomeHeader.classList.remove('scroll-up');
            return;
        }

        if (currentScroll > lastScroll && !welcomeHeader.classList.contains('scroll-down')) {
            // Scroll down
            welcomeHeader.classList.remove('scroll-up');
            welcomeHeader.classList.add('scroll-down');
        } else if (currentScroll < lastScroll && welcomeHeader.classList.contains('scroll-down')) {
            // Scroll up
            welcomeHeader.classList.remove('scroll-down');
            welcomeHeader.classList.add('scroll-up');
    }
    
    lastScroll = currentScroll;
});
}