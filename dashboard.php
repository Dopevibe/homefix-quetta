<?php
/**
 * HomeFix Quetta - Customer Dashboard
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_customer();

$customer = current_customer();
$user = Database::fetch("SELECT * FROM users WHERE id = ?", [$customer['id']]);
if (!$user) {
    customer_logout();
    header('Location: ' . base_url('login.php'));
    exit;
}

$pageTitle = 'Customer Dashboard | HomeFix Quetta';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Active tab detection
$activeTab = trim($_GET['tab'] ?? 'bookings');
if ($activeTab === 'profile' || $activeTab === 'personal') {
    $activeTab = 'profile';
} elseif ($activeTab === 'security' || $activeTab === 'password') {
    $activeTab = 'security';
} else {
    $activeTab = 'bookings';
}

// Fetch Customer Bookings with service and technician details
$bookings = Database::fetchAll(
    "SELECT b.*, s.name as service_name, s.price as service_price, c.name as category_name, c.icon as category_icon,
            t.name as technician_name, t.phone as technician_phone,
            r.id as review_id, r.rating as review_rating
     FROM bookings b
     JOIN services s ON b.service_id = s.id
     JOIN categories c ON s.category_id = c.id
     LEFT JOIN technicians t ON b.technician_id = t.id
     LEFT JOIN reviews r ON b.id = r.booking_id
     WHERE b.user_id = ?
     ORDER BY b.created_at DESC",
    [$user['id']]
);

// Calculate Stats
$totalCount = count($bookings);
$pendingCount = 0;
$completedCount = 0;
$cancelledCount = 0;

foreach ($bookings as $b) {
    if (in_array($b['status'], ['pending', 'confirmed', 'assigned', 'in_progress'])) {
        $pendingCount++;
    } else if ($b['status'] === 'completed') {
        $completedCount++;
    } else if ($b['status'] === 'cancelled') {
        $cancelledCount++;
    }
}
?>

<!-- Customer Header -->
<section class="bg-gradient-to-b from-slate-900 via-slate-900 to-slate-850 text-white py-10 lg:py-14 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-teal-600 flex items-center justify-center font-heading font-extrabold text-2xl text-white shadow-xl shadow-teal-600/30">
                <?= strtoupper(substr($user['name'], 0, 1)) ?>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-white"><?= e($user['name']) ?></h1>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-teal-500/20 text-teal-300 border border-teal-500/30">
                        <?= e($user['area'] ?? 'Quetta Resident') ?>
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1"><?= e($user['email']) ?> • <?= e($user['phone']) ?></p>
            </div>
        </div>

        <div>
            <a href="<?= base_url('booking.php') ?>" class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold shadow-lg">
                <i data-lucide="calendar-plus" class="w-4 h-4"></i>
                <span>Book New Service</span>
            </a>
        </div>
    </div>
</section>

<!-- Main Dashboard Content -->
<section class="py-10 bg-slate-50 min-h-[600px]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Stats Row -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-lg shrink-0">
                    <i data-lucide="calendar" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold font-heading text-slate-900"><?= $totalCount ?></div>
                    <div class="text-xs text-slate-400 font-medium">Total Bookings</div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg shrink-0">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold font-heading text-amber-600"><?= $pendingCount ?></div>
                    <div class="text-xs text-slate-400 font-medium">Active / Pending</div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg shrink-0">
                    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold font-heading text-emerald-600"><?= $completedCount ?></div>
                    <div class="text-xs text-slate-400 font-medium">Completed Jobs</div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg shrink-0">
                    <i data-lucide="x-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold font-heading text-slate-700"><?= $cancelledCount ?></div>
                    <div class="text-xs text-slate-400 font-medium">Cancelled</div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs for Mobile & Desktop -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-4 overflow-x-auto">
            <button type="button" class="dash-tab-btn px-4 sm:px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold flex items-center gap-2 transition <?= ($activeTab === 'bookings') ? 'active bg-teal-600 text-white shadow-md shadow-teal-600/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' ?> whitespace-nowrap" data-target="#tabBookings">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span>My Bookings (<?= count($bookings) ?>)</span>
            </button>
            <button type="button" class="dash-tab-btn px-4 sm:px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold flex items-center gap-2 transition <?= ($activeTab === 'profile') ? 'active bg-teal-600 text-white shadow-md shadow-teal-600/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' ?> whitespace-nowrap" data-target="#tabProfile">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span>Personal Information</span>
            </button>
            <button type="button" class="dash-tab-btn px-4 sm:px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold flex items-center gap-2 transition <?= ($activeTab === 'security') ? 'active bg-teal-600 text-white shadow-md shadow-teal-600/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' ?> whitespace-nowrap" data-target="#tabSecurity">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span>Security & Password</span>
            </button>
        </div>

        <!-- TAB 1: BOOKINGS -->
        <div id="tabBookings" class="dash-tab-content space-y-6 <?= ($activeTab === 'bookings') ? '' : 'hidden' ?>">
            
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="font-heading font-bold text-lg text-slate-900">Your Booking History</h3>
                        <p class="text-xs text-slate-500">Track current jobs or view completed repair records.</p>
                    </div>
                    <span class="text-xs font-mono font-bold text-teal-700 bg-teal-50 px-3 py-1.5 rounded-xl border border-teal-100">
                        <?= count($bookings) ?> Orders
                    </span>
                </div>

                <?php if (!empty($bookings)): ?>
                    <!-- Desktop Table View (Hidden on mobile) -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left text-xs sm:text-sm">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4">Reference / Date</th>
                                    <th class="px-6 py-4">Service & Quetta Area</th>
                                    <th class="px-6 py-4">Amount</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Technician</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                <?php foreach ($bookings as $b): ?>
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-6 py-4 font-medium">
                                            <a href="<?= base_url('tracking.php?ref=' . $b['booking_reference']) ?>" class="font-mono font-bold text-teal-700 hover:underline block">
                                                <?= e($b['booking_reference']) ?>
                                            </a>
                                            <span class="text-xs text-slate-400"><?= format_date($b['preferred_date']) ?> • <?= e($b['preferred_time']) ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-slate-900 block"><?= e($b['service_name']) ?></span>
                                            <span class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                                <i data-lucide="map-pin" class="w-3 h-3 text-teal-600"></i>
                                                <?= e($b['area']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-bold font-mono text-teal-900">
                                            <?= format_price($b['total_amount']) ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?= get_status_badge($b['status']) ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if (!empty($b['technician_name'])): ?>
                                                <span class="font-semibold text-slate-800 block"><?= e($b['technician_name']) ?></span>
                                                <a href="tel:<?= e($b['technician_phone']) ?>" class="text-[11px] text-teal-700 hover:underline"><?= e($b['technician_phone']) ?></a>
                                            <?php else: ?>
                                                <span class="text-slate-400 italic text-xs">Assigning Pro...</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <a href="<?= base_url('tracking.php?ref=' . $b['booking_reference']) ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-teal-50 text-teal-700 font-semibold text-xs hover:bg-teal-100 transition">
                                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Track
                                            </a>

                                            <?php if ($b['status'] === 'completed'): ?>
                                                <?php if (empty($b['review_id'])): ?>
                                                    <button type="button" 
                                                            class="leave-review-btn inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 font-semibold text-xs hover:bg-amber-100 transition"
                                                            data-booking-id="<?= $b['id'] ?>"
                                                            data-service-id="<?= $b['service_id'] ?>"
                                                            data-service-name="<?= e($b['service_name']) ?>">
                                                        <i data-lucide="star" class="w-3.5 h-3.5"></i> Rate Job
                                                    </button>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-semibold">
                                                        <i data-lucide="check" class="w-3 h-3"></i> Reviewed (<?= $b['review_rating'] ?>★)
                                                    </span>
                                                <?php endif; ?>
                                            <?php elseif (in_array($b['status'], ['pending', 'confirmed'])): ?>
                                                <button type="button" 
                                                        class="cancel-booking-btn inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 font-semibold text-xs hover:bg-rose-100 transition"
                                                        data-id="<?= $b['id'] ?>"
                                                        data-ref="<?= $b['booking_reference'] ?>">
                                                    Cancel
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Booking Cards View (100% responsive, zero horizontal scrolling) -->
                    <div class="divide-y divide-slate-100 md:hidden">
                        <?php foreach ($bookings as $b): ?>
                            <div class="p-4 sm:p-5 space-y-3.5 bg-white">
                                <!-- Top Bar: Ref & Status -->
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <a href="<?= base_url('tracking.php?ref=' . $b['booking_reference']) ?>" class="font-mono font-bold text-teal-700 text-xs block hover:underline">
                                            <?= e($b['booking_reference']) ?>
                                        </a>
                                        <h4 class="text-sm font-extrabold font-heading text-slate-900 leading-snug mt-0.5"><?= e($b['service_name']) ?></h4>
                                    </div>
                                    <div class="shrink-0">
                                        <?= get_status_badge($b['status']) ?>
                                    </div>
                                </div>

                                <!-- Metadata Box -->
                                <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100 space-y-1.5 text-xs text-slate-600">
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400 font-medium">Timing:</span>
                                        <span class="font-semibold text-slate-800"><?= format_date($b['preferred_date']) ?> • <?= e($b['preferred_time']) ?></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400 font-medium">Area:</span>
                                        <span class="font-semibold text-teal-700 flex items-center gap-1">
                                            <i data-lucide="map-pin" class="w-3 h-3"></i>
                                            <?= e($b['area']) ?>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center pt-1 border-t border-slate-200/60">
                                        <span class="text-slate-400 font-medium">Total Amount:</span>
                                        <span class="font-mono font-extrabold text-sm text-teal-800"><?= format_price($b['total_amount']) ?></span>
                                    </div>
                                    <?php if (!empty($b['technician_name'])): ?>
                                        <div class="flex justify-between items-center pt-1 border-t border-slate-200/60">
                                            <span class="text-slate-400 font-medium">Technician:</span>
                                            <span class="font-bold text-slate-800"><?= e($b['technician_name']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2 pt-1">
                                    <a href="<?= base_url('tracking.php?ref=' . $b['booking_reference']) ?>" class="flex-1 btn-primary py-2 rounded-xl text-xs font-bold text-center inline-flex items-center justify-center gap-1.5 shadow-sm">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                        <span>Live Tracking</span>
                                    </a>

                                    <?php if ($b['status'] === 'completed'): ?>
                                        <?php if (empty($b['review_id'])): ?>
                                            <button type="button" 
                                                    class="leave-review-btn px-4 py-2 rounded-xl bg-amber-500 text-white font-bold text-xs hover:bg-amber-600 transition inline-flex items-center gap-1 shadow-sm"
                                                    data-booking-id="<?= $b['id'] ?>"
                                                    data-service-id="<?= $b['service_id'] ?>"
                                                    data-service-name="<?= e($b['service_name']) ?>">
                                                <i data-lucide="star" class="w-3.5 h-3.5 fill-white"></i> Rate
                                            </button>
                                        <?php else: ?>
                                            <span class="px-3 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                                                <?= $b['review_rating'] ?>★ Done
                                            </span>
                                        <?php endif; ?>
                                    <?php elseif (in_array($b['status'], ['pending', 'confirmed'])): ?>
                                        <button type="button" 
                                                class="cancel-booking-btn px-4 py-2 rounded-xl bg-rose-50 text-rose-700 font-bold text-xs border border-rose-200 hover:bg-rose-100 transition"
                                                data-id="<?= $b['id'] ?>"
                                                data-ref="<?= $b['booking_reference'] ?>">
                                            Cancel
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="p-12 text-center space-y-3">
                        <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                            <i data-lucide="calendar" class="w-7 h-7"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 text-base">No Bookings Yet</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">You haven't requested any home repair services yet. When you do, they'll appear here with live tracking.</p>
                        <a href="<?= base_url('booking.php') ?>" class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold mt-2">
                            Schedule Your First Service
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- TAB 2: PERSONAL INFORMATION -->
        <div id="tabProfile" class="dash-tab-content space-y-6 <?= ($activeTab === 'profile') ? '' : 'hidden' ?>">
            <div class="max-w-2xl bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-lg text-slate-900">Personal Information</h3>
                        <p class="text-xs text-slate-500">Update your contact details and home address in Quetta.</p>
                    </div>
                </div>

                <form id="profileUpdateForm" class="space-y-4">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Full Name</label>
                        <input type="text" name="name" value="<?= e($user['name']) ?>" required class="form-input text-sm w-full">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Primary Email Address</label>
                        <input type="email" value="<?= e($user['email']) ?>" disabled class="form-input text-sm w-full bg-slate-100 text-slate-500 cursor-not-allowed">
                        <span class="text-[11px] text-slate-400 block">Your login email identifier cannot be changed directly.</span>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Mobile Phone</label>
                        <input type="tel" name="phone" value="<?= e($user['phone']) ?>" required class="form-input text-sm w-full font-mono">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Quetta Neighborhood</label>
                        <select name="area" class="form-input text-sm w-full">
                            <option value="">-- Select Neighborhood --</option>
                            <?php foreach (QUETTA_AREAS as $area): ?>
                                <option value="<?= $area ?>" <?= ($user['area'] === $area) ? 'selected' : '' ?>><?= $area ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Saved Address / Landmark</label>
                        <input type="text" name="address" value="<?= e($user['address'] ?? '') ?>" placeholder="House #, Street #, Sector, Quetta" class="form-input text-sm w-full">
                    </div>

                    <div class="pt-2">
                        <button type="submit" id="saveProfileBtn" class="btn-primary w-full sm:w-auto px-6 py-3 rounded-xl text-xs font-bold shadow-md">
                            Save Profile Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB 3: SECURITY & PASSWORD -->
        <div id="tabSecurity" class="dash-tab-content space-y-6 <?= ($activeTab === 'security') ? '' : 'hidden' ?>">
            <div class="max-w-2xl bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-lg text-slate-900">Security & Password</h3>
                        <p class="text-xs text-slate-500">Update your account password with encryption.</p>
                    </div>
                </div>

                <form id="passwordChangeForm" class="space-y-4">
                    <input type="hidden" name="action" value="change_password">
                    
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Current Password</label>
                        <input type="password" name="current_password" required placeholder="••••••••" class="form-input text-sm w-full">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">New Password</label>
                        <input type="password" name="new_password" required placeholder="Minimum 6 characters" class="form-input text-sm w-full">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Confirm New Password</label>
                        <input type="password" name="confirm_password" required placeholder="Repeat new password" class="form-input text-sm w-full">
                    </div>

                    <div class="pt-2">
                        <button type="submit" id="savePasswordBtn" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition shadow-md">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

<!-- Review Submission Modal -->
<div id="reviewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-200 space-y-5">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <div>
                <h3 class="font-heading font-bold text-lg text-slate-900">Rate Your Service</h3>
                <p id="reviewModalServiceName" class="text-xs text-teal-700 font-semibold"></p>
            </div>
            <button type="button" id="closeReviewModal" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="submitReviewForm" class="space-y-4">
            <input type="hidden" id="reviewModalBookingId" name="booking_id">
            <input type="hidden" id="reviewModalServiceId" name="service_id">

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Your Rating (1 to 5 Stars)</label>
                <div class="flex items-center gap-2" id="starRatingGroup">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <button type="button" class="star-select-btn text-amber-400 hover:scale-110 transition p-1" data-val="<?= $i ?>">
                            <i data-lucide="star" class="w-7 h-7 fill-amber-400"></i>
                        </button>
                    <?php endfor; ?>
                </div>
                <input type="hidden" id="selectedRatingInput" name="rating" value="5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Your Feedback / Review</label>
                <textarea name="review_text" rows="4" required placeholder="How was the technician's punctuality, technical skill, and cleanliness in Quetta?" class="form-input text-sm resize-none"></textarea>
            </div>

            <button type="submit" id="submitReviewBtn" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm">
                Submit Customer Review
            </button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Tab Navigation
    function activateTab(tabId, updateUrl = false) {
        let cleanName = 'bookings';
        if (!tabId) tabId = '#tabBookings';
        if (tabId === 'profile' || tabId === 'tabProfile' || tabId === '#tabProfile') {
            tabId = '#tabProfile';
            cleanName = 'profile';
        } else if (tabId === 'security' || tabId === 'tabSecurity' || tabId === '#tabSecurity') {
            tabId = '#tabSecurity';
            cleanName = 'security';
        } else {
            tabId = '#tabBookings';
            cleanName = 'bookings';
        }

        const btn = $('.dash-tab-btn[data-target="' + tabId + '"]');
        if (btn.length) {
            $('.dash-tab-btn').removeClass('active bg-teal-600 text-white shadow-md shadow-teal-600/20')
                              .addClass('bg-white text-slate-600 hover:bg-slate-100 border border-slate-200');
            
            btn.addClass('active bg-teal-600 text-white shadow-md shadow-teal-600/20')
               .removeClass('bg-white text-slate-600 hover:bg-slate-100 border border-slate-200');
            
            $('.dash-tab-content').addClass('hidden');
            $(tabId).removeClass('hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();

            if (updateUrl && window.history && window.history.replaceState) {
                const newUrl = window.location.pathname + '?tab=' + cleanName;
                window.history.replaceState({ tab: cleanName }, '', newUrl);
            }
        }
    }

    $('.dash-tab-btn').on('click', function() {
        const target = $(this).data('target');
        activateTab(target, true);
    });

    // Check URL query param ?tab=... or hash #... on load
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab') || window.location.hash.replace('#', '');
    if (tabParam) {
        activateTab(tabParam, false);
    }

    // Review Modal Opening
    $(document).on('click', '.leave-review-btn', function() {
        const bookingId = $(this).data('booking-id');
        const serviceId = $(this).data('service-id');
        const serviceName = $(this).data('service-name');

        $('#reviewModalBookingId').val(bookingId);
        $('#reviewModalServiceId').val(serviceId);
        $('#reviewModalServiceName').text(serviceName);
        $('#reviewModal').removeClass('hidden').addClass('flex');
    });

    $('#closeReviewModal').on('click', function() {
        $('#reviewModal').addClass('hidden').removeClass('flex');
    });

    // Star Selector
    $('.star-select-btn').on('click', function() {
        const val = $(this).data('val');
        $('#selectedRatingInput').val(val);
        $('.star-select-btn').each(function() {
            const btnVal = $(this).data('val');
            if (btnVal <= val) {
                $(this).find('svg').addClass('fill-amber-400 text-amber-400').removeClass('text-slate-300 fill-transparent');
            } else {
                $(this).find('svg').removeClass('fill-amber-400 text-amber-400').addClass('text-slate-300 fill-transparent');
            }
        });
    });

    // Submit Review AJAX
    $('#submitReviewForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const btn = $('#submitReviewBtn');
        btn.prop('disabled', true).html('Submitting...');

        $.ajax({
            url: 'ajax/reviews.php',
            type: 'POST',
            data: $(form).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thank You!',
                        text: res.message,
                        customClass: { popup: 'homefix-swal', confirmButton: 'homefix-confirm-btn' }
                    }).then(() => location.reload());
                } else {
                    btn.prop('disabled', false).html('Submit Customer Review');
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                }
            }
        });
    });

    // Cancel Booking Action
    $(document).on('click', '.cancel-booking-btn', function() {
        const id = $(this).data('id');
        const ref = $(this).data('ref');

        Swal.fire({
            title: 'Cancel Booking ' + ref + '?',
            text: 'Are you sure you want to cancel this scheduled service request in Quetta?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Cancel It',
            confirmButtonColor: '#EF4444',
            cancelButtonText: 'Keep Booking',
            customClass: { popup: 'homefix-swal', cancelButton: 'homefix-cancel-btn' }
        }).then((res) => {
            if (res.isConfirmed) {
                $.ajax({
                    url: 'ajax/bookings.php',
                    type: 'POST',
                    data: { action: 'cancel', booking_id: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Cancelled', text: response.message }).then(() => location.reload());
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                        }
                    }
                });
            }
        });
    });

    // Profile Update AJAX
    $('#profileUpdateForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const btn = $('#saveProfileBtn');
        const origText = btn.html();

        if (typeof HF !== 'undefined' && HF.btnLoading) {
            HF.btnLoading(btn, 'Saving...');
        } else {
            btn.prop('disabled', true).html('Saving...');
        }

        $.ajax({
            url: 'ajax/auth.php',
            type: 'POST',
            data: $(form).serialize(),
            dataType: 'json',
            success: function(res) {
                if (typeof HF !== 'undefined' && HF.btnReset) {
                    HF.btnReset(btn);
                } else {
                    btn.prop('disabled', false).html(origText);
                }
                if (res.success) {
                    if (typeof HF !== 'undefined') {
                        HF.toast('success', res.message);
                    } else {
                        alert(res.message);
                    }
                } else {
                    if (typeof HF !== 'undefined') {
                        HF.toast('error', res.message);
                    } else {
                        alert(res.message);
                    }
                }
            },
            error: function() {
                if (typeof HF !== 'undefined' && HF.btnReset) {
                    HF.btnReset(btn);
                    HF.toast('error', 'Connection error. Please try again.');
                } else {
                    btn.prop('disabled', false).html(origText);
                }
            }
        });
    });

    // Password Update AJAX
    $('#passwordChangeForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const btn = $('#savePasswordBtn');
        const origText = btn.html();

        if (typeof HF !== 'undefined' && HF.btnLoading) {
            HF.btnLoading(btn, 'Updating...');
        } else {
            btn.prop('disabled', true).html('Updating...');
        }

        $.ajax({
            url: 'ajax/auth.php',
            type: 'POST',
            data: $(form).serialize(),
            dataType: 'json',
            success: function(res) {
                if (typeof HF !== 'undefined' && HF.btnReset) {
                    HF.btnReset(btn);
                } else {
                    btn.prop('disabled', false).html(origText);
                }
                if (res.success) {
                    if (typeof HF !== 'undefined') {
                        HF.toast('success', res.message);
                    } else {
                        alert(res.message);
                    }
                    $('#passwordChangeForm')[0].reset();
                } else {
                    if (typeof HF !== 'undefined') {
                        HF.toast('error', res.message);
                    } else {
                        alert(res.message);
                    }
                }
            },
            error: function() {
                if (typeof HF !== 'undefined' && HF.btnReset) {
                    HF.btnReset(btn);
                    HF.toast('error', 'Connection error. Please try again.');
                } else {
                    btn.prop('disabled', false).html(origText);
                }
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
