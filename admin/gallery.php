<?php
/**
 * HomeFix Quetta - Admin Work Gallery Management
 */
$adminPageTitle = 'Manage Work Gallery | HomeFix Quetta Admin';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

$gallery = Database::fetchAll("SELECT * FROM gallery ORDER BY id ASC");
?>

<div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-900">
    
    <header class="min-h-[4rem] h-auto sm:h-16 py-2.5 sm:py-0 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-4 sm:px-6 shrink-0 z-20 gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <button type="button" id="adminSidebarToggle" onclick="toggleAdminSidebar(event)" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 shrink-0 transition" aria-label="Toggle sidebar">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h2 class="text-base sm:text-lg font-extrabold font-heading text-white tracking-tight truncate">Work Gallery</h2>
        </div>
        <span class="text-xs font-mono font-bold text-teal-400 bg-slate-900 px-3 py-1.5 rounded-xl border border-slate-800 shrink-0 whitespace-nowrap">
            <?= count($gallery) ?> Showcases
        </span>
    </header>

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 pb-32 sm:pb-16 lg:pb-12 space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($gallery as $g): ?>
                <div class="bg-slate-800/90 border border-slate-700/80 rounded-3xl p-6 shadow-xl space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-rose-400">Before</span>
                            <img src="<?= asset($g['before_image'] ?? $g['after_image']) ?>" alt="Before" class="w-full h-36 object-cover rounded-xl border border-slate-700">
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-400">After (Repaired)</span>
                            <img src="<?= asset($g['after_image']) ?>" alt="After" class="w-full h-36 object-cover rounded-xl border border-slate-700">
                        </div>
                    </div>

                    <div class="flex justify-between items-start pt-2 border-t border-slate-700">
                        <div>
                            <span class="text-xs font-bold text-teal-400 uppercase tracking-wide"><?= e($g['category']) ?></span>
                            <h4 class="font-heading font-bold text-base text-white"><?= e($g['title']) ?></h4>
                            <p class="text-xs text-slate-400 mt-1"><?= e($g['description']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
