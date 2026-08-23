<?php
/**
 * HomeFix Quetta - Interactive Booking Page
 */
$pageTitle = 'Book a Service in Quetta | HomeFix Quetta';
$pageDescription = 'Schedule certified plumbers, electricians, painters, and handymen across Quetta, Balochistan. Same-day & scheduled appointments.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch all active services grouped by category
$allServices = Database::fetchAll(
    "SELECT s.*, c.name as category_name 
     FROM services s 
     JOIN categories c ON s.category_id = c.id 
     WHERE s.status = 'active' 
     ORDER BY c.sort_order ASC, s.name ASC"
);

$preselectedServiceId = (int)($_GET['service'] ?? 0);
$preselectedArea = trim($_GET['area'] ?? '');

// Pre-fill customer details if logged in
$currUser = current_user();
$defaultName = $currUser['name'] ?? '';
$defaultEmail = $currUser['email'] ?? '';
$defaultPhone = $currUser['phone'] ?? '';
$defaultArea = $currUser['area'] ?? $preselectedArea;
$defaultAddress = $currUser['address'] ?? '';
?>

<!-- Booking Hero Header -->
<section class="bg-gradient-to-b from-slate-900 via-slate-900 to-slate-850 text-white py-12 lg:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">

        <h1 class="text-3xl sm:text-5xl font-extrabold font-heading text-white tracking-tight">
            Schedule Your Service in Quetta
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-xl mx-auto mt-2 font-normal">
            Select your preferred time slot and neighborhood. Our background-checked technician will arrive with full gear.
        </p>
    </div>
</section>

