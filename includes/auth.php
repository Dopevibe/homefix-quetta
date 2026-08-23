<?php
/**
 * Customer Authentication Middleware - Isolated Namespace
 * HomeFix Quetta
 */
require_once __DIR__ . '/../config/config.php';

if (!function_exists('is_customer_logged_in')) {
    function is_customer_logged_in() {
        return !empty($_SESSION['customer']['id']);
    }
}

if (!function_exists('current_customer')) {
    function current_customer() {
        if (!is_customer_logged_in()) {
            return null;
        }
        return $_SESSION['customer'];
    }
}

// Backward-compatible alias
if (!function_exists('current_user')) {
    function current_user() {
        return current_customer();
    }
}

if (!function_exists('require_customer')) {
    function require_customer($redirectParam = 'dashboard.php') {
        if (!is_customer_logged_in()) {
            $_SESSION['customer_redirect_url'] = $_SERVER['REQUEST_URI'] ?? $redirectParam;
            header('Location: ' . base_url('login.php?notice=auth_required'));
            exit;
        }
    }
}

if (!function_exists('require_auth')) {
    function require_auth() {
        require_customer();
    }
}

if (!function_exists('customer_logout')) {
    function customer_logout() {
        if (isset($_SESSION['customer'])) {
            unset($_SESSION['customer']);
        }
        if (isset($_SESSION['customer_redirect_url'])) {
            unset($_SESSION['customer_redirect_url']);
        }
    }
}

