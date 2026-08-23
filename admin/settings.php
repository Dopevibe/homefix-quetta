<?php
/**
 * HomeFix Quetta - Professional Admin Account Settings & Security
 * Platform: Full-Stack PHP 8+ / MySQL
 * Location: Quetta, Balochistan, Pakistan
 */
$adminPageTitle = 'Account Settings | HomeFix Quetta Admin';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

// Fetch current administrator profile directly from database
$adminId = $adminUser['id'] ?? ($_SESSION['user_id'] ?? 0);
$adminData = Database::fetch("SELECT * FROM users WHERE id = ?", [$adminId]) ?: [];

// Fallback to session values if DB record empty
$adminName = $adminData['name'] ?? ($_SESSION['user_name'] ?? 'Administrator');
$adminEmail = $adminData['email'] ?? ($_SESSION['user_email'] ?? 'admin@homefix.pk');
$adminPhone = $adminData['phone'] ?? ($_SESSION['user_phone'] ?? '+92 331 7374824');
$adminRole = ucfirst($adminData['role'] ?? ($_SESSION['user_role'] ?? 'admin'));
$adminStatus = ucfirst($adminData['status'] ?? 'active');
$adminCreated = !empty($adminData['created_at']) ? format_date($adminData['created_at'], 'M d, Y') : 'Active Member';
$adminUpdated = !empty($adminData['updated_at']) ? format_date($adminData['updated_at'], 'M d, Y \a\t h:i A') : 'Recently';
$adminInitial = strtoupper(substr($adminName, 0, 1));

// Resolve admin avatar image URL
$adminAvatarUrl = null;
if (!empty($adminData['avatar'])) {
    if (file_exists(ROOT_PATH . '/uploads/' . $adminData['avatar'])) {
        $adminAvatarUrl = asset('uploads/' . $adminData['avatar']);
    } elseif (file_exists(ROOT_PATH . '/' . $adminData['avatar'])) {
        $adminAvatarUrl = asset($adminData['avatar']);
    }
}
if (!$adminAvatarUrl && file_exists(ROOT_PATH . '/assets/images/avatars/admin.jpg')) {
    $adminAvatarUrl = asset('assets/images/avatars/admin.jpg');
}
?>

