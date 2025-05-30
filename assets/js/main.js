const debounce = (func, wait) => {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

const throttle = (func, limit) => {
    let inThrottle;
    return function executedFunction(...args) {
        if (!inThrottle) {
            func(...args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
};

const lazyLoadImages = () => {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                observer.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img.lazy').forEach(img => {
        imageObserver.observe(img);
    });
};

const initMobileNav = () => {
    const menuButton = document.querySelector('.menu-button');
    const navMenu = document.querySelector('.nav-menu');
    const body = document.body;

    if (!menuButton || !navMenu) return;

    const toggleMenu = () => {
        navMenu.classList.toggle('active');
        menuButton.classList.toggle('active');
        body.classList.toggle('overflow-hidden');
    };

    const closeMenu = (e) => {
        if (!navMenu.contains(e.target) && !menuButton.contains(e.target)) {
            navMenu.classList.remove('active');
            menuButton.classList.remove('active');
            body.classList.remove('overflow-hidden');
        }
    };

    menuButton.addEventListener('click', toggleMenu);
    document.addEventListener('click', closeMenu);
};

const initFormValidation = () => {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        const validateField = (field) => {
            let isValid = true;
            let errorMessage = '';
            
            if (field.required && !field.value.trim()) {
                isValid = false;
                errorMessage = 'This field is required';
            } else if (field.type === 'email' && !isValidEmail(field.value)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address';
            } else if (field.type === 'tel' && !isValidPhone(field.value)) {
                isValid = false;
                errorMessage = 'Please enter a valid phone number';
            }
            
            updateFieldValidation(field, isValid, errorMessage);
            return isValid;
        };
        
        const updateFieldValidation = (field, isValid, message) => {
            const fieldContainer = field.closest('.form-group');
            const errorElement = fieldContainer.querySelector('.error-message');
            
            if (isValid) {
                field.classList.remove('error');
                field.classList.add('valid');
                if (errorElement) errorElement.remove();
            } else {
                field.classList.remove('valid');
                field.classList.add('error');
                if (!errorElement) {
                    const error = document.createElement('div');
                    error.className = 'error-message';
                    error.textContent = message;
                    fieldContainer.appendChild(error);
                } else {
                    errorElement.textContent = message;
                }
            }
        };
        
        const isValidEmail = (email) => {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        };
        
        const isValidPhone = (phone) => {
            return /^\+?[\d\s-]{10,}$/.test(phone);
        };
        
        inputs.forEach(input => {
            input.addEventListener('blur', () => validateField(input));
            input.addEventListener('input', () => validateField(input));
        });
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            let isValid = true;
            inputs.forEach(input => {
                if (!validateField(input)) isValid = false;
            });
            
            if (isValid) {
                await handleFormSubmission(form);
            }
        });
    });
};

const handleFormSubmission = async (form) => {
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    
    try {
        submitButton.disabled = true;
        submitButton.textContent = 'Submitting...';
        
        const formData = new FormData(form);
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            showNotification('Form submitted successfully!', 'success');
            form.reset();
        } else {
            throw new Error('Form submission failed');
        }
    } catch (error) {
        showNotification('Error submitting form. Please try again.', 'error');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    }
};

const initSmoothScroll = () => {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
};

const initScrollAnimations = () => {
    const animateOnScroll = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                animateOnScroll.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });
    
    document.querySelectorAll('.animate-on-scroll').forEach(element => {
        animateOnScroll.observe(element);
    });
};

const initImageGallery = () => {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const modal = document.createElement('div');
    modal.className = 'modal';
    
    const modalContent = document.createElement('div');
    modalContent.className = 'modal-content';
    
    const closeButton = document.createElement('span');
    closeButton.className = 'close-button';
    closeButton.innerHTML = '&times;';
    
    modal.appendChild(modalContent);
    modalContent.appendChild(closeButton);
    document.body.appendChild(modal);
    
    galleryItems.forEach(item => {
        item.addEventListener('click', () => {
            const img = item.querySelector('img');
            modalContent.style.backgroundImage = `url(${img.src})`;
            modal.classList.add('active');
            document.body.classList.add('overflow-hidden');
        });
    });
    
    const closeModal = () => {
        modal.classList.remove('active');
        document.body.classList.remove('overflow-hidden');
    };
    
    closeButton.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
};

document.addEventListener('DOMContentLoaded', () => {
    lazyLoadImages();
    initMobileNav();
    initFormValidation();
    initSmoothScroll();
    initScrollAnimations();
    initImageGallery();
    
    setTimeout(() => {
    }, 1000);
});

// Export for testing
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        debounce,
        throttle,
        lazyLoadImages,
        initMobileNav,
        initFormValidation,
        initSmoothScroll,
        initScrollAnimations,
        initImageGallery
    };
}

const animateOnScroll = () => {
    const elements = document.querySelectorAll('.fade-in, .slide-up');
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementBottom = element.getBoundingClientRect().bottom;
        
        if (elementTop < window.innerHeight && elementBottom > 0) {
            element.classList.add('visible');
        }
    });
};

window.addEventListener('scroll', animateOnScroll);
window.addEventListener('load', animateOnScroll);

const mainHeader = document.querySelector('.header');
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll <= 0) {
        mainHeader.classList.remove('scroll-up');
        return;
    }
    
    if (currentScroll > lastScroll && !mainHeader.classList.contains('scroll-down')) {
        mainHeader.classList.remove('scroll-up');
        mainHeader.classList.add('scroll-down');
    } else if (currentScroll < lastScroll && mainHeader.classList.contains('scroll-down')) {
        mainHeader.classList.remove('scroll-down');
        mainHeader.classList.add('scroll-up');
    }
    
    lastScroll = currentScroll;
});

const handleResponsiveImages = () => {
    const images = document.querySelectorAll('img[data-srcset]');
    
    images.forEach(img => {
        const srcset = img.dataset.srcset;
        if (srcset) {
            const sources = srcset.split(',');
            const windowWidth = window.innerWidth;
            
            let selectedSrc = '';
            for (const source of sources) {
                const [url, width] = source.trim().split(' ');
                const minWidth = parseInt(width);
                
                if (windowWidth >= minWidth) {
                    selectedSrc = url;
                }
            }
            
            if (selectedSrc) {
                img.src = selectedSrc;
            }
        }
    });
};

window.addEventListener('resize', handleResponsiveImages);
window.addEventListener('load', handleResponsiveImages); 