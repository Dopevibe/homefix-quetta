<?php
/**
 * HomeFix Quetta - Admin Bookings Management
 */
$adminPageTitle = 'Manage Bookings | HomeFix Quetta Admin';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

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
    <header class="h-16 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
        <div class="flex items-center gap-4">
            <button id="adminSidebarToggle" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h2 class="text-lg font-bold font-heading text-white">Bookings Management</h2>
        </div>
        <span class="text-xs font-mono font-bold text-teal-400 bg-teal-950 px-3 py-1 rounded-full border border-teal-500/30">
            <?= count($bookings) ?> Records Found
        </span>
    </header>

    <main class="flex-1 overflow-y-auto p-6 space-y-6">
        
        <!-- Filters Bar -->
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-3xl p-5 shadow-lg">
            <form action="bookings.php" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Search Query</label>
                    <input type="text" name="search" value="<?= e($search) ?>" placeholder="Ref #, Name, Phone, Area..." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Status Filter</label>
                    <select name="status" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-teal-500">
                        <option value="all" <?= ($statusFilter === 'all') ? 'selected' : '' ?>>All Statuses</option>
                        <option value="pending" <?= ($statusFilter === 'pending') ? 'selected' : '' ?>>Pending</option>
                        <option value="confirmed" <?= ($statusFilter === 'confirmed') ? 'selected' : '' ?>>Confirmed</option>
                        <option value="assigned" <?= ($statusFilter === 'assigned') ? 'selected' : '' ?>>Assigned</option>
                        <option value="in_progress" <?= ($statusFilter === 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                        <option value="completed" <?= ($statusFilter === 'completed') ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= ($statusFilter === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Category Filter</label>
                    <select name="category" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-teal-500">
                        <option value="0">All Categories</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($categoryFilter === (int)$c['id']) ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-primary flex-1 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i> Apply Filters
                    </button>
                    <a href="bookings.php" class="p-2.5 rounded-xl bg-slate-700 hover:bg-slate-600 text-slate-300 text-xs font-semibold" title="Reset">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="bg-slate-800/90 border border-slate-700/80 rounded-3xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm text-slate-300">
                    <thead class="bg-slate-950 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Booking Ref / Time</th>
                            <th class="px-6 py-4">Customer Details & Area</th>
                            <th class="px-6 py-4">Service & Amount</th>
                            <th class="px-6 py-4">Technician</th>
                            <th class="px-6 py-4">Problem / Photo</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/60 text-slate-200">
                        <?php if (!empty($bookings)): ?>
                            <?php foreach ($bookings as $b): ?>
                                <tr class="hover:bg-slate-750 transition">
                                    <td class="px-6 py-4 font-medium">
                                        <span class="font-mono font-bold text-teal-400 text-sm block"><?= e($b['booking_reference']) ?></span>
                                        <span class="text-xs text-slate-400"><?= format_date($b['preferred_date']) ?></span>
                                        <span class="text-[11px] text-teal-300 block"><?= e($b['preferred_time']) ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-white block"><?= e($b['customer_name']) ?></span>
                                        <span class="text-xs text-slate-400 block"><?= e($b['customer_phone']) ?></span>
                                        <span class="text-[11px] font-semibold text-teal-300 flex items-center gap-1 mt-0.5">
                                            <i data-lucide="map-pin" class="w-3 h-3 text-teal-400"></i>
                                            <?= e($b['area']) ?>
                                        </span>
                                        <span class="text-[10px] text-slate-500 block truncate max-w-xs"><?= e($b['address']) ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-white block"><?= e($b['service_name']) ?></span>
                                        <span class="text-[11px] text-slate-400"><?= e($b['category_name']) ?></span>
                                        <span class="text-xs font-mono font-bold text-teal-400 block mt-1"><?= format_price($b['total_amount']) ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if (!empty($b['technician_name'])): ?>
                                            <span class="font-bold text-slate-200 block"><?= e($b['technician_name']) ?></span>
                                            <span class="text-[11px] text-slate-400"><?= e($b['technician_phone']) ?></span>
                                            <button type="button" 
                                                    class="assign-tech-btn text-[10px] text-teal-400 hover:underline mt-1 block"
                                                    data-id="<?= $b['id'] ?>"
                                                    data-ref="<?= e($b['booking_reference']) ?>"
                                                    data-tech-id="<?= $b['technician_id'] ?>">
                                                Reassign Pro
                                            </button>
                                        <?php else: ?>
                                            <button type="button" 
                                                    class="assign-tech-btn text-xs font-bold text-amber-400 bg-amber-950/80 border border-amber-500/40 px-3 py-1 rounded-lg hover:bg-amber-900 transition"
                                                    data-id="<?= $b['id'] ?>"
                                                    data-ref="<?= e($b['booking_reference']) ?>">
                                                + Assign Pro
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <p class="text-xs text-slate-300 line-clamp-2 italic">"<?= e($b['problem_description']) ?>"</p>
                                        <?php if (!empty($b['image_attachment'])): ?>
                                            <a href="<?= asset($b['image_attachment']) ?>" target="_blank" class="mt-1 inline-flex items-center gap-1 text-[11px] text-teal-400 hover:underline">
                                                <i data-lucide="image" class="w-3 h-3"></i> View Photo Attachment
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <select class="booking-status-select bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-slate-200 focus:outline-none focus:ring-1 focus:ring-teal-500"
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
                                    <td class="px-6 py-4 text-right space-x-1">
                                        <a href="<?= base_url('tracking.php?ref=' . $b['booking_reference']) ?>" target="_blank" class="p-2 rounded-lg bg-slate-700 hover:bg-teal-600 text-white inline-block" title="Live Customer Tracking">
                                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                        </a>
                                        <button type="button" 
                                                class="delete-item-btn p-2 rounded-lg bg-slate-700 hover:bg-rose-600 text-slate-300 hover:text-white inline-block"
                                                data-action="delete_booking"
                                                data-id="<?= $b['id'] ?>"
                                                data-title="Booking <?= $b['booking_reference'] ?>"
                                                title="Delete">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                    No bookings found matching filter criteria.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
