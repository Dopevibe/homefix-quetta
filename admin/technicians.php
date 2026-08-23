<?php
/**
 * HomeFix Quetta - Admin Technicians Management
 */
$adminPageTitle = 'Manage Technicians | HomeFix Quetta Admin';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

$technicians = Database::fetchAll("SELECT * FROM technicians ORDER BY status ASC, rating DESC");
?>

<div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-900">
    
    <header class="h-16 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
        <div class="flex items-center gap-4">
            <button id="adminSidebarToggle" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h2 class="text-lg font-bold font-heading text-white">Technicians & Service Providers</h2>
        </div>
        <button type="button" id="openAddTechModal" class="btn-primary px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-md">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Add New Technician</span>
        </button>
    </header>

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
        
        <!-- 1. Desktop Responsive Table View -->
        <div class="hidden md:block bg-slate-950 border border-slate-800 rounded-3xl shadow-xl overflow-hidden">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/80 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5">Technician</th>
                        <th class="px-3 py-3.5">Specialty</th>
                        <th class="px-3 py-3.5">Phone / Email</th>
                        <th class="px-3 py-3.5">Experience</th>
                        <th class="px-3 py-3.5 text-center">Rating</th>
                        <th class="px-3 py-3.5 text-center">Availability</th>
                        <th class="px-4 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-slate-200">
                    <?php if (!empty($technicians)): ?>
                        <?php foreach ($technicians as $t): ?>
                            <tr class="hover:bg-slate-900/60 transition">
                                <td class="px-4 py-3.5 font-bold">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-teal-900/80 text-teal-300 border border-teal-500/30 flex items-center justify-center font-heading font-extrabold text-sm shrink-0">
                                            <?= strtoupper(substr($t['name'], 0, 1)) ?>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-white block truncate"><?= e($t['name']) ?></span>
                                            <?= get_status_badge($t['status']) ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3.5 font-semibold text-teal-300 whitespace-nowrap">
                                    <?= e($t['specialty']) ?>
                                </td>
                                <td class="px-3 py-3.5 text-xs font-mono whitespace-nowrap">
                                    <span class="text-white block"><?= e($t['phone']) ?></span>
                                    <span class="text-slate-400 text-[11px]"><?= e($t['email'] ?? 'N/A') ?></span>
                                </td>
                                <td class="px-3 py-3.5 font-mono text-slate-300 whitespace-nowrap">
                                    <?= $t['experience_years'] ?> Years
                                </td>
                                <td class="px-3 py-3.5 whitespace-nowrap text-center">
                                    <span class="text-amber-400 font-bold inline-flex items-center gap-1 font-mono text-xs">
                                        <i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i>
                                        <?= number_format($t['rating'], 1) ?>
                                    </span>
                                </td>
                                <td class="px-3 py-3.5 whitespace-nowrap text-center">
                                    <?php if ($t['availability'] === 'available'): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-950 text-emerald-400 border border-emerald-500/30">Available</span>
                                    <?php elseif ($t['availability'] === 'busy'): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-950 text-amber-400 border border-amber-500/30">On Job</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-900 text-slate-400 border border-slate-700">Offline</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3.5 text-right whitespace-nowrap space-x-1">
                                    <button type="button" 
                                            class="edit-tech-btn p-2 rounded-xl bg-slate-800 hover:bg-teal-600 text-white transition inline-block"
                                            data-tech='<?= json_encode($t, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
                                            title="Edit Technician">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button type="button" 
                                            class="delete-item-btn p-2 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white transition inline-block"
                                            data-action="delete_technician"
                                            data-id="<?= $t['id'] ?>"
                                            data-title="<?= e($t['name']) ?>"
                                            title="Delete Technician">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="p-8 text-center text-slate-500">No technicians registered.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- 2. Mobile Responsive Card View -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 md:hidden">
            <?php if (!empty($technicians)): ?>
                <?php foreach ($technicians as $t): ?>
                    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-4 space-y-3 shadow-lg">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-11 h-11 rounded-xl bg-teal-900/80 text-teal-300 border border-teal-500/30 flex items-center justify-center font-heading font-extrabold text-sm shrink-0">
                                    <?= strtoupper(substr($t['name'], 0, 1)) ?>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-bold text-white truncate"><?= e($t['name']) ?></h3>
                                    <span class="text-xs font-semibold text-teal-400 block"><?= e($t['specialty']) ?></span>
                                </div>
                            </div>
                            <div>
                                <?= get_status_badge($t['status']) ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs bg-slate-900/60 p-2.5 rounded-xl border border-slate-800/80">
                            <div>
                                <span class="text-[10px] text-slate-500 block">Phone:</span>
                                <a href="tel:<?= e($t['phone']) ?>" class="text-slate-200 hover:text-teal-400 font-mono block truncate"><?= e($t['phone']) ?></a>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 block">Rating / Exp:</span>
                                <span class="text-amber-400 font-bold inline-flex items-center gap-1">
                                    <i data-lucide="star" class="w-3 h-3 fill-current"></i> <?= number_format($t['rating'], 1) ?>
                                    <span class="text-slate-400 font-normal ml-1">(<?= $t['experience_years'] ?>y)</span>
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <div>
                                <?php if ($t['availability'] === 'available'): ?>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-950 text-emerald-400 border border-emerald-500/30">● Available</span>
                                <?php elseif ($t['availability'] === 'busy'): ?>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-950 text-amber-400 border border-amber-500/30">● On Job</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-900 text-slate-400">● Offline</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" 
                                        class="edit-tech-btn px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-teal-600 text-slate-200 hover:text-white transition inline-flex items-center gap-1 text-xs font-semibold"
                                        data-tech='<?= json_encode($t, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                                    <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit
                                </button>
                                <button type="button" 
                                        class="delete-item-btn p-1.5 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white transition inline-block"
                                        data-action="delete_technician"
                                        data-id="<?= $t['id'] ?>"
                                        data-title="<?= e($t['name']) ?>"
                                        title="Delete">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>
