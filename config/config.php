<?php
/**
 * HomeFix Quetta - Global Configuration & Helpers
 * Platform: Full-Stack PHP 8+ / MySQL
 * Location: Quetta, Balochistan, Pakistan
 */

if (!ob_get_level()) {
    ob_start();
}

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// App Constants
if (!defined('APP_NAME')) define('APP_NAME', 'HomeFix Quetta');
if (!defined('APP_TAGLINE')) define('APP_TAGLINE', 'Reliable Home Services, Right at Your Door');
if (!defined('APP_VERSION')) define('APP_VERSION', '1.0.0');
if (!defined('APP_LOCATION')) define('APP_LOCATION', 'Quetta, Balochistan, Pakistan');
if (!defined('APP_PHONE')) define('APP_PHONE', '+92 331 7374824');
if (!defined('APP_PHONE_RAW')) define('APP_PHONE_RAW', '+923317374824');
if (!defined('APP_WHATSAPP')) define('APP_WHATSAPP', '+923317374824');
if (!defined('APP_EMAIL')) define('APP_EMAIL', 'support@homefix.pk');
if (!defined('APP_ADDRESS')) define('APP_ADDRESS', 'New Abdul Razzaq Electric, Shop No 9/10, Block 2, Satellite Town, Quetta');
if (!defined('APP_WORKING_HOURS')) define('APP_WORKING_HOURS', 'Mon - Sat: 8:00 AM - 9:00 PM | Sun: 10:00 AM - 6:00 PM');
if (!defined('CURRENCY_SYMBOL')) define('CURRENCY_SYMBOL', 'Rs. ');

// Paths
if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__));
if (!defined('UPLOADS_PATH')) define('UPLOADS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'uploads');

// Quetta Neighborhoods Serviced
if (!defined('QUETTA_AREAS')) {
    define('QUETTA_AREAS', [
        'Zarghoon Road',
        'Jinnah Town',
        'Samungli Road',
        'Cantt',
        'Satellite Town',
        'Airport Road',
        'Model Town',
        'Brewery Road',
        'Shahbaz Town',
        'Nawa Killi',
        'Spiny Road',
        'Sariab Road',
        'Double Road',
        'Chiltan Housing Scheme',
        'Arbab Town',
        'Sirki Road',
        'Patel Bagh',
        'Raisani Road',
        'Alamdar Road',
        'Hazara Town'
    ]);
}

// Standard Time Slots
if (!defined('TIME_SLOTS')) {
    define('TIME_SLOTS', [
        '08:00 AM - 10:00 AM',
        '10:00 AM - 12:00 PM',
        '12:00 PM - 02:00 PM',
        '02:00 PM - 04:00 PM',
        '04:00 PM - 06:00 PM',
        '06:00 PM - 08:00 PM'
    ]);
}

/**
 * Get dynamic Base URL with clean extensionless routing
 */
function base_url($path = '') {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Calculate folder offset if running in subdirectory
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
    
    // Find project directory path relative to docRoot
    $appDir = str_replace('\\', '/', dirname(__DIR__));
    $relativeDir = str_replace($docRoot, '', $appDir);
    $relativeDir = trim($relativeDir, '/');
    
    $base = $protocol . $host . ($relativeDir ? '/' . $relativeDir : '');
    
    $cleanPath = ltrim((string)$path, '/');
    
    // Convert index.php or index to root
    if ($cleanPath === 'index.php' || $cleanPath === 'index') {
        $cleanPath = '';
    } elseif (preg_match('/^([a-zA-Z0-9_-]+)\.php(\?.*|#.*)?$/', $cleanPath, $matches)) {
        // Strip .php for clean URLs (e.g. services.php?category=plumbing -> services?category=plumbing)
        $cleanPath = $matches[1] . ($matches[2] ?? '');
        if ($cleanPath === 'index') {
            $cleanPath = '';
        }
    }
    
    return rtrim($base, '/') . ($cleanPath !== '' ? '/' . $cleanPath : '');
}

/**
 * Asset URL helper (preserves exact asset paths)
 */
function asset($path) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
    
    $appDir = str_replace('\\', '/', dirname(__DIR__));
    $relativeDir = str_replace($docRoot, '', $appDir);
    $relativeDir = trim($relativeDir, '/');
    
    $base = $protocol . $host . ($relativeDir ? '/' . $relativeDir : '');
    return rtrim($base, '/') . '/' . ltrim((string)$path, '/');
}

/**
 * XSS escaping helper
 */
function e($string) {
    return htmlspecialchars((string)($string ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize input
 */
function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    return trim(strip_tags((string)$data));
}

/**
 * CSRF Token Generator & Validator
 */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '');
}

/**
 * JSON Response helper for AJAX
 */
function json_response($success, $message, $data = [], $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => (bool)$success,
        'message' => $message,
        'data'    => $data
    ]);
    exit;
}

/**
 * Price Formatter in Pakistani Rupees
 */
function format_price($amount) {
    return 'Rs. ' . number_format((float)($amount ?? 0), 0);
}

/**
 * Date Formatter
 */
function format_date($date, $format = 'M d, Y') {
    if (!$date) return 'N/A';
    return date($format, strtotime($date));
}

/**
 * Generate unique Booking Reference
 * e.g. HFQ-892104
 */
function generate_booking_ref() {
    return 'HFQ-' . strtoupper(substr(uniqid(), -6));
}

/**
 * Generate HTML Status Badge
 */
function get_status_badge($status) {
    $badges = [
        'pending'     => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-300"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>Pending</span>',
        'confirmed'   => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-300"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>Confirmed</span>',
        'assigned'    => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-300"><span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span>Assigned</span>',
        'in_progress' => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-300"><span class="w-1.5 h-1.5 rounded-full bg-purple-500 mr-1.5 animate-pulse"></span>In Progress</span>',
        'completed'   => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Completed</span>',
        'cancelled'   => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-300"><span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>Cancelled</span>',
        'active'      => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Active</span>',
        'inactive'    => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700"><span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>Inactive</span>',
        'approved'    => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Approved</span>',
        'hidden'      => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Hidden</span>'
    ];

    return $badges[$status] ?? '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">' . ucfirst($status) . '</span>';
}

/**
 * Get current logged in customer
 */
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

/**
 * Get current logged in admin
 */
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

/**
 * Handle secure file uploads
 */
function handle_file_upload($file, $subfolder = 'bookings') {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'No file uploaded or upload error occurred.'];
    }

    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $maxSize = 5 * 1024 * 1024; // 5 MB

    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'File size exceeds maximum limit of 5MB.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if (!in_array($mimeType, $allowedMimes)) {
        return ['success' => false, 'error' => 'Invalid image format. Allowed formats: JPG, PNG, WEBP.'];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExts)) {
        return ['success' => false, 'error' => 'Invalid file extension.'];
    }

    $targetDir = UPLOADS_PATH . DIRECTORY_SEPARATOR . $subfolder;
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $newFilename = uniqid('hfq_', true) . '.' . $ext;
    $targetPath = $targetDir . DIRECTORY_SEPARATOR . $newFilename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $relativePath = 'uploads/' . $subfolder . '/' . $newFilename;
        return ['success' => true, 'path' => $relativePath];
    }

    return ['success' => false, 'error' => 'Failed to save uploaded file on server.'];
}
