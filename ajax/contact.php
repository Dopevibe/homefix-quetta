<?php
/**
 * AJAX Contact Form Controller
 */
define('IS_AJAX', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    json_response(false, 'Please fill in all required fields (Name, Email, Subject, Message).');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response(false, 'Please enter a valid email address.');
}

try {
    Database::execute(
        "INSERT INTO contact_messages (name, email, phone, subject, message, is_read) VALUES (?, ?, ?, ?, ?, 0)",
        [$name, $email, $phone, $subject, $message]
    );

    json_response(true, 'Your message has been delivered to HomeFix Quetta. Our support team will get in touch shortly.');
} catch (Exception $e) {
    error_log('Contact Form Error: ' . $e->getMessage());
    json_response(false, 'Unable to send message at this time. Please try calling our helpline.');
}
