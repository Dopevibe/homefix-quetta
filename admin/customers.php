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

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 pb-32 sm:pb-16 lg:pb-12 space-y-6">
        
        <!-- Search & Stats Toolbar -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 bg-slate-950 p-4 sm:p-5 rounded-3xl border border-slate-800 shadow-xl">
            <div class="relative flex-1 min-w-0">
                <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500"></i>
                <input type="text" id="customerSearchInput" placeholder="Search customer name, email, phone, area..." 
                       class="w-full bg-slate-900 border border-slate-800 rounded-2xl pl-10 pr-4 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:border-teal-500">
            </div>

            <span class="text-xs font-mono font-bold text-teal-400 bg-slate-900 px-4 py-2.5 rounded-2xl border border-slate-800 whitespace-nowrap self-start sm:self-auto">
                <?= count($customers) ?> Customer Accounts
            </span>
        </div>

        <!-- MODERN RESPONSIVE CUSTOMER CARDS GRID (1-col on mobile, 2-col on tablet, 3-col on desktop) -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5" id="customerCardsGrid">
            <?php if (!empty($customers)): ?>
                <?php foreach ($customers as $c): ?>
                    <div class="customer-card bg-slate-950 border border-slate-800 hover:border-slate-700/80 rounded-3xl p-5 shadow-xl hover:shadow-2xl transition-all duration-200 flex flex-col justify-between space-y-4"
                         data-name="<?= strtolower(e($c['name'])) ?>"
                         data-email="<?= strtolower(e($c['email'])) ?>"
                         data-phone="<?= strtolower(e($c['phone'] ?? '')) ?>"
                         data-area="<?= strtolower(e($c['area'] ?? '')) ?>">
                        
                        <div class="space-y-3">
                            <!-- Card Header: Avatar + Name + Status + Area -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-teal-950 border border-teal-500/40 text-teal-300 flex items-center justify-center font-heading font-extrabold text-base shrink-0 shadow-md">
                                        <?= strtoupper(substr($c['name'], 0, 1)) ?>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-base font-extrabold font-heading text-white truncate leading-snug"><?= e($c['name']) ?></h3>
                                        <span class="text-xs font-semibold text-teal-400 block truncate mt-0.5 flex items-center gap-1">
                                            <i data-lucide="map-pin" class="w-3 h-3 text-teal-400"></i>
                                            <?= e($c['area'] ?? 'Quetta City') ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="shrink-0">
                                    <?= get_status_badge($c['status']) ?>
                                </div>
                            </div>

                            <!-- Metrics: Total Bookings + Total Spent -->
                            <div class="grid grid-cols-2 gap-2 text-xs bg-slate-900/80 p-3 rounded-2xl border border-slate-850">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-slate-500 block">Orders</span>
                                    <span class="text-sm font-mono font-bold text-white block mt-0.5"><?= $c['booking_count'] ?> Bookings</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] uppercase font-bold text-slate-500 block">Total Spent</span>
                                    <span class="text-sm font-mono font-extrabold text-teal-400 block mt-0.5"><?= format_price($c['total_spent'] ?? 0) ?></span>
                                </div>
                            </div>

                            <!-- Contact Info Links -->
                            <div class="space-y-1.5 text-xs text-slate-400 pt-1">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-500 shrink-0"></i>
                                    <a href="mailto:<?= e($c['email']) ?>" class="text-slate-300 hover:text-teal-400 truncate transition">
                                        <?= e($c['email']) ?>
                                    </a>
                                </div>
                                <?php if (!empty($c['phone'])): ?>
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="phone" class="w-3.5 h-3.5 text-teal-400 shrink-0"></i>
                                        <a href="tel:<?= e($c['phone']) ?>" class="text-slate-300 hover:text-teal-400 font-mono font-semibold truncate transition">
                                            <?= e($c['phone']) ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Card Footer: Member Since -->
                        <div class="pt-3 border-t border-slate-900 flex items-center justify-between text-xs text-slate-500 font-mono">
                            <span>Member Since</span>
                            <span class="text-slate-400 font-medium"><?= format_date($c['created_at']) ?></span>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full p-16 text-center text-slate-500 bg-slate-950 rounded-3xl border border-slate-800">
                    <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-slate-600"></i>
                    <h3 class="text-base font-bold text-slate-400 mb-1">No Customers Registered</h3>
                    <p class="text-xs text-slate-500">Customer profiles will appear here when users sign up.</p>
                </div>
            <?php endif; ?>
        </div>

    </main>
</div>

<script>
$(document).ready(function() {
    $('#customerSearchInput').on('input', function() {
        const query = ($(this).val() || '').toLowerCase().trim();
        $('.customer-card').each(function() {
            const el = $(this);
            const name = (el.data('name') || '').toString();
            const email = (el.data('email') || '').toString();
            const phone = (el.data('phone') || '').toString();
            const area = (el.data('area') || '').toString();

            if (!query || name.includes(query) || email.includes(query) || phone.includes(query) || area.includes(query)) {
                el.show();
            } else {
                el.hide();
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
