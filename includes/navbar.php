<?php
/**
 * HomeFix Quetta - Main Navigation Bar
 */
$currentScript = basename($_SERVER['SCRIPT_NAME'] ?? '');
?>
<!-- Top Utility Bar (Quetta Local Contact & Emergency Dispatch) -->
<div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800 hidden sm:block">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Active in <strong class="text-white">Quetta, Balochistan</strong> (Zarghoon Rd, Jinnah Town, Cantt, Satellite Town & More)</span>
            </div>
            <div class="hidden lg:flex items-center gap-1.5 text-slate-400">
                <i data-lucide="clock" class="w-3.5 h-3.5 text-emerald-400"></i>
                <span>Mon-Sat: 8 AM - 9 PM</span>
            </div>
        </div>
        <div class="flex items-center gap-5">
            <a href="tel:<?= APP_PHONE_RAW ?>" class="hover:text-emerald-400 transition flex items-center gap-1">
                <i data-lucide="phone-call" class="w-3.5 h-3.5 text-emerald-400"></i>
                <span>Helpline: <strong><?= APP_PHONE ?></strong></span>
            </a>
            <span class="text-slate-700">|</span>
            <a href="https://wa.me/<?= APP_WHATSAPP ?>" target="_blank" class="hover:text-emerald-400 text-emerald-400 transition flex items-center gap-1">
                <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                <span>WhatsApp Dispatch</span>
            </a>
        </div>
    </div>
</div>

<!-- Main Sticky Navbar -->
<header class="sticky top-0 z-40 w-full glass-nav">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            <!-- Brand Wordmark / Logo -->
            <a href="<?= base_url('index.php') ?>" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-teal-700 to-teal-500 flex items-center justify-center text-white shadow-md shadow-teal-700/20 group-hover:scale-105 transition duration-300">
                    <i data-lucide="wrench" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-2xl font-extrabold tracking-tight font-heading text-slate-900">Home<span class="text-teal-600">Fix</span></span>
                        <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-teal-100 text-teal-800 border border-teal-200">Quetta</span>
                    </div>
                    <p class="text-[11px] text-slate-500 -mt-1 hidden sm:block">Trusted Home Services</p>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="<?= base_url('index.php') ?>" class="text-sm font-medium transition <?= ($currentScript == 'index.php') ? 'text-teal-600 font-semibold' : 'text-slate-600 hover:text-teal-600' ?>">
                    Home
                </a>
                <a href="<?= base_url('services.php') ?>" class="text-sm font-medium transition <?= ($currentScript == 'services.php' || $currentScript == 'service-details.php') ? 'text-teal-600 font-semibold' : 'text-slate-600 hover:text-teal-600' ?>">
                    Services
                </a>
                <a href="<?= base_url('index.php#how-it-works') ?>" class="text-sm font-medium text-slate-600 hover:text-teal-600 transition">
                    How It Works
                </a>
                <a href="<?= base_url('about.php') ?>" class="text-sm font-medium transition <?= ($currentScript == 'about.php') ? 'text-teal-600 font-semibold' : 'text-slate-600 hover:text-teal-600' ?>">
                    About
                </a>
                <a href="<?= base_url('tracking.php') ?>" class="text-sm font-medium transition flex items-center gap-1 <?= ($currentScript == 'tracking.php') ? 'text-teal-600 font-semibold' : 'text-slate-600 hover:text-teal-600' ?>">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-teal-500"></i>
                    <span>Track Booking</span>
                </a>
                <a href="<?= base_url('contact.php') ?>" class="text-sm font-medium transition <?= ($currentScript == 'contact.php') ? 'text-teal-600 font-semibold' : 'text-slate-600 hover:text-teal-600' ?>">
                    Contact
                </a>
            </nav>

            <!-- Action CTAs & Auth Controls -->
            <div class="hidden lg:flex items-center gap-4">
                <?php if (is_customer_logged_in()): ?>
                    <?php $customer = current_customer(); ?>
                    <!-- Logged in Customer Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center gap-2.5 py-2 px-3.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition shadow-sm">
                            <div class="w-8 h-8 rounded-lg bg-teal-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">
                                <?= strtoupper(substr($customer['name'] ?? 'C', 0, 1)) ?>
                            </div>
                            <span class="text-sm font-semibold text-slate-800 max-w-[120px] truncate"><?= e($customer['name'] ?? 'Customer') ?></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-2xl shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200 z-50">
                            <div class="px-4 py-2.5 border-b border-slate-100">
                                <p class="text-[11px] font-medium text-slate-400">Signed in as</p>
                                <p class="text-xs font-bold text-slate-800 truncate"><?= e($customer['email'] ?? '') ?></p>
                            </div>
                            
                            <a href="<?= base_url('dashboard.php?tab=bookings') ?>" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 hover:bg-slate-50 font-semibold transition">
                                <i data-lucide="calendar" class="w-4 h-4 text-teal-600"></i> My Bookings
                            </a>
                            <a href="<?= base_url('dashboard.php?tab=profile') ?>" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 hover:bg-slate-50 font-semibold transition">
                                <i data-lucide="user" class="w-4 h-4 text-slate-400"></i> Personal Information
                            </a>
                            <a href="<?= base_url('dashboard.php?tab=security') ?>" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 hover:bg-slate-50 font-semibold transition">
                                <i data-lucide="shield-check" class="w-4 h-4 text-slate-400"></i> Security & Password
                            </a>
                            
                            <div class="border-t border-slate-100 mt-1"></div>
                            <a href="<?= base_url('logout.php') ?>" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-rose-600 hover:bg-rose-50 font-semibold transition">
                                <i data-lucide="log-out" class="w-4 h-4 text-rose-500"></i> Log Out
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url('login.php') ?>" class="text-sm font-semibold text-slate-700 hover:text-teal-600 transition px-3 py-2">
                        Sign In
                    </a>
                <?php endif; ?>

                <a href="<?= base_url('booking.php') ?>" class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm">
                    <i data-lucide="calendar-plus" class="w-4 h-4"></i>
                    <span>Book a Service</span>
                </a>
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex items-center gap-2 md:hidden">
                <a href="<?= base_url('booking.php') ?>" class="btn-primary text-xs font-semibold px-3 py-2 rounded-lg">
                    Book Now
                </a>
                <button id="mobileMenuBtn" class="p-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 transition">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>

        </div>
    </div>
