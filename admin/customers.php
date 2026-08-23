<?php
/**
 * HomeFix Quetta - Admin Customers Management
 */
$adminPageTitle = 'Customer Accounts | HomeFix Quetta Admin';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

$customers = Database::fetchAll(
    "SELECT u.*, COUNT(b.id) as booking_count, SUM(CASE WHEN b.status = 'completed' THEN b.total_amount ELSE 0 END) as total_spent 
     FROM users u 
     LEFT JOIN bookings b ON u.id = b.user_id 
     WHERE u.role = 'customer' 
     GROUP BY u.id 
     ORDER BY u.created_at DESC"
);
?>

<div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-900">
    
    <header class="h-16 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
        <div class="flex items-center gap-4">
            <button id="adminSidebarToggle" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h2 class="text-lg font-bold font-heading text-white">Registered Customer Profiles</h2>
        </div>
        <span class="text-xs font-mono font-bold text-teal-400 bg-teal-950 px-3 py-1 rounded-full border border-teal-500/30">
            <?= count($customers) ?> Customers Registered
        </span>
    </header>

    <main class="flex-1 overflow-y-auto p-6 space-y-6">
        
        <div class="bg-slate-800/90 border border-slate-700/80 rounded-3xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm text-slate-300">
                    <thead class="bg-slate-950 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Customer Name</th>
                            <th class="px-6 py-4">Contact (Email & Phone)</th>
                            <th class="px-6 py-4">Quetta Area</th>
                            <th class="px-6 py-4">Total Bookings</th>
                            <th class="px-6 py-4">Total Spent</th>
                            <th class="px-6 py-4">Join Date</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/60 text-slate-200">
                        <?php foreach ($customers as $c): ?>
                            <tr class="hover:bg-slate-750 transition">
                                <td class="px-6 py-4 font-bold flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-teal-900 text-teal-300 font-heading font-extrabold flex items-center justify-center text-xs">
                                        <?= strtoupper(substr($c['name'], 0, 1)) ?>
                                    </div>
                                    <span class="text-white"><?= e($c['name']) ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-white block"><?= e($c['email']) ?></span>
                                    <span class="text-xs text-slate-400 font-mono"><?= e($c['phone']) ?></span>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-teal-300">
                                    <?= e($c['area'] ?? 'Quetta') ?>
                                </td>
                                <td class="px-6 py-4 font-bold text-white font-mono">
                                    <?= $c['booking_count'] ?> Bookings
                                </td>
                                <td class="px-6 py-4 font-bold text-teal-400 font-mono">
                                    <?= format_price($c['total_spent'] ?? 0) ?>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400">
                                    <?= format_date($c['created_at']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= get_status_badge($c['status']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
