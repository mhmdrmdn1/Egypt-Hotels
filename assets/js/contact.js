AOS.init({
    duration: 1000,
    once: true
});

const contactForm = document.getElementById('contactForm');
if (contactForm) {
    const formGroups = contactForm.querySelectorAll('.form-group');
    const submitBtn = contactForm.querySelector('.submit-btn');
    const loadingSpinner = submitBtn.querySelector('.loading-spinner');

    formGroups.forEach(group => {
        const input = group.querySelector('input, textarea');
        const errorMessage = group.querySelector('.error-message');

        input.addEventListener('input', () => {
            validateField(input, group, errorMessage);
        });

        input.addEventListener('blur', () => {
            validateField(input, group, errorMessage);
        });
    });

    function validateField(input, group, errorMessage) {
        const value = input.value.trim();
        
        if (input.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!value || !emailRegex.test(value)) {
                group.classList.add('error');
                errorMessage.textContent = 'Please enter a valid email address';
                return false;
            }
        } else {
            if (!value) {
                group.classList.add('error');
                errorMessage.textContent = `Please enter your ${input.name}`;
                return false;
            }
        }

        group.classList.remove('error');
        return true;
    }

    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        let isValid = true;
        const formData = {};
        
        formGroups.forEach(group => {
            const input = group.querySelector('input, textarea');
            const errorMessage = group.querySelector('.error-message');
            if (!validateField(input, group, errorMessage)) {
                isValid = false;
            }
            formData[input.name] = input.value.trim();
        });

        if (!isValid) {
            return;
        }

        submitBtn.disabled = true;
        loadingSpinner.style.display = 'inline-block';
        submitBtn.textContent = 'Sending...';

        try {
            const response = await fetch('../pages/process_contact.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (data.success) {
                const successMessage = document.createElement('div');
                successMessage.className = 'success-message animate__animated animate__fadeIn';
                successMessage.textContent = data.message;
                successMessage.style.cssText = `
                    background: #28a745;
                    color: white;
                    padding: 15px;
                    border-radius: 5px;
                    margin-top: 20px;
                    text-align: center;
                `;
                
                contactForm.appendChild(successMessage);
                contactForm.reset();
                
                setTimeout(() => {
                    successMessage.classList.add('animate__fadeOut');
                    setTimeout(() => successMessage.remove(), 500);
                }, 3000);
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            const errorMessage = document.createElement('div');
            errorMessage.className = 'error-message animate__animated animate__fadeIn';
            errorMessage.textContent = error.message || 'Sorry, there was an error sending your message. Please try again.';
            errorMessage.style.cssText = `
                background: #dc3545;
                color: white;
                padding: 15px;
                border-radius: 5px;
                margin-top: 20px;
                text-align: center;
            `;
            
            contactForm.appendChild(errorMessage);
            
            setTimeout(() => {
                errorMessage.classList.add('animate__fadeOut');
                setTimeout(() => errorMessage.remove(), 500);
            }, 3000);
        } finally {
            submitBtn.disabled = false;
            loadingSpinner.style.display = 'none';
            submitBtn.textContent = 'Send Message';
        }
    });
}

const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
const navLinks = document.querySelector('.nav-links');

if (mobileMenuBtn && navLinks) {
    mobileMenuBtn.addEventListener('click', () => {
        navLinks.classList.toggle('active');
        mobileMenuBtn.classList.toggle('active');
        mobileMenuBtn.setAttribute('aria-expanded', 
            mobileMenuBtn.getAttribute('aria-expanded') === 'true' ? 'false' : 'true'
        );
    });
}

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 70,
                behavior: 'smooth'
            });
            
            if (navLinks.classList.contains('active')) {
                navLinks.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
                mobileMenuBtn.setAttribute('aria-expanded', 'false');
            }
        }
    });
});

const socialLinks = document.querySelectorAll('.social-links a');
socialLinks.forEach(link => {
    link.addEventListener('mouseenter', () => {
        link.style.transform = 'translateY(-5px)';
    });
    
    link.addEventListener('mouseleave', () => {
        link.style.transform = 'translateY(0)';
    });
});

const formInputs = document.querySelectorAll('.form-group input, .form-group textarea');
formInputs.forEach(input => {
    input.addEventListener('focus', () => {
        input.parentElement.style.transform = 'scale(1.02)';
    });
    
    input.addEventListener('blur', () => {
        input.parentElement.style.transform = 'scale(1)';
    });
});