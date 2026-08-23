<?php
/**
 * HomeFix Quetta - Admin Bookings Management
 */
$adminPageTitle = 'Manage Bookings | HomeFix Quetta Admin';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

// Automatically Mark All Unviewed Bookings as Viewed
try {
    Database::execute("UPDATE bookings SET is_viewed = 1 WHERE is_viewed = 0");
} catch (Exception $e) {
    try {
        Database::execute("ALTER TABLE bookings ADD COLUMN is_viewed TINYINT(1) DEFAULT 0");
        Database::execute("UPDATE bookings SET is_viewed = 1 WHERE is_viewed = 0");
    } catch (Exception $ex) {}
}

// Filter inputs
$statusFilter = trim($_GET['status'] ?? 'all');
$categoryFilter = (int)($_GET['category'] ?? 0);
$search = trim($_GET['search'] ?? '');

$sql = "SELECT b.*, s.name as service_name, c.name as category_name, t.name as technician_name, t.phone as technician_phone 
        FROM bookings b 
        JOIN services s ON b.service_id = s.id 
        JOIN categories c ON s.category_id = c.id 
        LEFT JOIN technicians t ON b.technician_id = t.id 
        WHERE 1=1";
$params = [];

if ($statusFilter !== 'all') {
    $sql .= " AND b.status = ?";
    $params[] = $statusFilter;
}

if ($categoryFilter > 0) {
    $sql .= " AND s.category_id = ?";
    $params[] = $categoryFilter;
}

