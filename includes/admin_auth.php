<?php
/**
 * Admin Authentication Middleware
 */
require_once __DIR__ . '/../config/config.php';

if (!function_exists('require_admin')) {
    function require_admin() {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: ' . base_url('admin/login.php?error=admin_required'));
            exit;
        }
    }
}

if (!function_exists('current_admin')) {
    function current_admin() {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            return null;
        }
        return [
            'id'    => $_SESSION['user_id'],
            'name'  => $_SESSION['user_name'] ?? 'Admin',
            'email' => $_SESSION['user_email'] ?? '',
            'role'  => $_SESSION['user_role'] ?? 'admin'
        ];
    }
}
