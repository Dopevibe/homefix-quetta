<?php
/**
 * HomeFix Quetta - Contact Us Page
 */
$pageTitle = 'Contact Us | HomeFix Quetta Support & Dispatch';
$pageDescription = 'Get in touch with HomeFix Quetta. Office in Satellite Town, 24/7 emergency dispatch helpline and WhatsApp support.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Contact Header -->
<section class="bg-gradient-to-b from-slate-900 via-slate-900 to-slate-850 text-white py-14 lg:py-20 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-3xl sm:text-5xl font-extrabold font-heading text-white tracking-tight">
            Contact HomeFix Quetta
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-xl mx-auto mt-2 font-normal">
            Have a question, feedback, or corporate inquiry? Reach our local Quetta team directly by phone, WhatsApp, or contact message.
        </p>
    </div>
</section>

<!-- Main Contact Grid -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Left 5 Cols: Office Details & Direct Channels -->
            <div class="lg:col-span-5 space-y-6">
                
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm space-y-6">
                    <h3 class="text-xl font-bold font-heading text-slate-900">Quetta Headquarters</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Our central office and dispatch center is located in Satellite Town, providing rapid access to all residential and commercial sectors in Quetta.
                    </p>

                    <div class="space-y-4 pt-2 text-xs sm:text-sm text-slate-700">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <strong class="block text-slate-900 font-bold">Office Address:</strong>
                                <span><?= APP_ADDRESS ?></span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0">
                                <i data-lucide="phone-call" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <strong class="block text-slate-900 font-bold">Helpline & Dispatch:</strong>
                                <a href="tel:<?= APP_PHONE_RAW ?>" class="text-teal-700 font-bold hover:underline"><?= APP_PHONE ?></a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <strong class="block text-slate-900 font-bold">Email Inquiries:</strong>
                                <a href="mailto:<?= APP_EMAIL ?>" class="text-teal-700 font-bold hover:underline"><?= APP_EMAIL ?></a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <strong class="block text-slate-900 font-bold">Working Hours:</strong>
                                <span><?= APP_WORKING_HOURS ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <a href="https://wa.me/<?= APP_WHATSAPP ?>" target="_blank" class="w-full py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-md transition">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            <span>Chat Live on WhatsApp Support</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Right 7 Cols: AJAX Contact Form -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-sm space-y-6">
                    <h3 class="text-2xl font-extrabold font-heading text-slate-900">Send Us a Message</h3>
                    <p class="text-xs sm:text-sm text-slate-500">Fill out this quick form and our support desk will respond within 2 business hours.</p>

                    <form id="contactForm" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="contactName" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Your Name <span class="text-rose-500">*</span></label>
                                <input type="text" id="contactName" name="name" required placeholder="e.g. Tariq Shahwani" class="form-input text-sm">
                            </div>
                            <div>
                                <label for="contactEmail" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address <span class="text-rose-500">*</span></label>
                                <input type="email" id="contactEmail" name="email" required placeholder="e.g. tariq@example.com" class="form-input text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="contactPhone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Phone Number</label>
                                <input type="tel" id="contactPhone" name="phone" placeholder="e.g. 0333 1234567" class="form-input text-sm">
                            </div>
                            <div>
                                <label for="contactSubject" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Subject <span class="text-rose-500">*</span></label>
                                <input type="text" id="contactSubject" name="subject" required placeholder="e.g. Maintenance Contract Inquiry" class="form-input text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="contactMessage" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Message <span class="text-rose-500">*</span></label>
                            <textarea id="contactMessage" name="message" rows="5" required placeholder="Tell us how we can assist you..." class="form-input text-sm resize-none"></textarea>
                        </div>

                        <button type="submit" id="submitContactBtn" class="btn-primary w-full py-4 rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-lg">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            <span>Send Message</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Contact AJAX Handler Script -->
<script>
$(document).ready(function() {
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const btn = $('#submitContactBtn');
        const orig = btn.html();

        btn.prop('disabled', true).html('Sending message...');

        $.ajax({
            url: 'ajax/contact.php',
            type: 'POST',
            data: $(form).serialize(),
            dataType: 'json',
            success: function(res) {
                btn.prop('disabled', false).html(orig);
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Message Sent!',
                        text: res.message,
                        customClass: { popup: 'homefix-swal', confirmButton: 'homefix-confirm-btn' }
                    });
                    form.reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Sending Failed',
                        text: res.message,
                        customClass: { popup: 'homefix-swal', confirmButton: 'homefix-confirm-btn' }
                    });
                }
            },
            error: function() {
                btn.prop('disabled', false).html(orig);
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Error',
                    text: 'Unable to reach the server. Please try again.',
                    customClass: { popup: 'homefix-swal', confirmButton: 'homefix-confirm-btn' }
                });
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
