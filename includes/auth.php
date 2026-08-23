<?php
/**
 * Customer Authentication Middleware
 */
require_once __DIR__ . '/../config/config.php';

if (!function_exists('require_auth')) {
    function require_auth() {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'] ?? 'dashboard.php';
            header('Location: ' . base_url('login.php?error=auth_required'));
            exit;
        }
    }
}

if (!function_exists('current_user')) {
    function current_user() {
        if (empty($_SESSION['user_id'])) return null;
        return [
            'id'      => $_SESSION['user_id'],
            'name'    => $_SESSION['user_name'] ?? 'Customer',
            'email'   => $_SESSION['user_email'] ?? '',
            'phone'   => $_SESSION['user_phone'] ?? '',
            'role'    => $_SESSION['user_role'] ?? 'customer',
            'avatar'  => $_SESSION['user_avatar'] ?? null,
            'area'    => $_SESSION['user_area'] ?? '',
            'address' => $_SESSION['user_address'] ?? ''
        ];
    }
}
