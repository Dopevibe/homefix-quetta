<?php
/**
 * AJAX Customer Authentication Controller
 */
define('IS_AJAX', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'register':
        $name = trim($_POST['name'] ?? '');
        $email = trim(strtolower($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '');
        $area = trim($_POST['area'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email) || empty($phone) || empty($password)) {
            json_response(false, 'Please fill in all required fields.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            json_response(false, 'Please enter a valid email address.');
        }

        if (strlen($password) < 6) {
            json_response(false, 'Password must be at least 6 characters long.');
        }

        if ($password !== $confirmPassword) {
            json_response(false, 'Passwords do not match.');
        }

        // Check duplicate email
        $existing = Database::fetch("SELECT id FROM users WHERE email = ?", [$email]);
        if ($existing) {
            json_response(false, 'An account with this email address already exists. Please sign in.');
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        try {
            Database::execute(
                "INSERT INTO users (name, email, phone, password, role, status, area, address) VALUES (?, ?, ?, ?, 'customer', 'active', ?, ?)",
                [$name, $email, $phone, $hashedPassword, $area, $address]
            );
            $userId = Database::lastInsertId();

            // Set session
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_phone'] = $phone;
            $_SESSION['user_role'] = 'customer';

            json_response(true, 'Welcome to HomeFix Quetta! Your account was created successfully.', [
                'redirect' => base_url('dashboard.php')
            ]);
        } catch (Exception $e) {
            error_log('Registration Error: ' . $e->getMessage());
            json_response(false, 'Unable to create account at this time. Please check your information and try again.');
        }
        break;

    case 'login':
        $email = trim(strtolower($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            json_response(false, 'Please provide both email and password.');
        }

        $user = Database::fetch("SELECT * FROM users WHERE email = ?", [$email]);

        if (!$user || !password_verify($password, $user['password'])) {
            json_response(false, 'Invalid email or password. Please try again.');
        }

        if ($user['status'] !== 'active') {
            json_response(false, 'Your account is inactive or suspended. Please contact support.');
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_phone'] = $user['phone'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_avatar'] = $user['avatar'];

        $redirectUrl = ($user['role'] === 'admin') ? base_url('admin/dashboard.php') : base_url('dashboard.php');
        if (!empty($_SESSION['redirect_url'])) {
            $redirectUrl = $_SESSION['redirect_url'];
            unset($_SESSION['redirect_url']);
        }

        json_response(true, 'Login successful. Welcome back, ' . $user['name'] . '!', [
            'redirect' => $redirectUrl,
            'role' => $user['role']
        ]);
        break;

    case 'update_profile':
        if (empty($_SESSION['user_id'])) {
            json_response(false, 'Unauthorized. Please sign in.', [], 401);
        }

        $userId = $_SESSION['user_id'];
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $area = trim($_POST['area'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if (empty($name) || empty($phone)) {
            json_response(false, 'Name and phone are required.');
        }

        $avatarPath = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $upload = handle_file_upload($_FILES['avatar'], 'avatars');
            if (!$upload['success']) {
                json_response(false, $upload['error']);
            }
            $avatarPath = str_replace('uploads/', '', $upload['path']);
        }

        if ($avatarPath) {
            Database::execute(
                "UPDATE users SET name = ?, phone = ?, area = ?, address = ?, avatar = ? WHERE id = ?",
                [$name, $phone, $area, $address, $avatarPath, $userId]
            );
            $_SESSION['user_avatar'] = $avatarPath;
        } else {
            Database::execute(
                "UPDATE users SET name = ?, phone = ?, area = ?, address = ? WHERE id = ?",
                [$name, $phone, $area, $address, $userId]
            );
        }

        $_SESSION['user_name'] = $name;
        $_SESSION['user_phone'] = $phone;

        $newAvatarUrl = $avatarPath ? asset('uploads/' . $avatarPath) : null;

        json_response(true, 'Profile updated successfully.', [
            'avatar_url' => $newAvatarUrl
        ]);
        break;

    case 'change_password':
        if (empty($_SESSION['user_id'])) {
            json_response(false, 'Unauthorized.', [], 401);
        }

        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword)) {
            json_response(false, 'Please fill in all password fields.');
        }

        if (strlen($newPassword) < 6) {
            json_response(false, 'New password must be at least 6 characters.');
        }

        if ($newPassword !== $confirmPassword) {
            json_response(false, 'New passwords do not match.');
        }

        $user = Database::fetch("SELECT password FROM users WHERE id = ?", [$userId]);
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            json_response(false, 'Current password is incorrect.');
        }

        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        Database::execute("UPDATE users SET password = ? WHERE id = ?", [$newHash, $userId]);

        json_response(true, 'Password changed successfully.');
        break;

    default:
        json_response(false, 'Invalid auth action.');
}
