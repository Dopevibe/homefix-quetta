<?php
/**
 * AJAX Bookings Controller
 */
define('IS_AJAX', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? $_GET['action'] ?? 'create';

switch ($action) {
    case 'create':
        $serviceId = (int)($_POST['service_id'] ?? 0);
        $name = trim($_POST['customer_name'] ?? '');
        $email = trim($_POST['customer_email'] ?? '');
        $phone = trim($_POST['customer_phone'] ?? '');
        $area = trim($_POST['area'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $date = trim($_POST['preferred_date'] ?? '');
        $time = trim($_POST['preferred_time'] ?? '');
        $problem = trim($_POST['problem_description'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        // Validation
        if (!$serviceId || empty($name) || empty($phone) || empty($area) || empty($address) || empty($date) || empty($time) || empty($problem)) {
            json_response(false, 'Please fill in all required booking fields.');
        }

        // Validate service
        $service = Database::fetch("SELECT s.*, c.name as category_name FROM services s JOIN categories c ON s.category_id = c.id WHERE s.id = ? AND s.status = 'active'", [$serviceId]);
        if (!$service) {
            json_response(false, 'Selected service is currently unavailable.');
        }

        // Validate date (must not be past date)
        if (strtotime($date) < strtotime(date('Y-m-d'))) {
            json_response(false, 'Preferred date cannot be in the past.');
        }

        // Check if user is logged in
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId && !empty($email)) {
            // Find existing user by email or leave as guest booking
            $existingUser = Database::fetch("SELECT id FROM users WHERE email = ?", [$email]);
            if ($existingUser) {
                $userId = $existingUser['id'];
            }
        }

        // Handle Image Attachment
        $imageAttachment = null;
        if (!empty($_FILES['problem_image']) && $_FILES['problem_image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = handle_file_upload($_FILES['problem_image'], 'bookings');
            if ($uploadResult['success']) {
                $imageAttachment = $uploadResult['path'];
            }
        }

        // Auto-assign matching technician in Quetta if available
        $tech = Database::fetch(
            "SELECT id FROM technicians WHERE (specialty LIKE ? OR specialty LIKE ?) AND availability = 'available' AND status = 'active' ORDER BY rating DESC LIMIT 1",
            ['%' . $service['category_name'] . '%', '%' . $service['name'] . '%']
        );
        $technicianId = $tech['id'] ?? null;

        $bookingRef = generate_booking_ref();
        $totalAmount = $service['price'];

        try {
            Database::execute(
                "INSERT INTO bookings (booking_reference, user_id, service_id, technician_id, customer_name, customer_email, customer_phone, address, area, preferred_date, preferred_time, problem_description, image_attachment, status, notes, total_amount) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?)",
                [
                    $bookingRef,
                    $userId,
                    $serviceId,
                    $technicianId,
                    $name,
                    $email,
                    $phone,
                    $address,
                    $area,
                    $date,
                    $time,
                    $problem,
                    $imageAttachment,
                    $notes,
                    $totalAmount
                ]
            );

            json_response(true, 'Booking confirmed successfully!', [
                'booking_reference' => $bookingRef,
                'service_name'      => $service['name'],
                'date'              => $date,
                'time'              => $time,
                'area'              => $area
            ]);
        } catch (Exception $e) {
            error_log('Booking Error: ' . $e->getMessage());
            json_response(false, 'Unable to confirm booking at this time. Please check your information and try again.');
        }
        break;

    case 'cancel':
        if (empty($_SESSION['user_id'])) {
            json_response(false, 'Please sign in to cancel your booking.', [], 401);
        }

        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $userId = $_SESSION['user_id'];

        $booking = Database::fetch("SELECT * FROM bookings WHERE id = ? AND user_id = ?", [$bookingId, $userId]);
        if (!$booking) {
            json_response(false, 'Booking not found or access denied.');
        }

        if (in_array($booking['status'], ['completed', 'cancelled'])) {
            json_response(false, 'This booking cannot be cancelled in its current state.');
        }

        Database::execute("UPDATE bookings SET status = 'cancelled', notes = CONCAT(IFNULL(notes,''), '\n[Cancelled by customer on ', NOW(), ']') WHERE id = ?", [$bookingId]);

        json_response(true, 'Booking has been cancelled.');
        break;

    case 'get_details':
        $ref = trim($_GET['ref'] ?? '');
        if (empty($ref)) {
            json_response(false, 'Booking reference is required.');
        }

        $booking = Database::fetch(
            "SELECT b.*, s.name as service_name, s.price as service_price, s.duration as service_duration, c.name as category_name,
                    t.name as technician_name, t.phone as technician_phone, t.rating as technician_rating, t.image as technician_image, t.specialty as technician_specialty
             FROM bookings b
             JOIN services s ON b.service_id = s.id
             JOIN categories c ON s.category_id = c.id
             LEFT JOIN technicians t ON b.technician_id = t.id
             WHERE b.booking_reference = ?",
            [$ref]
        );

        if (!$booking) {
            json_response(false, 'No booking found matching reference: ' . htmlspecialchars($ref));
        }

        json_response(true, 'Booking found', $booking);
        break;

    default:
        json_response(false, 'Invalid bookings action.');
}
