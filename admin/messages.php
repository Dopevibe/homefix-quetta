<?php
/**
 * HomeFix Quetta - Admin Contact Inquiries Management
 */
$adminPageTitle = 'Contact Inquiries | HomeFix Quetta Admin';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

$filter = trim($_GET['filter'] ?? 'all');
$sql = "SELECT * FROM contact_messages";
$params = [];

if ($filter === 'unread') {
    $sql .= " WHERE is_read = 0";
} elseif ($filter === 'read') {
    $sql .= " WHERE is_read = 1";
}

$sql .= " ORDER BY is_read ASC, created_at DESC";
$messages = Database::fetchAll($sql, $params);
$totalUnreadCount = (int)(Database::fetch("SELECT COUNT(*) as cnt FROM contact_messages WHERE is_read = 0")['cnt'] ?? 0);
$totalAllCount = (int)(Database::fetch("SELECT COUNT(*) as cnt FROM contact_messages")['cnt'] ?? 0);
?>

<div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-900">
    
    <!-- Top Bar -->
    <header class="h-16 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-6 shrink-0 z-20">
        <div class="flex items-center gap-4">
            <button id="adminSidebarToggle" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800" aria-label="Toggle sidebar">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <div>
                <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-0.5">
                    <span>Admin</span>
                    <i data-lucide="chevron-right" class="w-3 h-3 text-slate-600"></i>
                    <span class="text-teal-400 font-medium">Inquiries</span>
                </div>
                <h1 class="text-lg font-extrabold font-heading text-white tracking-tight">Contact & Support Inquiries</h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <?php if ($totalUnreadCount > 0): ?>
                <button id="markAllReadBtn" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-teal-600/20 text-teal-300 border border-teal-500/30 hover:bg-teal-600/30 transition text-xs font-semibold">
                    <i data-lucide="check-check" class="w-3.5 h-3.5"></i>
                    <span>Mark All Read (<?= $totalUnreadCount ?>)</span>
                </button>
            <?php endif; ?>
            <span class="text-xs font-mono font-bold text-teal-400 bg-slate-900 px-3 py-1.5 rounded-xl border border-slate-800">
                <?= count($messages) ?> / <?= $totalAllCount ?> Inquiries
            </span>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
        
        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-800 pb-3">
            <a href="messages.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition <?= ($filter === 'all') ? 'bg-teal-600 text-white shadow-md shadow-teal-900/30' : 'bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700' ?>">
                All Messages (<?= $totalAllCount ?>)
            </a>
            <a href="messages.php?filter=unread" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 <?= ($filter === 'unread') ? 'bg-amber-600 text-white shadow-md shadow-amber-900/30' : 'bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700' ?>">
                <span>Unread</span>
                <?php if ($totalUnreadCount > 0): ?>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-amber-400 text-slate-950 font-black"><?= $totalUnreadCount ?></span>
                <?php endif; ?>
            </a>
            <a href="messages.php?filter=read" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition <?= ($filter === 'read') ? 'bg-slate-700 text-white' : 'bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700' ?>">
                Read (<?= $totalAllCount - $totalUnreadCount ?>)
            </a>
        </div>

        <div class="bg-slate-950 border border-slate-800 rounded-3xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm text-slate-300">
                    <thead class="bg-slate-900/80 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Sender & Contact</th>
                            <th class="px-6 py-4">Subject</th>
                            <th class="px-6 py-4">Message Preview</th>
                            <th class="px-6 py-4">Received</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80 text-slate-200">
                        <?php if (!empty($messages)): ?>
                            <?php foreach ($messages as $m): ?>
                                <tr id="msg-row-<?= $m['id'] ?>" class="hover:bg-slate-900/60 transition <?= ($m['is_read'] == 0) ? 'bg-slate-900/90 font-semibold' : '' ?>">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg <?= ($m['is_read'] == 0) ? 'bg-teal-600 text-white font-black' : 'bg-slate-800 text-slate-400 font-bold' ?> flex items-center justify-center text-xs shrink-0">
                                                <?= strtoupper(substr($m['name'] ?? 'M', 0, 1)) ?>
                                            </div>
                                            <div class="min-w-0">
                                                <span class="font-bold text-white block truncate"><?= e($m['name']) ?></span>
                                                <a href="mailto:<?= e($m['email']) ?>" class="text-xs text-teal-400 hover:underline block truncate"><?= e($m['email']) ?></a>
                                                <?php if (!empty($m['phone'])): ?>
                                                    <span class="text-[11px] text-slate-400 block font-mono"><?= e($m['phone']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-bold text-slate-200">
                                        <?= e($m['subject']) ?>
                                    </td>
                                    <td class="px-6 py-4 max-w-sm text-slate-300">
                                        <p class="truncate text-xs text-slate-300"><?= e($m['message']) ?></p>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400 whitespace-nowrap">
                                        <?= format_date($m['created_at']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap" id="status-col-<?= $m['id'] ?>">
                                        <?php if ($m['is_read'] == 1): ?>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-800 text-slate-400 border border-slate-700">Read</span>
                                        <?php else: ?>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20 animate-pulse">● Unread</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                                        <!-- View Modal Trigger -->
                                        <button type="button" 
                                                class="view-msg-btn p-2 rounded-xl bg-slate-800 hover:bg-teal-600 text-slate-300 hover:text-white transition inline-flex items-center gap-1 text-xs font-semibold"
                                                data-id="<?= $m['id'] ?>"
                                                data-name="<?= e($m['name']) ?>"
                                                data-email="<?= e($m['email']) ?>"
                                                data-phone="<?= e($m['phone'] ?? 'N/A') ?>"
                                                data-subject="<?= e($m['subject']) ?>"
                                                data-message="<?= e($m['message']) ?>"
                                                data-date="<?= format_date($m['created_at']) ?>"
                                                data-read="<?= $m['is_read'] ?>"
                                                title="View Full Inquiry">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                            <span class="hidden sm:inline">View</span>
                                        </button>

                                        <!-- Delete Button -->
                                        <button type="button" 
                                                class="delete-item-btn p-2 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white transition inline-block"
                                                data-action="delete_message"
                                                data-id="<?= $m['id'] ?>"
                                                data-title="Message from <?= e($m['name']) ?>"
                                                title="Delete Inquiry">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-500">
                                    <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-slate-600"></i>
                                    <span>No messages found in this view.</span>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<!-- View Message Modal -->
<div id="viewMessageModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5 animate-in fade-in zoom-in duration-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <div>
                <span class="text-xs text-teal-400 font-bold uppercase tracking-wider">Inquiry Details</span>
                <h3 id="modalMsgSubject" class="text-base font-extrabold font-heading text-white"></h3>
            </div>
            <button id="closeMsgModalBtn" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="grid grid-cols-2 gap-3 text-xs bg-slate-950 p-4 rounded-2xl border border-slate-800">
            <div>
                <span class="text-slate-500 block">Sender Name:</span>
                <span id="modalMsgName" class="font-bold text-white block mt-0.5"></span>
            </div>
            <div>
                <span class="text-slate-500 block">Received:</span>
                <span id="modalMsgDate" class="text-slate-300 block mt-0.5"></span>
            </div>
            <div>
                <span class="text-slate-500 block">Email Address:</span>
                <a id="modalMsgEmail" href="#" class="text-teal-400 hover:underline block truncate mt-0.5"></a>
            </div>
            <div>
                <span class="text-slate-500 block">Phone Number:</span>
                <a id="modalMsgPhone" href="#" class="text-slate-300 hover:text-white font-mono block truncate mt-0.5"></a>
            </div>
        </div>

        <div>
            <span class="text-xs font-semibold text-slate-400 block mb-1.5">Message Content:</span>
            <div id="modalMsgContent" class="bg-slate-950 p-4 rounded-2xl border border-slate-800 text-xs sm:text-sm text-slate-200 leading-relaxed max-h-60 overflow-y-auto whitespace-pre-wrap"></div>
        </div>

        <div class="flex items-center justify-between pt-2 border-t border-slate-800">
            <a id="modalReplyBtn" href="#" class="btn-primary px-4 py-2 rounded-xl text-xs font-bold inline-flex items-center gap-1.5">
                <i data-lucide="reply" class="w-3.5 h-3.5"></i> Reply via Email
            </a>
            <button type="button" id="closeMsgModalSecondary" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('viewMessageModal');
    const closeBtn = document.getElementById('closeMsgModalBtn');
    const closeSecBtn = document.getElementById('closeMsgModalSecondary');

    const updateSidebarInquiryBadge = (count) => {
        const badge = document.querySelector('#adminSidebar a[href*="messages.php"] span.rounded-full');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline-block';
            } else {
                badge.remove();
            }
        }
    };

    // Open Message Details Modal
    document.querySelectorAll('.view-msg-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const email = this.getAttribute('data-email');
            const phone = this.getAttribute('data-phone');
            const subject = this.getAttribute('data-subject');
            const message = this.getAttribute('data-message');
            const date = this.getAttribute('data-date');
            const isRead = parseInt(this.getAttribute('data-read') || '0');

            document.getElementById('modalMsgSubject').textContent = subject;
            document.getElementById('modalMsgName').textContent = name;
            document.getElementById('modalMsgDate').textContent = date;
            
            const emailLink = document.getElementById('modalMsgEmail');
            emailLink.textContent = email;
            emailLink.href = 'mailto:' + email;

            const phoneLink = document.getElementById('modalMsgPhone');
            phoneLink.textContent = phone;
            phoneLink.href = phone !== 'N/A' ? 'tel:' + phone : '#';

            document.getElementById('modalMsgContent').textContent = message;
            document.getElementById('modalReplyBtn').href = 'mailto:' + email + '?subject=Re: ' + encodeURIComponent(subject);

            modal.classList.remove('hidden');

            // Mark as read in database if unread
            if (isRead === 0) {
                this.setAttribute('data-read', '1');
                const row = document.getElementById('msg-row-' + id);
                if (row) {
                    row.classList.remove('bg-slate-900/90', 'font-semibold');
                    const initialBadge = row.querySelector('div.bg-teal-600');
                    if (initialBadge) {
                        initialBadge.className = 'w-8 h-8 rounded-lg bg-slate-800 text-slate-400 font-bold flex items-center justify-center text-xs shrink-0';
                    }
                }
                const statusCol = document.getElementById('status-col-' + id);
                if (statusCol) {
                    statusCol.innerHTML = '<span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-800 text-slate-400 border border-slate-700">Read</span>';
                }

                try {
                    const res = await fetch('<?= base_url('ajax/admin.php') ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'action=toggle_message_read&id=' + id + '&is_read=1'
                    }).then(r => r.json());

                    if (res.success && typeof res.data !== 'undefined' && typeof res.data.unread_count !== 'undefined') {
                        updateSidebarInquiryBadge(res.data.unread_count);
                    }
                } catch (e) {}
            }
        });
    });

    const closeModal = () => modal.classList.add('hidden');
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (closeSecBtn) closeSecBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    // Mark All as Read Button
    const markAllBtn = document.getElementById('markAllReadBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', async () => {
            try {
                const res = await fetch('<?= base_url('ajax/admin.php') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=mark_all_messages_read'
                }).then(r => r.json());

                if (res.success) {
                    updateSidebarInquiryBadge(0);
                    window.location.reload();
                }
            } catch (e) {}
        });
    }
});
</script>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>