if (!empty($search)) {
    $sql .= " AND (b.booking_reference LIKE ? OR b.customer_name LIKE ? OR b.customer_phone LIKE ? OR b.area LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY b.created_at DESC";
$bookings = Database::fetchAll($sql, $params);
$categories = Database::fetchAll("SELECT * FROM categories ORDER BY name ASC");
?>

<div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-900">
    
    <!-- Top Bar -->
    <header class="min-h-[4rem] h-auto sm:h-16 py-2.5 sm:py-0 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-4 sm:px-6 shrink-0 z-20 gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <button type="button" id="adminSidebarToggle" onclick="toggleAdminSidebar(event)" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 shrink-0 transition" aria-label="Toggle sidebar">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h2 class="text-base sm:text-lg font-extrabold font-heading text-white tracking-tight truncate">Bookings Management</h2>
        </div>
        <span class="text-xs font-mono font-bold text-teal-400 bg-slate-900 px-3 py-1.5 rounded-xl border border-slate-800 shrink-0 whitespace-nowrap">
            <?= count($bookings) ?> Orders
        </span>
    </header>

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 pb-32 sm:pb-16 lg:pb-12 space-y-6">
        
        <!-- Controls & Filter Toolbar -->
        <div class="bg-slate-950 p-4 sm:p-5 rounded-3xl border border-slate-800 shadow-xl space-y-4">
            
            <form action="bookings.php" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500"></i>
                    <input type="text" name="search" value="<?= e($search) ?>" placeholder="Ref #, Name, Phone, Area..." class="w-full bg-slate-900 border border-slate-800 rounded-2xl pl-10 pr-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-teal-500">
                </div>

                <div>
                    <select name="status" class="w-full bg-slate-900 border border-slate-800 rounded-2xl px-3.5 py-2.5 text-xs text-slate-300 focus:outline-none focus:border-teal-500">
                        <option value="all" <?= ($statusFilter === 'all') ? 'selected' : '' ?>>All Statuses</option>
                        <option value="pending" <?= ($statusFilter === 'pending') ? 'selected' : '' ?>>Pending Review</option>
                        <option value="confirmed" <?= ($statusFilter === 'confirmed') ? 'selected' : '' ?>>Confirmed</option>
                        <option value="assigned" <?= ($statusFilter === 'assigned') ? 'selected' : '' ?>>Assigned to Tech</option>
                        <option value="in_progress" <?= ($statusFilter === 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                        <option value="completed" <?= ($statusFilter === 'completed') ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= ($statusFilter === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>

                <div>
                    <select name="category" class="w-full bg-slate-900 border border-slate-800 rounded-2xl px-3.5 py-2.5 text-xs text-slate-300 focus:outline-none focus:border-teal-500">
                        <option value="0">All Categories</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($categoryFilter === (int)$c['id']) ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="btn-primary flex-1 py-2.5 rounded-2xl text-xs font-bold flex items-center justify-center gap-1.5 shadow">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i> Apply
                    </button>
                    <a href="bookings.php" class="p-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-400 hover:text-white text-xs font-semibold" title="Reset Filters">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- 1. MODERN BOOKINGS CARDS FEED (Default, 1-col on mobile, 2-col on desktop, Zero overflow) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <?php if (!empty($bookings)): ?>
                <?php foreach ($bookings as $b): ?>
                    <div class="bg-slate-950 border border-slate-800 hover:border-slate-700/80 rounded-3xl p-5 sm:p-6 shadow-xl hover:shadow-2xl transition-all duration-200 flex flex-col justify-between space-y-4">
                        
                        <!-- Header: Ref + Status + Price -->
                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <a href="<?= base_url('tracking.php?ref=' . $b['booking_reference']) ?>" target="_blank" class="font-mono font-extrabold text-sm text-teal-400 hover:underline inline-flex items-center gap-1">
                                            <span><?= e($b['booking_reference']) ?></span>
                                            <i data-lucide="external-link" class="w-3 h-3 text-slate-500"></i>
                                        </a>
                                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase bg-slate-900 text-teal-300 border border-slate-800">
                                            <?= e($b['category_name']) ?>
                                        </span>
                                    </div>
                                    <h3 class="text-base font-extrabold font-heading text-white mt-1 leading-snug"><?= e($b['service_name']) ?></h3>
                                </div>

                                <div class="text-right shrink-0">
                                    <span class="text-base font-mono font-extrabold text-teal-400 block"><?= format_price($b['total_amount']) ?></span>
                                    <span class="text-[10px] uppercase font-bold text-slate-500 block">Total Est.</span>
                                </div>
                            </div>

                            <!-- Schedule & Timing -->
                            <div class="flex items-center gap-3 text-xs bg-slate-900/60 p-2.5 rounded-2xl border border-slate-850 text-slate-300 font-mono">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-teal-400"></i>
                                    <span><?= format_date($b['preferred_date']) ?></span>
                                </div>
                                <span class="text-slate-600">&bull;</span>
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="clock" class="w-3.5 h-3.5 text-teal-400"></i>
                                    <span><?= e($b['preferred_time']) ?></span>
                                </div>
                            </div>

                            <!-- Customer Info Box -->
                            <div class="space-y-1.5 text-xs bg-slate-900/80 p-3.5 rounded-2xl border border-slate-850">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-extrabold text-white text-sm"><?= e($b['customer_name']) ?></span>
                                    <span class="text-[11px] font-bold text-teal-300 bg-slate-950 px-2.5 py-0.5 rounded-lg border border-teal-500/20 inline-flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-teal-400"></i>
                                        <?= e($b['area']) ?>
                                    </span>
                                </div>
                                
                                <div class="flex items-center gap-3 text-slate-400 pt-1">
                                    <a href="tel:<?= e($b['customer_phone']) ?>" class="text-teal-400 hover:underline font-mono font-semibold inline-flex items-center gap-1">
                                        <i data-lucide="phone" class="w-3 h-3"></i>
                                        <span><?= e($b['customer_phone']) ?></span>
                                    </a>
                                    <?php if (!empty($b['address'])): ?>
                                        <span class="text-slate-500 truncate text-[11px]" title="<?= e($b['address']) ?>">&bull; <?= e($b['address']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Problem Note & Attachment -->
                            <?php if (!empty($b['problem_description'])): ?>
                                <div class="text-xs text-slate-300 italic bg-slate-900/40 p-3 rounded-2xl border border-slate-850">
                                    "<?= e($b['problem_description']) ?>"
                                    <?php if (!empty($b['image_attachment'])): ?>
                                        <a href="<?= asset($b['image_attachment']) ?>" target="_blank" class="mt-2 inline-flex items-center gap-1.5 text-[11px] font-bold text-teal-400 hover:underline block not-italic">
                                            <i data-lucide="image" class="w-3.5 h-3.5"></i>
                                            <span>View Customer Photo Attachment</span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Technician Assigned Box -->
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-900/90 border border-slate-800 text-xs">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-slate-500 block">Assigned Technician</span>
                                    <?php if (!empty($b['technician_name'])): ?>
                                        <span class="font-extrabold text-white text-xs block mt-0.5"><?= e($b['technician_name']) ?> <span class="text-slate-400 font-mono font-normal">(<?= e($b['technician_phone']) ?>)</span></span>
                                    <?php else: ?>
                                        <span class="text-amber-400 font-bold text-xs block mt-0.5">Not Assigned Yet</span>
                                    <?php endif; ?>
                                </div>

                                <div>
                                    <?php if (!empty($b['technician_name'])): ?>
                                        <button type="button" 
                                                class="assign-tech-btn px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-teal-300 text-xs font-bold transition border border-slate-700"
                                                data-id="<?= $b['id'] ?>"
                                                data-ref="<?= e($b['booking_reference']) ?>"
                                                data-tech-id="<?= $b['technician_id'] ?>">
                                            Reassign
                                        </button>
                                    <?php else: ?>
                                        <button type="button" 
                                                class="assign-tech-btn px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-slate-950 text-xs font-black transition shadow"
                                                data-id="<?= $b['id'] ?>"
                                                data-ref="<?= e($b['booking_reference']) ?>">
                                            + Assign Pro
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer: Status Update & Actions -->
                        <div class="pt-3 border-t border-slate-900 flex items-center justify-between gap-3">
                            <div class="flex-1">
                                <select class="booking-status-select w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white font-bold focus:outline-none focus:border-teal-500"
                                        data-id="<?= $b['id'] ?>"
                                        data-current="<?= $b['status'] ?>">
                                    <option value="pending" <?= ($b['status'] === 'pending') ? 'selected' : '' ?>>Status: Pending</option>
                                    <option value="confirmed" <?= ($b['status'] === 'confirmed') ? 'selected' : '' ?>>Status: Confirmed</option>
                                    <option value="assigned" <?= ($b['status'] === 'assigned') ? 'selected' : '' ?>>Status: Assigned</option>
                                    <option value="in_progress" <?= ($b['status'] === 'in_progress') ? 'selected' : '' ?>>Status: In Progress</option>
                                    <option value="completed" <?= ($b['status'] === 'completed') ? 'selected' : '' ?>>Status: Completed</option>
                                    <option value="cancelled" <?= ($b['status'] === 'cancelled') ? 'selected' : '' ?>>Status: Cancelled</option>
                                </select>
                            </div>

                            <div class="flex items-center gap-1.5 shrink-0">
                                <a href="<?= base_url('tracking.php?ref=' . $b['booking_reference']) ?>" target="_blank" class="p-2 rounded-xl bg-slate-800 hover:bg-teal-600 text-slate-200 hover:text-white transition inline-flex items-center justify-center" title="Live Customer Tracking">
                                    <i data-lucide="external-link" class="w-4 h-4"></i>
                                </a>
                                <button type="button" 
                                        class="delete-item-btn p-2 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white transition inline-flex items-center justify-center"
                                        data-action="delete_booking"
                                        data-id="<?= $b['id'] ?>"
                                        data-title="Booking <?= $b['booking_reference'] ?>"
                                        title="Delete Booking">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full p-16 text-center text-slate-500 bg-slate-950 rounded-3xl border border-slate-800">
                    <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-3 text-slate-600"></i>
                    <h3 class="text-base font-bold text-slate-400 mb-1">No Bookings Found</h3>
                    <p class="text-xs text-slate-500">No service requests match the current filters.</p>
                </div>
            <?php endif; ?>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
