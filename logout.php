<?php
/**
 * HomeFix Quetta - Customer Logout Controller
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

customer_logout();

header('Location: ' . base_url('login.php?logged_out=1'));
exit;

