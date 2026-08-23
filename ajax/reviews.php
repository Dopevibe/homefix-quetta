<?php
/**
 * AJAX Reviews Submission Controller
 */
define('IS_AJAX', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user_id'])) {
    json_response(false, 'Please sign in to submit a review.', [], 401);
}

$bookingId = (int)($_POST['booking_id'] ?? 0);
$serviceId = (int)($_POST['service_id'] ?? 0);
$rating = (int)($_POST['rating'] ?? 5);
$reviewText = trim($_POST['review_text'] ?? '');
$userId = $_SESSION['user_id'];
$customerName = $_SESSION['user_name'] ?? 'Customer';

if (!$bookingId || empty($reviewText)) {
    json_response(false, 'Please select a rating and write your review.');
}

if ($rating < 1 || $rating > 5) {
    $rating = 5;
}

// Verify booking belongs to user and is completed
$booking = Database::fetch("SELECT * FROM bookings WHERE id = ? AND user_id = ?", [$bookingId, $userId]);
if (!$booking) {
    json_response(false, 'Booking not found or permission denied.');
}

if ($booking['status'] !== 'completed') {
    json_response(false, 'Reviews can only be submitted for completed services.');
}

// Check if review already submitted for this booking
$existingReview = Database::fetch("SELECT id FROM reviews WHERE booking_id = ?", [$bookingId]);
if ($existingReview) {
    json_response(false, 'You have already submitted a review for this completed booking.');
}

if (!$serviceId) {
    $serviceId = $booking['service_id'];
}

try {
    Database::execute(
        "INSERT INTO reviews (booking_id, user_id, service_id, customer_name, rating, review_text, status) VALUES (?, ?, ?, ?, ?, ?, 'approved')",
        [$bookingId, $userId, $serviceId, $customerName, $rating, $reviewText]
    );

    json_response(true, 'Thank you! Your review has been published.');
} catch (Exception $e) {
    error_log('Review Submission Error: ' . $e->getMessage());
    json_response(false, 'Unable to submit review at this time. Please try again.');
}
