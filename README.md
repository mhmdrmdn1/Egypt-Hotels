# Egypt Hotels - Hotel Booking Website

A modern and responsive hotel booking website for Egypt, built with HTML5, CSS3, and JavaScript.

## Features

- **Homepage**: Modern design with hero section, featured hotels, and special offers
- **Explore Page**: Advanced search filters and hotel listings with map integration
- **Gallery Page**: Photo gallery showcasing hotels and destinations
- **About Page**: Company information and team details
- **Book Page**: Comprehensive booking system with room selection
- **Contact Page**: Contact form, office location map, and FAQ section

## Project Structure

```
Hotel/
├── assets/
│   ├── css/
│   │   ├── styles.css (shared styles)
│   │   ├── explore.css
│   │   ├── gallery.css
│   │   ├── about.css
│   │   ├── book.css
│   │   └── contact.css
│   ├── js/
│   │   ├── script.js (shared functionality)
│   │   ├── explore.js
│   │   ├── gallery.js
│   │   ├── about.js
│   │   ├── book.js
│   │   └── contact.js
│   └── images/
│       └── (website images)
└── pages/
    ├── index.html
    ├── explore.html
    ├── gallery.html
    ├── about.html
    ├── book.html
    └── contact.html
```

## Technologies Used

- HTML5
- CSS3
- JavaScript (ES6+)
- [AOS.js](https://michalsnik.github.io/aos/) - Animate On Scroll Library
- [Swiper.js](https://swiperjs.com/) - Modern Mobile Touch Slider
- [Google Maps API](https://developers.google.com/maps) - Maps Integration

## Setup

1. Clone the repository:
```bash
git clone https://github.com/yourusername/egypt-hotels.git
```

2. Navigate to the project directory:
```bash
cd egypt-hotels
```

3. Replace the Google Maps API key in `contact.html`:
```html
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
```

4. Open `index.html` in your browser or use a local development server.

## Browser Support

The website is compatible with the following browsers:
- Google Chrome (latest)
- Mozilla Firefox (latest)
- Safari (latest)
- Microsoft Edge (latest)

## Responsive Design

The website is fully responsive and optimized for:
- Desktop (1024px and above)
- Tablet (768px to 1023px)
- Mobile (below 768px)

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contact

Your Name - your.email@example.com
Project Link: https://github.com/yourusername/egypt-hotels 