</div>

<!-- Add/Edit Tech Modal -->
<div id="techModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-800 border border-slate-700 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl space-y-5 text-slate-100 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b border-slate-700 pb-3">
            <h3 id="techModalTitle" class="font-heading font-bold text-lg text-white">Add Technician</h3>
            <button type="button" id="closeTechModal" class="p-1 rounded-lg text-slate-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="techForm" class="space-y-4">
            <input type="hidden" id="techId" name="id" value="0">
            <input type="hidden" name="action" value="save_technician">

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Full Name *</label>
                <input type="text" id="techName" name="name" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Specialty / Trade *</label>
                <input type="text" id="techSpecialty" name="specialty" required placeholder="e.g. Master Plumber & Pipe Specialist" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Phone Number *</label>
                    <input type="tel" id="techPhone" name="phone" required placeholder="0300 1234567" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Email</label>
                    <input type="email" id="techEmail" name="email" placeholder="tech@homefix.pk" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Experience (Years)</label>
                    <input type="number" id="techExperience" name="experience_years" min="1" value="5" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Rating (1-5)</label>
                    <input type="number" id="techRating" name="rating" min="1" max="5" step="0.1" value="4.9" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Availability</label>
                    <select id="techAvailability" name="availability" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
                        <option value="available">Available</option>
                        <option value="busy">Busy / On Job</option>
                        <option value="offline">Offline</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Account Status</label>
                    <select id="techStatus" name="status" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm">
                Save Technician Details
            </button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#openAddTechModal').on('click', function() {
        $('#techForm')[0].reset();
        $('#techId').val(0);
        $('#techModalTitle').text('Add Technician');
        $('#techModal').removeClass('hidden').addClass('flex');
    });

    $('#closeTechModal').on('click', function() {
        $('#techModal').addClass('hidden').removeClass('flex');
    });

    $(document).on('click', '.edit-tech-btn', function() {
        const t = $(this).data('tech');
        $('#techId').val(t.id);
        $('#techName').val(t.name);
        $('#techSpecialty').val(t.specialty);
        $('#techPhone').val(t.phone);
        $('#techEmail').val(t.email || '');
        $('#techExperience').val(t.experience_years);
        $('#techRating').val(t.rating);
        $('#techAvailability').val(t.availability);
        $('#techStatus').val(t.status);
        $('#techModalTitle').text('Edit Technician: ' + t.name);
        $('#techModal').removeClass('hidden').addClass('flex');
    });

    $('#techForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '../ajax/admin.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    Swal.fire({ icon: 'success', title: 'Saved!', text: res.message, timer: 1200, showConfirmButton: false }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                }
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
