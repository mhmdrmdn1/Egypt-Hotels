<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Egypt Hotels</title>
    <link rel="shortcut icon" href="../assets/images/icons/web-icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="../assets/css/navbar_sidebar.css">
    <link rel="stylesheet" href="../assets/css/contact.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/chatbot.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include 'navbar_sidebar.php'; ?>
    <!-- Header Section -->
    <section class="contact-header" style="background: url(../assets/images/icons/contact-hero.jpg) no-repeat center center; background-size: cover;">
        <div class="header-content">
            <div class="header-text">
                <h1>Contact Us</h1>
                <p>Reach out to us for any inquiries, support, or feedback.<br> Our team is always ready to assist you and ensure your experience is exceptional.</p>
            </div>
            
        </div>
    </header>

    <!-- Contact Info Blocks -->
    <div class="header-image">
                <img src="../assets/images/icons/contact-header.jpg" alt="Contact Us" />
            </div>
    <section class="contact-info-blocks section-animate">
        
        <div class="info-block">
            
            <i class="fas fa-phone"></i>
            <div>
                <h3>+20 106 978 7819</h3>
                <span>Call us for reservations or support</span>
            </div>
        </div>
        <div class="info-block">
            <i class="fas fa-envelope"></i>
            <div>
                <h3>egypthotels25@gmail.com</h3>
                <span>Email us for any questions</span>
            </div>
        </div>
        <div class="info-block">
            <i class="fas fa-map-marker-alt"></i>
            <div>
                <h3>Cairo, Egypt</h3>
                <span>Find us at our main office</span>
            </div>
        </div>
    </section>

    <!-- Main Two-Column Section -->
    <section class="contact-main">
        <div class="main-container">
            <div class="main-left">
                <div class="form-card animate-from-left">
                    <h2>Get In Touch!</h2>
                    <p>Fill out the form and our team will get back to you as soon as possible.</p>
                    <form class="contact-form" method="post" action="process_contact.php">
                        <input type="email" name="email" placeholder="Email" required />
                        <input type="text" name="name" placeholder="Name" required />
                        <textarea name="message" placeholder="Message" required></textarea>
                        <button type="submit">Submit</button>
                    </form>
                </div>
            </div>
            <div class="main-right">
                <div class="location-card animate-from-right">
                    <h2>Our Location</h2>
                    <p>Visit our main office in Cairo or contact us for directions. We look forward to welcoming you!</p>
                    <div class="social-media">
                        <a href="https://www.facebook.com/profile.php?id=61576084713550" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/egypt_hotels25" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/egypt_hotels25/" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/in/egypt-hotels-404222365/" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://wa.me/201069787819" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="location-card animate-from-bottom">
                    <h2>Working Hours</h2>
                    <p>
                        Our team is available:<br>
                        <strong>Every day from 9:00 AM to 10:00 PM</strong><br>
                        For urgent inquiries, contact us by phone or WhatsApp.<br>
                        <span style="color:#253d58;"><i class="fas fa-phone"></i> +20 106 978 7819</span>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="location-map">
        <div class="map-embed">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3456.789012345678!2d31.2357!3d30.0444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14583fa60b21beeb%3A0x79dfb296e8423bba!2sCairo%2C+Cairo+Governorate%2C+Egypt!5e0!3m2!1sen!2sus!4v1549138819013" width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="../pages/images/Logo-footer.png" alt="Egypt Hotels Logo" class="footer-logo-img">
                    </div>
                    <p>Your trusted partner for memorable stays in Egypt. Discover luxury, comfort, and authentic hospitality.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/profile.php?id=61576084713550" aria-label="Follow us on Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="https://x.com/egypt_hotels25" aria-label="Follow us on Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/egypt_hotels25/" aria-label="Follow us on Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/in/egypt-hotels-404222365/" aria-label="Follow us on LinkedIn"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="explore.php">Explore Hotels</a></li>
                        <li><a href="gallery.php">Photo Gallery</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt"></i> Banisuef</li>
                        <li><i class="fas fa-phone"></i> +20 1069787819</li>
                        <li><i class="fas fa-envelope"></i> egypthotels25@gmail.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Egypt Hotels. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/contact.js"></script>
<script src="../assets/js/chatbot.js"></script>
<script>
    </script>
<script>
        const openSidebarBtn = document.getElementById('openSidebarBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');
        const sidebarMenu = document.getElementById('sidebarMenu');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebarMenu.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebarMenu.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        openSidebarBtn.addEventListener('click', openSidebar);
        closeSidebarBtn.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSidebar();
        });
    </script>
    <script>
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
</script>
<script>
document.querySelectorAll('.section-animate').forEach(section => {
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if(entry.isIntersecting) {
        entry.target.style.opacity = 1;
        entry.target.style.animationPlayState = 'running';
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.2 });
  observer.observe(section);
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const contactForm = document.querySelector('.contact-form');
  if (contactForm) {
    // إضافة عنصر لرسالة النجاح
    let successMsg = document.createElement('div');
    successMsg.className = 'success-message';
    successMsg.style.cssText = 'display:none;background:#e6f9ed;color:#1a7f4e;padding:14px 18px;border-radius:8px;margin:12px 0 0 0;text-align:center;font-weight:600;font-size:1.08rem;box-shadow:0 2px 8px rgba(34,169,100,0.08);';
    contactForm.parentNode.insertBefore(successMsg, contactForm.nextSibling);

    contactForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      const form = e.target;
      const name = form.name.value.trim();
      const email = form.email.value.trim();
      const message = form.message.value.trim();
      const btn = form.querySelector('button[type="submit"]');
      btn.disabled = true;
      btn.textContent = 'Sending...';
      try {
        const res = await fetch('process_contact.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name, email, message })
        });
        const data = await res.json();
        if (data.success) {
          successMsg.textContent = 'Your message has been sent successfully!';
          successMsg.style.display = 'block';
          form.reset();
          setTimeout(() => { successMsg.style.display = 'none'; }, 3000);
        } else {
          alert(data.message || 'Error sending message.');
        }
      } catch (err) {
        alert('Error sending message.');
      }
      btn.disabled = false;
      btn.textContent = 'Submit';
    });
  }
});
</script>
</html> 