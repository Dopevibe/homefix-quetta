<?php
/**
 * HomeFix Quetta - Customer Dashboard
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_auth();

$user = Database::fetch("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
if (!$user) {
    header('Location: ' . base_url('logout.php'));
    exit;
}

$pageTitle = 'Customer Dashboard | HomeFix Quetta';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

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

        <!-- Bookings List -->
        <div id="bookings" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-heading font-bold text-lg text-slate-900">Your Booking History</h3>
                <span class="text-xs text-slate-500 font-semibold"><?= count($bookings) ?> Records</span>
            </div>

            <?php if (!empty($bookings)): ?>
                <div class="overflow-x-auto">
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

        <!-- Profile Update & Security Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Update Profile -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-lg text-slate-900">Personal Information</h3>
                <form id="profileUpdateForm" class="space-y-4">
                    <input type="hidden" name="action" value="update_profile">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name</label>
                        <input type="text" name="name" value="<?= e($user['name']) ?>" required class="form-input text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Mobile Phone</label>
                        <input type="tel" name="phone" value="<?= e($user['phone']) ?>" required class="form-input text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Quetta Neighborhood</label>
                        <select name="area" class="form-input text-sm">
                            <option value="">-- Select Neighborhood --</option>
                            <?php foreach (QUETTA_AREAS as $area): ?>
                                <option value="<?= $area ?>" <?= ($user['area'] === $area) ? 'selected' : '' ?>><?= $area ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Saved Address</label>
                        <input type="text" name="address" value="<?= e($user['address'] ?? '') ?>" placeholder="Street / House details in Quetta" class="form-input text-sm">
                    </div>
                    <button type="submit" id="saveProfileBtn" class="btn-primary px-6 py-2.5 rounded-xl text-xs font-bold">
                        Save Profile Changes
                    </button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-lg text-slate-900">Security & Password</h3>
                <form id="passwordChangeForm" class="space-y-4">
                    <input type="hidden" name="action" value="change_password">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Current Password</label>
                        <input type="password" name="current_password" required placeholder="••••••••" class="form-input text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">New Password</label>
                        <input type="password" name="new_password" required placeholder="Min. 6 chars" class="form-input text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Confirm New Password</label>
                        <input type="password" name="confirm_password" required placeholder="Repeat new password" class="form-input text-sm">
                    </div>
                    <button type="submit" id="savePasswordBtn" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition">
                        Update Password
                    </button>
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
        $.ajax({
            url: 'ajax/auth.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    HF.toast('success', res.message);
                } else {
                    HF.toast('error', res.message);
                }
            }
        });
    });

    // Password Update AJAX
    $('#passwordChangeForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax/auth.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    HF.toast('success', res.message);
                    $('#passwordChangeForm')[0].reset();
                } else {
                    HF.toast('error', res.message);
                }
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
