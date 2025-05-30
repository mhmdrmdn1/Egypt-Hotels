@echo off
setlocal enabledelayedexpansion

:: Create directories
mkdir assets\images\hero 2>nul
mkdir assets\images\gallery 2>nul
mkdir assets\images\hotels 2>nul
mkdir assets\images\team 2>nul
mkdir assets\images\awards 2>nul
mkdir assets\images\icons 2>nul
mkdir assets\images\destinations 2>nul

:: Download hero images
curl -o assets\images\hero\main-hero.jpg "https://images.unsplash.com/photo-1539768942893-daf53e448371?q=80"
curl -o assets\images\hero\gallery-hero.jpg "https://images.unsplash.com/photo-1569383746724-6f1e6f3c1296?q=80"
curl -o assets\images\hero\explore-hero.jpg "https://images.unsplash.com/photo-1549633030-89d0743bad01?q=80"
curl -o assets\images\hero\about-hero.jpg "https://images.unsplash.com/photo-1600607686527-6fb886090705?q=80"
curl -o assets\images\hero\book-hero.jpg "https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80"
curl -o assets\images\hero\contact-hero.jpg "https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80"

:: Download gallery images
curl -o assets\images\gallery\room1.jpg "https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80"
curl -o assets\images\gallery\room2.jpg "https://images.unsplash.com/photo-1595576508898-0ad5c879a061?q=80"
curl -o assets\images\gallery\pool.jpg "https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?q=80"
curl -o assets\images\gallery\spa.jpg "https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80"
curl -o assets\images\gallery\view1.jpg "https://images.unsplash.com/photo-1548588627-f978862b85e1?q=80"
curl -o assets\images\gallery\view2.jpg "https://images.unsplash.com/photo-1543489822-c49534f3271f?q=80"
curl -o assets\images\gallery\restaurant1.jpg "https://images.unsplash.com/photo-1592861956120-e524fc739696?q=80"
curl -o assets\images\gallery\restaurant2.jpg "https://images.unsplash.com/photo-1590846406792-0adc7f938f1d?q=80"

:: Download hotel images
curl -o assets\images\hotels\hotel1.jpg "https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80"
curl -o assets\images\hotels\hotel2.jpg "https://images.unsplash.com/photo-1566665797739-1674de7a421a?q=80"
curl -o assets\images\hotels\hotel3.jpg "https://images.unsplash.com/photo-1564501049412-61c2a3083791?q=80"
curl -o assets\images\hotels\hotel1-1.jpg "https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80"
curl -o assets\images\hotels\hotel1-2.jpg "https://images.unsplash.com/photo-1630660664869-c9d3cc676880?q=80"
curl -o assets\images\hotels\hotel1-3.jpg "https://images.unsplash.com/photo-1584132967334-10e028bd69f7?q=80"

:: Download destination images
curl -o assets\images\destinations\cairo.jpg "https://images.unsplash.com/photo-1572252009286-268acec5ca0a?q=80"
curl -o assets\images\destinations\sharm.jpg "https://images.unsplash.com/photo-1559628233-100c798642d4?q=80"
curl -o assets\images\destinations\hurghada.jpg "https://images.unsplash.com/photo-1544207240-42827f174f8b?q=80"

:: Download team images
curl -o assets\images\team\ceo.jpg "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80"
curl -o assets\images\team\manager.jpg "https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80"
curl -o assets\images\team\designer.jpg "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80"

:: Download award images
curl -o assets\images\awards\award1.jpg "https://images.unsplash.com/photo-1621274147744-cfb5694bb233?q=80"
curl -o assets\images\awards\award2.jpg "https://images.unsplash.com/photo-1621274147744-cfb5694bb233?q=80"
curl -o assets\images\awards\award3.jpg "https://images.unsplash.com/photo-1621274147744-cfb5694bb233?q=80"

:: Download icons
curl -o assets\images\icons\location.png "https://cdn-icons-png.flaticon.com/256/2776/2776067.png"
curl -o assets\images\icons\phone.png "https://cdn-icons-png.flaticon.com/256/126/126341.png"
curl -o assets\images\icons\email.png "https://cdn-icons-png.flaticon.com/256/3178/3178158.png"

echo All images have been downloaded. 