<?php
/**
 * HomeFix Quetta - Customer Registration Page
 */
$pageTitle = 'Create Account | HomeFix Quetta';
$pageDescription = 'Sign up for a free HomeFix Quetta account to book, manage, and track home repairs across Quetta.';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('dashboard.php'));
    exit;
}
?>

<section class="min-h-[calc(100vh-160px)] flex items-center justify-center py-14 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-xl w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xl page-enter opacity-0">
        
        <!-- Header -->
        <div class="text-center">
            <div class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center mx-auto mb-3 shadow-md shadow-teal-600/30">
                <i data-lucide="user-plus" class="w-6 h-6"></i>
            </div>
            <h2 class="text-2xl font-extrabold font-heading text-slate-900">Create Customer Account</h2>
            <p class="text-xs text-slate-500 mt-1">Join thousands of homeowners trusting HomeFix Quetta</p>
        </div>

        <!-- Registration Form -->
        <form id="customerRegisterForm" class="space-y-4 hf-form" novalidate>
            <input type="hidden" name="action" value="register">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="regName" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                    <input type="text" id="regName" name="name" required placeholder="e.g. Farhan Baloch" class="form-input text-sm w-full">
                </div>
                <div class="form-group">
                    <label for="regEmail" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                    <input type="email" id="regEmail" name="email" required placeholder="farhan@example.com" class="form-input text-sm w-full">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="regPhone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Phone Number <span class="text-rose-500">*</span></label>
                    <input type="tel" id="regPhone" name="phone" required placeholder="0333 7819201" class="form-input text-sm w-full">
                </div>
                <div class="form-group">
                    <label for="regArea" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Quetta Area / Sector</label>
                    <select id="regArea" name="area" class="form-input text-sm w-full">
                        <option value="" disabled selected>-- Select Neighborhood --</option>
                        <?php foreach (QUETTA_AREAS as $area): ?>
                            <option value="<?= $area ?>"><?= $area ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="regAddress" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Address</label>
                <input type="text" id="regAddress" name="address" placeholder="House/Street details in Quetta" class="form-input text-sm w-full">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="regPassword" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password <span class="text-rose-500">*</span></label>
                    <input type="password" id="regPassword" name="password" required placeholder="At least 6 chars" class="form-input text-sm w-full">
                    <div class="password-strength mt-2" id="passwordStrengthBar" style="display:none">
                        <div class="strength-bar h-1 rounded-full bg-slate-200 overflow-hidden">
                            <div class="h-full transition-all duration-300 w-0"></div>
                        </div>
                        <span class="strength-label text-[10px] font-semibold mt-1 block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="regConfirmPassword" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Confirm Password <span class="text-rose-500">*</span></label>
                    <input type="password" id="regConfirmPassword" name="confirm_password" required placeholder="Repeat password" class="form-input text-sm w-full">
                    <span class="match-indicator text-[10px] font-semibold mt-1 block hidden"></span>
                </div>
            </div>

            <button type="submit" id="registerSubmitBtn" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-lg transition-all active:scale-[0.98]">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>Complete Registration</span>
            </button>
        </form>

        <!-- Footer Switch -->
        <div class="text-center pt-2 border-t border-slate-100 text-xs text-slate-500">
            Already have an account? 
            <a href="<?= base_url('login.php') ?>" class="font-bold text-teal-700 hover:underline ml-1">Sign In</a>
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

    // Inline validation on blur for required fields
    $('#customerRegisterForm input[required]').on('blur', function() {
        if (!$(this).val()) {
            if (typeof HF !== 'undefined' && HF.showFieldError) {
                HF.showFieldError(this, 'This field is required');
            }
        } else {
            if (typeof HF !== 'undefined' && HF.clearFieldError) {
                HF.clearFieldError(this);
            }
        }
    });

    $('#regEmail').on('blur', function() {
        const val = $(this).val();
        if (val && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            if (typeof HF !== 'undefined' && HF.showFieldError) {
                HF.showFieldError(this, 'Please enter a valid email address');
            }
        }
    });

    // Password strength indicator
    $('#regPassword').on('input', function() {
        const val = $(this).val();
        const strengthDiv = $('#passwordStrengthBar');
        const bar = strengthDiv.find('.h-full');
        const label = strengthDiv.find('.strength-label');

        if (val.length === 0) {
            strengthDiv.hide();
            return;
        }
        
        strengthDiv.show();
        
        let strength = 0;
        if (val.length >= 6) strength += 25;
        if (val.length >= 8) strength += 25;
        if (/[A-Z]/.test(val)) strength += 25;
        if (/[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val)) strength += 25;

        // Visual update
        bar.removeClass('bg-rose-500 bg-amber-500 bg-teal-500 bg-emerald-500');
        
        if (strength <= 25) {
            bar.addClass('bg-rose-500').css('width', '25%');
            label.text('Weak').removeClass('text-amber-500 text-teal-500 text-emerald-500').addClass('text-rose-500');
        } else if (strength <= 50) {
            bar.addClass('bg-amber-500').css('width', '50%');
            label.text('Fair').removeClass('text-rose-500 text-teal-500 text-emerald-500').addClass('text-amber-500');
        } else if (strength <= 75) {
            bar.addClass('bg-teal-500').css('width', '75%');
            label.text('Good').removeClass('text-rose-500 text-amber-500 text-emerald-500').addClass('text-teal-500');
        } else {
            bar.addClass('bg-emerald-500').css('width', '100%');
            label.text('Strong').removeClass('text-rose-500 text-amber-500 text-teal-500').addClass('text-emerald-500');
        }
    });

    // Confirm password match check
    $('#regConfirmPassword, #regPassword').on('input', function() {
        const p1 = $('#regPassword').val();
        const p2 = $('#regConfirmPassword').val();
        const indicator = $('.match-indicator');
        
        if (p2.length === 0) {
            indicator.addClass('hidden');
            return;
        }
        
        indicator.removeClass('hidden');
        if (p1 === p2) {
            indicator.text('Passwords match').removeClass('text-rose-500').addClass('text-emerald-500');
            if (typeof HF !== 'undefined' && HF.clearFieldError) {
                HF.clearFieldError('#regConfirmPassword');
            }
        } else {
            indicator.text('Passwords do not match').removeClass('text-emerald-500').addClass('text-rose-500');
        }
    });

    // Submit handler
    $('#customerRegisterForm').on('submit', function(e) {
        e.preventDefault();
        
        // Final check
        const p1 = $('#regPassword').val();
        const p2 = $('#regConfirmPassword').val();
        if (p1 !== p2) {
            if (typeof HF !== 'undefined' && HF.showFieldError) {
                HF.showFieldError('#regConfirmPassword', 'Passwords do not match');
            }
            return;
        }

        const form = this;
        const btn = $('#registerSubmitBtn');
        const origContent = btn.html();

        if (typeof HF !== 'undefined' && HF.btnLoading) {
            HF.btnLoading(btn, 'Creating Account...');
        } else {
            btn.prop('disabled', true).html('Creating Account...');
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
                    setTimeout(() => {
                        window.location.href = res.data.redirect || 'dashboard.php';
                    }, 800);
                } else {
                    if (typeof HF !== 'undefined' && HF.btnReset) {
                        HF.btnReset(btn, origContent);
                    } else {
                        btn.prop('disabled', false).html(origContent);
                    }
                    
                    if (res.message.toLowerCase().includes('email')) {
                        if (typeof HF !== 'undefined' && HF.showFieldError) {
                            HF.showFieldError('#regEmail', res.message);
                        }
                    } else {
                        if (typeof HF !== 'undefined' && HF.toast) {
                            HF.toast('error', res.message);
                        }
                    }
                }
            },
            error: function() {
                if (typeof HF !== 'undefined' && HF.btnReset) {
                    HF.btnReset(btn, origContent);
                    HF.toast('error', 'Server unreachable. Please try again later.');
                } else {
                    btn.prop('disabled', false).html(origContent);
                }
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
