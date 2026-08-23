<?php
/**
 * HomeFix Quetta - Customer Login Page
 */
$pageTitle = 'Sign In | HomeFix Quetta';
$pageDescription = 'Sign in to your HomeFix Quetta account to manage and track your home service bookings.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

if (isset($_SESSION['user_id'])) {
    if (($_SESSION['user_role'] ?? '') === 'admin') {
        header('Location: ' . base_url('admin/dashboard.php'));
    } else {
        header('Location: ' . base_url('dashboard.php'));
    }
    exit;
}
?>

<section class="min-h-[calc(100vh-160px)] flex items-center justify-center py-14 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xl page-enter opacity-0">
        
        <!-- Header -->
        <div class="text-center">
            <div class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center mx-auto mb-3 shadow-md shadow-teal-600/30">
                <i data-lucide="lock" class="w-6 h-6"></i>
            </div>
            <h2 class="text-2xl font-extrabold font-heading text-slate-900">Welcome Back</h2>
            <p class="text-xs text-slate-500 mt-1">Sign in to your HomeFix Quetta account</p>
        </div>

        <!-- Login Form -->
        <form id="customerLoginForm" class="space-y-4 hf-form" novalidate>
            <input type="hidden" name="action" value="login">
            
            <div class="form-group">
                <label for="loginEmail" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                <input type="email" id="loginEmail" name="email" required placeholder="name@example.com" class="form-input text-sm w-full">
            </div>

            <div class="form-group">
                <div class="flex justify-between items-center mb-1.5">
                    <label for="loginPassword" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                </div>
                <div class="relative">
                    <input type="password" id="loginPassword" name="password" required placeholder="••••••••" class="form-input text-sm pr-10 w-full">
                    <button type="button" id="togglePasswordBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <button type="submit" id="loginSubmitBtn" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-lg transition-all active:scale-[0.98]">
                <i data-lucide="log-in" class="w-4 h-4"></i>
                <span>Sign In</span>
            </button>
        </form>

        <!-- Footer Switch -->
        <div class="text-center pt-2 border-t border-slate-100 text-xs text-slate-500">
            Don't have an account yet? 
            <a href="<?= base_url('register.php') ?>" class="font-bold text-teal-700 hover:underline ml-1">Create Account</a>
        </div>

    </div>
</section>

<script>
$(document).ready(function() {
    // Entrance Animation
    if (typeof gsap !== 'undefined') {
        gsap.to('.page-enter', { opacity: 1, y: 0, duration: 0.6, ease: "power3.out" });
    } else {
        $('.page-enter').removeClass('opacity-0').css('opacity', 1);
    }

    // Toggle Password Visibility
    $('#togglePasswordBtn').on('click', function() {
        const input = $('#loginPassword');
        const icon = $(this).find('svg, i');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.replaceWith('<i data-lucide="eye-off" class="w-4 h-4"></i>');
        } else {
            input.attr('type', 'password');
            icon.replaceWith('<i data-lucide="eye" class="w-4 h-4"></i>');
        }
        lucide.createIcons();
    });

    // Inline Validation
    $('#loginEmail').on('blur', function() {
        const val = $(this).val();
        if (val && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            HF.showFieldError(this, 'Please enter a valid email address');
        } else {
            HF.clearFieldError(this);
        }
    });

    $('#loginPassword').on('blur', function() {
        if ($(this).val().length > 0 && $(this).val().length < 6) {
            HF.showFieldError(this, 'Password must be at least 6 characters');
        } else {
            HF.clearFieldError(this);
        }
    });

    // Submit AJAX
    $('#customerLoginForm').on('submit', function(e) {
        e.preventDefault();
        
        // Simple pre-validation
        let hasError = false;
        if (!$('#loginEmail').val()) {
            HF.showFieldError('#loginEmail', 'Email is required');
            hasError = true;
        }
        if (!$('#loginPassword').val()) {
            HF.showFieldError('#loginPassword', 'Password is required');
            hasError = true;
        }
        if (hasError) return;

        const form = this;
        const btn = $('#loginSubmitBtn');
        const origContent = btn.html();

        if (typeof HF !== 'undefined' && HF.btnLoading) {
            HF.btnLoading(btn, 'Authenticating...');
        } else {
            btn.prop('disabled', true).html('Authenticating...');
        }

        $.ajax({
            url: 'ajax/auth.php',
            type: 'POST',
            data: $(form).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    if (typeof HF !== 'undefined' && HF.btnSuccess) {
                        HF.btnSuccess(btn, 'Success! Redirecting...');
                    } else {
                        btn.html('Success!');
                    }
                    setTimeout(function() {
                        window.location.href = res.data.redirect || 'dashboard.php';
                    }, 800);
                } else {
                    if (typeof HF !== 'undefined' && HF.btnReset) {
                        HF.btnReset(btn, origContent);
                    } else {
                        btn.prop('disabled', false).html(origContent);
                    }
                    if (res.message.toLowerCase().includes('password')) {
                        HF.showFieldError('#loginPassword', res.message);
                    } else {
                        HF.showFieldError('#loginEmail', res.message);
                    }
                }
            },
            error: function() {
                if (typeof HF !== 'undefined' && HF.btnReset) {
                    HF.btnReset(btn, origContent);
                    HF.toast('error', 'Connection Error. Please try again.');
                } else {
                    btn.prop('disabled', false).html(origContent);
                }
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
