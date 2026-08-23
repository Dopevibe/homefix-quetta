<?php
/**
 * HomeFix Quetta - Admin Layout Header
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin_auth.php';
require_admin();

$adminUser = current_admin();
$adminPageTitle = $adminPageTitle ?? 'Admin Dashboard | HomeFix Quetta';

// Fetch quick badges for sidebar
$pendingBookingsCount = Database::fetch("SELECT COUNT(*) as cnt FROM bookings WHERE status IN ('pending', 'confirmed')")['cnt'] ?? 0;
$unreadMessagesCount = Database::fetch("SELECT COUNT(*) as cnt FROM contact_messages WHERE is_read = 0")['cnt'] ?? 0;
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
<body class="bg-slate-900 text-slate-100 font-sans antialiased flex h-screen overflow-hidden">
