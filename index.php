<?php
/**
 * HomeFix Quetta - Home Landing Page
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pageTitle = 'HomeFix Quetta | Reliable Home Services, Right at Your Door';
$pageDescription = 'Book trusted plumbers, electricians, painters, and handymen in Quetta, Balochistan. Fast, verified, and guaranteed home repairs.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch Active Categories safely
try {
    $categories = Database::fetchAll("SELECT * FROM categories WHERE status = 'active' ORDER BY sort_order ASC, name ASC") ?: [];
} catch (Exception $e) { $categories = []; }

// Fetch Featured Services safely
try {
    $featuredServices = Database::fetchAll(
        "SELECT s.*, c.name as category_name, c.icon as category_icon 
         FROM services s 
         JOIN categories c ON s.category_id = c.id 
         WHERE s.status = 'active' AND s.is_featured = 1 
         ORDER BY s.id ASC LIMIT 6"
    ) ?: [];
} catch (Exception $e) { $featuredServices = []; }

// Fetch Approved Reviews safely
try {
    $reviews = Database::fetchAll(
        "SELECT r.*, s.name as service_name 
         FROM reviews r 
         LEFT JOIN services s ON r.service_id = s.id 
         WHERE r.status = 'approved' 
         ORDER BY r.created_at DESC LIMIT 6"
    ) ?: [];
} catch (Exception $e) { $reviews = []; }

// Fetch Gallery Items safely
try {
    $gallery = Database::fetchAll("SELECT * FROM gallery WHERE status = 'active' ORDER BY id ASC LIMIT 4") ?: [];
} catch (Exception $e) { $gallery = []; }

// Fetch Technicians safely
try {
    $technicians = Database::fetchAll("SELECT * FROM technicians WHERE status = 'active' ORDER BY rating DESC LIMIT 4") ?: [];
} catch (Exception $e) { $technicians = []; }
?>

<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-slate-900 via-slate-900 to-slate-850 text-white pt-12 pb-24 lg:pt-20 lg:pb-32">
    <!-- Subtle Background Glows -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                


                <!-- Main Headline -->
                <h1 class="hero-title text-4xl sm:text-5xl lg:text-6xl font-extrabold font-heading text-white tracking-tight leading-[1.15]">
                    Your Home. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-teal-300 to-emerald-400">Our Expertise.</span>
                </h1>

                <!-- Supporting Description -->
                <p class="hero-desc text-base sm:text-lg text-slate-300 max-w-xl mx-auto lg:mx-0 font-normal leading-relaxed">
                    Reliable plumbers, electricians, painters, and handymen delivered directly to your doorstep in <strong>Quetta, Balochistan</strong> with transparent fixed pricing.
                </p>

                <!-- Search & Quick Booking Bar -->
                <div class="hero-cta bg-white/10 backdrop-blur-md p-2 sm:p-2.5 rounded-2xl border border-white/15 shadow-2xl max-w-lg mx-auto lg:mx-0">
                    <form action="<?= base_url('services.php') ?>" method="GET" class="flex flex-col sm:flex-row gap-2">
                        <div class="relative flex-1">
                            <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                            <input type="text" name="search" placeholder="What service do you need? (e.g. Plumbing, Geyser, Wiring)" class="w-full bg-white text-slate-900 placeholder-slate-400 pl-10 pr-4 py-3 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <button type="submit" class="btn-primary px-6 py-3 rounded-xl text-sm font-bold flex items-center justify-center gap-2 shrink-0">
                            <span>Find Pro</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>

                <!-- Trust Badges & Micro-proof -->
                <div class="hero-cta pt-4 flex flex-wrap items-center justify-center lg:justify-start gap-6 text-xs text-slate-300">
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-full bg-teal-500/20 text-teal-400 flex items-center justify-center">
                            <i data-lucide="check" class="w-3.5 h-3.5 stroke-[3]"></i>
                        </div>
                        <span>CNIC & Police Vetted</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-full bg-teal-500/20 text-teal-400 flex items-center justify-center">
                            <i data-lucide="check" class="w-3.5 h-3.5 stroke-[3]"></i>
                        </div>
                        <span>30-Day Guarantee</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-full bg-teal-500/20 text-teal-400 flex items-center justify-center">
                            <i data-lucide="check" class="w-3.5 h-3.5 stroke-[3]"></i>
                        </div>
                        <span>Arrives in < 45 Mins</span>
                    </div>
                </div>

            </div>

            <!-- Right Hero Visual & Floating Interactive Badges -->
            <div class="lg:col-span-5 relative">
                <div class="hero-visual relative mx-auto max-w-md lg:max-w-none">
                    <!-- Main Frame -->
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-700 bg-slate-800">
                        <img src="<?= asset('assets/images/hero_homefix.jpg') ?>" alt="HomeFix Quetta Technician" class="w-full h-auto object-cover max-h-[500px]">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6 text-white">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/80 backdrop-blur-md text-white inline-flex items-center gap-1.5 mb-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-white"></span> On-Duty Quetta Technicians
                            </span>
                            <h3 class="font-heading font-bold text-lg text-white">Equipped with Pro Tools & Spare Parts</h3>
                            <p class="text-xs text-slate-300">Ready for dispatch in Zarghoon Rd, Jinnah Town, Cantt & Satellite Town.</p>
                        </div>
                    </div>

                    <!-- Floating Card: 4.9 Rating -->
                    <div class="hero-float-card floating-element absolute -top-4 -left-6 bg-white/95 backdrop-blur-md p-3.5 rounded-2xl shadow-xl border border-slate-100 hidden sm:flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold">
                            <i data-lucide="star" class="w-5 h-5 fill-amber-500 text-amber-500"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-1">
                                <span class="font-heading font-extrabold text-slate-900 text-base">4.9 / 5.0</span>
                                <span class="text-xs text-slate-400">(1.2k+ reviews)</span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium">Top Rated in Quetta</p>
                        </div>
                    </div>

                    <!-- Floating Card: Completed Orders -->
                    <div class="hero-float-card floating-element-delayed absolute -bottom-6 -right-6 bg-white/95 backdrop-blur-md p-3.5 rounded-2xl shadow-xl border border-slate-100 hidden sm:flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center">
                            <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <div class="font-heading font-extrabold text-slate-900 text-base">15,400+</div>
                            <p class="text-xs text-slate-500 font-medium">Jobs Done Across Quetta</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- Service Categories Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <h2 class="text-3xl sm:text-4xl font-extrabold font-heading text-slate-900 mb-4">
                What Can We Fix For You Today?
            </h2>
            <p class="text-slate-500 text-sm sm:text-base">
                Select a category to browse specialized maintenance services tailored for homes and businesses in Quetta.
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($categories as $cat): ?>
                <a href="<?= base_url('services.php?category=' . urlencode($cat['slug'])) ?>" class="animate-on-scroll group relative bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-200/80 hover:border-teal-400 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-teal-100 text-teal-700 group-hover:bg-teal-600 group-hover:text-white flex items-center justify-center mb-5 transition duration-300 shadow-sm">
                            <i data-lucide="<?= e($cat['icon'] ?? 'wrench') ?>" class="w-7 h-7"></i>
                        </div>
                        <h3 class="font-heading font-bold text-lg text-slate-900 group-hover:text-teal-700 transition mb-2">
                            <?= e($cat['name']) ?>
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">
                            <?= e($cat['description']) ?>
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs font-semibold text-teal-700 group-hover:text-teal-800">
                        <span>Explore Category</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition duration-200"></i>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Why Choose HomeFix Quetta -->
<section class="py-20 bg-slate-900 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-5 space-y-6">
                <span class="text-xs uppercase font-bold tracking-wider px-3 py-1 rounded-full bg-teal-900/60 text-teal-300 border border-teal-500/30">
                    The HomeFix Advantage
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading text-white leading-tight">
                    Why Quetta Residents Trust HomeFix Over Random Local Hustlers
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed">
                    Finding dependable, punctual, and honest repairmen in Quetta used to be frustrating. HomeFix modernizes local service with standard pricing, background verification, and unconditional warranty support.
                </p>

                <div class="space-y-4 pt-2">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="user-check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-base">Verified & CNIC Checked</h4>
                            <p class="text-xs text-slate-400">All technicians are locally vetted with verified identity and police records.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="receipt" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-base">Upfront Fixed Rates</h4>
                            <p class="text-xs text-slate-400">Clear rate cards in PKR with zero hidden costs or post-job disputes.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="shield-alert" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-base">30-Day Service Guarantee</h4>
                            <p class="text-xs text-slate-400">If the issue reoccurs within 30 days, we re-service it totally free.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Counters -->
            <div class="lg:col-span-7 grid grid-cols-2 gap-6">
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-3xl p-8 text-center backdrop-blur-sm">
                    <div class="text-4xl sm:text-5xl font-extrabold font-heading text-teal-400 mb-2 counter-val" data-target="15000" data-suffix="+">0</div>
                    <h4 class="text-white font-bold text-base mb-1">Completed Bookings</h4>
                    <p class="text-xs text-slate-400">Across residential & commercial properties in Quetta</p>
                </div>
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-3xl p-8 text-center backdrop-blur-sm">
                    <div class="text-4xl sm:text-5xl font-extrabold font-heading text-amber-400 mb-2 counter-val" data-target="99" data-suffix="%">0</div>
                    <h4 class="text-white font-bold text-base mb-1">Satisfaction Rate</h4>
                    <p class="text-xs text-slate-400">Based on authentic Quetta customer feedback</p>
                </div>
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-3xl p-8 text-center backdrop-blur-sm">
                    <div class="text-4xl sm:text-5xl font-extrabold font-heading text-emerald-400 mb-2 counter-val" data-target="85" data-suffix="+">0</div>
                    <h4 class="text-white font-bold text-base mb-1">Vetted Professionals</h4>
                    <p class="text-xs text-slate-400">Master plumbers, electricians & painting experts</p>
                </div>
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-3xl p-8 text-center backdrop-blur-sm">
                    <div class="text-4xl sm:text-5xl font-extrabold font-heading text-teal-300 mb-2 counter-val" data-target="35" data-suffix=" min">0</div>
                    <h4 class="text-white font-bold text-base mb-1">Avg Response Time</h4>
                    <p class="text-xs text-slate-400">Rapid emergency dispatch across town</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- How It Works (4 Steps) -->
<section id="how-it-works" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs uppercase font-bold tracking-wider px-3 py-1 rounded-full bg-teal-100 text-teal-800 border border-teal-200">
                Simple 4-Step Process
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold font-heading text-slate-900 mt-3 mb-4">
                How HomeFix Quetta Works
            </h2>
            <p class="text-slate-500 text-sm sm:text-base">
                Booking reliable repair professionals in Quetta takes under 60 seconds.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative">
            
            <!-- Step 1 -->
            <div class="process-step bg-white rounded-2xl p-7 border border-slate-200/80 shadow-sm relative z-10 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-teal-600 text-white font-bold text-lg flex items-center justify-center mb-6 shadow-md shadow-teal-600/20">
                        1
                    </div>
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-2">Choose Service</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Select from our home repair and maintenance categories with transparent fixed pricing.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-1.5 text-xs font-semibold text-teal-600">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Instant Estimates
                </div>
            </div>

            <!-- Step 2 -->
            <div class="process-step bg-white rounded-2xl p-7 border border-slate-200/80 shadow-sm relative z-10 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-teal-600 text-white font-bold text-lg flex items-center justify-center mb-6 shadow-md shadow-teal-600/20">
                        2
                    </div>
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-2">Select Date & Time</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Pick your preferred time slot and neighborhood in Quetta (e.g. Jinnah Town, Zarghoon Rd).
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-1.5 text-xs font-semibold text-teal-600">
                    <i data-lucide="calendar" class="w-4 h-4"></i> Flexible Scheduling
                </div>
            </div>

            <!-- Step 3 -->
            <div class="process-step bg-white rounded-2xl p-7 border border-slate-200/80 shadow-sm relative z-10 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-teal-600 text-white font-bold text-lg flex items-center justify-center mb-6 shadow-md shadow-teal-600/20">
                        3
                    </div>
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-2">Pro Arrives at Door</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        A verified, background-checked specialist arrives equipped with tools and parts.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-1.5 text-xs font-semibold text-teal-600">
                    <i data-lucide="map-pin" class="w-4 h-4"></i> Live Status Tracking
                </div>
            </div>

            <!-- Step 4 -->
            <div class="process-step bg-white rounded-2xl p-7 border border-slate-200/80 shadow-sm relative z-10 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-teal-600 text-white font-bold text-lg flex items-center justify-center mb-6 shadow-md shadow-teal-600/20">
                        4
                    </div>
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-2">Pay & Review</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Inspect the completed work, pay securely in cash or bank transfer, and rate your pro.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-1.5 text-xs font-semibold text-teal-600">
                    <i data-lucide="shield-check" class="w-4 h-4"></i> 30-Day Guarantee
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Featured Popular Services -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4">
            <div>
                <span class="text-xs uppercase font-bold tracking-wider px-3 py-1 rounded-full bg-teal-50 text-teal-700 border border-teal-100">
                    Top Booked
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading text-slate-900 mt-2">
                    Popular Services in Quetta
                </h2>
            </div>
            <a href="<?= base_url('services.php') ?>" class="inline-flex items-center gap-2 text-sm font-bold text-teal-700 hover:text-teal-800 transition">
                <span>View All Services</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($featuredServices as $srv): ?>
                <div class="service-card animate-on-scroll bg-white rounded-3xl border border-slate-200/80 overflow-hidden flex flex-col justify-between shadow-sm hover:shadow-xl transition-all duration-300">
                    <div>
                        <div class="relative h-52 w-full overflow-hidden bg-slate-100">
                            <img src="<?= asset($srv['image'] ?? 'assets/images/services/plumbing_leak.jpg') ?>" alt="<?= e($srv['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                            
                            <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-semibold bg-white/95 backdrop-blur-md text-slate-800 shadow-sm flex items-center gap-1.5">
                                <i data-lucide="<?= e($srv['category_icon'] ?? 'wrench') ?>" class="w-3.5 h-3.5 text-teal-600"></i>
                                <?= e($srv['category_name']) ?>
                            </span>

                            <div class="absolute bottom-4 right-4 text-white font-mono text-xs bg-slate-900/80 px-2.5 py-1 rounded-lg backdrop-blur-sm flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3 text-teal-400"></i>
                                <?= e($srv['duration']) ?>
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="font-heading font-bold text-lg text-slate-900 mb-2">
                                <a href="<?= base_url('service-details.php?slug=' . urlencode($srv['slug'])) ?>" class="hover:text-teal-600 transition">
                                    <?= e($srv['name']) ?>
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 mb-4">
                                <?= e($srv['description']) ?>
                            </p>
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-3 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Fixed Rate</span>
                            <span class="text-xl font-extrabold text-teal-700 font-heading"><?= format_price($srv['price']) ?></span>
                        </div>
                        <a href="<?= base_url('booking.php?service=' . $srv['id']) ?>" class="btn-primary px-4 py-2.5 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 shadow-sm">
                            <span>Book Now</span>
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Before & After Work Gallery Showcase -->
<section class="py-20 bg-slate-900 text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs uppercase font-bold tracking-wider px-3 py-1 rounded-full bg-teal-900/60 text-teal-300 border border-teal-500/30">
                Real Quality Proof
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold font-heading text-white mt-3 mb-4">
                Before & After Repair Showcase
            </h2>
            <p class="text-slate-400 text-sm sm:text-base">
                Drag the interactive sliders horizontally to inspect real repair transformations completed by our Quetta technicians.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php foreach ($gallery as $gal): ?>
                <div class="bg-slate-800 border border-slate-700/80 rounded-3xl p-5 shadow-2xl space-y-4">
                    <!-- Comparison Container -->
                    <div class="img-compare-container h-64 sm:h-72 w-full relative">
                        <!-- After Image (Base) -->
                        <img src="<?= asset($gal['after_image']) ?>" alt="<?= e($gal['title']) ?> After" class="w-full h-full object-cover absolute inset-0">
                        
                        <!-- Before Image (Clipped overlay) -->
                        <div class="img-compare-before">
                            <img src="<?= asset($gal['before_image'] ?? $gal['after_image']) ?>" alt="<?= e($gal['title']) ?> Before" class="w-full h-full object-cover max-w-none">
                            <span class="absolute bottom-4 left-4 text-[10px] uppercase font-bold tracking-wider bg-rose-600/90 text-white px-2 py-0.5 rounded shadow">Before</span>
                        </div>

                        <span class="absolute bottom-4 right-4 text-[10px] uppercase font-bold tracking-wider bg-emerald-600/90 text-white px-2 py-0.5 rounded shadow">After</span>

                        <!-- Draggable Divider Line -->
                        <div class="img-compare-slider">
                            <div class="img-compare-handle">
                                <i data-lucide="move-horizontal" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="pt-2 flex justify-between items-start gap-4">
                        <div>
                            <span class="text-[11px] font-bold text-teal-400 uppercase tracking-wide"><?= e($gal['category']) ?></span>
                            <h4 class="font-heading font-bold text-base text-white"><?= e($gal['title']) ?></h4>
                            <p class="text-xs text-slate-400 mt-1"><?= e($gal['description']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Quetta Service Coverage Map (Leaflet.js) -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-5 space-y-6">
                <span class="text-xs uppercase font-bold tracking-wider px-3 py-1 rounded-full bg-teal-50 text-teal-700 border border-teal-100">
                    Local Quetta Coverage
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading text-slate-900 leading-tight">
                    Covering Every Neighborhood in Quetta Valley
                </h2>
                <p class="text-slate-600 text-sm leading-relaxed">
                    With mobile field units stationed across key sectors, our licensed technicians reach your doorstep anywhere in Quetta in under 45 minutes.
                </p>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <?php 
                    $previewAreas = ['Zarghoon Road', 'Jinnah Town', 'Cantt Area', 'Satellite Town', 'Samungli Road', 'Model Town', 'Airport Road', 'Spiny & Brewery'];
                    foreach ($previewAreas as $a):
                    ?>
                        <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-teal-600 shrink-0"></i>
                            <span class="text-xs font-semibold text-slate-800"><?= $a ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="pt-4">
                    <a href="<?= base_url('booking.php') ?>" class="btn-primary inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm">
                        <i data-lucide="calendar-check" class="w-4 h-4"></i>
                        <span>Book in Your Neighborhood</span>
                    </a>
                </div>
            </div>

            <!-- Leaflet Map Container -->
            <div class="lg:col-span-7">
                <div class="rounded-3xl overflow-hidden shadow-xl border border-slate-200 bg-slate-100 relative">
                    <div id="quetta-coverage-map" class="w-full h-96 sm:h-[420px] z-10"></div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Customer Testimonials -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs uppercase font-bold tracking-wider px-3 py-1 rounded-full bg-teal-100 text-teal-800 border border-teal-200">
                Customer Stories
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold font-heading text-slate-900 mt-3 mb-4">
                What Quetta Residents Are Saying
            </h2>
            <p class="text-slate-500 text-sm sm:text-base">
                Real feedback from verified homeowners and businesses across Balochistan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($reviews as $rev): ?>
                <div class="animate-on-scroll bg-white rounded-3xl p-7 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                    <div>
                        <!-- Stars -->
                        <div class="flex items-center gap-1 mb-4 text-amber-400">
                            <?php for ($i = 0; $i < (int)$rev['rating']; $i++): ?>
                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed italic mb-6">
                            "<?= e($rev['review_text']) ?>"
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <h4 class="font-heading font-bold text-sm text-slate-900"><?= e($rev['customer_name']) ?></h4>
                            <p class="text-[11px] text-teal-600 font-medium"><?= e($rev['service_name'] ?? 'Home Maintenance') ?></p>
                        </div>
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-bold">
                            Verified
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Final Strong CTA -->
<section class="py-20 bg-gradient-to-r from-teal-900 via-teal-800 to-slate-900 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h2 class="text-3xl sm:text-5xl font-extrabold font-heading text-white tracking-tight mb-4 max-w-2xl mx-auto">
            Need a Trusted Repair Professional Right Now?
        </h2>
        <p class="text-teal-100 text-base sm:text-lg max-w-xl mx-auto mb-8 font-normal">
            Book online in seconds, track your technician live on a map, and enjoy our 30-day quality guarantee.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="<?= base_url('booking.php') ?>" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white text-teal-900 font-extrabold text-base shadow-2xl hover:bg-teal-50 transition transform hover:-translate-y-1">
                Book a Service in Quetta
            </a>
            <a href="tel:<?= APP_PHONE_RAW ?>" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-slate-900/60 border border-white/20 text-white font-bold text-base hover:bg-slate-900 transition flex items-center justify-center gap-2">
                <i data-lucide="phone-call" class="w-5 h-5 text-teal-300"></i>
                <span>Helpline: <?= APP_PHONE ?></span>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
