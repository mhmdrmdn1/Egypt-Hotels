-- Update hotel amenities with features, hours, and price
-- Swimming Pool
UPDATE hotel_amenities 
SET features = '["Heated in winter", "Lifeguard on duty", "Pool bar available", "Towels provided"]',
    hours = '6:00 AM - 10:00 PM',
    price = 'Free for hotel guests'
WHERE amenity_id = 2;

-- Free Wi-Fi
UPDATE hotel_amenities 
SET features = '["Available in all rooms", "No password required", "24/7 support"]',
    hours = '24/7',
    price = 'Free'
WHERE amenity_id = 3;

-- Restaurant
UPDATE hotel_amenities 
SET features = '["Buffet and à la carte", "Vegetarian options", "Kids menu available"]',
    hours = '7:00 AM - 11:00 PM',
    price = 'Charges apply'
WHERE amenity_id = 4;

-- Spa
UPDATE hotel_amenities 
SET features = '["Sauna & steam room", "Massage therapies", "Beauty treatments"]',
    hours = '9:00 AM - 9:00 PM',
    price = 'Extra charge'
WHERE amenity_id = 5;

-- Fitness Center
UPDATE hotel_amenities 
SET features = '["Cardio & weights", "Personal trainers", "Yoga classes"]',
    hours = '24/7',
    price = 'Free for hotel guests'
WHERE amenity_id = 6;

-- Private Beach
UPDATE hotel_amenities 
SET features = '["Sunbeds and umbrellas included", "Beach towels provided", "Lifeguard on duty"]',
    hours = '7:00 AM - Sunset',
    price = 'Free for hotel guests'
WHERE amenity_id = 7;

-- Kids Club
UPDATE hotel_amenities 
SET features = '["Supervised play area", "Daily activities", "Kids pool"]',
    hours = '10:00 AM - 6:00 PM',
    price = 'Free for hotel guests'
WHERE amenity_id = 8;

-- Parking
UPDATE hotel_amenities 
SET features = '["Covered parking", "Security monitored", "Accessible parking"]',
    hours = '24/7',
    price = 'Free for guests, visitors EGP 50/day'
WHERE amenity_id = 9;

-- Room Service
UPDATE hotel_amenities 
SET features = '["Full menu", "Express delivery", "Special requests"]',
    hours = '24/7',
    price = 'Service charge may apply'
WHERE amenity_id = 10;

-- Business Center
UPDATE hotel_amenities 
SET features = '["Meeting rooms", "Printing & scanning", "Secretarial services"]',
    hours = '8:00 AM - 8:00 PM',
    price = 'Some services extra'
WHERE amenity_id = 12;

-- Bar/Lounge
UPDATE hotel_amenities 
SET features = '["Live music", "Signature cocktails", "Snacks available"]',
    hours = '4:00 PM - 1:00 AM',
    price = 'Charges apply'
WHERE amenity_id = 15;

-- Water Sports
UPDATE hotel_amenities 
SET features = '["Snorkeling", "Diving", "Kayaking", "Equipment rental"]',
    hours = '9:00 AM - 6:00 PM',
    price = 'Extra charge'
WHERE amenity_id = 19;

-- Diving Center
UPDATE hotel_amenities 
SET features = '["PADI courses", "Equipment rental", "Guided dives"]',
    hours = '9:00 AM - 6:00 PM',
    price = 'Extra charge'
WHERE amenity_id = 18;

-- Shuttle Service
UPDATE hotel_amenities 
SET features = '["Airport pickup", "Scheduled times", "Luggage assistance"]',
    hours = '6:00 AM - 11:00 PM',
    price = 'Free for hotel guests'
WHERE amenity_id = 20;

-- Garden
UPDATE hotel_amenities 
SET features = '["Local plants", "Walking paths", "Relaxation areas"]',
    hours = '24/7',
    price = 'Included'
WHERE amenity_id = 16;

-- Tennis Court
UPDATE hotel_amenities 
SET features = '["Professional courts", "Equipment rental", "Lessons available"]',
    hours = '8:00 AM - 10:00 PM',
    price = 'Free for hotel guests'
WHERE amenity_id = 17; 