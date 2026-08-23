<?php
/**
 * HomeFix Quetta - Customer Login Page
 */
$pageTitle = 'Sign In | HomeFix Quetta';
$pageDescription = 'Sign in to your HomeFix Quetta account to manage and track your home service bookings.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

if (is_customer_logged_in()) {
    $redirect = $_GET['redirect'] ?? 'dashboard.php';
    header('Location: ' . base_url($redirect));
    exit;
}

$notice = $_GET['notice'] ?? '';
$redirectTo = $_GET['redirect'] ?? '';
?>

<section class="min-h-[calc(100vh-160px)] flex items-center justify-center py-14 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xl page-enter opacity-0">
        
        <!-- Header -->
        <div class="text-center">
            <div class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center mx-auto mb-3 shadow-md shadow-teal-600/30">
                <i data-lucide="lock" class="w-6 h-6"></i>
            </div>
            <h2 class="text-2xl font-extrabold font-heading text-slate-900">Welcome Back</h2>
            <p class="text-xs text-slate-500 mt-1">Sign in to your HomeFix Quetta customer account</p>
        </div>

        <?php if ($notice === 'login_to_book' || $notice === 'auth_required'): ?>
            <div class="p-3.5 rounded-2xl text-xs font-semibold bg-teal-50 border border-teal-200 text-teal-800 flex items-center gap-2.5 shadow-sm">
                <i data-lucide="info" class="w-4 h-4 text-teal-600 shrink-0"></i>
                <span>Please sign in to schedule and track your service in Quetta.</span>
            </div>
        <?php endif; ?>

        <!-- Alert Box for Error / Notice -->
        <div id="loginAlertBox" class="hidden p-3.5 rounded-2xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-700 flex items-center gap-2.5">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
            <span id="loginAlertMsg">Invalid email or password.</span>
        </div>

        <!-- Login Form -->
        <form id="customerLoginForm" class="space-y-4 hf-form" novalidate>
            <input type="hidden" name="action" value="login">
            <?php if (!empty($redirectTo)): ?>
                <input type="hidden" name="redirect_to" value="<?= e($redirectTo) ?>">
            <?php endif; ?>
            
            <div class="form-group space-y-1.5">
                <label for="loginEmail" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
                <div class="relative">
                    <input type="email" id="loginEmail" name="email" required placeholder="name@example.com" class="form-input text-sm w-full">
                </div>
                <div id="emailErrorMsg" class="hidden text-xs text-rose-600 font-semibold mt-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Please enter your email address</span>
                </div>
            </div>

            <div class="form-group space-y-1.5">
                <div class="flex justify-between items-center">
                    <label for="loginPassword" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                </div>
                <div class="relative">
                    <input type="password" id="loginPassword" name="password" required placeholder="••••••••" class="form-input text-sm pr-10 w-full">
                    <button type="button" id="togglePasswordBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1" aria-label="Toggle password visibility">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="passwordErrorMsg" class="hidden text-xs text-rose-600 font-semibold mt-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Please enter your password</span>
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
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    function showEmailErr(msg) {
        $('#loginEmail').addClass('is-invalid border-rose-500 ring-1 ring-rose-500');
        $('#emailErrorMsg').find('span').text(msg);
        $('#emailErrorMsg').removeClass('hidden').addClass('flex');
    }

    function clearEmailErr() {
        $('#loginEmail').removeClass('is-invalid border-rose-500 ring-1 ring-rose-500');
        $('#emailErrorMsg').addClass('hidden').removeClass('flex');
    }

    function showPassErr(msg) {
        $('#loginPassword').addClass('is-invalid border-rose-500 ring-1 ring-rose-500');
        $('#passwordErrorMsg').find('span').text(msg);
        $('#passwordErrorMsg').removeClass('hidden').addClass('flex');
    }

    function clearPassErr() {
        $('#loginPassword').removeClass('is-invalid border-rose-500 ring-1 ring-rose-500');
        $('#passwordErrorMsg').addClass('hidden').removeClass('flex');
    }

    function showAlert(msg) {
        $('#loginAlertMsg').text(msg);
        $('#loginAlertBox').removeClass('hidden').addClass('flex');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function hideAlert() {
        $('#loginAlertBox').addClass('hidden').removeClass('flex');
    }

    // Real-time input clearing
    $('#loginEmail').on('input', function() {
        clearEmailErr();
        hideAlert();
    });

    $('#loginPassword').on('input', function() {
        clearPassErr();
        hideAlert();
    });

    // Blur Validation
    $('#loginEmail').on('blur', function() {
        const val = $(this).val().trim();
        if (val && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            showEmailErr('Please enter a valid email address (e.g. name@example.com)');
        }
    });

    $('#loginPassword').on('blur', function() {
        const val = $(this).val();
        if (val.length > 0 && val.length < 6) {
            showPassErr('Password must be at least 6 characters');
        }
    });

    // Submit AJAX
    $('#customerLoginForm').on('submit', function(e) {
        e.preventDefault();
        hideAlert();
        
        const email = $('#loginEmail').val().trim();
        const password = $('#loginPassword').val();
        let hasError = false;

        if (!email) {
            showEmailErr('Please enter your email address');
            hasError = true;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showEmailErr('Please enter a valid email address format');
            hasError = true;
        }

        if (!password) {
            showPassErr('Please enter your account password');
            hasError = true;
        } else if (password.length < 6) {
            showPassErr('Password must be at least 6 characters');
            hasError = true;
        }

        if (hasError) {
            showAlert('Please fill in both email and password correctly.');
            return;
        }

        const form = this;
        const btn = $('#loginSubmitBtn');
        const origContent = btn.html();

        if (typeof HF !== 'undefined' && HF.btnLoading) {
            HF.btnLoading(btn, 'Authenticating...');
        } else {
            btn.prop('disabled', true).html('<i data-lucide="loader" class="w-4 h-4 animate-spin inline-block mr-2"></i> Authenticating...');
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
                    if (typeof HF !== 'undefined') {
                        HF.toast('success', res.message);
                    }
                    setTimeout(function() {
                        window.location.href = (res.data && res.data.redirect) ? res.data.redirect : 'dashboard.php';
                    }, 600);
                } else {
                    if (typeof HF !== 'undefined' && HF.btnReset) {
                        HF.btnReset(btn);
                    } else {
                        btn.prop('disabled', false).html(origContent);
                    }
                    showAlert(res.message || 'Invalid email or password. Please try again.');
                    showEmailErr('Check your email');
                    showPassErr('Check your password');
                    if (typeof HF !== 'undefined') {
                        HF.toast('error', res.message || 'Authentication failed');
                    }
                }
            },
            error: function() {
                if (typeof HF !== 'undefined' && HF.btnReset) {
                    HF.btnReset(btn);
                } else {
                    btn.prop('disabled', false).html(origContent);
                }
                showAlert('Connection error. Please check your internet connection and try again.');
                if (typeof HF !== 'undefined') {
                    HF.toast('error', 'Connection Error. Please try again.');
                }
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
