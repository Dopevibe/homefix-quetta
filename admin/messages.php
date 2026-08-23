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
        
        <!-- Filter Tabs & Stats Bar -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 bg-slate-950 p-4 sm:p-5 rounded-3xl border border-slate-800 shadow-xl">
            <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
                <a href="messages.php" class="px-4 py-2 rounded-2xl text-xs font-bold transition whitespace-nowrap <?= ($filter === 'all') ? 'bg-teal-600 text-white shadow-md shadow-teal-900/30' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
                    All Inquiries (<?= $totalAllCount ?>)
                </a>
                <a href="messages.php?filter=unread" class="px-4 py-2 rounded-2xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap <?= ($filter === 'unread') ? 'bg-amber-600 text-white shadow-md shadow-amber-900/30' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
                    <span>Unread</span>
                    <?php if ($totalUnreadCount > 0): ?>
                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-400 text-slate-950 font-black"><?= $totalUnreadCount ?></span>
                    <?php endif; ?>
                </a>
                <a href="messages.php?filter=read" class="px-4 py-2 rounded-2xl text-xs font-bold transition whitespace-nowrap <?= ($filter === 'read') ? 'bg-slate-800 text-white' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
                    Read (<?= $totalAllCount - $totalUnreadCount ?>)
                </a>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" id="markAllReadBtn" class="px-4 py-2 rounded-2xl bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white text-xs font-bold border border-slate-800 transition flex items-center gap-1.5">
                    <i data-lucide="check-check" class="w-3.5 h-3.5 text-teal-400"></i>
                    <span>Mark All Read</span>
                </button>
            </div>
        </div>

        <!-- HIGH-END RESPONSIVE SUPPORT TICKET FEED (Zero horizontal scrolling on phone and desktop) -->
        <div class="space-y-4">
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $m): ?>
                    <div id="msg-row-<?= $m['id'] ?>" 
                         class="bg-slate-950 border border-slate-800 hover:border-slate-700/80 rounded-3xl p-5 sm:p-6 shadow-xl hover:shadow-2xl transition-all duration-200 space-y-4 cursor-pointer relative <?= ($m['is_read'] == 0) ? 'ring-1 ring-amber-500/30 bg-slate-950' : '' ?>"
                         data-id="<?= $m['id'] ?>">
                        
                        <!-- Card Header: Sender + Time + Status Badge -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-12 h-12 rounded-2xl <?= ($m['is_read'] == 0) ? 'bg-teal-950 border border-teal-500/40 text-teal-300' : 'bg-slate-900 border border-slate-800 text-slate-400' ?> flex items-center justify-center font-heading font-extrabold text-base shrink-0 shadow-md">
                                    <?= strtoupper(substr($m['name'] ?? 'M', 0, 1)) ?>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-base font-extrabold font-heading text-white truncate leading-snug"><?= e($m['name']) ?></h3>
                                    <span class="text-xs font-mono text-slate-400 flex items-center gap-1 mt-0.5">
                                        <i data-lucide="clock" class="w-3 h-3 text-slate-500"></i>
                                        <?= format_date($m['created_at']) ?>
                                    </span>
                                </div>
                            </div>

                            <div id="status-col-<?= $m['id'] ?>" class="shrink-0">
                                <?php if ($m['is_read'] == 1): ?>
                                    <span class="whitespace-nowrap px-3 py-1 rounded-full text-xs font-bold bg-slate-900 text-slate-400 border border-slate-800">
                                        Read
                                    </span>
                                <?php else: ?>
                                    <span class="whitespace-nowrap px-3 py-1 rounded-full text-xs font-bold bg-amber-950/90 text-amber-300 border border-amber-500/40 shadow-sm inline-flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                                        <span>New Inquiry</span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Card Body: Subject & Message Preview -->
                        <div class="space-y-2 bg-slate-900/60 p-4 rounded-2xl border border-slate-850">
                            <h4 class="text-sm font-extrabold text-white flex items-center gap-2">
                                <i data-lucide="message-square" class="w-4 h-4 text-teal-400 shrink-0"></i>
                                <span><?= e($m['subject']) ?></span>
                            </h4>
                            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                                <?= nl2br(e($m['message'])) ?>
                            </p>
                        </div>

                        <!-- Card Footer: Contact Links & Actions -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-1 border-t border-slate-900" onclick="event.stopPropagation();">
                            
                            <!-- Contact Shortcuts -->
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="mailto:<?= e($m['email']) ?>" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-850 border border-slate-800 text-teal-400 hover:text-teal-300 text-xs font-semibold inline-flex items-center gap-1.5 transition">
                                    <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                    <span class="truncate max-w-[180px] sm:max-w-xs"><?= e($m['email']) ?></span>
                                </a>
                                <?php if (!empty($m['phone'])): ?>
                                    <a href="tel:<?= e($m['phone']) ?>" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-850 border border-slate-800 text-slate-300 hover:text-white text-xs font-mono font-semibold inline-flex items-center gap-1.5 transition">
                                        <i data-lucide="phone" class="w-3.5 h-3.5 text-teal-400"></i>
                                        <span><?= e($m['phone']) ?></span>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        class="view-msg-btn flex-1 sm:flex-none px-4 py-2 rounded-xl bg-slate-800 hover:bg-teal-600 text-slate-200 hover:text-white transition inline-flex items-center justify-center gap-1.5 text-xs font-bold shadow"
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
                                    <span>View & Reply</span>
                                </button>

                                <button type="button" 
                                        class="delete-item-btn p-2 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white transition inline-flex items-center justify-center shadow"
                                        data-action="delete_message"
                                        data-id="<?= $m['id'] ?>"
                                        data-title="Message from <?= e($m['name']) ?>"
                                        title="Delete Inquiry">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>

                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-16 text-center text-slate-500 bg-slate-950 rounded-3xl border border-slate-800">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-slate-600"></i>
                    <h3 class="text-base font-bold text-slate-400 mb-1">No Inquiries Found</h3>
                    <p class="text-xs text-slate-500">All customer support messages are up to date.</p>
                </div>
            <?php endif; ?>
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
                    statusCol.innerHTML = '<span class="whitespace-nowrap px-3 py-1 rounded-full text-xs font-bold bg-slate-900 text-slate-400 border border-slate-800">Read</span>';
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

    // Full Row Click to Open Inquiry Modal
    document.querySelectorAll('[id^="msg-row-"]').forEach(row => {
        row.addEventListener('click', function(e) {
            if (!e.target.closest('.delete-item-btn') && !e.target.closest('.view-msg-btn')) {
                const viewBtn = this.querySelector('.view-msg-btn');
                if (viewBtn) viewBtn.click();
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

