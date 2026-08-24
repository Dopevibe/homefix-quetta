<?php
/**
 * HomeFix Quetta - Global Footer
 */
?>
<footer class="bg-slate-950 text-slate-400 mt-auto border-t border-slate-800">
    <!-- Top Highlights Banner -->
    <div class="border-b border-slate-800 bg-slate-900/70 py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="bg-slate-900 border border-slate-800 hover:border-teal-500/40 rounded-2xl p-6 transition-all duration-300 shadow-md flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-teal-500/15 border border-teal-500/30 text-teal-400 flex items-center justify-center shrink-0 shadow-sm">
                        <i data-lucide="shield-check" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-extrabold text-base mb-1">Verified Technicians</h4>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">Background checked & vetted in Quetta</p>
                    </div>
                </div>

                <div class="bg-slate-900 border border-slate-800 hover:border-teal-500/40 rounded-2xl p-6 transition-all duration-300 shadow-md flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-teal-500/15 border border-teal-500/30 text-teal-400 flex items-center justify-center shrink-0 shadow-sm">
                        <i data-lucide="badge-percent" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-extrabold text-base mb-1">Upfront Fixed Rates</h4>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">No hidden surprises or extra charges</p>
                    </div>
                </div>

                <div class="bg-slate-900 border border-slate-800 hover:border-teal-500/40 rounded-2xl p-6 transition-all duration-300 shadow-md flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-teal-500/15 border border-teal-500/30 text-teal-400 flex items-center justify-center shrink-0 shadow-sm">
                        <i data-lucide="award" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-extrabold text-base mb-1">30-Day Warranty</h4>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">Complete quality guarantee on all repairs</p>
                    </div>
                </div>

                <div class="bg-slate-900 border border-slate-800 hover:border-teal-500/40 rounded-2xl p-6 transition-all duration-300 shadow-md flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-teal-500/15 border border-teal-500/30 text-teal-400 flex items-center justify-center shrink-0 shadow-sm">
                        <i data-lucide="zap" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-extrabold text-base mb-1">Rapid Arrival</h4>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">Under 45 mins average across Quetta</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Main Footer Links -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
            
            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-600 flex items-center justify-center text-white">
                        <i data-lucide="wrench" class="w-5 h-5"></i>
                    </div>
                    <span class="text-2xl font-extrabold font-heading text-white">Home<span class="text-teal-400">Fix</span> Quetta</span>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed max-w-sm">
                    Quetta’s premier on-demand home repair & maintenance platform. Connecting homeowners with trusted local electricians, plumbers, painters, and handymen across Balochistan.
                </p>
                <div class="pt-2 flex items-center gap-3">
                    <a href="https://wa.me/<?= APP_WHATSAPP ?>" target="_blank" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-teal-600 text-white flex items-center justify-center transition">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                    </a>
                    <a href="tel:<?= APP_PHONE_RAW ?>" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-teal-600 text-white flex items-center justify-center transition">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                    </a>
                    <a href="mailto:<?= APP_EMAIL ?>" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-teal-600 text-white flex items-center justify-center transition">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <!-- Service Categories -->
            <div>
                <h4 class="text-white font-semibold text-sm mb-4 font-heading tracking-wide">Popular Services</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="<?= base_url('services.php?category=plumbing') ?>" class="hover:text-teal-400 transition">Plumbing & Water Tanks</a></li>
                    <li><a href="<?= base_url('services.php?category=electrical') ?>" class="hover:text-teal-400 transition">Electrical & Solar UPS</a></li>
                    <li><a href="<?= base_url('services.php?category=painting') ?>" class="hover:text-teal-400 transition">Wall Painting & Stucco</a></li>
                    <li><a href="<?= base_url('services.php?category=handyman') ?>" class="hover:text-teal-400 transition">Handyman & Repairs</a></li>
                </ul>
            </div>

            <!-- Quetta Neighborhoods -->
            <div>
                <h4 class="text-white font-semibold text-sm mb-4 font-heading tracking-wide">Quetta Service Areas</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="<?= base_url('booking.php?area=Zarghoon+Road') ?>" class="hover:text-teal-400 transition">Zarghoon Road Hub</a></li>
                    <li><a href="<?= base_url('booking.php?area=Jinnah+Town') ?>" class="hover:text-teal-400 transition">Jinnah Town & Samungli</a></li>
                    <li><a href="<?= base_url('booking.php?area=Cantt') ?>" class="hover:text-teal-400 transition">Quetta Cantonment</a></li>
                    <li><a href="<?= base_url('booking.php?area=Satellite+Town') ?>" class="hover:text-teal-400 transition">Satellite Town & Double Rd</a></li>
                    <li><a href="<?= base_url('booking.php?area=Model+Town') ?>" class="hover:text-teal-400 transition">Model Town & Airport Rd</a></li>
                    <li><a href="<?= base_url('booking.php?area=Brewery+Road') ?>" class="hover:text-teal-400 transition">Brewery & Spiny Road</a></li>
                </ul>
            </div>

            <!-- Contact & Office -->
            <div>
                <h4 class="text-white font-semibold text-sm mb-4 font-heading tracking-wide">Quetta HQ</h4>
                <div class="space-y-3 text-sm text-slate-400">
                    <div class="flex items-start gap-2.5">
                        <i data-lucide="map-pin" class="w-4 h-4 text-teal-400 shrink-0 mt-1"></i>
                        <span><?= APP_ADDRESS ?></span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="phone" class="w-4 h-4 text-teal-400 shrink-0"></i>
                        <span><?= APP_PHONE ?></span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="mail" class="w-4 h-4 text-teal-400 shrink-0"></i>
                        <span><?= APP_EMAIL ?></span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="clock" class="w-4 h-4 text-teal-400 shrink-0"></i>
                        <span><?= APP_WORKING_HOURS ?></span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Copyright -->
        <div class="border-t border-slate-900 mt-12 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
            <p>© <?= date('Y') ?> HomeFix Quetta. All rights reserved. Built for Balochistan.</p>
            <div class="flex items-center gap-6">
                <a href="<?= base_url('about.php') ?>" class="hover:text-slate-400 transition">About Us</a>
                <a href="<?= base_url('services.php') ?>" class="hover:text-slate-400 transition">Services</a>
                <a href="<?= base_url('contact.php') ?>" class="hover:text-slate-400 transition">Contact Support</a>
                <a href="<?= base_url('admin/login.php') ?>" class="text-slate-600 hover:text-teal-400 transition">Admin Portal</a>
            </div>
        </div>
    </div>
</footer>

<!-- Floating WhatsApp Support Button -->
<a href="https://wa.me/<?= APP_WHATSAPP ?>?text=<?= urlencode('Hello HomeFix Quetta, I need assistance booking a home service.') ?>" target="_blank" class="fixed bottom-6 right-6 z-40 bg-emerald-500 hover:bg-emerald-600 text-white p-3.5 rounded-full shadow-2xl hover:scale-110 transition duration-300 flex items-center justify-center group" title="Chat on WhatsApp">
    <i data-lucide="message-circle" class="w-7 h-7"></i>
    <span class="max-w-0 overflow-hidden whitespace-nowrap group-hover:max-w-xs group-hover:ml-2 transition-all duration-300 ease-in-out text-sm font-semibold pr-1">Chat With Support</span>
</a>

<!-- Scripts -->
<script src="<?= asset('assets/js/main.js') ?>"></script>
<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
</body>
</html>
