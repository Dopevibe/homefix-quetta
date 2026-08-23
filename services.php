<?php
/**
 * HomeFix Quetta - Services Catalog & Live Search
 */
$pageTitle = 'Services Directory | HomeFix Quetta';
$pageDescription = 'Browse, search and filter all home repair and maintenance services in Quetta, Balochistan. Fixed prices, verified professionals, fast doorstep arrival.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch Categories for Filter Pills
$categories = Database::fetchAll("SELECT * FROM categories WHERE status = 'active' ORDER BY sort_order ASC, name ASC");
$activeCategorySlug = trim($_GET['category'] ?? 'all');
$searchQuery = trim($_GET['search'] ?? '');

// Initial Services Query
$sql = "SELECT s.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon 
        FROM services s 
        JOIN categories c ON s.category_id = c.id 
        WHERE s.status = 'active'";
$params = [];

if (!empty($searchQuery)) {
    $sql .= " AND (s.name LIKE ? OR s.description LIKE ? OR c.name LIKE ?)";
    $params[] = "%$searchQuery%";
    $params[] = "%$searchQuery%";
    $params[] = "%$searchQuery%";
}

if (!empty($activeCategorySlug) && $activeCategorySlug !== 'all') {
    $sql .= " AND c.slug = ?";
    $params[] = $activeCategorySlug;
}

$sql .= " ORDER BY s.is_featured DESC, s.id ASC";
$initialServices = Database::fetchAll($sql, $params);
?>

<!-- Services Catalog Header -->
<section class="bg-gradient-to-b from-slate-900 via-slate-900 to-slate-850 text-white py-14 lg:py-20 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">

        <h1 class="text-3xl sm:text-5xl font-extrabold font-heading text-white tracking-tight">
            Home Services Directory in Quetta
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto mt-3 font-normal">
            Find certified electricians, plumbers, painters, and handymen available for doorstep dispatch in your neighborhood.
        </p>

        <!-- Search Bar -->
        <div class="mt-8 max-w-2xl mx-auto">
            <div class="relative">
                <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                <input type="text" id="serviceSearchInput" value="<?= e($searchQuery) ?>" placeholder="Search by service name, fault or category (e.g. Geyser, Inverter, Leakage)..." class="w-full bg-white text-slate-900 pl-12 pr-4 py-4 rounded-2xl text-sm font-medium shadow-2xl focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
        </div>
    </div>
</section>

<!-- Filter Controls & Dynamic Services Grid -->
<section class="py-12 bg-slate-50 min-h-[600px]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Category Filter Pills & Sort Bar -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-10 pb-6 border-b border-slate-200">
            
            <!-- Category Pills -->
            <div class="flex flex-wrap gap-2">
                <button type="button" data-category="all" class="category-filter-btn px-4 py-2 rounded-xl text-xs font-semibold border border-slate-200 transition <?= ($activeCategorySlug === 'all') ? 'active bg-emerald-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100' ?>">
                    All Categories
                </button>
                <?php foreach ($categories as $cat): ?>
                    <button type="button" data-category="<?= e($cat['slug']) ?>" class="category-filter-btn px-4 py-2 rounded-xl text-xs font-semibold border border-slate-200 transition flex items-center gap-1.5 <?= ($activeCategorySlug === $cat['slug']) ? 'active bg-emerald-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100' ?>">
                        <i data-lucide="<?= e($cat['icon'] ?? 'wrench') ?>" class="w-3.5 h-3.5"></i>
                        <span><?= e($cat['name']) ?></span>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Sort Dropdown & Count Badge -->
            <div class="flex items-center gap-3 w-full lg:w-auto justify-between lg:justify-end">
                <span id="servicesCountBadge" class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <?= count($initialServices) ?> Services Available
                </span>

                <div class="relative">
                    <select id="serviceSortSelect" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500 cursor-pointer shadow-sm">
                        <option value="default">Sort: Recommended</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="name_asc">Name: A to Z</option>
                    </select>
                </div>
            </div>

        </div>

        <!-- Grid Container (Populated dynamically via AJAX or PHP initial load) -->
        <div id="servicesGridContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($initialServices)): ?>
                <?php foreach ($initialServices as $srv): ?>
                    <div class="service-card bg-white rounded-3xl border border-slate-200/80 overflow-hidden flex flex-col justify-between group shadow-sm hover:shadow-xl transition-all duration-300">
                        <div>
                            <!-- Service Image -->
                            <div class="relative h-52 w-full overflow-hidden bg-slate-100">
                                <img src="<?= asset($srv['image'] ?? 'assets/images/services/plumbing_leak.jpg') ?>" 
                                     alt="<?= e($srv['name']) ?>" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                     loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                                
                                <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-semibold bg-white/95 backdrop-blur-md text-slate-800 shadow-sm flex items-center gap-1.5">
                                    <i data-lucide="<?= e($srv['category_icon'] ?? 'wrench') ?>" class="w-3.5 h-3.5 text-teal-600"></i>
                                    <?= e($srv['category_name']) ?>
                                </span>

                                <?php if ($srv['is_featured']): ?>
                                    <span class="absolute top-4 right-4 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500 text-white shadow-sm flex items-center gap-1">
                                        <i data-lucide="star" class="w-3 h-3 fill-current"></i> Featured
                                    </span>
                                <?php endif; ?>

                                <div class="absolute bottom-4 right-4 text-white font-mono text-xs bg-slate-900/80 px-2.5 py-1 rounded-lg backdrop-blur-sm flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3 h-3 text-teal-400"></i>
                                    <?= e($srv['duration']) ?>
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="p-6">
                                <h3 class="font-heading font-bold text-lg text-slate-900 group-hover:text-teal-600 transition mb-2">
                                    <a href="<?= base_url('service-details.php?slug=' . urlencode($srv['slug'])) ?>">
                                        <?= e($srv['name']) ?>
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 mb-4">
                                    <?= e($srv['description']) ?>
                                </p>

                                <?php if (!empty($srv['includes_list'])): 
                                    $lines = array_filter(explode("\n", $srv['includes_list']));
                                    $firstTwo = array_slice($lines, 0, 2);
                                ?>
                                    <div class="space-y-1.5 pt-3 border-t border-slate-100 mb-4">
                                        <?php foreach ($firstTwo as $line): ?>
                                            <div class="flex items-center gap-2 text-xs text-slate-600">
                                                <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500 shrink-0"></i>
                                                <span class="truncate"><?= e(trim($line)) ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 pb-6 pt-3 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Fixed Rate</span>
                                <span class="text-xl font-extrabold text-teal-700 font-heading"><?= format_price($srv['price']) ?></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="<?= base_url('service-details.php?slug=' . urlencode($srv['slug'])) ?>" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                                    Details
                                </a>
                                <a href="<?= base_url('booking.php?service=' . $srv['id']) ?>" class="btn-primary px-4 py-2.5 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 shadow-sm">
                                    <span>Book Now</span>
                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full bg-white rounded-3xl border border-slate-200 p-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="search-x" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">No services found</h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto mb-6">We couldn't find any services matching your criteria in Quetta.</p>
                    <button onclick="resetServiceFilters()" class="px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium transition">Reset Filters</button>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<!-- Services AJAX JS Integration -->
<script src="<?= asset('assets/js/services.js') ?>"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
