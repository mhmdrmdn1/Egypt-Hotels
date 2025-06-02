# Egypt Hotels Booking Website

A modern, responsive, and feature-rich hotel booking platform for Egypt, built with cutting-edge web technologies.

## 🌟 Key Features

- **Homepage**: Elegant design featuring a dynamic hero section, curated hotel selections, and exclusive deals
- **Explore Page**: Advanced search functionality with comprehensive filters and interactive map integration
- **Gallery Page**: High-resolution photo gallery showcasing premium hotels and tourist destinations
- **About Page**: Detailed company information and professional team profiles
- **Booking Page**: Streamlined booking system with real-time room availability and instant confirmation
- **Contact Page**: Interactive contact form, office location mapping, and comprehensive FAQ section

## 📁 Project Structure

```
Booking-Hotel-Project/
├── assets/
│   ├── css/
│   │   ├── styles.css (core styles)
│   │   ├── explore.css
│   │   ├── gallery.css
│   │   ├── about.css
│   │   ├── book.css
│   │   └── contact.css
│   ├── js/
│   │   ├── script.js (core functionality)
│   │   ├── explore.js
│   │   ├── gallery.js
│   │   ├── about.js
│   │   ├── book.js
│   │   └── contact.js
│   ├── images/
│   │   └── (optimized website assets)
│   └── user_uploads/
│       └── (user uploaded content)
├── pages/
│   ├── book/
│   │   └── (booking related files)
│   ├── profile/
│   │   └── (user profile files)
│   ├── login/
│   │   └── (authentication files)
│   ├── admin_dashboard/
│   │   └── (admin panel files)
│   ├── api/
│   │   └── (API endpoints)
│   ├── reviews/
│   │   └── (review system files)
│   ├── index.php
│   ├── explore.php
│   ├── gallery.php
│   ├── about.php
│   ├── contact.php
│   ├── book.php
│   ├── profile.php
│   ├── hotel-details.php
│   ├── hotel-full-details.php
│   ├── room-details.php
│   ├── navbar_sidebar.php
│   ├── process_contact.php
│   ├── admin_login.php
│   └── (database management files)
├── config/
│   └── (configuration files)
├── classes/
│   └── (PHP classes)
├── scripts/
│   └── (utility scripts)
├── images/
│   └── (project images)
├── profile/
│   └── (user profile assets)
├── index.php
├── welcome.php
├── upload_user_image.php
├── import_hotels.php
├── download_images.ps1
├── download_images.bat
├── composer.json
├── README.md
└── LICENSE
```

## 🛠️ Technologies Used

- **Frontend Framework**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP
- **Database**: MySQL with phpMyAdmin
- **Server**: XAMPP (Apache, MySQL, PHP)
- **Animation**: [AOS.js](https://michalsnik.github.io/aos/) - Smooth scroll animations
- **UI Components**: [Swiper.js](https://swiperjs.com/) - Modern touch slider
- **Maps Integration**: [Google Maps API](https://developers.google.com/maps) - Interactive location services
- **Performance**: Optimized assets and lazy loading
- **Security**: HTTPS and secure data handling

## 🚀 Getting Started

1. Clone the repository:
```bash
git clone https://github.com/mhmdrmdn1/Booking-Hotel-Project.git
```

2. Navigate to the project directory:
```bash
cd Booking-Hotel-Project
```

3. Install dependencies:
```bash
composer install
```

4. Configure your database:
   - Start XAMPP Control Panel
   - Start Apache and MySQL services
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database
   - Import the database schema
   - Update database credentials in config files

5. Configure Google Maps API:
   - Obtain an API key from [Google Cloud Console](https://console.cloud.google.com)
   - Update the API key in `contact.php`

6. Launch the project:
   - Place the project in your XAMPP htdocs directory
   - Access the project through: http://localhost/Booking-Hotel-Project

## 🌐 Browser Support

The website is optimized for modern browsers:
- Google Chrome (latest)
- Mozilla Firefox (latest)
- Safari (latest)
- Microsoft Edge (latest)

## 📱 Responsive Design

Fully responsive layout optimized for:
- Desktop (≥1024px)
- Tablet (768px - 1023px)
- Mobile (<768px)

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under our custom license agreement - see the [LICENSE](LICENSE) file for details.

## 📞 Contact

For support and inquiries:
- Email: egypthotels25@gmail.com
- Website: www.egypt-hotels.com
- Project Link: https://github.com/mhmdrmdn1/Booking-Hotel-Project 