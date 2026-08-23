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

    <main class="flex-1 overflow-y-auto p-6 space-y-6">
        
        <div class="bg-slate-800/90 border border-slate-700/80 rounded-3xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm text-slate-300">
                    <thead class="bg-slate-950 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Customer & Service</th>
                            <th class="px-6 py-4">Rating</th>
                            <th class="px-6 py-4">Feedback Message</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Moderation Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/60 text-slate-200">
                        <?php if (!empty($reviews)): ?>
                            <?php foreach ($reviews as $r): ?>
                                <tr class="hover:bg-slate-750 transition">
                                    <td class="px-6 py-4 font-medium">
                                        <span class="font-bold text-white block"><?= e($r['customer_name']) ?></span>
                                        <span class="text-xs text-teal-400"><?= e($r['service_name'] ?? 'General') ?></span>
                                        <?php if (!empty($r['booking_reference'])): ?>
                                            <span class="text-[10px] text-slate-400 block font-mono">Ref: <?= e($r['booking_reference']) ?> (<?= e($r['area']) ?>)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex text-amber-400">
                                            <?php for ($i = 0; $i < (int)$r['rating']; $i++): ?>
                                                <i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 max-w-sm italic text-slate-300">
                                        "<?= e($r['review_text']) ?>"
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400">
                                        <?= format_date($r['created_at']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= get_status_badge($r['status']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <?php if ($r['status'] !== 'approved'): ?>
                                            <button type="button" class="review-status-btn px-2.5 py-1 rounded-lg bg-emerald-950 text-emerald-400 border border-emerald-500/30 text-xs font-bold hover:bg-emerald-900" data-id="<?= $r['id'] ?>" data-status="approved">
                                                Approve
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="review-status-btn px-2.5 py-1 rounded-lg bg-slate-800 text-slate-400 border border-slate-700 text-xs font-bold hover:bg-slate-700" data-id="<?= $r['id'] ?>" data-status="hidden">
                                                Hide
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="review-status-btn px-2.5 py-1 rounded-lg bg-rose-950 text-rose-400 border border-rose-500/30 text-xs font-bold hover:bg-rose-900" data-id="<?= $r['id'] ?>" data-status="delete">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="p-8 text-center text-slate-500">No reviews to moderate.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
