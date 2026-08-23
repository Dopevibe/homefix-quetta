<?php
/**
 * HomeFix Quetta - Global Header
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = $pageTitle ?? 'HomeFix Quetta | Trusted Local Home Services in Quetta';
$pageDescription = $pageDescription ?? 'Book verified plumbing, electrical, painting and handyman services in Quetta, Balochistan with HomeFix. Fast doorstep service.';
$currentUser = current_customer();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Primary SEO Meta Tags -->
    <title><?= e($pageTitle) ?></title>
    <meta name="title" content="<?= e($pageTitle) ?>">
    <meta name="description" content="<?= e($pageDescription) ?>">
    <meta name="keywords" content="HomeFix Quetta, Plumber Quetta, Electrician Quetta, Painter Quetta, Handyman Quetta, Home Services Balochistan, Zarghoon Road, Jinnah Town, Samungli Road, Cantt, Satellite Town">
    <meta name="author" content="HomeFix Quetta">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= base_url() ?>">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($pageDescription) ?>">
    <meta property="og:image" content="<?= asset('assets/images/hero_homefix.jpg') ?>">

    <!-- Favicon / Theme -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%230D9488'><path d='M12 2L2 12h3v8h6v-6h2v6h6v-8h3L12 2z'/></svg>">
    <meta name="theme-color" content="#0D9488">

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

    <!-- Leaflet.js Map CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- GSAP & ScrollTrigger CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Custom Design System CSS -->
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">
