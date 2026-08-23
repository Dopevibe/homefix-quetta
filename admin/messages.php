<?php
/**
 * HomeFix Quetta - Admin Contact Inquiries
 */
$adminPageTitle = 'Contact Inquiries | HomeFix Quetta Admin';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

$messages = Database::fetchAll("SELECT * FROM contact_messages ORDER BY is_read ASC, created_at DESC");
?>

<div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-900">
    
    <header class="h-16 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
        <div class="flex items-center gap-4">
            <button id="adminSidebarToggle" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h2 class="text-lg font-bold font-heading text-white">Contact & Support Inquiries</h2>
        </div>
        <span class="text-xs font-mono font-bold text-teal-400 bg-teal-950 px-3 py-1 rounded-full border border-teal-500/30">
            <?= count($messages) ?> Messages
        </span>
    </header>

    <main class="flex-1 overflow-y-auto p-6 space-y-6">
        
        <div class="bg-slate-800/90 border border-slate-700/80 rounded-3xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm text-slate-300">
                    <thead class="bg-slate-950 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Sender & Contact</th>
                            <th class="px-6 py-4">Subject</th>
                            <th class="px-6 py-4">Message</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Read</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/60 text-slate-200">
                        <?php if (!empty($messages)): ?>
                            <?php foreach ($messages as $m): ?>
                                <tr class="hover:bg-slate-750 transition <?= ($m['is_read'] == 0) ? 'bg-slate-800 font-semibold' : '' ?>">
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-white block"><?= e($m['name']) ?></span>
                                        <a href="mailto:<?= e($m['email']) ?>" class="text-xs text-teal-400 hover:underline"><?= e($m['email']) ?></a>
                                        <?php if (!empty($m['phone'])): ?>
                                            <span class="text-[11px] text-slate-400 block font-mono"><?= e($m['phone']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-bold text-slate-200">
                                        <?= e($m['subject']) ?>
                                    </td>
                                    <td class="px-6 py-4 max-w-sm text-slate-300">
                                        <?= nl2br(e($m['message'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400">
                                        <?= format_date($m['created_at']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= ($m['is_read'] == 1) ? '<span class="text-slate-500 text-xs">Read</span>' : '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-950 text-teal-300 border border-teal-500/30">New</span>' ?>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-1">
                                        <button type="button" 
                                                class="delete-item-btn p-2 rounded-lg bg-slate-700 hover:bg-rose-600 text-slate-300 hover:text-white inline-block"
                                                data-action="delete_message"
                                                data-id="<?= $m['id'] ?>"
                                                data-title="Message from <?= e($m['name']) ?>"
                                                title="Delete">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="p-8 text-center text-slate-500">Inbox is clean. No inquiries.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
