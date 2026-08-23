<?php
/**
 * AJAX Admin Actions Controller
 */
define('IS_AJAX', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin_auth.php';

header('Content-Type: application/json; charset=utf-8');

// Admin Permission Check
if (!is_admin_logged_in()) {
    json_response(false, 'Unauthorized. Admin privilege required.', [], 403);
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'assign_technician':
        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $techId = (int)($_POST['technician_id'] ?? 0);

        if (!$bookingId || !$techId) {
            json_response(false, 'Booking ID and Technician are required.');
        }

        $tech = Database::fetch("SELECT name FROM technicians WHERE id = ?", [$techId]);
        if (!$tech) {
            json_response(false, 'Selected technician does not exist.');
        }

        Database::execute(
            "UPDATE bookings SET technician_id = ?, status = CASE WHEN status = 'pending' THEN 'assigned' ELSE status END, updated_at = NOW() WHERE id = ?",
            [$techId, $bookingId]
        );

        json_response(true, 'Technician ' . $tech['name'] . ' assigned successfully.');
        break;

    case 'update_booking_status':
        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $status = trim($_POST['status'] ?? '');
        $allowedStatuses = ['pending', 'confirmed', 'assigned', 'in_progress', 'completed', 'cancelled'];

        if (!$bookingId || !in_array($status, $allowedStatuses)) {
            json_response(false, 'Invalid booking status.');
        }

        Database::execute("UPDATE bookings SET status = ?, updated_at = NOW() WHERE id = ?", [$status, $bookingId]);
        json_response(true, 'Booking status updated to ' . strtoupper($status) . '.');
        break;

    case 'delete_booking':
        $id = (int)($_POST['id'] ?? 0);
        Database::execute("DELETE FROM bookings WHERE id = ?", [$id]);
        json_response(true, 'Booking record deleted successfully.');
        break;

    case 'save_service':
        $id = (int)($_POST['id'] ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $duration = trim($_POST['duration'] ?? '1 - 2 Hours');
        $description = trim($_POST['description'] ?? '');
        $detailedDescription = trim($_POST['detailed_description'] ?? '');
        $includesList = trim($_POST['includes_list'] ?? '');
        $isFeatured = !empty($_POST['is_featured']) ? 1 : 0;
        $status = $_POST['status'] ?? 'active';

        if (empty($name) || !$categoryId || $price <= 0) {
            json_response(false, 'Please provide service name, category, and valid price.');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

        $imagePath = null;
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $up = handle_file_upload($_FILES['image'], 'services');
            if ($up['success']) {
                $imagePath = $up['path'];
            }
        }

        if ($id > 0) {
            // Update
            if ($imagePath) {
                Database::execute(
                    "UPDATE services SET category_id=?, name=?, slug=?, description=?, detailed_description=?, price=?, duration=?, image=?, includes_list=?, is_featured=?, status=? WHERE id=?",
                    [$categoryId, $name, $slug, $description, $detailedDescription, $price, $duration, $imagePath, $includesList, $isFeatured, $status, $id]
                );
            } else {
                Database::execute(
                    "UPDATE services SET category_id=?, name=?, slug=?, description=?, detailed_description=?, price=?, duration=?, includes_list=?, is_featured=?, status=? WHERE id=?",
                    [$categoryId, $name, $slug, $description, $detailedDescription, $price, $duration, $includesList, $isFeatured, $status, $id]
                );
            }
            json_response(true, 'Service updated successfully.');
        } else {
            // Insert
            $imagePath = $imagePath ?? 'assets/images/services/plumbing_leak.jpg';
            Database::execute(
                "INSERT INTO services (category_id, name, slug, description, detailed_description, price, duration, image, includes_list, is_featured, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [$categoryId, $name, $slug, $description, $detailedDescription, $price, $duration, $imagePath, $includesList, $isFeatured, $status]
            );
            json_response(true, 'Service created successfully.');
        }
        break;

    case 'delete_service':
        $id = (int)($_POST['id'] ?? 0);
        Database::execute("DELETE FROM services WHERE id = ?", [$id]);
        json_response(true, 'Service deleted successfully.');
        break;

    case 'save_category':
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? 'wrench');
        $status = $_POST['status'] ?? 'active';

        if (empty($name)) {
            json_response(false, 'Category name is required.');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

        $imagePath = null;
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $up = handle_file_upload($_FILES['image'], 'categories');
            if ($up['success']) $imagePath = $up['path'];
        }

        if ($id > 0) {
            if ($imagePath) {
                Database::execute("UPDATE categories SET name=?, slug=?, description=?, icon=?, image=?, status=? WHERE id=?", [$name, $slug, $description, $icon, $imagePath, $status, $id]);
            } else {
                Database::execute("UPDATE categories SET name=?, slug=?, description=?, icon=?, status=? WHERE id=?", [$name, $slug, $description, $icon, $status, $id]);
            }
            json_response(true, 'Category updated successfully.');
        } else {
            $imagePath = $imagePath ?? 'assets/images/categories/plumbing.jpg';
            Database::execute("INSERT INTO categories (name, slug, description, icon, image, status) VALUES (?, ?, ?, ?, ?, ?)", [$name, $slug, $description, $icon, $imagePath, $status]);
            json_response(true, 'Category created successfully.');
        }
        break;

    case 'delete_category':
        $id = (int)($_POST['id'] ?? 0);
        Database::execute("DELETE FROM categories WHERE id = ?", [$id]);
        json_response(true, 'Category deleted successfully.');
        break;

    case 'save_technician':
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $specialty = trim($_POST['specialty'] ?? '');
        $experience = (int)($_POST['experience_years'] ?? 3);
        $rating = (float)($_POST['rating'] ?? 4.9);
        $availability = $_POST['availability'] ?? 'available';
        $status = $_POST['status'] ?? 'active';

        if (empty($name) || empty($phone) || empty($specialty)) {
            json_response(false, 'Technician name, phone, and specialty are required.');
        }

        $imagePath = null;
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $up = handle_file_upload($_FILES['image'], 'technicians');
            if ($up['success']) $imagePath = $up['path'];
        }

        if ($id > 0) {
            if ($imagePath) {
                Database::execute("UPDATE technicians SET name=?, email=?, phone=?, specialty=?, experience_years=?, rating=?, availability=?, status=?, image=? WHERE id=?", [$name, $email, $phone, $specialty, $experience, $rating, $availability, $status, $imagePath, $id]);
            } else {
                Database::execute("UPDATE technicians SET name=?, email=?, phone=?, specialty=?, experience_years=?, rating=?, availability=?, status=? WHERE id=?", [$name, $email, $phone, $specialty, $experience, $rating, $availability, $status, $id]);
            }
            json_response(true, 'Technician updated successfully.');
        } else {
            $imagePath = $imagePath ?? 'assets/images/technicians/tech1.jpg';
            Database::execute("INSERT INTO technicians (name, email, phone, specialty, experience_years, rating, availability, status, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", [$name, $email, $phone, $specialty, $experience, $rating, $availability, $status, $imagePath]);
            json_response(true, 'Technician added successfully.');
        }
        break;

    case 'delete_technician':
        $id = (int)($_POST['id'] ?? 0);
        Database::execute("DELETE FROM technicians WHERE id = ?", [$id]);
        json_response(true, 'Technician deleted successfully.');
        break;

    case 'moderate_review':
        $reviewId = (int)($_POST['review_id'] ?? 0);
        $status = trim($_POST['status'] ?? 'approved');
        if (in_array($status, ['approved', 'hidden'])) {
            Database::execute("UPDATE reviews SET status = ? WHERE id = ?", [$status, $reviewId]);
            json_response(true, 'Review status updated to ' . ucfirst($status));
        } else if ($status === 'delete') {
            Database::execute("DELETE FROM reviews WHERE id = ?", [$reviewId]);
            json_response(true, 'Review deleted successfully.');
        }
        json_response(false, 'Invalid review action.');
        break;

    case 'delete_message':
        $id = (int)($_POST['id'] ?? 0);
        Database::execute("DELETE FROM contact_messages WHERE id = ?", [$id]);
        json_response(true, 'Message deleted successfully.');
        break;

    case 'toggle_message_read':
        $id = (int)($_POST['id'] ?? 0);
        $status = (int)($_POST['is_read'] ?? 1);
        Database::execute("UPDATE contact_messages SET is_read = ? WHERE id = ?", [$status, $id]);
        $unreadCount = (int)(Database::fetch("SELECT COUNT(*) as cnt FROM contact_messages WHERE is_read = 0")['cnt'] ?? 0);
        json_response(true, 'Message status updated.', ['unread_count' => $unreadCount]);
        break;

    case 'mark_all_messages_read':
        Database::execute("UPDATE contact_messages SET is_read = 1 WHERE is_read = 0");
        json_response(true, 'All inquiries marked as read.', ['unread_count' => 0]);
        break;

    case 'mark_booking_viewed':
        $id = (int)($_POST['id'] ?? 0);
        Database::execute("UPDATE bookings SET is_viewed = 1 WHERE id = ?", [$id]);
        $unviewedCount = (int)(Database::fetch("SELECT COUNT(*) as cnt FROM bookings WHERE is_viewed = 0")['cnt'] ?? 0);
        json_response(true, 'Booking marked as viewed.', ['unviewed_count' => $unviewedCount]);
        break;

    default:
        json_response(false, 'Invalid admin action.');
}
