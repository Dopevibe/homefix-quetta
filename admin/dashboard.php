<?php
/**
 * HomeFix Quetta - Admin Dashboard
 */
$adminPageTitle = 'Operations Dashboard | HomeFix Quetta Admin';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

// Fetch High Level Metrics
$totalRevenue = Database::fetch("SELECT SUM(total_amount) as total FROM bookings WHERE status = 'completed'")['total'] ?? 0;
$totalBookings = Database::fetch("SELECT COUNT(*) as total FROM bookings")['total'] ?? 0;
$pendingBookings = Database::fetch("SELECT COUNT(*) as total FROM bookings WHERE status IN ('pending', 'confirmed')")['total'] ?? 0;
$activeTechnicians = Database::fetch("SELECT COUNT(*) as total FROM technicians WHERE status = 'active'")['total'] ?? 0;
$totalCustomers = Database::fetch("SELECT COUNT(*) as total FROM users WHERE role = 'customer'")['total'] ?? 0;

// Fetch Recent Bookings
$recentBookings = Database::fetchAll(
    "SELECT b.*, s.name as service_name, c.name as category_name, t.name as technician_name 
     FROM bookings b 
     JOIN services s ON b.service_id = s.id 
     JOIN categories c ON s.category_id = c.id 
     LEFT JOIN technicians t ON b.technician_id = t.id 
     ORDER BY b.created_at DESC LIMIT 8"
);

// Fetch Pending Reviews
$pendingReviews = Database::fetchAll(
    "SELECT r.*, s.name as service_name 
     FROM reviews r 
     LEFT JOIN services s ON r.service_id = s.id 
     ORDER BY r.created_at DESC LIMIT 4"
);

// Fetch Area Distribution
$areaStats = Database::fetchAll(
    "SELECT area, COUNT(*) as booking_count, SUM(total_amount) as total_val 
     FROM bookings 
     WHERE area IS NOT NULL AND area != '' 
     GROUP BY area 
     ORDER BY booking_count DESC LIMIT 5"
);
?>