<!-- Booking Form & Live Summary -->
<section class="py-14 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <form id="bookingForm" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Left 8 Cols: Form Fields -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Section 1: Choose Service -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-teal-100 text-teal-700 font-bold flex items-center justify-center text-sm">
                            1
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-lg text-slate-900">Select Required Service</h3>
                            <p class="text-xs text-slate-500">Choose the exact repair or maintenance package you need</p>
                        </div>
                    </div>

                    <div>
                        <label for="serviceSelect" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Service Package <span class="text-rose-500">*</span></label>
                        <select id="serviceSelect" name="service_id" required class="form-input text-sm">
                            <option value="" disabled <?= empty($preselectedServiceId) ? 'selected' : '' ?>>-- Select a Service --</option>
                            <?php foreach ($allServices as $srv): ?>
                                <option value="<?= $srv['id'] ?>" 
                                        data-price="<?= $srv['price'] ?>" 
                                        data-duration="<?= e($srv['duration']) ?>"
                                        data-category="<?= e($srv['category_name']) ?>"
                                        <?= ($preselectedServiceId === (int)$srv['id']) ? 'selected' : '' ?>>
                                    <?= e($srv['name']) ?> (<?= format_price($srv['price']) ?>) — [<?= e($srv['category_name']) ?>]
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="problemDescription" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Describe the Problem or Scope <span class="text-rose-500">*</span></label>
                        <textarea id="problemDescription" name="problem_description" rows="3" required placeholder="e.g. Water pump not turning on, or kitchen pipe leaking beneath the counter, or circuit breaker tripping..." class="form-input text-sm resize-none"></textarea>
                    </div>

                    <!-- Photo Upload with Live Preview -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Optional Photo of the Fault / Site</label>
                        <div class="border-2 border-dashed border-slate-300 hover:border-teal-500 rounded-2xl p-4 text-center cursor-pointer transition relative bg-slate-50/50">
                            <input type="file" id="problemImageInput" name="problem_image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                            <div class="flex flex-col items-center justify-center gap-1 text-slate-500">
                                <i data-lucide="upload-cloud" class="w-8 h-8 text-teal-600"></i>
                                <p class="text-xs font-medium"><strong class="text-teal-700">Click to upload</strong> or drag & drop</p>
                                <p class="text-[11px] text-slate-400">JPG, PNG or WEBP (Max 5MB)</p>
                            </div>
                        </div>

                        <!-- Live Preview Box -->
                        <div id="imagePreviewBox" class="mt-3 hidden relative inline-block">
                            <img id="imagePreviewImg" src="" alt="Preview" class="w-24 h-24 object-cover rounded-xl border border-slate-200 shadow-md">
                            <button type="button" id="removeImageBtn" class="absolute -top-2 -right-2 w-6 h-6 bg-rose-600 text-white rounded-full flex items-center justify-center text-xs shadow hover:bg-rose-700">
                                ×
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Date & Time in Quetta -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-slate-900 text-white font-bold flex items-center justify-center text-sm">
                            2
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-lg text-slate-900">Schedule Preferred Appointment</h3>
                            <p class="text-xs text-slate-500">Select when you want our technician to visit your property</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="preferredDate" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Preferred Date <span class="text-rose-500">*</span></label>
                            <input type="date" id="preferredDate" name="preferred_date" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" required class="form-input text-sm">
                        </div>
                        <div>
                            <label for="preferredTime" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Preferred Time Slot <span class="text-rose-500">*</span></label>
                            <select id="preferredTime" name="preferred_time" required class="form-input text-sm">
                                <?php foreach (TIME_SLOTS as $slot): ?>
                                    <option value="<?= $slot ?>" <?= ($slot === '10:00 AM - 12:00 PM') ? 'selected' : '' ?>><?= $slot ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Address & Contact Details in Quetta -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-slate-900 text-white font-bold flex items-center justify-center text-sm">
                            3
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-lg text-slate-900">Quetta Location & Contact</h3>
                            <p class="text-xs text-slate-500">Where should our technician report for the service?</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="customerName" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" id="customerName" name="customer_name" value="<?= e($defaultName) ?>" required placeholder="e.g. Farhan Baloch" class="form-input text-sm">
                        </div>
                        <div>
                            <label for="customerPhone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Mobile Phone / WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="tel" id="customerPhone" name="customer_phone" value="<?= e($defaultPhone) ?>" required placeholder="e.g. 0333 7819201" class="form-input text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="customerEmail" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address (Optional)</label>
                            <input type="email" id="customerEmail" name="customer_email" value="<?= e($defaultEmail) ?>" placeholder="e.g. name@example.com" class="form-input text-sm">
                        </div>
                        <div>
                            <label for="areaSelect" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Quetta Neighborhood / Sector <span class="text-rose-500">*</span></label>
                            <select id="areaSelect" name="area" required class="form-input text-sm">
                                <option value="" disabled selected>-- Select Area in Quetta --</option>
                                <?php foreach (QUETTA_AREAS as $area): ?>
                                    <option value="<?= $area ?>" <?= ($defaultArea === $area) ? 'selected' : '' ?>><?= $area ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="customerAddress" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Complete Street Address / House # <span class="text-rose-500">*</span></label>
                        <input type="text" id="customerAddress" name="address" value="<?= e($defaultAddress) ?>" required placeholder="e.g. House 45, Street 4, Sector B, Near Government Girls College" class="form-input text-sm">
                    </div>

                    <div>
                        <label for="bookingNotes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Special Instructions / Gate Landmark (Optional)</label>
                        <input type="text" id="bookingNotes" name="notes" placeholder="e.g. Green gate next to Al-Falah Mosque" class="form-input text-sm">
                    </div>
                </div>

            </div>

            <!-- Right 4 Cols: Sticky Order Summary & Submit -->
            <div class="lg:col-span-4 space-y-6">
                
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xl sticky top-28 space-y-6">
                    <h3 class="text-xl font-bold font-heading text-slate-900 border-b border-slate-100 pb-4">
                        Booking Summary
                    </h3>

                    <!-- Selected Service Details -->
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between items-start">
                            <span class="text-slate-500">Service:</span>
                            <span id="summaryServiceTitle" class="font-bold text-slate-800 text-right max-w-[180px]">Select a Service</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Category:</span>
                            <span id="serviceEstimateCategory" class="font-semibold text-slate-700">General</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Est. Duration:</span>
                            <span id="serviceEstimateDuration" class="font-mono text-slate-700">1 - 2 Hours</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">City / Region:</span>
                            <span class="font-semibold text-teal-800 bg-teal-50 px-2 py-0.5 rounded">Quetta, Balochistan</span>
                        </div>
                    </div>

                    <!-- Pricing Total -->
                    <div class="p-4 bg-teal-50/80 border border-teal-200/80 rounded-2xl text-center space-y-1">
                        <span class="text-[11px] font-bold text-teal-800 uppercase tracking-wider">Estimated Total</span>
                        <div id="summaryTotalPrice" class="text-3xl font-extrabold text-teal-900 font-heading">Rs. 0</div>
                        <p class="text-[10px] text-teal-700">No payment required now. Pay upon job completion.</p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBookingBtn" class="btn-primary w-full py-4 rounded-xl font-extrabold text-sm flex items-center justify-center gap-2 shadow-lg">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        <span>Confirm & Submit Booking</span>
                    </button>

                    <!-- Trust Points -->
                    <div class="space-y-2.5 pt-2 border-t border-slate-100 text-xs text-slate-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                            <span>100% CNIC verified technicians</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="badge-check" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                            <span>30-Day post-repair guarantee</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="phone-call" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                            <span>Quetta Helpline: <?= APP_PHONE ?></span>
                        </div>
                    </div>
                </div>

            </div>

        </form>

    </div>
</section>

<!-- Booking JS -->
<script src="<?= asset('assets/js/booking.js') ?>"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
