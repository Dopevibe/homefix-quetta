<?php
/**
 * HomeFix Quetta - Admin Sidebar Navigation
 */
$currentAdminScript = basename($_SERVER['SCRIPT_NAME'] ?? '');
?>
<!-- Sidebar Backdrop Overlay (Mobile) -->
<div id="adminSidebarBackdrop" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-30 hidden transition-opacity duration-300"></div>

<!-- Sidebar -->
<aside id="adminSidebar" class="w-64 bg-slate-950 border-r border-slate-800 flex flex-col justify-between shrink-0 transition-transform duration-300 z-40 fixed lg:static inset-y-0 left-0 -translate-x-full lg:translate-x-0 shadow-2xl lg:shadow-none">
    
    <!-- Top Brand & Navigation -->
    <div class="p-6 space-y-6 overflow-y-auto">
        
        <!-- Logo -->
        <a href="<?= base_url('admin/dashboard.php') ?>" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-teal-600 flex items-center justify-center text-white shadow-md shadow-teal-600/30">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-xl font-extrabold font-heading text-white">Home<span class="text-teal-400">Fix</span></span>
                <span class="text-[10px] block font-bold text-teal-400 tracking-wider uppercase">Admin Console</span>
            </div>
        </a>

        <!-- Navigation Links -->
        <nav class="space-y-1 text-sm">
            
            <a href="<?= base_url('admin/dashboard.php') ?>" class="admin-nav-item <?= ($currentAdminScript === 'dashboard.php') ? 'active' : '' ?>">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                <span>Dashboard</span>
            </a>

            <a href="<?= base_url('admin/bookings.php') ?>" class="admin-nav-item justify-between <?= ($currentAdminScript === 'bookings.php') ? 'active' : '' ?>">
                <div class="flex items-center gap-3">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <span>Bookings</span>
                </div>
                <?php if ($pendingBookingsCount > 0): ?>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-white">
                        <?= $pendingBookingsCount ?>
                    </span>
                <?php endif; ?>
            </a>

            <a href="<?= base_url('admin/services.php') ?>" class="admin-nav-item <?= ($currentAdminScript === 'services.php') ? 'active' : '' ?>">
                <i data-lucide="wrench" class="w-4 h-4"></i>
                <span>Services</span>
            </a>

            <a href="<?= base_url('admin/categories.php') ?>" class="admin-nav-item <?= ($currentAdminScript === 'categories.php') ? 'active' : '' ?>">
                <i data-lucide="layers" class="w-4 h-4"></i>
                <span>Categories</span>
            </a>

            <a href="<?= base_url('admin/technicians.php') ?>" class="admin-nav-item <?= ($currentAdminScript === 'technicians.php') ? 'active' : '' ?>">
                <i data-lucide="users" class="w-4 h-4"></i>
                <span>Technicians</span>
            </a>

            <a href="<?= base_url('admin/customers.php') ?>" class="admin-nav-item <?= ($currentAdminScript === 'customers.php') ? 'active' : '' ?>">
                <i data-lucide="user-check" class="w-4 h-4"></i>
                <span>Customers</span>
            </a>

            <a href="<?= base_url('admin/reviews.php') ?>" class="admin-nav-item <?= ($currentAdminScript === 'reviews.php') ? 'active' : '' ?>">
                <i data-lucide="star" class="w-4 h-4"></i>
                <span>Reviews</span>
            </a>

            <a href="<?= base_url('admin/gallery.php') ?>" class="admin-nav-item <?= ($currentAdminScript === 'gallery.php') ? 'active' : '' ?>">
                <i data-lucide="image" class="w-4 h-4"></i>
                <span>Work Gallery</span>
            </a>

            <a href="<?= base_url('admin/messages.php') ?>" class="admin-nav-item justify-between <?= ($currentAdminScript === 'messages.php') ? 'active' : '' ?>">
                <div class="flex items-center gap-3">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    <span>Inquiries</span>
                </div>
                <?php if ($unreadMessagesCount > 0): ?>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-500 text-white">
                        <?= $unreadMessagesCount ?>
                    </span>
                <?php endif; ?>
            </a>

            <a href="<?= base_url('admin/settings.php') ?>" class="admin-nav-item <?= ($currentAdminScript === 'settings.php') ? 'active' : '' ?>">
                <i data-lucide="settings" class="w-4 h-4"></i>
                <span>Settings</span>
            </a>

        </nav>
    </div>

    <?php
    // Resolve Authoritative Admin Avatar with Cache-Buster
    $adminSidebarAvatarUrl = null;
    if (!empty($adminUser['avatar'])) {
        if (file_exists(ROOT_PATH . '/uploads/' . $adminUser['avatar'])) {
            $adminSidebarAvatarUrl = asset('uploads/' . $adminUser['avatar']) . '?v=' . filemtime(ROOT_PATH . '/uploads/' . $adminUser['avatar']);
        } elseif (file_exists(ROOT_PATH . '/' . $adminUser['avatar'])) {
            $adminSidebarAvatarUrl = asset($adminUser['avatar']) . '?v=' . filemtime(ROOT_PATH . '/' . $adminUser['avatar']);
        }
    }
    if (!$adminSidebarAvatarUrl && file_exists(ROOT_PATH . '/assets/images/avatars/admin.jpg')) {
        $adminSidebarAvatarUrl = asset('assets/images/avatars/admin.jpg') . '?v=' . filemtime(ROOT_PATH . '/assets/images/avatars/admin.jpg');
    }
    ?>

    <!-- Bottom User Info & Links -->
    <div class="p-4 border-t border-slate-800/80 space-y-3 bg-slate-950/50">
        <a href="<?= base_url('index.php') ?>" target="_blank" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-teal-400 hover:bg-slate-900 transition">
            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            <span>View Public Website</span>
        </a>

        <div class="flex items-center justify-between pt-2 border-t border-slate-800 px-1">
            <div class="flex items-center gap-2.5">
                <?php if ($adminSidebarAvatarUrl): ?>
                    <img src="<?= $adminSidebarAvatarUrl ?>" alt="<?= e($adminUser['name'] ?? 'Admin') ?>" class="w-8 h-8 rounded-lg object-cover ring-1 ring-teal-500/30 shadow" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-8 h-8 rounded-lg bg-slate-800 text-teal-400 font-bold items-center justify-center text-xs" style="display:none;">
                        <?= strtoupper(substr($adminUser['name'] ?? 'A', 0, 1)) ?>
                    </div>
                <?php else: ?>
                    <div class="w-8 h-8 rounded-lg bg-slate-800 text-teal-400 font-bold flex items-center justify-center text-xs">
                        <?= strtoupper(substr($adminUser['name'] ?? 'A', 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div class="truncate max-w-[120px]">
                    <span class="text-xs font-bold text-slate-200 block truncate"><?= e($adminUser['name'] ?? 'Administrator') ?></span>
                    <span class="text-[10px] text-slate-500 block truncate"><?= e($adminUser['email'] ?? '') ?></span>
                </div>
            </div>
            <a href="<?= base_url('admin/logout.php') ?>" class="text-slate-400 hover:text-rose-400 p-1.5 rounded-lg hover:bg-slate-800 transition" title="Logout">
                <i data-lucide="log-out" class="w-4 h-4"></i>
            </a>
        </div>
    </div>

</aside>
