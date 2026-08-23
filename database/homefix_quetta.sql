-- HomeFix Quetta - Database Schema & Realistic Seed Data
-- Platform: PHP 8+ / MySQL 8+ / MariaDB 10.4+
-- Location: Quetta, Balochistan, Pakistan

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `phone` VARCHAR(30) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'customer') DEFAULT 'customer',
  `status` ENUM('active', 'inactive', 'banned') DEFAULT 'active',
  `avatar` VARCHAR(255) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `area` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_users_role` (`role`),
  INDEX `idx_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `categories`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `description` TEXT NOT NULL,
  `icon` VARCHAR(50) NOT NULL DEFAULT 'wrench',
  `image` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `sort_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `services`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(180) NOT NULL UNIQUE,
  `description` TEXT NOT NULL,
  `detailed_description` LONGTEXT DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `duration` VARCHAR(80) NOT NULL DEFAULT '1 - 2 Hours',
  `image` VARCHAR(255) DEFAULT NULL,
  `includes_list` TEXT DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `is_featured` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX `idx_services_category` (`category_id`),
  INDEX `idx_services_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `technicians`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `technicians`;
CREATE TABLE `technicians` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `specialty` VARCHAR(120) NOT NULL,
  `experience_years` INT NOT NULL DEFAULT 3,
  `rating` DECIMAL(3,2) NOT NULL DEFAULT 4.90,
  `completed_jobs` INT NOT NULL DEFAULT 120,
  `image` VARCHAR(255) DEFAULT NULL,
  `availability` ENUM('available', 'busy', 'offline') DEFAULT 'available',
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_technicians_availability` (`availability`),
  INDEX `idx_technicians_specialty` (`specialty`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `bookings`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `booking_reference` VARCHAR(30) NOT NULL UNIQUE,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `service_id` INT UNSIGNED NOT NULL,
  `technician_id` INT UNSIGNED DEFAULT NULL,
  `customer_name` VARCHAR(120) NOT NULL,
  `customer_email` VARCHAR(150) NOT NULL,
  `customer_phone` VARCHAR(30) NOT NULL,
  `address` TEXT NOT NULL,
  `area` VARCHAR(100) NOT NULL,
  `preferred_date` DATE NOT NULL,
  `preferred_time` VARCHAR(50) NOT NULL,
  `problem_description` TEXT NOT NULL,
  `image_attachment` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('pending', 'confirmed', 'assigned', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
  `notes` TEXT DEFAULT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (`technician_id`) REFERENCES `technicians`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  INDEX `idx_bookings_ref` (`booking_reference`),
  INDEX `idx_bookings_status` (`status`),
  INDEX `idx_bookings_date` (`preferred_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `reviews`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT UNSIGNED DEFAULT NULL,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `service_id` INT UNSIGNED NOT NULL,
  `customer_name` VARCHAR(120) NOT NULL,
  `rating` TINYINT(1) NOT NULL DEFAULT 5,
  `review_text` TEXT NOT NULL,
  `status` ENUM('pending', 'approved', 'hidden') DEFAULT 'approved',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX `idx_reviews_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `gallery`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `before_image` VARCHAR(255) DEFAULT NULL,
  `after_image` VARCHAR(255) NOT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `contact_messages`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE `contact_messages` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  `subject` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `settings`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ========================================================
-- SEED DATA
-- ========================================================

-- Settings
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'HomeFix Quetta'),
('tagline', 'Reliable Home Services, Right at Your Door'),
('contact_email', 'support@homefix.pk'),
('contact_phone', '+92 331 7374824'),
('whatsapp_number', '+923317374824'),
('office_address', 'New Abdul Razzaq Electric, Shop No 9/10, Block 2, Satellite Town, Quetta'),
('working_hours', 'Mon - Sat: 8:00 AM - 9:00 PM | Sun: 10:00 AM - 6:00 PM'),
('currency_symbol', 'Rs. ');

-- Users (Admin: Admin@123, Customer: Customer@123)
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `status`, `avatar`, `address`, `area`) VALUES
(1, 'HomeFix Admin', 'admin@homefix.pk', '+92 300 8392011', '$2y$10$xW/x.kyqdJrZv2vBk9TZ.OYCXEwb7zXH2dKZtwuex5rLcPZoAvFwm', 'admin', 'active', 'assets/images/avatars/admin.jpg', 'HQ Zarghoon Road', 'Zarghoon Road'),
(2, 'Farhan Baloch', 'customer@homefix.pk', '+92 333 7819201', '$2y$10$IkSrXP/SOEx7WUenCMTsv.wikiq/PqVVmsZGG.NQweugXbPM9.aeS', 'customer', 'active', 'assets/images/avatars/farhan.jpg', 'House 45, Street 4, Sector B', 'Jinnah Town'),
(3, 'Dr. Amina Kakar', 'amina.kakar@gmail.com', '+92 312 9012345', '$2y$10$IkSrXP/SOEx7WUenCMTsv.wikiq/PqVVmsZGG.NQweugXbPM9.aeS', 'customer', 'active', NULL, 'Villa 12, Officers Colony', 'Cantt'),
(4, 'Tariq Shahwani', 'tariq.s@hotmail.com', '+92 345 8823190', '$2y$10$IkSrXP/SOEx7WUenCMTsv.wikiq/PqVVmsZGG.NQweugXbPM9.aeS', 'customer', 'active', NULL, 'Plot 88, Near Serena', 'Zarghoon Road');

-- Categories (4 Core Trades)
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `image`, `status`, `sort_order`) VALUES
(1, 'Plumbing', 'plumbing', 'Expert pipe leakage repairs, instant geyser setup, water tank cleaning, and sanitary fittings in Quetta.', 'droplet', 'assets/images/categories/plumbing.jpg', 'active', 1),
(2, 'Electrical', 'electrical', 'Complete home wiring diagnostics, circuit breaker fixes, solar UPS inverter setup, and lighting.', 'zap', 'assets/images/categories/electrical.jpg', 'active', 2),
(3, 'Painting', 'painting', 'Interior & exterior wall painting, water-resistant stucco, roof waterproofing, and moisture treatment.', 'paint-bucket', 'assets/images/categories/painting.jpg', 'active', 3),
(4, 'Handyman', 'handyman', 'TV wall bracket mounting, curtain rod hanging, floating shelves, and precision masonry drilling.', 'wrench', 'assets/images/categories/handyman.jpg', 'active', 4);

-- Services
INSERT INTO `services` (`id`, `category_id`, `name`, `slug`, `description`, `detailed_description`, `price`, `duration`, `image`, `includes_list`, `status`, `is_featured`) VALUES
(1, 1, 'Pipe Leakage & Sanitary Fixture Repair', 'pipe-leakage-sanitary-repair', 'Instant repair of leaking pipes, faucets, flush tanks, washbasins, and shower mixers.', 'Our master plumbers arrive equipped with modern diagnostic tools and heavy-duty fittings to resolve pipe bursts, concealed pipeline drips, valve replacements, and kitchen drain chokes without damaging your tiles in Quetta.', 1500.00, '1 - 2 Hours', 'assets/images/services/plumbing_leak.jpg', 'Inspection of concealed & exposed pipes\nFaucet or valve replacement\nWater pressure test\n30-day workmanship warranty', 'active', 1),
(2, 1, 'Instant & Storage Geyser Installation / Repair', 'geyser-installation-repair', 'Gas & electric geyser repair, burner cleaning, thermostat tuning, and winter prep in Quetta.', 'Ensure your family gets instant hot water during cold Quetta winters. We service gas burners, replace electric heating coils, descale storage tanks, and fix gas solenoid valves safely.', 2200.00, '1.5 - 3 Hours', 'assets/images/services/geyser.jpg', 'Burner ignition and thermocouple check\nElectric element & thermostat diagnostics\nGas leak safety inspection\nFull pressure test', 'active', 1),
(3, 1, 'Underground & Overhead Water Tank Cleaning', 'water-tank-cleaning', 'High-pressure mechanized cleaning, mud removal, and anti-bacterial disinfection.', 'Keep your household water pure. We empty, scrub, vacuum, and chlorinate your RCC underground and rooftop plastic tanks with food-grade disinfectants.', 3500.00, '2 - 4 Hours', 'assets/images/services/tank_cleaning.jpg', 'Sludge & sediment high-pressure vacuuming\nTile & wall chemical scrubbing\nUV/Chlorine antibacterial rinse\nFinal purity check', 'active', 0),
(4, 2, 'Complete House Electrical Diagnostics & Fixes', 'house-electrical-diagnostics', 'Troubleshooting tripping breakers, short circuits, voltage surges, and burnt wires.', 'Our licensed electricians inspect your DB board, check phase loads, tighten loose connections, and test breaker trip sensitivities to ensure fire safety.', 1800.00, '1 - 3 Hours', 'assets/images/services/electrical_diag.jpg', 'Distribution Board (DB) load balancing\nShort circuit trace and repair\nEarthing/Grounding check\n30-day service warranty', 'active', 1),
(5, 2, 'UPS & Solar Inverter Wiring & Installation', 'ups-solar-inverter-setup', 'Safe wiring, battery maintenance, inverter calibration, and backup power switchover.', 'Beat load shedding in Quetta with proper inverter and hybrid solar connectivity. We wire dedicated breaker circuits, calculate battery load, and replace old DC cables.', 3000.00, '2 - 4 Hours', 'assets/images/services/ups_solar.jpg', 'Inverter mounting & connection\nBattery gravity & terminal check\nDedicated sub-distribution wiring\nSafety test with full house load', 'active', 1),
(6, 2, 'Ceiling Fan, Chandelier & Light Fixture Setup', 'fan-chandelier-light-setup', 'Installation of modern LED panels, cove lighting, heavy chandeliers, and ceiling fans.', 'Precision drilling, ceiling hook reinforcement, wiring concealed leads, and mounting elegant light fixtures with zero wobble.', 1200.00, '1 - 2 Hours', 'assets/images/services/lights.jpg', 'Heavy fixture anchoring\nDimmer switch and regulator wiring\nConcealed wire jointing\nFunctional check', 'active', 0),
(7, 2, 'Short Circuit & Earthing Grounding Repair', 'short-circuit-earthing-repair', 'Fixing electric shocks from home appliances, neutral wire faults, and earthing pits.', 'Protect your family from stray electric currents. We install earth leakage circuit breakers (ELCB) and construct copper earthing rods to secure your entire residence.', 2500.00, '2 - 4 Hours', 'assets/images/services/earthing.jpg', 'Stray current voltage testing\nELCB/RCCB installation\nEarth resistance check\n30-day warranty', 'active', 0),
(8, 3, 'Single Room & Accent Wall Painting', 'single-room-accent-painting', 'Surface preparation, crack putty filling, primer coat, and 2 premium emulsion coats.', 'Transform any room in your house in a single day. We protect your floors and furniture with drop cloths, sand imperfections, and roll on smooth, washable paint.', 6000.00, '1 Day', 'assets/images/services/room_painting.jpg', 'Floor and furniture masking protection\nWall sanding and acrylic putty application\nUndercoat primer application\n2 finish coats of branded emulsion', 'active', 1),
(9, 3, 'Complete House Interior & Exterior Painting', 'full-house-interior-exterior-painting', 'Full villa/house painting with weather-shield exterior paint and luxury silk emulsion interior.', 'Comprehensive painting by a dedicated team of master painters. Includes crack repair, plaster patching, damp-proofing undercoats, and 2 durable top coats.', 18500.00, '3 - 5 Days', 'assets/images/services/house_painting.jpg', 'Complete wall scraping & surface repair\nExterior weather-shield application\nInterior silk/matt finish coats\nComplete cleanup & floor wash after painting', 'active', 1),
(10, 3, 'Roof Waterproofing & Seepage Treatment', 'roof-waterproofing-seepage', 'Elastomeric membrane coating, chemical grouting, and moisture barrier sealing.', 'Protect your ceiling from winter rain and melting snow in Quetta. We seal expansion joints and apply multi-layer UV reflective waterproofing coatings.', 9500.00, '1 - 2 Days', 'assets/images/services/waterproofing.jpg', 'Surface debris scraping & power wash\nCrack repair with polyurethane sealant\nFiber mesh reinforcement on parapet corners\n3 coats of elastomeric waterproof barrier', 'active', 1),
(11, 4, 'General Handyman & Wall Mounting Service', 'general-handyman-wall-mounting', 'Drilling for LCD/LED TV brackets, mirror hanging, curtain rods, and floating shelves.', 'Got a list of small jobs around the house? Our equipped handyman arrives with masonry drills, laser levels, anchor bolts, and fixings to get everything mounted neat and sturdy.', 1500.00, '1 - 2 Hours', 'assets/images/services/handyman.jpg', 'Laser-leveled precision drilling\nHeavy-duty metal rawl plugs & screws\nMounting up to 4 household items\nClean dust collection while drilling', 'active', 1),
(12, 4, 'TV Wall Bracket & LCD Mounting', 'tv-bracket-lcd-mounting', 'Secure wall mounting of 32\" to 85\" LED/OLED televisions with concealed wire routing.', 'Professional installation of fixed, tilt, or swivel TV brackets into concrete, brick, or stud walls. Cable management included.', 1800.00, '1 - 2 Hours', 'assets/images/services/tv_mounting.jpg', 'Heavy-duty TV bracket supply/mounting\nConcrete masonry anchor drilling\nConcealed wire trunking\nAngle & tilt alignment test', 'active', 1);

-- Technicians (Vetted in Quetta)
INSERT INTO `technicians` (`id`, `name`, `email`, `phone`, `specialty`, `experience_years`, `rating`, `completed_jobs`, `image`, `availability`, `status`) VALUES
(1, 'Ustad Ghulam Rasool', 'ghulam.r@homefix.pk', '+92 301 8329102', 'Master Plumber & Pipe Specialist', 11, 4.95, 340, 'assets/images/technicians/tech1.jpg', 'available', 'active'),
(2, 'Engineer Zeeshan Achakzai', 'zeeshan.a@homefix.pk', '+92 313 8840192', 'Licensed Senior Electrician', 8, 4.92, 280, 'assets/images/technicians/tech2.jpg', 'available', 'active'),
(3, 'Naimatullah Mengal', 'naimat.m@homefix.pk', '+92 334 7192044', 'Geyser & Water System Technician', 9, 4.88, 310, 'assets/images/technicians/tech3.jpg', 'available', 'active'),
(4, 'Muhammad Aslam Tareen', 'aslam.t@homefix.pk', '+92 321 8291033', 'Solar & Inverter Setup Specialist', 10, 4.94, 265, 'assets/images/technicians/tech4.jpg', 'available', 'active'),
(5, 'Bashir Ahmed Bugti', 'bashir.b@homefix.pk', '+92 306 9102837', 'Wall Painting & Waterproofing Expert', 10, 4.90, 215, 'assets/images/technicians/tech5.jpg', 'available', 'active'),
(6, 'Kamran Durrani', 'kamran.d@homefix.pk', '+92 303 8192044', 'General Handyman & Wall Mounting', 5, 4.87, 175, 'assets/images/technicians/tech6.jpg', 'available', 'active');

-- Bookings
INSERT INTO `bookings` (`id`, `booking_reference`, `user_id`, `service_id`, `technician_id`, `customer_name`, `customer_email`, `customer_phone`, `address`, `area`, `preferred_date`, `preferred_time`, `problem_description`, `image_attachment`, `status`, `notes`, `total_amount`) VALUES
(1, 'HFQ-892101', 2, 2, 3, 'Farhan Baloch', 'customer@homefix.pk', '+92 333 7819201', 'House 45, Street 4, Sector B', 'Jinnah Town', '2026-08-16', '10:00 AM - 12:00 PM', 'Gas geyser pilot not staying lit and burner requires maintenance.', NULL, 'confirmed', 'Customer requested morning slot.', 2200.00),
(2, 'HFQ-741902', 3, 1, 1, 'Dr. Amina Kakar', 'amina.kakar@gmail.com', '+92 312 9012345', 'Villa 12, Officers Colony', 'Cantt', '2026-08-15', '02:00 PM - 04:00 PM', 'Concealed kitchen pipe dripping under the sink cabinet, water pressure dropped.', NULL, 'in_progress', 'Technician Ghulam Rasool dispatched with copper joint fittings.', 1500.00),
(3, 'HFQ-630184', 4, 4, 2, 'Tariq Shahwani', 'tariq.s@hotmail.com', '+92 345 8823190', 'Plot 88, Near Serena Hotel', 'Zarghoon Road', '2026-08-14', '11:00 AM - 01:00 PM', 'Main 63A circuit breaker tripping whenever water motor is turned on.', NULL, 'completed', 'Completed successfully. Replaced faulty 32A breaker and rebalanced load.', 1800.00),
(4, 'HFQ-519280', 2, 8, 5, 'Farhan Baloch', 'customer@homefix.pk', '+92 333 7819201', 'House 45, Street 4, Sector B', 'Jinnah Town', '2026-08-10', '03:00 PM - 05:00 PM', 'Single bedroom accent wall painting and putty work.', NULL, 'completed', 'Completed with two coats of silk emulsion.', 6000.00),
(5, 'HFQ-410928', NULL, 11, 6, 'Sardar Mir Jan Jamali', 'mirjan.jamali@gmail.com', '+92 300 9281726', 'Bungalow 18, Phase 2', 'Model Town', '2026-08-17', '09:00 AM - 01:00 PM', 'TV wall bracket mounting and curtain rods hanging.', NULL, 'pending', 'Customer requested morning arrival.', 1500.00);

-- Reviews (Approved)
INSERT INTO `reviews` (`id`, `booking_id`, `user_id`, `service_id`, `customer_name`, `rating`, `review_text`, `status`, `created_at`) VALUES
(1, 3, 4, 4, 'Tariq Shahwani', 5, 'Engineer Zeeshan arrived exactly on time in Zarghoon Road. Fixed the breaker trip issue within 45 minutes. Super polite and professional. HomeFix is a blessing for Quetta!', 'approved', '2026-08-14 14:30:00'),
(2, 4, 2, 8, 'Farhan Baloch', 5, 'Ustad Ghulam Rasool and Bashir did an incredible job. Quality paint finish and clean workspace. Highly recommended in Quetta!', 'approved', '2026-08-10 17:45:00'),
(3, NULL, NULL, 2, 'Noman Kasi (Samungli Rd)', 5, 'Called them for Instant Geyser repair. They diagnosed the gas valve issue and fixed it immediately. 10/10 service.', 'approved', '2026-08-12 11:20:00'),
(4, NULL, NULL, 11, 'Mrs. Dr. Kakar (Cantt)', 5, 'Got two heavy LED TVs mounted on brick walls with laser alignment. Solid work.', 'approved', '2026-08-08 16:15:00');

-- Gallery (Before & After Showcase)
INSERT INTO `gallery` (`id`, `title`, `category`, `description`, `before_image`, `after_image`, `status`) VALUES
(1, 'Concealed Bathroom Pipe Leak & Fixture Overhaul', 'Plumbing', 'Replaced cracked concealed pipe lines with German PPRC piping and mounted new ceramic vanity.', 'assets/images/gallery/plumb_before.jpg', 'assets/images/gallery/plumb_after.jpg', 'active'),
(2, 'Burnt Main Distribution Board & Breakers Overhaul', 'Electrical', 'Reorganized hazardous messy house wiring with Schneider circuit breakers and digital voltage protector.', 'assets/images/gallery/db_before.jpg', 'assets/images/gallery/db_after.jpg', 'active'),
(3, 'Living Room Damp Wall Waterproofing & Silk Painting', 'Painting', 'Treated damp peeling wall with waterproof plaster and applied smooth silk emulsion finish.', 'assets/images/gallery/paint_before.jpg', 'assets/images/gallery/paint_after.jpg', 'active');

-- Contact Messages
INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `is_read`) VALUES
(1, 'Engr. Bilal Khan', 'bilal.k@gmail.com', '+92 333 9018273', 'Commercial Maintenance Contract', 'We manage an office building on Spiny Road and would like to discuss regular electrical and plumbing maintenance.', 1),
(2, 'Zubair Ahmed', 'zubair.quetta@yahoo.com', '+92 312 8829104', 'Solar Inverter Wiring Inquiry', 'Do you provide solar panel earthing and battery connection in Satellite Town?', 0);
