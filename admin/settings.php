<?php
/**
 * HomeFix Quetta - Admin Account Settings & Password Change
 */
$adminPageTitle = 'Account Settings | HomeFix Quetta Admin';
require_once __DIR__ . '/../includes/admin_header.php';

// Fetch current administrator profile
$adminData = Database::fetch("SELECT * FROM users WHERE id = ?", [$adminUser['id']]) ?: [];
?>

<div class="flex h-screen overflow-hidden bg-slate-900">

    <!-- Admin Sidebar -->
    <?php require_once __DIR__ . '/../includes/admin_sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">

        <!-- Top Header Bar -->
        <header class="h-20 bg-slate-950/80 backdrop-blur border-b border-slate-800 flex items-center justify-between px-6 lg:px-8 shrink-0 sticky top-0 z-20">
            <div class="flex items-center gap-4">
                <button id="toggleSidebarMobile" class="p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white lg:hidden">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <div>
                    <h1 class="text-xl font-extrabold font-heading text-white">Account Settings</h1>
                    <p class="text-xs text-slate-400">Manage administrator profile and update your password</p>
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="p-6 lg:p-8 space-y-8 max-w-5xl">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Profile Information Card -->
                <div class="bg-slate-950 border border-slate-800/80 rounded-2xl p-6 sm:p-8 shadow-xl">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800">
                        <div class="w-10 h-10 rounded-xl bg-teal-500/10 text-teal-400 flex items-center justify-center">
                            <i data-lucide="user-check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold font-heading text-white">Administrator Profile</h2>
                            <p class="text-xs text-slate-400">Update your name and contact details</p>
                        </div>
                    </div>

                    <form id="adminProfileForm" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Full Name</label>
                            <input type="text" name="name" value="<?= e($adminData['name'] ?? '') ?>" required
                                   class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-teal-500 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Email Address</label>
                            <input type="email" value="<?= e($adminData['email'] ?? '') ?>" disabled
                                   class="w-full bg-slate-900/50 border border-slate-800/50 rounded-xl px-4 py-2.5 text-sm text-slate-500 cursor-not-allowed">
                            <span class="text-[11px] text-slate-500 mt-1 block">Primary login email address</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Phone Number</label>
                            <input type="text" name="phone" value="<?= e($adminData['phone'] ?? '') ?>" required
                                   placeholder="0331 7374824"
                                   class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-teal-500 transition">
                        </div>

                        <div class="pt-2">
                            <button type="submit" id="saveProfileBtn"
                                    class="w-full bg-teal-600 hover:bg-teal-500 text-white font-bold py-2.5 px-5 rounded-xl text-sm transition duration-200 flex items-center justify-center gap-2 shadow-lg shadow-teal-900/30">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                <span>Save Profile</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Card -->
                <div class="bg-slate-950 border border-slate-800/80 rounded-2xl p-6 sm:p-8 shadow-xl">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                            <i data-lucide="key-round" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold font-heading text-white">Change Password</h2>
                            <p class="text-xs text-slate-400">Ensure account security with a strong password</p>
                        </div>
                    </div>

                    <form id="adminPasswordForm" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Current Password</label>
                            <input type="password" name="current_password" required
                                   placeholder="••••••••"
                                   class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">New Password</label>
                            <input type="password" name="new_password" required minlength="6"
                                   placeholder="Minimum 6 characters"
                                   class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Confirm New Password</label>
                            <input type="password" name="confirm_password" required minlength="6"
                                   placeholder="Re-enter new password"
                                   class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500 transition">
                        </div>

                        <div class="pt-2">
                            <button type="submit" id="savePasswordBtn"
                                    class="w-full bg-amber-600 hover:bg-amber-500 text-white font-bold py-2.5 px-5 rounded-xl text-sm transition duration-200 flex items-center justify-center gap-2 shadow-lg shadow-amber-900/30">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                                <span>Update Password</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();

    // Profile update form handler
    const profileForm = document.getElementById('adminProfileForm');
    profileForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('saveProfileBtn');
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span>Saving...</span>';

        const formData = new FormData(profileForm);
        formData.append('action', 'update_profile');

        try {
            const res = await fetch('<?= base_url('ajax/auth.php') ?>', {
                method: 'POST',
                body: formData
            }).then(r => r.json());

            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: res.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.message });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An unexpected error occurred.' });
        } finally {
            btn.disabled = false;
            btn.innerHTML = origText;
            lucide.createIcons();
        }
    });

    // Password change form handler
    const passwordForm = document.getElementById('adminPasswordForm');
    passwordForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('savePasswordBtn');
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span>Updating...</span>';

        const formData = new FormData(passwordForm);
        formData.append('action', 'change_password');

        try {
            const res = await fetch('<?= base_url('ajax/auth.php') ?>', {
                method: 'POST',
                body: formData
            }).then(r => r.json());

            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: res.message,
                    timer: 2500,
                    showConfirmButton: false
                });
                passwordForm.reset();
            } else {
                Swal.fire({ icon: 'error', title: 'Failed', text: res.message });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An unexpected error occurred.' });
        } finally {
            btn.disabled = false;
            btn.innerHTML = origText;
            lucide.createIcons();
        }
    });
});
</script>
