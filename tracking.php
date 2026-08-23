<?php
/**
 * HomeFix Quetta - Live Booking Tracking Page
 */
$pageTitle = 'Live Booking Status Tracking | HomeFix Quetta';
$pageDescription = 'Track your HomeFix service request live. Monitor technician dispatch, arrival time, and job status in Quetta.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$ref = trim($_GET['ref'] ?? '');
$booking = null;

if (!empty($ref)) {
    $booking = Database::fetch(
        "SELECT b.*, s.name as service_name, s.price as service_price, s.duration as service_duration, c.name as category_name, c.icon as category_icon,
                t.name as technician_name, t.phone as technician_phone, t.rating as technician_rating, t.specialty as technician_specialty, t.image as technician_image
         FROM bookings b
         JOIN services s ON b.service_id = s.id
         JOIN categories c ON s.category_id = c.id
         LEFT JOIN technicians t ON b.technician_id = t.id
         WHERE b.booking_reference = ?",
        [$ref]
    );
}

// Status workflow mapping
$statusSteps = ['pending', 'confirmed', 'assigned', 'in_progress', 'completed'];
$currentStatus = $booking['status'] ?? 'pending';
$currentIndex = array_search($currentStatus, $statusSteps);
if ($currentIndex === false && $currentStatus !== 'cancelled') {
    $currentIndex = 0;
}
?>

