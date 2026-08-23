<?php
/**
 * Admin Authentication Middleware - Isolated Namespace
 * HomeFix Quetta Control Panel
 */
require_once __DIR__ . '/../config/config.php';

if (!function_exists('is_admin_logged_in')) {
    function is_admin_logged_in() {
        return !empty($_SESSION['admin']['id']) && (($_SESSION['admin']['role'] ?? '') === 'admin');
    }
}

if (!function_exists('current_admin')) {
    function current_admin() {
        if (!is_admin_logged_in()) {
            return null;
        }
        return $_SESSION['admin'];
    }
}

if (!function_exists('require_admin')) {
    function require_admin() {
        if (!is_admin_logged_in()) {
            $_SESSION['admin_redirect_url'] = $_SERVER['REQUEST_URI'] ?? 'admin/dashboard.php';
            header('Location: ' . base_url('admin/login.php?error=admin_required'));
            exit;
        }
    }
}

if (!function_exists('admin_logout')) {
    function admin_logout() {
        if (isset($_SESSION['admin'])) {
            unset($_SESSION['admin']);
        }
        if (isset($_SESSION['admin_redirect_url'])) {
            unset($_SESSION['admin_redirect_url']);
        }
    }
}

