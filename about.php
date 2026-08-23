<?php
/**
 * HomeFix Quetta - About Us Page
 */
$pageTitle = 'About Us | HomeFix Quetta - Built for Balochistan';
$pageDescription = 'Discover how HomeFix Quetta is transforming home repair and maintenance services across Quetta, Balochistan through verified technicians, upfront pricing and reliability.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Technicians count & Services count
$totalServices = Database::fetch("SELECT COUNT(*) as cnt FROM services WHERE status = 'active'")['cnt'] ?? 16;
$totalTechs = Database::fetch("SELECT COUNT(*) as cnt FROM technicians WHERE status = 'active'")['cnt'] ?? 8;
$totalBookings = Database::fetch("SELECT COUNT(*) as cnt FROM bookings")['cnt'] ?? 1500;
?>

<!-- About Hero -->
<section class="bg-gradient-to-b from-slate-900 via-slate-900 to-slate-850 text-white py-16 lg:py-24 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-4xl sm:text-5xl font-extrabold font-heading text-white tracking-tight max-w-3xl mx-auto leading-tight">
            Setting a New Standard of Trust for Home Services in Quetta
        </h1>
        <p class="text-slate-300 text-base sm:text-lg max-w-2xl mx-auto mt-4 leading-relaxed font-normal">
            We are on a mission to eliminate price gouging, unpunctuality, and poor craftsmanship from home maintenance across Quetta valley.
        </p>
    </div>
</section>

<!-- Story & Vision -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-6 space-y-6">
                <span class="text-xs uppercase font-bold tracking-wider px-3 py-1 rounded-full bg-teal-50 text-teal-700 border border-teal-100">
                    Our Story
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading text-slate-900 leading-tight">
                    Built Locally to Solve Everyday Quetta Headaches
                </h2>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                    For years, families and businesses in Quetta relied on informal roadside labor or word-of-mouth recommendations for electrical wiring, geyser repair, and plumbing leaks. The result was often recurring faults, unexpected charges, and safety concerns.
                </p>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                    <strong>HomeFix Quetta</strong> was launched to provide an organized, professional alternative. We screen every specialist with government CNIC verification, provide structured technical toolkits, enforce fixed price lists in PKR, and back all completed jobs with our signature <strong>30-Day Workmanship Guarantee</strong>.
                </p>

                <div class="grid grid-cols-2 gap-4 pt-4">
                    <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl">
                        <div class="text-2xl font-extrabold text-teal-700 font-heading">100%</div>
                        <div class="text-xs font-semibold text-slate-800 mt-1">CNIC & Police Vetted</div>
                        <p class="text-[11px] text-slate-500 mt-0.5">Strict safety standards</p>
                    </div>
                    <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl">
                        <div class="text-2xl font-extrabold text-teal-700 font-heading">30 Days</div>
                        <div class="text-xs font-semibold text-slate-800 mt-1">Service Guarantee</div>
                        <p class="text-[11px] text-slate-500 mt-0.5">Zero hassle re-service</p>
                    </div>
                </div>
            </div>

            <!-- Visual Feature -->
            <div class="lg:col-span-6">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-200 bg-slate-100">
                    <img src="<?= asset('assets/images/hero_homefix.jpg') ?>" alt="HomeFix Quetta Team" class="w-full h-auto object-cover max-h-[480px]">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-transparent to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white">
                        <h4 class="font-heading font-bold text-lg text-white">Headquartered in Satellite Town, Quetta</h4>
                        <p class="text-xs text-slate-300">Central dispatch center serving Jinnah Town, Cantt, Zarghoon Road, Samungli, and all surrounding areas.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Animated Stats Counter Section -->
<section class="py-16 bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="p-6 bg-slate-800/60 border border-slate-700 rounded-3xl backdrop-blur-sm">
                <div class="text-4xl sm:text-5xl font-extrabold font-heading text-teal-400 mb-2 counter-val" data-target="<?= $totalServices ?>" data-suffix="+"><?= $totalServices ?>+</div>
                <h4 class="text-white font-bold text-sm">Services Offered</h4>
                <p class="text-xs text-slate-400 mt-1">Plumbing, Painting, Solar, Electrical & More</p>
            </div>
            <div class="p-6 bg-slate-800/60 border border-slate-700 rounded-3xl backdrop-blur-sm">
                <div class="text-4xl sm:text-5xl font-extrabold font-heading text-amber-400 mb-2 counter-val" data-target="15000" data-suffix="+">15,000+</div>
                <h4 class="text-white font-bold text-sm">Completed Jobs</h4>
                <p class="text-xs text-slate-400 mt-1">Residential & Commercial in Quetta</p>
            </div>
            <div class="p-6 bg-slate-800/60 border border-slate-700 rounded-3xl backdrop-blur-sm">
                <div class="text-4xl sm:text-5xl font-extrabold font-heading text-emerald-400 mb-2 counter-val" data-target="85" data-suffix="+">85+</div>
                <h4 class="text-white font-bold text-sm">Verified Technicians</h4>
                <p class="text-xs text-slate-400 mt-1">Master tradesmen across disciplines</p>
            </div>
            <div class="p-6 bg-slate-800/60 border border-slate-700 rounded-3xl backdrop-blur-sm">
                <div class="text-4xl sm:text-5xl font-extrabold font-heading text-teal-300 mb-2 counter-val" data-target="99" data-suffix="%">99%</div>
                <h4 class="text-white font-bold text-sm">Happy Customers</h4>
                <p class="text-xs text-slate-400 mt-1">5-star rated local service</p>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs uppercase font-bold tracking-wider px-3 py-1 rounded-full bg-teal-100 text-teal-800 border border-teal-200">
                Our Principles
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold font-heading text-slate-900 mt-3 mb-4">
                What We Stand For
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center">
                    <i data-lucide="shield" class="w-7 h-7"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-slate-900">Uncompromising Safety</h3>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    We know that welcoming a technician into your home requires complete trust. Every staff member undergoes thorough verification, identification checks, and code-of-conduct training.
                </p>
            </div>
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center">
                    <i data-lucide="clock" class="w-7 h-7"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-slate-900">Punctuality & Speed</h3>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    When an emergency water leak or short circuit strikes, delays are unacceptable. Our decentralized mobile fleet reaches any neighborhood in Quetta within 45 minutes of booking.
                </p>
            </div>
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <i data-lucide="scale" class="w-7 h-7"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-slate-900">Fixed & Fair Pricing</h3>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    No bargaining, no surprise additions. You know the exact cost before our professional starts, with formal digital receipts and simple payment methods.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-16 bg-white border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
        <h2 class="text-3xl font-extrabold font-heading text-slate-900">
            Ready to experience effortless home maintenance in Quetta?
        </h2>
        <div class="flex justify-center gap-4">
            <a href="<?= base_url('booking.php') ?>" class="btn-primary px-8 py-3.5 rounded-xl font-bold text-sm">
                Book a Service Now
            </a>
            <a href="<?= base_url('contact.php') ?>" class="px-6 py-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-sm transition">
                Contact Our Team
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