<!-- Tracking Hero Header -->
<section class="bg-gradient-to-b from-slate-900 via-slate-900 to-slate-850 text-white py-12 lg:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-3xl sm:text-5xl font-extrabold font-heading text-white tracking-tight">
            Track Your Service Status
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-xl mx-auto mt-2 font-normal">
            Enter your 6-digit booking reference code (e.g. <span class="text-teal-400 font-mono font-bold">HFQ-892101</span>) to check the live technician assignment and arrival progress.
        </p>

        <!-- Search Reference Form -->
        <div class="mt-8 max-w-lg mx-auto">
            <form action="<?= base_url('tracking.php') ?>" method="GET" class="flex gap-2 bg-white/10 p-2 rounded-2xl backdrop-blur-md border border-white/15 shadow-2xl">
                <input type="text" name="ref" value="<?= e($ref) ?>" required placeholder="Enter Booking Reference (e.g. HFQ-892101)" class="w-full bg-white text-slate-900 uppercase font-mono font-bold text-sm px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                <button type="submit" class="btn-primary px-6 py-3 rounded-xl text-sm font-bold shrink-0 flex items-center gap-1.5">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    <span>Track</span>
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Tracking Results Section -->
<section class="py-14 bg-slate-50 min-h-[500px]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <?php if (!empty($ref) && $booking): ?>
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/80 shadow-xl space-y-8">
                
                <!-- Status Top Bar -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-6 border-b border-slate-100">
                    <div>
                        <div class="text-xs text-slate-400 uppercase font-bold tracking-wider">Booking Reference</div>
                        <div class="text-2xl sm:text-3xl font-extrabold font-mono text-slate-900 tracking-wider flex items-center gap-3 mt-0.5">
                            <span><?= e($booking['booking_reference']) ?></span>
                            <?= get_status_badge($booking['status']) ?>
                        </div>
                    </div>
                    <div class="text-left sm:text-right">
                        <div class="text-xs text-slate-400">Scheduled Date & Time</div>
                        <div class="text-sm font-bold text-slate-800"><?= format_date($booking['preferred_date']) ?></div>
                        <div class="text-xs font-semibold text-teal-700"><?= e($booking['preferred_time']) ?></div>
                    </div>
                </div>

                <!-- Animated Progress Stepper (GSAP Supported) -->
                <?php if ($currentStatus === 'cancelled'): ?>
                    <div class="p-6 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center shrink-0 text-rose-600">
                            <i data-lucide="x-circle" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-base">This Booking Has Been Cancelled</h4>
                            <p class="text-xs text-rose-600">If you wish to reschedule or need emergency service, please book a new request or call our hotline.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="py-4">
                        <h4 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-6">Service Lifecycle</h4>
                        
                        <div class="grid grid-cols-5 gap-2 relative text-center">
                            
                            <!-- Step 1: Pending -->
                            <div class="space-y-2">
                                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-xs transition duration-300 <?= ($currentIndex >= 0) ? 'bg-teal-600 text-white shadow-md shadow-teal-600/30' : 'bg-slate-100 text-slate-400' ?>">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </div>
                                <div class="text-[11px] font-bold <?= ($currentIndex >= 0) ? 'text-teal-900' : 'text-slate-400' ?>">Requested</div>
                            </div>

                            <!-- Step 2: Confirmed -->
                            <div class="space-y-2">
                                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-xs transition duration-300 <?= ($currentIndex >= 1) ? 'bg-teal-600 text-white shadow-md shadow-teal-600/30' : 'bg-slate-100 text-slate-400' ?>">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </div>
                                <div class="text-[11px] font-bold <?= ($currentIndex >= 1) ? 'text-teal-900' : 'text-slate-400' ?>">Confirmed</div>
                            </div>

                            <!-- Step 3: Assigned -->
                            <div class="space-y-2">
                                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-xs transition duration-300 <?= ($currentIndex >= 2) ? 'bg-teal-600 text-white shadow-md shadow-teal-600/30' : 'bg-slate-100 text-slate-400' ?>">
                                    <i data-lucide="user-check" class="w-4 h-4"></i>
                                </div>
                                <div class="text-[11px] font-bold <?= ($currentIndex >= 2) ? 'text-teal-900' : 'text-slate-400' ?>">Pro Assigned</div>
                            </div>

                            <!-- Step 4: In Progress -->
                            <div class="space-y-2">
                                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-xs transition duration-300 <?= ($currentIndex >= 3) ? 'bg-teal-600 text-white shadow-md shadow-teal-600/30' : 'bg-slate-100 text-slate-400' ?>">
                                    <i data-lucide="wrench" class="w-4 h-4 <?= ($currentIndex === 3) ? 'animate-spin' : '' ?>"></i>
                                </div>
                                <div class="text-[11px] font-bold <?= ($currentIndex >= 3) ? 'text-teal-900' : 'text-slate-400' ?>">In Progress</div>
                            </div>

                            <!-- Step 5: Completed -->
                            <div class="space-y-2">
                                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-xs transition duration-300 <?= ($currentIndex >= 4) ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'bg-slate-100 text-slate-400' ?>">
                                    <i data-lucide="award" class="w-4 h-4"></i>
                                </div>
                                <div class="text-[11px] font-bold <?= ($currentIndex >= 4) ? 'text-emerald-900' : 'text-slate-400' ?>">Completed</div>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>

                <!-- Booking Content Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4 border-t border-slate-100">
                    
                    <!-- Left: Service Details -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold font-heading text-slate-900 uppercase tracking-wide">Service Information</h4>
                        <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl space-y-3 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Service Name:</span>
                                <span class="font-bold text-slate-800"><?= e($booking['service_name']) ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Category:</span>
                                <span class="font-semibold text-teal-700"><?= e($booking['category_name']) ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Total Price:</span>
                                <span class="font-extrabold text-teal-800 text-sm"><?= format_price($booking['total_amount']) ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Quetta Location:</span>
                                <span class="font-semibold text-slate-700"><?= e($booking['area']) ?></span>
                            </div>
                            <div class="pt-2 border-t border-slate-200/60 text-slate-600">
                                <strong>Address:</strong> <?= e($booking['address']) ?>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs space-y-1">
                            <span class="font-bold text-slate-700">Problem Description:</span>
                            <p class="text-slate-600 italic">"<?= e($booking['problem_description']) ?>"</p>
                        </div>
                    </div>

                    <!-- Right: Assigned Technician -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold font-heading text-slate-900 uppercase tracking-wide">Assigned Professional</h4>
                        <?php if (!empty($booking['technician_name'])): ?>
                            <div class="p-5 bg-teal-50/70 border border-teal-200/80 rounded-2xl space-y-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-xl bg-teal-700 text-white font-bold flex items-center justify-center text-lg">
                                        <?= strtoupper(substr($booking['technician_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <h5 class="font-heading font-bold text-base text-slate-900"><?= e($booking['technician_name']) ?></h5>
                                        <p class="text-xs text-teal-700 font-semibold"><?= e($booking['technician_specialty'] ?? 'Vetted Specialist') ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between text-xs pt-2 border-t border-teal-200/60">
                                    <span class="text-slate-600">Pro Contact:</span>
                                    <a href="tel:<?= e($booking['technician_phone']) ?>" class="font-bold text-teal-800 hover:underline flex items-center gap-1">
                                        <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                        <span><?= e($booking['technician_phone']) ?></span>
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl text-center space-y-2">
                                <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center mx-auto">
                                    <i data-lucide="clock" class="w-5 h-5"></i>
                                </div>
                                <h5 class="font-bold text-xs text-slate-700">Assigning Quetta Specialist</h5>
                                <p class="text-[11px] text-slate-500">Our dispatch manager is matching the closest on-duty technician in <?= e($booking['area']) ?>.</p>
                            </div>
                        <?php endif; ?>

                        <!-- Direct Help -->
                        <div class="p-4 bg-slate-900 text-white rounded-2xl flex items-center justify-between text-xs">
                            <div>
                                <div class="font-bold">Need Help With This Booking?</div>
                                <div class="text-slate-400 text-[11px]">Quetta 24/7 Operations Desk</div>
                            </div>
                            <a href="tel:<?= APP_PHONE_RAW ?>" class="btn-primary text-xs font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1">
                                <i data-lucide="phone-call" class="w-3.5 h-3.5"></i> Call Desk
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        <?php elseif (!empty($ref)): ?>
            <div class="bg-white rounded-3xl p-12 border border-slate-200 text-center space-y-4">
                <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto">
                    <i data-lucide="alert-circle" class="w-8 h-8"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-slate-900">Booking Reference Not Found</h3>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    We could not locate any booking matching reference code <strong class="font-mono text-slate-800"><?= e($ref) ?></strong>. Please double check your SMS/email receipt.
                </p>
                <a href="<?= base_url('booking.php') ?>" class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold">
                    Schedule a New Service
                </a>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-3xl p-12 border border-slate-200 text-center space-y-4">
                <div class="w-16 h-16 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center mx-auto">
                    <i data-lucide="map-pin" class="w-8 h-8"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-slate-900">Track Any HomeFix Booking</h3>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Enter your booking reference above to view the technician arrival status, or sign in to view all your previous bookings.
                </p>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