<!-- Main Content Wrapper -->
<div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-900">

    <!-- Top Navigation Header -->
    <header class="h-16 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-6 shrink-0 z-20">
        <div class="flex items-center gap-4">
            <button id="adminSidebarToggle" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition" aria-label="Toggle sidebar">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <div>
                <!-- Subtle Breadcrumb -->
                <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-0.5">
                    <span>Admin</span>
                    <i data-lucide="chevron-right" class="w-3 h-3 text-slate-600"></i>
                    <span class="text-teal-400 font-medium">Settings</span>
                </div>
                <h1 class="text-lg font-extrabold font-heading text-white tracking-tight">Account Settings</h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="<?= base_url('admin/dashboard.php') ?>" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-800 text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/80 transition">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Back to Dashboard</span>
            </a>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span><?= e($adminStatus) ?></span>
            </span>
        </div>
    </header>

    <!-- Scrollable Main Container -->
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 pb-32 sm:pb-16 lg:pb-12 space-y-6">
        <div class="max-w-6xl mx-auto space-y-6">

            <!-- Subtitle Intro Banner -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2 border-b border-slate-800/60">
                <div>
                    <h2 class="text-xl font-extrabold font-heading text-white tracking-tight">Administrator Preferences</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Manage your administrator profile, security preferences, and account information.</p>
                </div>
            </div>

            <!-- Two-Column Grid: Profile (Left) & Security (Right) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- Column 1: Profile & Personal Info (7 cols on desktop) -->
                <div class="lg:col-span-7 bg-slate-950 border border-slate-800 rounded-2xl p-5 sm:p-7 shadow-lg shadow-black/20 space-y-6">
                    
                    <!-- Profile Header / Overview Box with Interactive Avatar Upload -->
                    <div class="flex items-center gap-4 pb-5 border-b border-slate-800/80">
                        <div class="relative shrink-0">
                            <label for="avatarFileInput" class="group relative cursor-pointer block rounded-2xl overflow-hidden ring-2 ring-teal-500/30 hover:ring-teal-400 transition shadow-md shadow-black/40" title="Click to upload a new profile photo">
                                <?php if ($adminAvatarUrl): ?>
                                    <img id="avatarPreviewImg" src="<?= $adminAvatarUrl ?>" alt="<?= e($adminName) ?>" class="w-16 h-16 rounded-2xl object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div id="avatarFallbackBadge" class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-teal-700 via-teal-600 to-teal-500 text-white font-extrabold font-heading text-xl items-center justify-center shadow-md shadow-teal-900/30" style="display:none;">
                                        <?= e($adminInitial) ?>
                                    </div>
                                <?php else: ?>
                                    <img id="avatarPreviewImg" src="" alt="<?= e($adminName) ?>" class="w-16 h-16 rounded-2xl object-cover hidden">
                                    <div id="avatarFallbackBadge" class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-teal-700 via-teal-600 to-teal-500 text-white font-extrabold font-heading text-xl flex items-center justify-center shadow-md shadow-teal-900/30">
                                        <?= e($adminInitial) ?>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Hover Overlay -->
                                <div class="absolute inset-0 bg-slate-950/75 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex flex-col items-center justify-center text-white text-[10px] font-bold">
                                    <i data-lucide="camera" class="w-4 h-4 mb-0.5 text-teal-400"></i>
                                    <span>Change</span>
                                </div>
                            </label>
                            <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-slate-950 flex items-center justify-center" title="Account Active"></span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 id="profileNameDisplay" class="text-base font-bold font-heading text-white truncate"><?= e($adminName) ?></h3>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold tracking-wider uppercase bg-teal-500/10 text-teal-400 border border-teal-500/20">
                                    <?= e($adminRole) ?>
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 truncate mt-0.5"><?= e($adminEmail) ?></p>
                            <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                                <label for="avatarFileInput" class="text-[11px] text-teal-400 hover:text-teal-300 font-semibold cursor-pointer inline-flex items-center gap-1 transition">
                                    <i data-lucide="upload-cloud" class="w-3 h-3"></i> Upload New Photo
                                </label>
                                <span class="text-slate-600 text-xs">•</span>
                                <span class="text-[11px] text-slate-500">JPG, PNG, WEBP (Max 5MB)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Editable Profile Form -->
                    <form id="adminProfileForm" class="space-y-4" enctype="multipart/form-data">
                        <!-- Hidden Avatar File Input -->
                        <input type="file" id="avatarFileInput" name="avatar" accept="image/jpeg,image/png,image/webp" class="hidden">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div>
                                <label for="adminNameInput" class="block text-xs font-semibold text-slate-300 mb-1.5">
                                    Full Name <span class="text-teal-400">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="adminNameInput" name="name" value="<?= e($adminName) ?>" required
                                           placeholder="e.g. HomeFix Admin"
                                           class="w-full bg-slate-900/90 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500/30 transition">
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="adminPhoneInput" class="block text-xs font-semibold text-slate-300 mb-1.5">
                                    Phone Number <span class="text-teal-400">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="adminPhoneInput" name="phone" value="<?= e($adminPhone) ?>" required
                                           placeholder="e.g. +92 331 7374824"
                                           class="w-full bg-slate-900/90 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500/30 transition">
                                </div>
                            </div>
                        </div>

                        <!-- Email Address (Read-only Login Identifier) -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                                Primary Email Address
                            </label>
                            <div class="relative">
                                <input type="email" value="<?= e($adminEmail) ?>" disabled
                                       class="w-full bg-slate-900/40 border border-slate-800/80 rounded-xl px-3.5 py-2.5 text-sm text-slate-400 cursor-not-allowed select-none">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-600">
                                    <i data-lucide="lock" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1.5 flex items-center gap-1">
                                <i data-lucide="info" class="w-3 h-3 text-slate-500 shrink-0"></i>
                                <span>Primary login identifier for the administrative console.</span>
                            </p>
                        </div>

                        <!-- Action Controls -->
                        <div class="pt-3 border-t border-slate-800/80 flex items-center justify-end gap-3">
                            <button type="button" id="resetProfileBtn" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-900 transition">
                                Reset
                            </button>
                            <button type="submit" id="saveProfileBtn"
                                    class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-xs shadow-md shadow-teal-900/30 transition duration-150">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </form>

                </div>

                <!-- Column 2: Security & Password (5 cols on desktop) -->
                <div class="lg:col-span-5 bg-slate-950 border border-slate-800 rounded-2xl p-5 sm:p-7 shadow-lg shadow-black/20 space-y-5">
                    
                    <!-- Section Title & Subtitle -->
                    <div>
                        <div class="flex items-center gap-2 text-white font-bold font-heading text-base">
                            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center shrink-0">
                                <i data-lucide="shield" class="w-4 h-4"></i>
                            </div>
                            <span>Security & Authentication</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Protect your administrator account with a strong password.</p>
                    </div>

                    <!-- Password Security Status Callout -->
                    <div class="p-3.5 rounded-xl bg-amber-500/5 border border-amber-500/15 flex items-start gap-3">
                        <div class="w-5 h-5 rounded-md bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="key-round" class="w-3 h-3"></i>
                        </div>
                        <div class="text-[11px] leading-relaxed text-slate-300">
                            <span class="font-bold text-amber-300 block mb-0.5">Password Authentication</span>
                            Password protection is active with strong BCRYPT hashing. Never share your administrative credentials.
                        </div>
                    </div>

                    <!-- Change Password Form -->
                    <form id="adminPasswordForm" class="space-y-4">
                        
                        <!-- Current Password -->
                        <div>
                            <label for="currentPasswordInput" class="block text-xs font-semibold text-slate-300 mb-1.5">
                                Current Password <span class="text-amber-400">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="currentPasswordInput" name="current_password" required
                                       placeholder="Enter current password"
                                       class="w-full bg-slate-900/90 border border-slate-800 rounded-xl pl-3.5 pr-10 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition">
                                <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 hover:text-slate-300 transition" data-target="currentPasswordInput" aria-label="Toggle current password visibility">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="newPasswordInput" class="block text-xs font-semibold text-slate-300 mb-1.5">
                                New Password <span class="text-amber-400">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="newPasswordInput" name="new_password" required minlength="6"
                                       placeholder="Minimum 6 characters"
                                       class="w-full bg-slate-900/90 border border-slate-800 rounded-xl pl-3.5 pr-10 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition">
                                <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 hover:text-slate-300 transition" data-target="newPasswordInput" aria-label="Toggle new password visibility">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>

                            <!-- Password Strength Indicator -->
                            <div class="mt-2 space-y-1.5" id="strengthContainer">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-slate-400 font-medium">Strength:</span>
                                    <span id="strengthLabel" class="font-bold text-slate-400">Too Short</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden">
                                    <div id="strengthBar" class="h-full w-0 bg-rose-500 rounded-full transition-all duration-300"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label for="confirmPasswordInput" class="block text-xs font-semibold text-slate-300 mb-1.5">
                                Confirm New Password <span class="text-amber-400">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="confirmPasswordInput" name="confirm_password" required minlength="6"
                                       placeholder="Re-enter new password"
                                       class="w-full bg-slate-900/90 border border-slate-800 rounded-xl pl-3.5 pr-10 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition">
                                <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 hover:text-slate-300 transition" data-target="confirmPasswordInput" aria-label="Toggle confirm password visibility">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <span id="passwordMatchMessage" class="text-[11px] mt-1 block hidden"></span>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-3 border-t border-slate-800/80">
                            <button type="submit" id="savePasswordBtn"
                                    class="w-full bg-amber-600 hover:bg-amber-500 active:scale-[0.98] text-white font-bold py-2.5 px-4 rounded-xl text-xs transition duration-150 flex items-center justify-center gap-2 shadow-md shadow-amber-900/20">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                                <span>Update Password</span>
                            </button>
                        </div>
                    </form>

                </div>

            </div>

            <!-- Bottom Account Information & Security Audit Card -->
            <div class="bg-slate-950/60 border border-slate-800 rounded-2xl p-5 sm:p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-800/80">
                    <i data-lucide="info" class="w-4 h-4 text-teal-400"></i>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300 font-heading">Account Overview & System Metadata</h3>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800/60">
                        <span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider mb-0.5">Account Role</span>
                        <span class="font-bold text-slate-200"><?= e($adminRole) ?></span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800/60">
                        <span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider mb-0.5">Account Status</span>
                        <span class="font-bold text-emerald-400 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            <span><?= e($adminStatus) ?></span>
                        </span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800/60">
                        <span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider mb-0.5">Member Since</span>
                        <span class="font-bold text-slate-200"><?= e($adminCreated) ?></span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800/60">
                        <span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider mb-0.5">Encryption</span>
                        <span class="font-bold text-teal-400">BCRYPT Standard</span>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>