</header>

<!-- Mobile Slide-In Offcanvas Drawer -->
<div id="mobileMenu" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm">
    <div class="drawer-content absolute right-0 top-0 bottom-0 w-80 max-w-full bg-white p-6 shadow-2xl flex flex-col justify-between overflow-y-auto">
        <div>
            <div class="flex justify-between items-center pb-6 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-lg bg-teal-600 flex items-center justify-center text-white">
                        <i data-lucide="wrench" class="w-5 h-5"></i>
                    </div>
                    <span class="text-xl font-bold font-heading text-slate-900">Home<span class="text-teal-600">Fix</span></span>
                </div>
                <button id="closeMobileMenu" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <nav class="mt-6 flex flex-col gap-2">
                <a href="<?= base_url('index.php') ?>" class="px-4 py-3 rounded-xl font-medium text-slate-700 hover:bg-teal-50 hover:text-teal-700 transition">Home</a>
                <a href="<?= base_url('services.php') ?>" class="px-4 py-3 rounded-xl font-medium text-slate-700 hover:bg-teal-50 hover:text-teal-700 transition">Services Directory</a>
                <a href="<?= base_url('index.php#how-it-works') ?>" class="px-4 py-3 rounded-xl font-medium text-slate-700 hover:bg-teal-50 hover:text-teal-700 transition">How It Works</a>
                <a href="<?= base_url('about.php') ?>" class="px-4 py-3 rounded-xl font-medium text-slate-700 hover:bg-teal-50 hover:text-teal-700 transition">About HomeFix</a>
                <a href="<?= base_url('tracking.php') ?>" class="px-4 py-3 rounded-xl font-medium text-slate-700 hover:bg-teal-50 hover:text-teal-700 transition flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-teal-600"></i> Track Booking Status
                </a>
                <a href="<?= base_url('contact.php') ?>" class="px-4 py-3 rounded-xl font-medium text-slate-700 hover:bg-teal-50 hover:text-teal-700 transition">Contact Us</a>
            </nav>
        </div>

        <div class="pt-6 border-t border-slate-100 space-y-3">
            <?php if (is_customer_logged_in()): ?>
                <?php $cust = current_customer(); ?>
                <div class="p-3 bg-teal-50 rounded-2xl border border-teal-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-600 text-white font-bold flex items-center justify-center text-sm">
                        <?= strtoupper(substr($cust['name'] ?? 'C', 0, 1)) ?>
                    </div>
                    <div class="truncate">
                        <div class="text-xs font-bold text-slate-800 truncate"><?= e($cust['name']) ?></div>
                        <div class="text-[11px] text-slate-500 truncate"><?= e($cust['email']) ?></div>
                    </div>
                </div>
                <a href="<?= base_url('dashboard.php') ?>" class="w-full text-center block py-3 rounded-xl bg-teal-600 text-white font-semibold text-sm">Customer Dashboard</a>
                <a href="<?= base_url('logout.php') ?>" class="w-full text-center block py-2.5 rounded-xl border border-rose-200 text-rose-600 font-semibold text-sm">Log Out</a>
            <?php else: ?>
                <a href="<?= base_url('login.php') ?>" class="w-full text-center block py-3 rounded-xl bg-slate-100 text-slate-800 font-semibold text-sm">Customer Login</a>
                <a href="<?= base_url('register.php') ?>" class="w-full text-center block py-3 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm">Create Account</a>
            <?php endif; ?>
            <a href="<?= base_url('booking.php') ?>" class="w-full text-center block py-3 rounded-xl btn-primary font-semibold text-sm">Book a Technician</a>
        </div>
    </div>
</div>
