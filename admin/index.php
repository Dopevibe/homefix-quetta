<?php
/**
 * Admin Router Index
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth.php';

if (is_admin_logged_in()) {
    header('Location: ' . base_url('admin/dashboard.php'));
} else {
    header('Location: ' . base_url('admin/login.php'));
}
exit;