<!-- Main Content Area -->
<div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-900">
    
    <!-- Top Bar -->
    <header class="h-16 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
        <div class="flex items-center gap-4">
            <button id="adminSidebarToggle" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h2 class="text-lg font-bold font-heading text-white">Operations Overview</h2>
        </div>

        <div class="flex items-center gap-4">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-950/80 text-emerald-400 border border-emerald-500/30">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Quetta Gateway Live</span>
            </span>
        </div>
    </header>

    <!-- Scrollable Workspace -->
    <main class="flex-1 overflow-y-auto p-6 space-y-6">
        
        <!-- Metrics Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
            
            <div class="bg-slate-800/80 border border-slate-700/80 rounded-3xl p-5 shadow-lg space-y-3">
                <div class="flex justify-between items-center text-teal-400">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Revenue (PKR)</span>
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
                <div class="text-2xl font-extrabold font-heading text-white"><?= format_price($totalRevenue) ?></div>
                <div class="text-[11px] text-teal-400 flex items-center gap-1">
                    <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                    <span>Completed jobs</span>
                </div>
            </div>

            <div class="bg-slate-800/80 border border-slate-700/80 rounded-3xl p-5 shadow-lg space-y-3">
                <div class="flex justify-between items-center text-blue-400">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Bookings</span>
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
                <div class="text-2xl font-extrabold font-heading text-white"><?= $totalBookings ?></div>
                <div class="text-[11px] text-slate-400">All Quetta requests</div>
            </div>

            <div class="bg-slate-800/80 border border-slate-700/80 rounded-3xl p-5 shadow-lg space-y-3">
                <div class="flex justify-between items-center text-amber-400">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Action Required</span>
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                </div>
                <div class="text-2xl font-extrabold font-heading text-amber-400"><?= $pendingBookings ?></div>
                <div class="text-[11px] text-amber-300">Pending / Unassigned</div>
            </div>

            <div class="bg-slate-800/80 border border-slate-700/80 rounded-3xl p-5 shadow-lg space-y-3">
                <div class="flex justify-between items-center text-emerald-400">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Active Techs</span>
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <div class="text-2xl font-extrabold font-heading text-white"><?= $activeTechnicians ?></div>
                <div class="text-[11px] text-emerald-400">On duty in Quetta</div>
            </div>

            <div class="bg-slate-800/80 border border-slate-700/80 rounded-3xl p-5 shadow-lg space-y-3">
                <div class="flex justify-between items-center text-indigo-400">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Customers</span>
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                </div>
                <div class="text-2xl font-extrabold font-heading text-white"><?= $totalCustomers ?></div>
                <div class="text-[11px] text-indigo-400">Registered profiles</div>
            </div>

        </div>

        <!-- Recent Bookings Table & Quick Dispatch -->
        <div class="bg-slate-800/90 border border-slate-700/80 rounded-3xl shadow-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-700 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <h3 class="font-heading font-bold text-base text-white">Recent Service Requests</h3>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-teal-950 text-teal-400 border border-teal-500/30">Live Queue</span>
                </div>
                <a href="<?= base_url('admin/bookings.php') ?>" class="text-xs font-semibold text-teal-400 hover:underline">
                    View All Bookings →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm text-slate-300">
                    <thead class="bg-slate-950 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-3.5">Ref / Date</th>
                            <th class="px-6 py-3.5">Customer & Area</th>
                            <th class="px-6 py-3.5">Service</th>
                            <th class="px-6 py-3.5">Price</th>
                            <th class="px-6 py-3.5">Technician</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/60 text-slate-200">
                        <?php foreach ($recentBookings as $b): ?>
                            <tr class="hover:bg-slate-750 transition">
                                <td class="px-6 py-3.5 font-medium">
                                    <span class="font-mono font-bold text-teal-400 block"><?= e($b['booking_reference']) ?></span>
                                    <span class="text-[11px] text-slate-400"><?= format_date($b['preferred_date']) ?> • <?= e($b['preferred_time']) ?></span>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="font-bold text-white block"><?= e($b['customer_name']) ?></span>
                                    <span class="text-[11px] text-slate-400"><?= e($b['customer_phone']) ?> • <strong class="text-teal-300"><?= e($b['area']) ?></strong></span>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="font-semibold block"><?= e($b['service_name']) ?></span>
                                    <span class="text-[10px] text-slate-400"><?= e($b['category_name']) ?></span>
                                </td>
                                <td class="px-6 py-3.5 font-bold font-mono text-teal-400">
                                    <?= format_price($b['total_amount']) ?>
                                </td>
                                <td class="px-6 py-3.5">
                                    <?php if (!empty($b['technician_name'])): ?>
                                        <span class="font-bold text-slate-200 block"><?= e($b['technician_name']) ?></span>
                                    <?php else: ?>
                                        <button type="button" 
                                                class="assign-tech-btn text-xs font-bold text-amber-400 bg-amber-950/80 border border-amber-500/40 px-2.5 py-1 rounded-lg hover:bg-amber-900 transition"
                                                data-id="<?= $b['id'] ?>"
                                                data-ref="<?= e($b['booking_reference']) ?>">
                                            + Assign Tech
                                        </button>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-3.5">
                                    <select class="booking-status-select bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1 text-xs text-slate-200 focus:outline-none focus:ring-1 focus:ring-teal-500"
                                            data-id="<?= $b['id'] ?>"
                                            data-current="<?= $b['status'] ?>">
                                        <option value="pending" <?= ($b['status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
                                        <option value="confirmed" <?= ($b['status'] === 'confirmed') ? 'selected' : '' ?>>Confirmed</option>
                                        <option value="assigned" <?= ($b['status'] === 'assigned') ? 'selected' : '' ?>>Assigned</option>
                                        <option value="in_progress" <?= ($b['status'] === 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                                        <option value="completed" <?= ($b['status'] === 'completed') ? 'selected' : '' ?>>Completed</option>
                                        <option value="cancelled" <?= ($b['status'] === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </td>
                                <td class="px-6 py-3.5 text-right space-x-1">
                                    <a href="<?= base_url('tracking.php?ref=' . $b['booking_reference']) ?>" target="_blank" class="p-1.5 rounded-lg bg-slate-700 hover:bg-teal-600 text-white inline-block" title="Live Customer View">
                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <button type="button" class="delete-item-btn p-1.5 rounded-lg bg-slate-700 hover:bg-rose-600 text-slate-300 hover:text-white inline-block" 
                                            data-action="delete_booking" 
                                            data-id="<?= $b['id'] ?>" 
                                            data-title="Booking <?= $b['booking_reference'] ?>" 
                                            title="Delete">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2-Column: Reviews Queue & Quetta Neighborhood Distribution -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Reviews Queue (7 Cols) -->
            <div class="lg:col-span-7 bg-slate-800/90 border border-slate-700/80 rounded-3xl p-6 shadow-xl space-y-4">
                <div class="flex justify-between items-center border-b border-slate-700 pb-3">
                    <h3 class="font-heading font-bold text-base text-white">Recent Customer Reviews</h3>
                    <a href="<?= base_url('admin/reviews.php') ?>" class="text-xs text-teal-400 font-semibold hover:underline">Manage All →</a>
                </div>

                <?php if (!empty($pendingReviews)): ?>
                    <div class="space-y-3">
                        <?php foreach ($pendingReviews as $rev): ?>
                            <div class="p-4 bg-slate-900 border border-slate-700 rounded-2xl space-y-2 text-xs">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <strong class="text-white"><?= e($rev['customer_name']) ?></strong>
                                        <span class="text-teal-400 ml-2">(<?= e($rev['service_name'] ?? 'General') ?>)</span>
                                    </div>
                                    <div class="flex text-amber-400">
                                        <?php for ($i = 0; $i < (int)$rev['rating']; $i++): ?>
                                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="text-slate-300 italic">"<?= e($rev['review_text']) ?>"</p>
                                <div class="flex justify-between items-center pt-2 border-t border-slate-800 text-[11px]">
                                    <span class="text-slate-500"><?= format_date($rev['created_at']) ?></span>
                                    <div class="space-x-2">
                                        <?php if ($rev['status'] !== 'approved'): ?>
                                            <button type="button" class="review-status-btn text-emerald-400 hover:underline font-bold" data-id="<?= $rev['id'] ?>" data-status="approved">Approve</button>
                                        <?php else: ?>
                                            <button type="button" class="review-status-btn text-slate-400 hover:underline" data-id="<?= $rev['id'] ?>" data-status="hidden">Hide</button>
                                        <?php endif; ?>
                                        <button type="button" class="review-status-btn text-rose-400 hover:underline font-bold" data-id="<?= $rev['id'] ?>" data-status="delete">Delete</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-slate-500 italic">No reviews in the system yet.</p>
                <?php endif; ?>
            </div>

            <!-- Neighborhood Stats (5 Cols) -->
            <div class="lg:col-span-5 bg-slate-800/90 border border-slate-700/80 rounded-3xl p-6 shadow-xl space-y-4">
                <div class="flex justify-between items-center border-b border-slate-700 pb-3">
                    <h3 class="font-heading font-bold text-base text-white">Top Quetta Demand Hubs</h3>
                    <span class="text-xs text-slate-400 font-mono">By Bookings</span>
                </div>

                <div class="space-y-3">
                    <?php foreach ($areaStats as $area): ?>
                        <div class="p-3 bg-slate-900 rounded-xl flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-teal-400"></i>
                                <span class="font-bold text-white"><?= e($area['area']) ?></span>
                            </div>
                            <div class="text-right">
                                <span class="font-extrabold text-teal-400 block"><?= $area['booking_count'] ?> jobs</span>
                                <span class="text-[10px] text-slate-500"><?= format_price($area['total_val'] ?? 0) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

    </main>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
