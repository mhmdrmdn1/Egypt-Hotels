<?php
require_once '../../config/database.php';

// Array of amenities with their descriptions and icons
$amenities = [
    [
        'name' => 'Private Beach',
        'description' => 'Exclusive beach access for hotel guests with pristine shores and crystal-clear waters.',
        'icon' => 'fas fa-umbrella-beach'
    ],
    [
        'name' => 'Kids Club',
        'description' => 'Supervised activities and entertainment for children of all ages.',
        'icon' => 'fas fa-child'
    ],
    [
        'name' => 'Bar/Lounge',
        'description' => 'Elegant bar and lounge area serving premium drinks and cocktails.',
        'icon' => 'fas fa-glass-martini-alt'
    ],
    [
        'name' => 'Swimming Pool',
        'description' => 'Outdoor swimming pool with sun loungers and pool service.',
        'icon' => 'fas fa-swimming-pool'
    ],
    [
        'name' => 'Spa',
        'description' => 'Full-service spa offering massages, treatments, and wellness services.',
        'icon' => 'fas fa-spa'
    ],
    [
        'name' => 'Restaurant',
        'description' => 'Fine dining restaurant serving international and local cuisine.',
        'icon' => 'fas fa-utensils'
    ],
    [
        'name' => 'Gym',
        'description' => 'Modern fitness center with cardio and weight training equipment.',
        'icon' => 'fas fa-dumbbell'
    ],
    [
        'name' => 'WiFi',
        'description' => 'Complimentary high-speed WiFi throughout the property.',
        'icon' => 'fas fa-wifi'
    ],
    [
        'name' => 'Room Service',
        'description' => '24-hour room service with extensive menu options.',
        'icon' => 'fas fa-concierge-bell'
    ],
    [
        'name' => 'Beach Access',
        'description' => 'Direct access to the beach with complimentary beach towels.',
        'icon' => 'fas fa-umbrella-beach'
    ],
    [
        'name' => 'Air Conditioning',
        'description' => 'Climate-controlled rooms for your comfort.',
        'icon' => 'fas fa-snowflake'
    ],
    [
        'name' => 'Conference Room',
        'description' => 'Modern meeting facilities for business events.',
        'icon' => 'fas fa-chalkboard-teacher'
    ],
    [
        'name' => 'Garden',
        'description' => 'Beautiful landscaped gardens for relaxation.',
        'icon' => 'fas fa-leaf'
    ],
    [
        'name' => 'Tennis Court',
        'description' => 'Professional tennis courts with equipment rental.',
        'icon' => 'fas fa-table-tennis'
    ],
    [
        'name' => 'Shuttle Service',
        'description' => 'Complimentary shuttle service to nearby attractions.',
        'icon' => 'fas fa-shuttle-van'
    ],
    [
        'name' => 'Kids Pool',
        'description' => 'Dedicated swimming pool for children with safety features.',
        'icon' => 'fas fa-swimming-pool'
    ],
    [
        'name' => 'Pool Bar',
        'description' => 'Poolside bar serving refreshing drinks and snacks.',
        'icon' => 'fas fa-cocktail'
    ],
    [
        'name' => 'Business Center',
        'description' => 'Fully equipped business center with printing services.',
        'icon' => 'fas fa-briefcase'
    ],
    [
        'name' => 'Eco Design',
        'description' => 'Environmentally friendly design and practices.',
        'icon' => 'fas fa-seedling'
    ],
    [
        'name' => '24/7 Front Desk',
        'description' => 'Round-the-clock front desk service for guest assistance.',
        'icon' => 'fas fa-clock'
    ],
    [
        'name' => 'Airport Transfer',
        'description' => 'Convenient airport transfer service available.',
        'icon' => 'fas fa-plane-departure'
    ],
    [
        'name' => 'Medical Service',
        'description' => 'On-call medical assistance for emergencies.',
        'icon' => 'fas fa-first-aid'
    ],
    [
        'name' => 'Housekeeping',
        'description' => 'Daily housekeeping service to maintain cleanliness.',
        'icon' => 'fas fa-broom'
    ],
    [
        'name' => 'Meeting Rooms',
        'description' => 'Various sized meeting rooms for business gatherings.',
        'icon' => 'fas fa-handshake'
    ],
    [
        'name' => 'Wheelchair Access',
        'description' => 'Accessible facilities for guests with mobility needs.',
        'icon' => 'fas fa-wheelchair'
    ]
];

try {
    // First, create the amenities table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS amenities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        icon VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);

    // Prepare the insert statement
    $stmt = $pdo->prepare("INSERT INTO amenities (name, description, icon) VALUES (:name, :description, :icon)");

    // Insert each amenity
    foreach ($amenities as $amenity) {
        $stmt->execute([
            ':name' => $amenity['name'],
            ':description' => $amenity['description'],
            ':icon' => $amenity['icon']
        ]);
    }

    echo "Successfully added amenities to the database.";

} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
} 