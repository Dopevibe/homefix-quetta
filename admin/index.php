<?php
/**
 * Admin Router Index
 */
require_once __DIR__ . '/../config/config.php';

if (!empty($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'admin') {
    header('Location: ' . base_url('admin/dashboard.php'));
} else {
    header('Location: ' . base_url('admin/login.php'));
}
exit;
