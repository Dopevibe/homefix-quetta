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
        
        <!-- Controls & Filter Toolbar -->
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 bg-slate-950 p-4 sm:p-5 rounded-3xl border border-slate-800 shadow-xl">
            
            <!-- Search Input -->
            <div class="relative flex-1 min-w-0">
                <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="techSearchInput" placeholder="Search technician name, specialty, phone..." 
                       class="w-full bg-slate-900/90 border border-slate-800 rounded-2xl pl-10 pr-4 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 transition">
            </div>

            <!-- Filters & View Switcher -->
            <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
                <select id="techAvailabilityFilter" class="bg-slate-900 border border-slate-800 rounded-2xl px-3.5 py-2.5 text-xs text-slate-300 focus:outline-none focus:border-teal-500">
                    <option value="all">All Availability</option>
                    <option value="available">Available Only</option>
                    <option value="busy">On Job (Busy)</option>
                    <option value="offline">Offline</option>
                </select>

                <!-- View Switcher -->
                <div class="flex items-center bg-slate-900 p-1 rounded-2xl border border-slate-800 shrink-0">
                    <button type="button" id="techViewGrid" class="p-2 rounded-xl bg-teal-600 text-white shadow-sm transition" title="Roster Grid View">
                        <i data-lucide="grid-3x3" class="w-4 h-4"></i>
                    </button>
                    <button type="button" id="techViewList" class="p-2 rounded-xl text-slate-400 hover:text-white transition" title="Compact List View">
                        <i data-lucide="list" class="w-4 h-4"></i>
                    </button>
                </div>

                <span class="text-xs font-mono font-bold text-teal-400 bg-slate-900 px-3.5 py-2.5 rounded-2xl border border-slate-800 whitespace-nowrap">
                    <?= count($technicians) ?> Pros
                </span>
            </div>
        </div>

        <!-- 1. MODERN PERSONNEL ROSTER GRID VIEW (Default) -->
        <div id="techGridView" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            <?php if (!empty($technicians)): ?>
                <?php foreach ($technicians as $t): ?>
                    <div class="tech-item-card bg-slate-950 border border-slate-800 hover:border-slate-700/80 rounded-3xl p-5 shadow-xl hover:shadow-2xl hover:shadow-teal-950/20 transition-all duration-200 flex flex-col justify-between space-y-4"
                         data-name="<?= strtolower(e($t['name'])) ?>" 
                         data-specialty="<?= strtolower(e($t['specialty'])) ?>"
                         data-availability="<?= strtolower(e($t['availability'])) ?>">
                        
                        <!-- Top Header: Avatar + Name + Specialty + Availability -->
                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-teal-950 border border-teal-500/40 text-teal-300 flex items-center justify-center font-heading font-extrabold text-base shrink-0 shadow-md">
                                        <?= strtoupper(substr($t['name'], 0, 1)) ?>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-base font-extrabold font-heading text-white truncate leading-snug"><?= e($t['name']) ?></h3>
                                        <span class="text-xs font-semibold text-teal-400 block truncate mt-0.5"><?= e($t['specialty']) ?></span>
                                    </div>
                                </div>
                                
                                <div>
                                    <?php if ($t['availability'] === 'available'): ?>
                                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold uppercase bg-emerald-950/90 text-emerald-400 border border-emerald-500/30 shadow-sm">
                                            ● Available
                                        </span>
                                    <?php elseif ($t['availability'] === 'busy'): ?>
                                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold uppercase bg-amber-950/90 text-amber-400 border border-amber-500/30 shadow-sm">
                                            ● On Job
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold uppercase bg-slate-900 text-slate-400 border border-slate-750">
                                            ● Offline
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Performance & Experience Metrics -->
                            <div class="grid grid-cols-2 gap-2 text-xs bg-slate-900/80 p-3 rounded-2xl border border-slate-850">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-slate-500 block">Experience</span>
                                    <span class="text-xs font-mono font-bold text-slate-200 block mt-0.5"><?= $t['experience_years'] ?> Years</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] uppercase font-bold text-slate-500 block">Rating</span>
                                    <span class="text-xs font-mono font-extrabold text-amber-400 inline-flex items-center gap-1 mt-0.5">
                                        <i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i>
                                        <?= number_format($t['rating'], 1) ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Contact Info Links -->
                            <div class="space-y-1.5 text-xs text-slate-400 pt-1">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="phone" class="w-3.5 h-3.5 text-teal-400 shrink-0"></i>
                                    <a href="tel:<?= e($t['phone']) ?>" class="text-slate-300 hover:text-teal-400 font-mono font-semibold truncate transition">
                                        <?= e($t['phone']) ?>
                                    </a>
                                </div>
                                <?php if (!empty($t['email'])): ?>
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-500 shrink-0"></i>
                                        <a href="mailto:<?= e($t['email']) ?>" class="text-slate-400 hover:text-teal-400 truncate transition">
                                            <?= e($t['email']) ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Card Action Footer -->
                        <div class="pt-3 border-t border-slate-900 flex items-center gap-2">
                            <button type="button" 
                                    class="edit-tech-btn flex-1 py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-teal-600 text-slate-200 hover:text-white font-bold text-xs transition inline-flex items-center justify-center gap-1.5 shadow"
                                    data-tech='<?= json_encode($t, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                                <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                <span>Edit Details</span>
                            </button>
                            <button type="button" 
                                    class="delete-item-btn p-2.5 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white font-bold text-xs transition inline-flex items-center justify-center"
                                    data-action="delete_technician"
                                    data-id="<?= $t['id'] ?>"
                                    data-title="<?= e($t['name']) ?>"
                                    title="Delete Technician">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full p-12 text-center text-slate-500 bg-slate-950 rounded-3xl border border-slate-800">
                    <i data-lucide="users" class="w-10 h-10 mx-auto mb-2 text-slate-600"></i>
                    <p>No technicians registered yet. Click "Add New Technician" above.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- 2. STREAMLINED LIST VIEW (Alternative Compact View) -->
        <div id="techListView" class="hidden bg-slate-950 border border-slate-800 rounded-3xl shadow-xl overflow-hidden">
            <div class="divide-y divide-slate-800/80">
                <?php foreach ($technicians as $t): ?>
                    <div class="tech-item-row p-4 sm:p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 hover:bg-slate-900/60 transition"
                         data-name="<?= strtolower(e($t['name'])) ?>" 
                         data-specialty="<?= strtolower(e($t['specialty'])) ?>"
                         data-availability="<?= strtolower(e($t['availability'])) ?>">
                        
                        <div class="flex items-center gap-3.5 min-w-0 flex-1">
                            <div class="w-12 h-12 rounded-2xl bg-teal-950 border border-teal-500/30 text-teal-300 flex items-center justify-center font-heading font-extrabold text-sm shrink-0">
                                <?= strtoupper(substr($t['name'], 0, 1)) ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                    <h4 class="text-sm font-extrabold font-heading text-white truncate"><?= e($t['name']) ?></h4>
                                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase bg-slate-900 text-teal-300 border border-slate-800">
                                        <?= e($t['specialty']) ?>
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 text-xs text-slate-400">
                                    <span class="font-mono"><?= e($t['phone']) ?></span>
                                    <span>&bull; <?= $t['experience_years'] ?>y exp</span>
                                    <span class="text-amber-400 font-bold inline-flex items-center gap-0.5">
                                        <i data-lucide="star" class="w-3 h-3 fill-current"></i> <?= number_format($t['rating'], 1) ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between md:justify-end gap-4 w-full md:w-auto shrink-0 border-t md:border-t-0 pt-2 md:pt-0 border-slate-850">
                            <div>
                                <?php if ($t['availability'] === 'available'): ?>
                                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold uppercase bg-emerald-950/90 text-emerald-400 border border-emerald-500/30">
                                        Available
                                    </span>
                                <?php elseif ($t['availability'] === 'busy'): ?>
                                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold uppercase bg-amber-950/90 text-amber-400 border border-amber-500/30">
                                        On Job
                                    </span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold uppercase bg-slate-900 text-slate-400">
                                        Offline
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        class="edit-tech-btn px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-teal-600 text-slate-200 hover:text-white text-xs font-bold transition inline-flex items-center gap-1.5"
                                        data-tech='<?= json_encode($t, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                                    <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit
                                </button>
                                <button type="button" 
                                        class="delete-item-btn p-2 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white transition inline-block"
                                        data-action="delete_technician"
                                        data-id="<?= $t['id'] ?>"
                                        data-title="<?= e($t['name']) ?>"
                                        title="Delete Technician">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
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
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Technician Name *</label>
                <input type="text" id="techName" name="name" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Primary Trade / Specialty *</label>
                <input type="text" id="techSpecialty" name="specialty" placeholder="e.g. Senior Electrician & Solar Pro" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Phone Number *</label>
                    <input type="text" id="techPhone" name="phone" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Email Address</label>
                    <input type="email" id="techEmail" name="email" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Experience (Years)</label>
                    <input type="number" id="techExperience" name="experience_years" min="0" max="50" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Initial Rating</label>
                    <input type="number" id="techRating" name="rating" step="0.1" min="1" max="5" value="5.0" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
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
    // Live Search & Availability Filter
    function filterTechs() {
        const query = ($('#techSearchInput').val() || '').toLowerCase().trim();
        const availability = ($('#techAvailabilityFilter').val() || 'all').toLowerCase().trim();

        $('.tech-item-row, .tech-item-card').each(function() {
            const el = $(this);
            const name = (el.data('name') || '').toString();
            const spec = (el.data('specialty') || '').toString();
            const avail = (el.data('availability') || '').toString();

            const matchQuery = !query || name.includes(query) || spec.includes(query);
            const matchAvail = (availability === 'all') || (avail === availability);

            if (matchQuery && matchAvail) {
                el.show();
            } else {
                el.hide();
            }
        });
    }

    $('#techSearchInput').on('input', filterTechs);
    $('#techAvailabilityFilter').on('change', filterTechs);

    // View Switcher (Grid vs List)
    $('#techViewGrid').on('click', function() {
        $(this).addClass('bg-teal-600 text-white').removeClass('text-slate-400');
        $('#techViewList').removeClass('bg-teal-600 text-white').addClass('text-slate-400');
        $('#techGridView').removeClass('hidden').addClass('grid');
        $('#techListView').addClass('hidden');
    });

    $('#techViewList').on('click', function() {
        $(this).addClass('bg-teal-600 text-white').removeClass('text-slate-400');
        $('#techViewGrid').removeClass('bg-teal-600 text-white').addClass('text-slate-400');
        $('#techGridView').addClass('hidden').removeClass('grid');
        $('#techListView').removeClass('hidden');
    });

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