<!-- Client-side Logic & Micro-Interactions -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Helper Toast Dispatcher
    const showToast = (type, title, message) => {
        const textContent = message || title;
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                html: `<div style="color:#f8fafc; font-size:13px; font-weight:600; text-align:left; padding-left:4px;">${textContent}</div>`,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#0B0F17',
                customClass: {
                    popup: 'border border-slate-700/80 rounded-xl shadow-2xl'
                }
            });
        } else if (typeof HF !== 'undefined' && HF.toast) {
            HF.toast(type, textContent);
        } else {
            alert(textContent);
        }
    };

    // Password Visibility Toggle
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (!input) return;

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            this.innerHTML = isPassword 
                ? '<i data-lucide="eye-off" class="w-4 h-4 text-teal-400"></i>' 
                : '<i data-lucide="eye" class="w-4 h-4 text-slate-500"></i>';

            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    });

    // Password Strength Meter
    const newPassInput = document.getElementById('newPasswordInput');
    const strengthBar = document.getElementById('strengthBar');
    const strengthLabel = document.getElementById('strengthLabel');

    if (newPassInput && strengthBar && strengthLabel) {
        newPassInput.addEventListener('input', function () {
            const val = this.value;
            let score = 0;

            if (val.length >= 6) score++;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            if (val.length === 0) {
                strengthBar.style.width = '0%';
                strengthLabel.textContent = 'Too Short';
                strengthLabel.className = 'font-bold text-slate-500';
            } else if (val.length < 6) {
                strengthBar.style.width = '20%';
                strengthBar.className = 'h-full bg-rose-500 rounded-full transition-all duration-300';
                strengthLabel.textContent = 'Too Short (Min 6)';
                strengthLabel.className = 'font-bold text-rose-400';
            } else if (score <= 2) {
                strengthBar.style.width = '40%';
                strengthBar.className = 'h-full bg-amber-500 rounded-full transition-all duration-300';
                strengthLabel.textContent = 'Fair';
                strengthLabel.className = 'font-bold text-amber-400';
            } else if (score <= 4) {
                strengthBar.style.width = '75%';
                strengthBar.className = 'h-full bg-blue-500 rounded-full transition-all duration-300';
                strengthLabel.textContent = 'Good';
                strengthLabel.className = 'font-bold text-blue-400';
            } else {
                strengthBar.style.width = '100%';
                strengthBar.className = 'h-full bg-emerald-500 rounded-full transition-all duration-300';
                strengthLabel.textContent = 'Strong';
                strengthLabel.className = 'font-bold text-emerald-400';
            }
        });
    }

    // Confirm Password Matching Validation
    const confirmPassInput = document.getElementById('confirmPasswordInput');
    const matchMessage = document.getElementById('passwordMatchMessage');

    if (confirmPassInput && newPassInput && matchMessage) {
        const checkMatch = () => {
            if (!confirmPassInput.value) {
                matchMessage.classList.add('hidden');
                return;
            }
            if (confirmPassInput.value === newPassInput.value) {
                matchMessage.textContent = '✓ Passwords match';
                matchMessage.className = 'text-[11px] mt-1 text-emerald-400 block';
            } else {
                matchMessage.textContent = '✕ Passwords do not match';
                matchMessage.className = 'text-[11px] mt-1 text-rose-400 block';
            }
        };

        confirmPassInput.addEventListener('input', checkMatch);
        newPassInput.addEventListener('input', checkMatch);
    }

    // Avatar File Selection & Live Preview
    const avatarInput = document.getElementById('avatarFileInput');
    const avatarPreviewImg = document.getElementById('avatarPreviewImg');
    const avatarFallbackBadge = document.getElementById('avatarFallbackBadge');

    if (avatarInput) {
        avatarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
                const ext = file.name.split('.').pop().toLowerCase();
                if (!allowedExts.includes(ext)) {
                    showToast('error', 'Invalid Format', 'Only JPG, PNG, and WEBP image formats are supported.');
                    this.value = '';
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    showToast('warning', 'File Too Large', 'Avatar image must be less than 5MB.');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (avatarPreviewImg) {
                        avatarPreviewImg.src = e.target.result;
                        avatarPreviewImg.style.display = 'block';
                        avatarPreviewImg.classList.remove('hidden');
                    }
                    if (avatarFallbackBadge) {
                        avatarFallbackBadge.style.display = 'none';
                    }
                    showToast('info', 'Photo Selected', 'Click "Save Changes" to apply your new profile photo.');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Profile Form Submission
    const profileForm = document.getElementById('adminProfileForm');
    const resetProfileBtn = document.getElementById('resetProfileBtn');
    const originalName = "<?= e(addslashes($adminName)) ?>";
    const originalPhone = "<?= e(addslashes($adminPhone)) ?>";

    if (resetProfileBtn) {
        resetProfileBtn.addEventListener('click', () => {
            document.getElementById('adminNameInput').value = originalName;
            document.getElementById('adminPhoneInput').value = originalPhone;
        });
    }

    if (profileForm) {
        profileForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('saveProfileBtn');
            const origContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Saving...</span>';

            const formData = new FormData(profileForm);
            formData.append('action', 'update_profile');

            try {
                const res = await fetch('<?= base_url('ajax/auth.php') ?>', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());

                if (res.success) {
                    showToast('success', 'Profile Updated', res.message || 'Profile updated successfully.');
                    const updatedName = document.getElementById('adminNameInput').value;
                    const nameDisplay = document.getElementById('profileNameDisplay');
                    if (nameDisplay) nameDisplay.textContent = updatedName;

                    // Synchronize sidebar name display
                    const sidebarNameEl = document.querySelector('#adminSidebar .p-4 span.font-bold');
                    if (sidebarNameEl) sidebarNameEl.textContent = updatedName;

                    if (res.data && res.data.avatar_url) {
                        if (avatarPreviewImg) {
                            avatarPreviewImg.src = res.data.avatar_url;
                            avatarPreviewImg.style.display = 'block';
                            avatarPreviewImg.classList.remove('hidden');
                        }
                        if (avatarFallbackBadge) {
                            avatarFallbackBadge.style.display = 'none';
                        }
                        const sidebarAvatarContainer = document.querySelector('#adminSidebar .p-4 .flex.items-center.gap-2\\.5');
                        if (sidebarAvatarContainer) {
                            let sImg = sidebarAvatarContainer.querySelector('img');
                            if (!sImg) {
                                sImg = document.createElement('img');
                                sImg.className = 'w-8 h-8 rounded-lg object-cover ring-1 ring-teal-500/30 shadow';
                                sImg.alt = updatedName;
                                sidebarAvatarContainer.prepend(sImg);
                                const badge = sidebarAvatarContainer.querySelector('div.bg-slate-800');
                                if (badge) badge.style.display = 'none';
                            }
                            sImg.src = res.data.avatar_url;
                            sImg.style.display = 'block';
                        }
                    }
                } else {
                    showToast('error', 'Error', res.message || 'Failed to update profile.');
                }
            } catch (err) {
                showToast('error', 'Error', 'Network error. Please try again.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = origContent;
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        });
    }

    // Password Form Submission
    const passwordForm = document.getElementById('adminPasswordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('savePasswordBtn');
            const origContent = btn.innerHTML;

            const newPass = document.getElementById('newPasswordInput').value;
            const confirmPass = document.getElementById('confirmPasswordInput').value;

            if (newPass.length < 6) {
                showToast('warning', 'Validation', 'New password must be at least 6 characters.');
                return;
            }

            if (newPass !== confirmPass) {
                showToast('warning', 'Validation', 'New passwords do not match.');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Updating...</span>';

            const formData = new FormData(passwordForm);
            formData.append('action', 'change_password');

            try {
                const res = await fetch('<?= base_url('ajax/auth.php') ?>', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());

                if (res.success) {
                    showToast('success', 'Success', res.message || 'Password updated successfully.');
                    passwordForm.reset();
                    if (strengthBar) strengthBar.style.width = '0%';
                    if (strengthLabel) {
                        strengthLabel.textContent = 'Too Short';
                        strengthLabel.className = 'font-bold text-slate-500';
                    }
                    if (matchMessage) matchMessage.classList.add('hidden');
                } else {
                    showToast('error', 'Failed', res.message || 'Current password is incorrect.');
                }
            } catch (err) {
                showToast('error', 'Error', 'Network error. Please try again.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = origContent;
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        });
    }
});
</script>
