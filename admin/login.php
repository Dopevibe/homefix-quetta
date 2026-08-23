<?php
/**
 * HomeFix Quetta - Admin Login Portal
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin_auth.php';

if (is_admin_logged_in()) {
    header('Location: ' . base_url('admin/dashboard.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | HomeFix Quetta Control Panel</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4 font-sans relative overflow-hidden">
    
    <!-- Subtle Background Elements -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full bg-slate-900 border border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl relative z-10 space-y-6">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-teal-600 flex items-center justify-center text-white mx-auto shadow-lg shadow-teal-600/30">
                <i data-lucide="shield-check" class="w-7 h-7"></i>
            </div>
            <h1 class="text-2xl font-extrabold font-heading text-white">Home<span class="text-teal-400">Fix</span> Admin</h1>
            <p class="text-xs text-slate-400">Restricted Operations Console for Quetta, Balochistan</p>
        </div>


        <!-- Form -->
        <form id="adminLoginForm" class="space-y-4">
            <input type="hidden" name="action" value="login">

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Admin Email</label>
                <input type="email" id="adminEmail" name="email" required placeholder="admin@homefix.pk" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Password</label>
                <input type="password" id="adminPassword" name="password" required placeholder="••••••••" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <button type="submit" id="adminLoginSubmitBtn" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-lg">
                <i data-lucide="shield" class="w-4 h-4"></i>
                <span>Authenticate Admin</span>
            </button>
        </form>

        <div class="text-center pt-2 border-t border-slate-800 text-xs text-slate-500">
            <a href="<?= base_url('index.php') ?>" class="hover:text-teal-400 transition flex items-center justify-center gap-1">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Return to Public Website
            </a>
        </div>

    </div>

    <script>
    lucide.createIcons();

    $('#adminLoginForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const btn = $('#adminLoginSubmitBtn');

        btn.prop('disabled', true).html('<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span>Verifying Authorization...</span>');

        $.ajax({
            url: '../ajax/auth.php',
            type: 'POST',
            data: $(form).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    if (res.data.role !== 'admin') {
                        btn.prop('disabled', false).html('<i data-lucide="shield" class="w-4 h-4"></i><span>Authenticate Admin</span>');
                        lucide.createIcons();
                        Swal.fire({ icon: 'error', title: 'Access Denied', text: 'This account does not have administrative privileges.' });
                        return;
                    }
                    btn.html('<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span>Access Granted — Redirecting...</span>');
                    btn.removeClass('bg-gradient-to-r').addClass('!bg-emerald-600');
                    setTimeout(function() {
                        window.location.href = 'dashboard.php';
                    }, 800);
                } else {
                    btn.prop('disabled', false).html('<i data-lucide="shield" class="w-4 h-4"></i><span>Authenticate Admin</span>');
                    lucide.createIcons();
                    Swal.fire({ icon: 'error', title: 'Authentication Failed', text: res.message });
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i data-lucide="shield" class="w-4 h-4"></i><span>Authenticate Admin</span>');
                lucide.createIcons();
                Swal.fire({ icon: 'error', title: 'Connection Error', text: 'Unable to reach the server. Please try again.' });
            }
        });
    });
    </script>
</body>
</html>
