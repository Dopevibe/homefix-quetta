<?php
/**
 * HomeFix Quetta - Single Service Details Page
 */
$slug = trim($_GET['slug'] ?? '');
$id = (int)($_GET['id'] ?? 0);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

if (!empty($slug)) {
    $service = Database::fetch(
        "SELECT s.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon 
         FROM services s 
         JOIN categories c ON s.category_id = c.id 
         WHERE s.slug = ? AND s.status = 'active'",
        [$slug]
    );
} else if ($id > 0) {
    $service = Database::fetch(
        "SELECT s.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon 
         FROM services s 
         JOIN categories c ON s.category_id = c.id 
         WHERE s.id = ? AND s.status = 'active'",
        [$id]
    );
} else {
    header('Location: ' . base_url('services.php'));
    exit;
}

if (!$service) {
    header('Location: ' . base_url('services.php'));
    exit;
}

$pageTitle = $service['name'] . ' in Quetta | HomeFix Quetta';
$pageDescription = $service['description'] . ' - Fixed rate ' . format_price($service['price']) . '. Verified doorstep service in Quetta, Balochistan.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch Reviews for this service
$reviews = Database::fetchAll(
    "SELECT * FROM reviews WHERE service_id = ? AND status = 'approved' ORDER BY created_at DESC",
    [$service['id']]
);

// Fetch Related Services
$relatedServices = Database::fetchAll(
    "SELECT s.*, c.name as category_name, c.icon as category_icon 
     FROM services s 
     JOIN categories c ON s.category_id = c.id 
     WHERE s.category_id = ? AND s.id != ? AND s.status = 'active' LIMIT 3",
    [$service['category_id'], $service['id']]
);
?>

<!-- Service Header Banner -->
<section class="bg-gradient-to-b from-slate-900 via-slate-900 to-slate-850 text-white py-12 lg:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-slate-400 mb-6">
            <a href="<?= base_url('index.php') ?>" class="hover:text-teal-400">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="<?= base_url('services.php') ?>" class="hover:text-teal-400">Services</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="<?= base_url('services.php?category=' . $service['category_slug']) ?>" class="hover:text-teal-400"><?= e($service['category_name']) ?></a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-slate-200 truncate max-w-xs"><?= e($service['name']) ?></span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-8 space-y-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-teal-500/20 text-teal-300 border border-teal-500/30">
                    <i data-lucide="<?= e($service['category_icon'] ?? 'wrench') ?>" class="w-3.5 h-3.5"></i>
                    <?= e($service['category_name']) ?>
                </span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold font-heading text-white tracking-tight leading-tight">
                    <?= e($service['name']) ?>
                </h1>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-2xl">
                    <?= e($service['description']) ?>
                </p>

                <div class="flex flex-wrap items-center gap-6 pt-2 text-xs text-slate-300">
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="clock" class="w-4 h-4 text-teal-400"></i>
                        <span>Duration: <strong><?= e($service['duration']) ?></strong></span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="shield-check" class="w-4 h-4 text-teal-400"></i>
                        <span>30-Day Guarantee</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-4 h-4 text-teal-400"></i>
                        <span>Available All Across Quetta</span>
                    </div>
                </div>
            </div>

            <!-- Price Card Header Highlight -->
            <div class="lg:col-span-4 bg-slate-800/90 border border-slate-700 rounded-3xl p-6 backdrop-blur-md text-center lg:text-left shadow-2xl">
                <span class="text-xs uppercase font-bold text-slate-400 tracking-wider">Fixed Starting Rate</span>
                <div class="text-3xl sm:text-4xl font-extrabold text-teal-400 font-heading my-1"><?= format_price($service['price']) ?></div>
                <p class="text-xs text-slate-400 mb-6">Includes standard labor & diagnostic check</p>
                <a href="<?= base_url('booking.php?service=' . $service['id']) ?>" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <span>Book This Service Now</span>
                </a>
            </div>
        </div>

    </div>
</section>

