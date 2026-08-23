<?php
/**
 * HomeFix Quetta - Admin Reviews Moderation
 */
$adminPageTitle = 'Manage Customer Reviews | HomeFix Quetta Admin';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

$reviews = Database::fetchAll(
    "SELECT r.*, s.name as service_name, b.booking_reference, b.area 
     FROM reviews r 
     LEFT JOIN services s ON r.service_id = s.id 
     LEFT JOIN bookings b ON r.booking_id = b.id 
     ORDER BY r.created_at DESC"
);
?>

<div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-900">
    
    <header class="h-16 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
        <div class="flex items-center gap-4">
            <button id="adminSidebarToggle" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h2 class="text-lg font-bold font-heading text-white">Customer Reviews Moderation</h2>
        </div>
        <span class="text-xs font-mono font-bold text-teal-400 bg-teal-950 px-3 py-1 rounded-full border border-teal-500/30">
            <?= count($reviews) ?> Reviews Total
        </span>
    </header>

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 pb-32 sm:pb-16 lg:pb-12 space-y-6">
        
        <!-- Controls & Stats Toolbar -->
        <div class="flex items-center justify-between gap-4 bg-slate-950 p-4 sm:p-5 rounded-3xl border border-slate-800 shadow-xl">
            <div>
                <h3 class="text-sm font-extrabold text-white">Customer Feedback Stream</h3>
                <p class="text-xs text-slate-400">Moderate published testimonials shown across the public Quetta portal.</p>
            </div>
            <span class="text-xs font-mono font-bold text-teal-400 bg-slate-900 px-4 py-2 rounded-2xl border border-slate-800 whitespace-nowrap">
                <?= count($reviews) ?> Reviews Total
            </span>
        </div>

        <!-- MODERN RESPONSIVE REVIEW MODERATION CARDS FEED (1-col on mobile, 2-col on desktop) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $r): ?>
                    <div class="bg-slate-950 border border-slate-800 hover:border-slate-700/80 rounded-3xl p-5 sm:p-6 shadow-xl hover:shadow-2xl transition-all duration-200 flex flex-col justify-between space-y-4">
                        
                        <div class="space-y-3">
                            <!-- Card Header: Reviewer + Service + Status -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-11 h-11 rounded-2xl bg-teal-950 border border-teal-500/40 text-teal-300 flex items-center justify-center font-heading font-extrabold text-base shrink-0 shadow-md">
                                        <?= strtoupper(substr($r['customer_name'] ?? 'C', 0, 1)) ?>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-base font-extrabold font-heading text-white truncate leading-snug"><?= e($r['customer_name']) ?></h4>
                                        <span class="text-xs font-semibold text-teal-400 block truncate mt-0.5"><?= e($r['service_name'] ?? 'General Service') ?></span>
                                    </div>
                                </div>

                                <div class="shrink-0">
                                    <?= get_status_badge($r['status']) ?>
                                </div>
                            </div>

                            <!-- Star Rating & Meta -->
                            <div class="flex items-center justify-between text-xs bg-slate-900/60 p-3 rounded-2xl border border-slate-850">
                                <div class="flex items-center gap-1 text-amber-400">
                                    <?php for ($i = 0; $i < (int)$r['rating']; $i++): ?>
                                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                    <?php endfor; ?>
                                    <span class="text-xs font-mono font-bold text-amber-300 ml-1.5"><?= number_format((float)$r['rating'], 1) ?></span>
                                </div>

                                <span class="text-xs font-mono text-slate-400"><?= format_date($r['created_at']) ?></span>
                            </div>

                            <!-- Feedback Message Text -->
                            <div class="bg-slate-900/80 p-4 rounded-2xl border border-slate-850 text-xs sm:text-sm text-slate-200 leading-relaxed italic">
                                "<?= e($r['review_text']) ?>"
                            </div>

                            <?php if (!empty($r['booking_reference'])): ?>
                                <div class="text-[11px] font-mono text-slate-400 flex items-center gap-1">
                                    <i data-lucide="hash" class="w-3 h-3 text-teal-400"></i>
                                    <span>Booking Ref: <?= e($r['booking_reference']) ?> (<?= e($r['area']) ?>)</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Card Action Footer -->
                        <div class="pt-3 border-t border-slate-900 flex items-center justify-between gap-2">
                            <div>
                                <?php if ($r['status'] !== 'approved'): ?>
                                    <button type="button" 
                                            class="review-status-btn px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition shadow" 
                                            data-id="<?= $r['id'] ?>" 
                                            data-status="approved">
                                        ✓ Approve Review
                                    </button>
                                <?php else: ?>
                                    <button type="button" 
                                            class="review-status-btn px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition border border-slate-700" 
                                            data-id="<?= $r['id'] ?>" 
                                            data-status="hidden">
                                        Hide Review
                                    </button>
                                <?php endif; ?>
                            </div>

                            <button type="button" 
                                    class="review-status-btn p-2 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white transition inline-flex items-center justify-center" 
                                    data-id="<?= $r['id'] ?>" 
                                    data-status="delete"
                                    title="Delete Review">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full p-16 text-center text-slate-500 bg-slate-950 rounded-3xl border border-slate-800">
                    <i data-lucide="star-off" class="w-12 h-12 mx-auto mb-3 text-slate-600"></i>
                    <h3 class="text-base font-bold text-slate-400 mb-1">No Reviews to Moderate</h3>
                    <p class="text-xs text-slate-500">Customer feedback will show up here once submitted.</p>
                </div>
            <?php endif; ?>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
