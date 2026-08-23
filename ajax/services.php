<?php
/**
 * AJAX Services Search & Filtering Controller
 */
define('IS_AJAX', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? 'filter';
$search = trim($_GET['search'] ?? '');
$categorySlug = trim($_GET['category'] ?? 'all');
$sort = trim($_GET['sort'] ?? 'default');

$sql = "SELECT s.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon 
        FROM services s 
        JOIN categories c ON s.category_id = c.id 
        WHERE s.status = 'active'";
$params = [];

if (!empty($search)) {
    $sql .= " AND (s.name LIKE ? OR s.description LIKE ? OR c.name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($categorySlug) && $categorySlug !== 'all') {
    $sql .= " AND c.slug = ?";
    $params[] = $categorySlug;
}

switch ($sort) {
    case 'price_low':
        $sql .= " ORDER BY s.price ASC";
        break;
    case 'price_high':
        $sql .= " ORDER BY s.price DESC";
        break;
    case 'name_asc':
        $sql .= " ORDER BY s.name ASC";
        break;
    default:
        $sql .= " ORDER BY s.is_featured DESC, s.id ASC";
        break;
}

$services = Database::fetchAll($sql, $params);

ob_start();
if (!empty($services)):
    foreach ($services as $srv):
?>
    <div class="service-card bg-white rounded-2xl border border-slate-200/80 overflow-hidden flex flex-col justify-between group shadow-sm hover:shadow-xl transition-all duration-300">
        <div>
            <!-- Service Image Header -->
            <div class="relative h-48 w-full overflow-hidden bg-slate-100">
                <img src="<?= asset($srv['image'] ?? 'assets/images/services/plumbing_leak.jpg') ?>" 
                     alt="<?= e($srv['name']) ?>" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                     loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                
                <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-semibold bg-white/90 backdrop-blur-md text-slate-800 shadow-sm flex items-center gap-1.5">
                    <i data-lucide="<?= e($srv['category_icon'] ?? 'wrench') ?>" class="w-3.5 h-3.5 text-teal-600"></i>
                    <?= e($srv['category_name']) ?>
                </span>

                <?php if ($srv['is_featured']): ?>
                    <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500 text-white shadow-sm flex items-center gap-1">
                        <i data-lucide="star" class="w-3 h-3 fill-current"></i> Featured
                    </span>
                <?php endif; ?>

                <div class="absolute bottom-3 right-3 text-white font-mono text-xs bg-slate-900/80 px-2.5 py-1 rounded-lg backdrop-blur-sm flex items-center gap-1">
                    <i data-lucide="clock" class="w-3 h-3 text-teal-400"></i>
                    <?= e($srv['duration']) ?>
                </div>
            </div>

            <!-- Service Info -->
            <div class="p-6">
                <h3 class="font-heading font-bold text-lg text-slate-900 group-hover:text-teal-600 transition mb-2">
                    <a href="<?= base_url('service-details.php?slug=' . urlencode($srv['slug'])) ?>">
                        <?= e($srv['name']) ?>
                    </a>
                </h3>
                <p class="text-sm text-slate-500 line-clamp-2 mb-4">
                    <?= e($srv['description']) ?>
                </p>

                <!-- Includes Snippet -->
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

        <!-- Footer Pricing & CTA -->
        <div class="px-6 pb-6 pt-2 flex items-center justify-between border-t border-slate-100 bg-slate-50/50">
            <div>
                <span class="text-[11px] text-slate-400 block font-medium">Starting from</span>
                <span class="text-lg font-extrabold text-teal-700 font-heading"><?= format_price($srv['price']) ?></span>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?= base_url('service-details.php?slug=' . urlencode($srv['slug'])) ?>" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                    Details
                </a>
                <a href="<?= base_url('booking.php?service=' . $srv['id']) ?>" class="btn-primary px-4 py-2 rounded-xl text-xs font-semibold inline-flex items-center gap-1.5">
                    <span>Book Now</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>
    </div>
<?php 
    endforeach;
endif;
$html = ob_get_clean();

json_response(true, 'Services retrieved', [
    'count' => count($services),
    'html'  => $html
]);
