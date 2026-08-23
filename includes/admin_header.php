<?php
/**
 * HomeFix Quetta - Admin Layout Header
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin_auth.php';
require_admin();

// Single Authoritative Source of Truth for Admin Profile
$adminUser = current_admin();
if (!empty($_SESSION['user_id'])) {
    try {
        $dbAdmin = Database::fetch("SELECT id, name, email, phone, role, status, avatar, created_at, updated_at FROM users WHERE id = ?", [$_SESSION['user_id']]);
        if ($dbAdmin) {
            $adminUser = $dbAdmin;
            $_SESSION['user_name'] = $dbAdmin['name'];
            $_SESSION['user_email'] = $dbAdmin['email'];
            $_SESSION['user_phone'] = $dbAdmin['phone'];
            $_SESSION['user_role'] = $dbAdmin['role'];
            $_SESSION['user_avatar'] = $dbAdmin['avatar'];
        }
    } catch (Exception $e) {
        error_log('Admin user sync error: ' . $e->getMessage());
    }
}

$adminPageTitle = $adminPageTitle ?? 'Admin Dashboard | HomeFix Quetta';

// Dynamic Real-time Badge Counters
try {
    $pendingBookingsCount = (int)(Database::fetch("SELECT COUNT(*) as cnt FROM bookings WHERE is_viewed = 0")['cnt'] ?? 0);
} catch (Exception $e) {
    try {
        Database::execute("ALTER TABLE bookings ADD COLUMN is_viewed TINYINT(1) DEFAULT 0");
        $pendingBookingsCount = (int)(Database::fetch("SELECT COUNT(*) as cnt FROM bookings WHERE is_viewed = 0")['cnt'] ?? 0);
    } catch (Exception $ex) {
        $pendingBookingsCount = (int)(Database::fetch("SELECT COUNT(*) as cnt FROM bookings WHERE status = 'pending'")['cnt'] ?? 0);
    }
}

try {
    $unreadMessagesCount = (int)(Database::fetch("SELECT COUNT(*) as cnt FROM contact_messages WHERE is_read = 0")['cnt'] ?? 0);
} catch (Exception $e) {
    $unreadMessagesCount = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($adminPageTitle) ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              teal: {
                50: '#F0FDFA',
                100: '#CCFBF1',
                200: '#99F6E4',
                300: '#5EEAD4',
                400: '#2DD4BF',
                500: '#14B8A6',
                600: '#0D9488',
                700: '#0F766E',
                800: '#115E59',
                900: '#134E4A',
              },
              slate: {
                850: '#151E2E',
                950: '#0B0F17',
              }
            },
            fontFamily: {
              heading: ['Outfit', 'sans-serif'],
              sans: ['Plus Jakarta Sans', 'sans-serif'],
            }
          }
        }
      }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Design System CSS -->
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
</head>
<body class="bg-slate-900 text-slate-100 font-sans antialiased flex h-screen h-[100dvh] overflow-hidden">
