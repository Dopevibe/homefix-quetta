<?php
/**
 * Migration Script: Filter Categories & Services to Plumbing, Electrical, Painting & Handyman
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = Database::getInstance()->getConnection();

// Disable foreign keys temporarily
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

// Truncate tables to cleanly re-populate with exact matching services
$pdo->exec("TRUNCATE TABLE reviews;");
$pdo->exec("TRUNCATE TABLE bookings;");
$pdo->exec("TRUNCATE TABLE services;");
$pdo->exec("TRUNCATE TABLE categories;");
$pdo->exec("TRUNCATE TABLE technicians;");
$pdo->exec("TRUNCATE TABLE gallery;");

// Insert 4 Selected Categories
$categories = [
    [1, 'Plumbing', 'plumbing', 'Expert pipe leakage repairs, instant geyser setup, water tank cleaning, and sanitary fittings in Quetta.', 'droplet', 'assets/images/categories/plumbing.jpg', 'active', 1],
    [2, 'Electrical', 'electrical', 'Complete home wiring diagnostics, circuit breaker fixes, solar UPS inverter setup, and lighting.', 'zap', 'assets/images/categories/electrical.jpg', 'active', 2],
    [3, 'Painting', 'painting', 'Interior & exterior wall painting, water-resistant stucco, roof waterproofing, and moisture treatment.', 'paint-bucket', 'assets/images/categories/painting.jpg', 'active', 3],
    [4, 'Handyman', 'handyman', 'TV wall bracket mounting, curtain rod hanging, floating shelves, and precision masonry drilling.', 'wrench', 'assets/images/categories/handyman.jpg', 'active', 4]
];

$catStmt = $pdo->prepare("INSERT INTO categories (id, name, slug, description, icon, image, status, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($categories as $cat) {
    $catStmt->execute($cat);
}

// Insert Services with exact matching photos
$services = [
    // Plumbing
    [
        1, 1, 'Pipe Leakage & Sanitary Fixture Repair', 'pipe-leakage-sanitary-repair',
        'Instant repair of leaking pipes, faucets, flush tanks, washbasins, and shower mixers.',
        'Our master plumbers arrive equipped with modern diagnostic tools and heavy-duty fittings to resolve pipe bursts, concealed pipeline drips, valve replacements, and kitchen drain chokes without damaging your tiles in Quetta.',
        1500.00, '1 - 2 Hours', 'assets/images/services/plumbing_leak.jpg',
        "Inspection of concealed & exposed pipes\nFaucet or valve replacement\nWater pressure test\n30-day workmanship warranty",
        'active', 1
    ],
    [
        2, 1, 'Instant & Storage Geyser Installation / Repair', 'geyser-installation-repair',
        'Gas & electric geyser repair, burner cleaning, thermostat tuning, and winter prep in Quetta.',
        'Ensure your family gets instant hot water during cold Quetta winters. We service gas burners, replace electric heating coils, descale storage tanks, and fix gas solenoid valves safely.',
        2200.00, '1.5 - 3 Hours', 'assets/images/services/geyser.jpg',
        "Burner ignition and thermocouple check\nElectric element & thermostat diagnostics\nGas leak safety inspection\nFull pressure test",
        'active', 1
    ],
    [
        3, 1, 'Underground & Overhead Water Tank Cleaning', 'water-tank-cleaning',
        'High-pressure mechanized cleaning, mud removal, and anti-bacterial disinfection.',
        'Keep your household water pure. We empty, scrub, vacuum, and chlorinate your RCC underground and rooftop plastic tanks with food-grade disinfectants.',
        3500.00, '2 - 4 Hours', 'assets/images/services/tank_cleaning.jpg',
        "Sludge & sediment high-pressure vacuuming\nTile & wall chemical scrubbing\nUV/Chlorine antibacterial rinse\nFinal purity check",
        'active', 0
    ],

    // Electrical
    [
        4, 2, 'Complete House Electrical Diagnostics & Fixes', 'house-electrical-diagnostics',
        'Troubleshooting tripping breakers, short circuits, voltage surges, and burnt wires.',
        'Our licensed electricians inspect your DB board, check phase loads, tighten loose connections, and test breaker trip sensitivities to ensure fire safety.',
        1800.00, '1 - 3 Hours', 'assets/images/services/electrical_diag.jpg',
        "Distribution Board (DB) load balancing\nShort circuit trace and repair\nEarthing/Grounding check\n30-day service warranty",
        'active', 1
    ],
    [
        5, 2, 'UPS & Solar Inverter Wiring & Installation', 'ups-solar-inverter-setup',
        'Safe wiring, battery maintenance, inverter calibration, and backup power switchover.',
        'Beat load shedding in Quetta with proper inverter and hybrid solar connectivity. We wire dedicated breaker circuits, calculate battery load, and replace old DC cables.',
        3000.00, '2 - 4 Hours', 'assets/images/services/ups_solar.jpg',
        "Inverter mounting & connection\nBattery gravity & terminal check\nDedicated sub-distribution wiring\nSafety test with full house load",
        'active', 1
    ],
    [
        6, 2, 'Ceiling Fan, Chandelier & Light Fixture Setup', 'fan-chandelier-light-setup',
        'Installation of modern LED panels, cove lighting, heavy chandeliers, and ceiling fans.',
        'Precision drilling, ceiling hook reinforcement, wiring concealed leads, and mounting elegant light fixtures with zero wobble.',
        1200.00, '1 - 2 Hours', 'assets/images/services/lights.jpg',
        "Heavy fixture anchoring\nDimmer switch and regulator wiring\nConcealed wire jointing\nFunctional check",
        'active', 0
    ],
    [
        7, 2, 'Short Circuit & Earthing Grounding Repair', 'short-circuit-earthing-repair',
        'Fixing electric shocks from home appliances, neutral wire faults, and earthing pits.',
        'Protect your family from stray electric currents. We install earth leakage circuit breakers (ELCB) and construct copper earthing rods to secure your entire residence.',
        2500.00, '2 - 4 Hours', 'assets/images/services/earthing.jpg',
        "Stray current voltage testing\nELCB/RCCB installation\nEarth resistance check\n30-day warranty",
        'active', 0
    ],

    // Painting
    [
        8, 3, 'Single Room & Accent Wall Painting', 'single-room-accent-painting',
        'Surface preparation, crack putty filling, primer coat, and 2 premium emulsion coats.',
        'Transform any room in your house in a single day. We protect your floors and furniture with drop cloths, sand imperfections, and roll on smooth, washable paint.',
        6000.00, '1 Day', 'assets/images/services/room_painting.jpg',
        "Floor and furniture masking protection\nWall sanding and acrylic putty application\nUndercoat primer application\n2 finish coats of branded emulsion",
        'active', 1
    ],
    [
        9, 3, 'Complete House Interior & Exterior Painting', 'full-house-interior-exterior-painting',
        'Full villa/house painting with weather-shield exterior paint and luxury silk emulsion interior.',
        'Comprehensive painting by a dedicated team of master painters. Includes crack repair, plaster patching, damp-proofing undercoats, and 2 durable top coats.',
        18500.00, '3 - 5 Days', 'assets/images/services/house_painting.jpg',
        "Complete wall scraping & surface repair\nExterior weather-shield application\nInterior silk/matt finish coats\nComplete cleanup & floor wash after painting",
        'active', 1
    ],
    [
        10, 3, 'Roof Waterproofing & Seepage Treatment', 'roof-waterproofing-seepage',
        'Elastomeric membrane coating, chemical grouting, and moisture barrier sealing.',
        'Protect your ceiling from winter rain and melting snow in Quetta. We seal expansion joints and apply multi-layer UV reflective waterproofing coatings.',
        9500.00, '1 - 2 Days', 'assets/images/services/waterproofing.jpg',
        "Surface debris scraping & power wash\nCrack repair with polyurethane sealant\nFiber mesh reinforcement on parapet corners\n3 coats of elastomeric waterproof barrier",
        'active', 1
    ],

    // Handyman
    [
        11, 4, 'General Handyman & Wall Mounting Service', 'general-handyman-wall-mounting',
        'Drilling for LCD/LED TV brackets, mirror hanging, curtain rods, and floating shelves.',
        'Got a list of small jobs around the house? Our equipped handyman arrives with masonry drills, laser levels, anchor bolts, and fixings to get everything mounted neat and sturdy.',
        1500.00, '1 - 2 Hours', 'assets/images/services/handyman.jpg',
        "Laser-leveled precision drilling\nHeavy-duty metal rawl plugs & screws\nMounting up to 4 household items\nClean dust collection while drilling",
        'active', 1
    ],
    [
        12, 4, 'TV Wall Bracket & LCD Mounting', 'tv-bracket-lcd-mounting',
        'Secure wall mounting of 32\" to 85\" LED/OLED televisions with concealed wire routing.',
        'Professional installation of fixed, tilt, or swivel TV brackets into concrete, brick, or stud walls. Cable management included.',
        1800.00, '1 - 2 Hours', 'assets/images/services/tv_mounting.jpg',
        "Heavy-duty TV bracket supply/mounting\nConcrete masonry anchor drilling\nConcealed wire trunking\nAngle & tilt alignment test",
        'active', 1
    ]
];

$srvStmt = $pdo->prepare("INSERT INTO services (id, category_id, name, slug, description, detailed_description, price, duration, image, includes_list, status, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($services as $srv) {
    $srvStmt->execute($srv);
}

// Technicians for the 4 trades
$technicians = [
    [1, 'Ustad Ghulam Rasool', 'ghulam.r@homefix.pk', '+92 301 8329102', 'Master Plumber & Pipe Specialist', 11, 4.95, 340, 'assets/images/technicians/tech1.jpg', 'available', 'active'],
    [2, 'Engineer Zeeshan Achakzai', 'zeeshan.a@homefix.pk', '+92 313 8840192', 'Licensed Senior Electrician', 8, 4.92, 280, 'assets/images/technicians/tech2.jpg', 'available', 'active'],
    [3, 'Naimatullah Mengal', 'naimat.m@homefix.pk', '+92 334 7192044', 'Geyser & Water System Technician', 9, 4.88, 310, 'assets/images/technicians/tech3.jpg', 'available', 'active'],
    [4, 'Muhammad Aslam Tareen', 'aslam.t@homefix.pk', '+92 321 8291033', 'Solar & Inverter Setup Specialist', 10, 4.94, 265, 'assets/images/technicians/tech4.jpg', 'available', 'active'],
    [5, 'Bashir Ahmed Bugti', 'bashir.b@homefix.pk', '+92 306 9102837', 'Wall Painting & Waterproofing Expert', 10, 4.90, 215, 'assets/images/technicians/tech5.jpg', 'available', 'active'],
    [6, 'Kamran Durrani', 'kamran.d@homefix.pk', '+92 303 8192044', 'General Handyman & Wall Mounting', 5, 4.87, 175, 'assets/images/technicians/tech6.jpg', 'available', 'active']
];

$techStmt = $pdo->prepare("INSERT INTO technicians (id, name, email, phone, specialty, experience_years, rating, completed_jobs, image, availability, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($technicians as $t) {
    $techStmt->execute($t);
}

// Work Gallery for the 4 trades
$gallery = [
    [1, 'Concealed Bathroom Pipe Leak & Fixture Overhaul', 'Plumbing', 'Replaced cracked concealed pipe lines with German PPRC piping and mounted new ceramic vanity.', 'assets/images/gallery/plumb_before.jpg', 'assets/images/gallery/plumb_after.jpg', 'active'],
    [2, 'Burnt Main Distribution Board & Breakers Overhaul', 'Electrical', 'Reorganized hazardous messy house wiring with Schneider circuit breakers and digital voltage protector.', 'assets/images/gallery/db_before.jpg', 'assets/images/gallery/db_after.jpg', 'active'],
    [3, 'Living Room Damp Wall Waterproofing & Silk Painting', 'Painting', 'Treated damp peeling wall with waterproof plaster and applied smooth silk emulsion finish.', 'assets/images/gallery/paint_before.jpg', 'assets/images/gallery/paint_after.jpg', 'active']
];

$galStmt = $pdo->prepare("INSERT INTO gallery (id, title, category, description, before_image, after_image, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
foreach ($gallery as $g) {
    $galStmt->execute($g);
}

// Sample Bookings matching remaining services
$bookings = [
    [1, 'HFQ-892101', 2, 2, 3, 'Farhan Baloch', 'customer@homefix.pk', '+92 333 7819201', 'House 45, Street 4, Sector B', 'Jinnah Town', '2026-08-16', '10:00 AM - 12:00 PM', 'Gas geyser pilot not staying lit and burner requires maintenance.', NULL, 'confirmed', 'Customer requested morning slot.', 2200.00],
    [2, 'HFQ-741902', 3, 1, 1, 'Dr. Amina Kakar', 'amina.kakar@gmail.com', '+92 312 9012345', 'Villa 12, Officers Colony', 'Cantt', '2026-08-15', '02:00 PM - 04:00 PM', 'Concealed kitchen pipe dripping under the sink cabinet, water pressure dropped.', NULL, 'in_progress', 'Technician Ghulam Rasool dispatched with copper joint fittings.', 1500.00],
    [3, 'HFQ-630184', 4, 4, 2, 'Tariq Shahwani', 'tariq.s@hotmail.com', '+92 345 8823190', 'Plot 88, Near Serena Hotel', 'Zarghoon Road', '2026-08-14', '11:00 AM - 01:00 PM', 'Main 63A circuit breaker tripping whenever water motor is turned on.', NULL, 'completed', 'Completed successfully. Replaced faulty 32A breaker and rebalanced load.', 1800.00],
    [4, 'HFQ-519280', 2, 8, 5, 'Farhan Baloch', 'customer@homefix.pk', '+92 333 7819201', 'House 45, Street 4, Sector B', 'Jinnah Town', '2026-08-10', '03:00 PM - 05:00 PM', 'Single bedroom accent wall painting and putty work.', NULL, 'completed', 'Completed with two coats of silk emulsion.', 6000.00],
    [5, 'HFQ-410928', NULL, 11, 6, 'Sardar Mir Jan Jamali', 'mirjan.jamali@gmail.com', '+92 300 9281726', 'Bungalow 18, Phase 2', 'Model Town', '2026-08-17', '09:00 AM - 01:00 PM', 'TV wall bracket mounting and curtain rods hanging.', NULL, 'pending', 'Customer requested morning arrival.', 1500.00]
];

$bookStmt = $pdo->prepare("INSERT INTO bookings (id, booking_reference, user_id, service_id, technician_id, customer_name, customer_email, customer_phone, address, area, preferred_date, preferred_time, problem_description, image_attachment, status, notes, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($bookings as $b) {
    $bookStmt->execute($b);
}

// Sample Reviews
$reviews = [
    [1, 3, 4, 4, 'Tariq Shahwani', 5, 'Engineer Zeeshan arrived exactly on time in Zarghoon Road. Fixed the breaker trip issue within 45 minutes. Super polite and professional. HomeFix is a blessing for Quetta!', 'approved', '2026-08-14 14:30:00'],
    [2, 4, 2, 8, 'Farhan Baloch', 5, 'Ustad Ghulam Rasool and Bashir did an incredible job. Quality paint finish and clean workspace. Highly recommended in Quetta!', 'approved', '2026-08-10 17:45:00'],
    [3, NULL, NULL, 2, 'Noman Kasi (Samungli Rd)', 5, 'Called them for Instant Geyser repair. They diagnosed the gas valve issue and fixed it immediately. 10/10 service.', 'approved', '2026-08-12 11:20:00'],
    [4, NULL, NULL, 11, 'Mrs. Dr. Kakar (Cantt)', 5, 'Got two heavy LED TVs mounted on brick walls with laser alignment. Solid work.', 'approved', '2026-08-08 16:15:00']
];

$revStmt = $pdo->prepare("INSERT INTO reviews (id, booking_id, user_id, service_id, customer_name, rating, review_text, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($reviews as $r) {
    $revStmt->execute($r);
}

$pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

echo "Database updated successfully with Plumbing, Electrical, Painting and Handyman services.\n";
