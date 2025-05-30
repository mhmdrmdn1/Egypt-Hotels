-- Drop existing tables if they exist
DROP TABLE IF EXISTS hotel_amenities;
DROP TABLE IF EXISTS amenities;

-- Create amenities table
CREATE TABLE amenities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create hotel_amenities junction table
CREATE TABLE hotel_amenities (
    hotel_id INT,
    amenity_id INT,
    PRIMARY KEY (hotel_id, amenity_id),
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE
);

-- Insert amenities data
INSERT INTO amenities (name, description, icon) VALUES
('Private Beach', 'Exclusive beach access for hotel guests with pristine shores and crystal-clear waters.', 'fas fa-umbrella-beach'),
('Kids Club', 'Supervised activities and entertainment for children of all ages.', 'fas fa-child'),
('Bar/Lounge', 'Elegant bar and lounge area serving premium drinks and cocktails.', 'fas fa-glass-martini-alt'),
('Swimming Pool', 'Outdoor swimming pool with sun loungers and pool service.', 'fas fa-swimming-pool'),
('Spa', 'Full-service spa offering massages, treatments, and wellness services.', 'fas fa-spa'),
('Restaurant', 'Fine dining restaurant serving international and local cuisine.', 'fas fa-utensils'),
('Gym', 'Modern fitness center with cardio and weight training equipment.', 'fas fa-dumbbell'),
('WiFi', 'Complimentary high-speed WiFi throughout the property.', 'fas fa-wifi'),
('Room Service', '24-hour room service with extensive menu options.', 'fas fa-concierge-bell'),
('Beach Access', 'Direct access to the beach with complimentary beach towels.', 'fas fa-umbrella-beach'),
('Air Conditioning', 'Climate-controlled rooms for your comfort.', 'fas fa-snowflake'),
('Conference Room', 'Modern meeting facilities for business events.', 'fas fa-chalkboard-teacher'),
('Garden', 'Beautiful landscaped gardens for relaxation.', 'fas fa-leaf'),
('Tennis Court', 'Professional tennis courts with equipment rental.', 'fas fa-table-tennis'),
('Shuttle Service', 'Complimentary shuttle service to nearby attractions.', 'fas fa-shuttle-van'),
('Kids Pool', 'Dedicated swimming pool for children with safety features.', 'fas fa-swimming-pool'),
('Pool Bar', 'Poolside bar serving refreshing drinks and snacks.', 'fas fa-cocktail'),
('Business Center', 'Fully equipped business center with printing services.', 'fas fa-briefcase'),
('Eco Design', 'Environmentally friendly design and practices.', 'fas fa-seedling'),
('24/7 Front Desk', 'Round-the-clock front desk service for guest assistance.', 'fas fa-clock'),
('Airport Transfer', 'Convenient airport transfer service available.', 'fas fa-plane-departure'),
('Medical Service', 'On-call medical assistance for emergencies.', 'fas fa-first-aid'),
('Housekeeping', 'Daily housekeeping service to maintain cleanliness.', 'fas fa-broom'),
('Meeting Rooms', 'Various sized meeting rooms for business gatherings.', 'fas fa-handshake'),
('Wheelchair Access', 'Accessible facilities for guests with mobility needs.', 'fas fa-wheelchair'); 