<!-- Service Scope & Details Body -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Left Column: Details, Includes & Reviews -->
            <div class="lg:col-span-8 space-y-10">
                
                <!-- Main Image Banner -->
                <div class="rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-slate-100 max-h-[420px]">
                    <img src="<?= asset($service['image'] ?? 'assets/images/services/plumbing_leak.jpg') ?>" alt="<?= e($service['name']) ?>" class="w-full h-full object-cover">
                </div>

                <!-- Detailed Scope -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm space-y-6">
                    <h2 class="text-2xl font-extrabold font-heading text-slate-900">Service Overview & Scope</h2>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed whitespace-pre-line">
                        <?= e($service['detailed_description'] ?? $service['description']) ?>
                    </p>

                    <!-- What is Included -->
                    <?php if (!empty($service['includes_list'])): 
                        $items = array_filter(explode("\n", $service['includes_list']));
                    ?>
                        <div class="pt-6 border-t border-slate-100">
                            <h3 class="text-lg font-bold font-heading text-slate-900 mb-4">What's Included in This Package</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <?php foreach ($items as $item): ?>
                                    <div class="flex items-start gap-3 p-3 bg-slate-50 border border-slate-200/60 rounded-xl">
                                        <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                                            <i data-lucide="check" class="w-3.5 h-3.5 stroke-[3]"></i>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-700"><?= e(trim($item)) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Customer Reviews for this Service -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm space-y-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-extrabold font-heading text-slate-900">Customer Reviews</h3>
                        <span class="text-xs font-bold px-3 py-1 bg-teal-50 text-teal-800 rounded-full border border-teal-100">
                            <?= count($reviews) ?> Verified Reviews
                        </span>
                    </div>

                    <?php if (!empty($reviews)): ?>
                        <div class="space-y-4">
                            <?php foreach ($reviews as $rev): ?>
                                <div class="p-4 bg-slate-50 border border-slate-200/60 rounded-2xl space-y-2">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-teal-600 text-white font-bold text-xs flex items-center justify-center">
                                                <?= strtoupper(substr($rev['customer_name'], 0, 1)) ?>
                                            </div>
                                            <span class="font-bold text-xs text-slate-900"><?= e($rev['customer_name']) ?></span>
                                        </div>
                                        <div class="flex text-amber-400 text-xs">
                                            <?php for ($i = 0; $i < (int)$rev['rating']; $i++): ?>
                                                <i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-600 italic">"<?= e($rev['review_text']) ?>"</p>
                                    <span class="text-[10px] text-slate-400 block"><?= format_date($rev['created_at']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-xs text-slate-500 italic">No reviews submitted yet for this service. Book today and be the first to review!</p>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Right Column: Sticky Booking & Support Widget -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Sticky Quick Booking Box -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-lg sticky top-28 space-y-5">
                    <h3 class="text-xl font-bold font-heading text-slate-900">Book in Quetta</h3>
                    <p class="text-xs text-slate-500">Pick your convenient date and neighborhood. Our pro will arrive with all diagnostic equipment.</p>

                    <div class="p-4 bg-teal-50 border border-teal-200 rounded-2xl space-y-1 text-center">
                        <div class="text-[11px] font-bold text-teal-800 uppercase tracking-wider">Service Fee</div>
                        <div class="text-3xl font-extrabold text-teal-900 font-heading"><?= format_price($service['price']) ?></div>
                    </div>

                    <a href="<?= base_url('booking.php?service=' . $service['id']) ?>" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                        <i data-lucide="calendar-plus" class="w-4 h-4"></i>
                        <span>Proceed to Booking</span>
                    </a>

                    <div class="pt-4 border-t border-slate-100 space-y-3 text-xs text-slate-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
                            <span>Pay in cash or online after inspection</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
                            <span>30-Day workmanship warranty</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
                            <span>Free rescheduling or cancellation</span>
                        </div>
                    </div>

                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-center">
                        <p class="text-[11px] text-slate-500 mb-1">Need immediate emergency support?</p>
                        <a href="tel:<?= APP_PHONE_RAW ?>" class="text-xs font-bold text-teal-700 hover:text-teal-800 flex items-center justify-center gap-1">
                            <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                            <span>Call <?= APP_PHONE ?></span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

        <!-- Related Services -->
        <?php if (!empty($relatedServices)): ?>
            <div class="mt-16 pt-12 border-t border-slate-200">
                <h3 class="text-2xl font-bold font-heading text-slate-900 mb-8">Related <?= e($service['category_name']) ?> Services</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($relatedServices as $rel): ?>
                        <div class="bg-white rounded-2xl p-5 border border-slate-200 flex flex-col justify-between shadow-sm hover:shadow-md transition">
                            <div>
                                <h4 class="font-bold text-slate-900 font-heading mb-1">
                                    <a href="<?= base_url('service-details.php?slug=' . $rel['slug']) ?>" class="hover:text-teal-600">
                                        <?= e($rel['name']) ?>
                                    </a>
                                </h4>
                                <p class="text-xs text-slate-500 line-clamp-2 mb-3"><?= e($rel['description']) ?></p>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                                <span class="text-sm font-extrabold text-teal-700 font-heading"><?= format_price($rel['price']) ?></span>
                                <a href="<?= base_url('booking.php?service=' . $rel['id']) ?>" class="btn-primary text-xs font-semibold px-3 py-1.5 rounded-lg">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
