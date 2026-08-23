<?php
/**
 * HomeFix Quetta - Admin Logout Controller
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth.php';

admin_logout();

header('Location: ' . base_url('admin/login.php?logged_out=1'));
exit;

