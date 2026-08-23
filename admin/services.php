<?php
/**
 * HomeFix Quetta - Admin Services Management
 */
$adminPageTitle = 'Manage Services | HomeFix Quetta Admin';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

$services = Database::fetchAll(
    "SELECT s.*, c.name as category_name 
     FROM services s 
     JOIN categories c ON s.category_id = c.id 
     ORDER BY s.category_id ASC, s.name ASC"
);
$categories = Database::fetchAll("SELECT * FROM categories WHERE status = 'active' ORDER BY name ASC");
?>

<div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-900">
    
    <!-- Top Bar -->
    <header class="h-16 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
        <div class="flex items-center gap-4">
            <button id="adminSidebarToggle" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h2 class="text-lg font-bold font-heading text-white">Services Directory Management</h2>
        </div>
        <button type="button" id="openAddServiceModal" class="btn-primary px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-md">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add New Service</span>
        </button>
    </header>

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 pb-32 sm:pb-16 lg:pb-12 space-y-6">
        
        <!-- Controls & Filter Toolbar -->
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 bg-slate-950 p-4 sm:p-5 rounded-3xl border border-slate-800 shadow-xl">
            
            <!-- Search Input -->
            <div class="relative flex-1 min-w-0">
                <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="serviceSearchInput" placeholder="Search by service name, fault, category..." 
                       class="w-full bg-slate-900/90 border border-slate-800 rounded-2xl pl-10 pr-4 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 transition">
            </div>

            <!-- Category & View Switcher -->
            <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
                <select id="serviceCategoryFilter" class="bg-slate-900 border border-slate-800 rounded-2xl px-3.5 py-2.5 text-xs text-slate-300 focus:outline-none focus:border-teal-500">
                    <option value="all">All Categories</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= strtolower(e($c['name'])) ?>"><?= e($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Layout Toggle Buttons (Grid vs List) -->
                <div class="flex items-center bg-slate-900 p-1 rounded-2xl border border-slate-800 shrink-0">
                    <button type="button" id="viewModeGrid" class="p-2 rounded-xl bg-teal-600 text-white shadow-sm transition" title="Catalog Grid View">
                        <i data-lucide="grid-3x3" class="w-4 h-4"></i>
                    </button>
                    <button type="button" id="viewModeList" class="p-2 rounded-xl text-slate-400 hover:text-white transition" title="Compact List View">
                        <i data-lucide="list" class="w-4 h-4"></i>
                    </button>
                </div>

                <span class="text-xs font-mono font-bold text-teal-400 bg-slate-900 px-3.5 py-2.5 rounded-2xl border border-slate-800 whitespace-nowrap">
                    <?= count($services) ?> Services
                </span>
            </div>
        </div>

        <!-- 1. MODERN PRODUCT CATALOG GRID VIEW (Default, Responsive 1-3 cols) -->
        <div id="servicesGridView" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $s): ?>
                    <div class="service-item-card bg-slate-950 border border-slate-800 hover:border-slate-700/80 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl hover:shadow-teal-950/20 transition-all duration-200 flex flex-col justify-between group"
                         data-name="<?= strtolower(e($s['name'])) ?>" 
                         data-category="<?= strtolower(e($s['category_name'])) ?>">
                        
                        <div>
                            <!-- Card Header Image & Overlay Badges -->
                            <div class="relative h-44 w-full bg-slate-900 overflow-hidden">
                                <img src="<?= asset($s['image'] ?? 'assets/images/services/plumbing_leak.jpg') ?>" 
                                     alt="<?= e($s['name']) ?>" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                                
                                <!-- Category & Status Pills Over Image -->
                                <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2">
                                    <span class="px-3 py-1 rounded-xl text-xs font-bold uppercase tracking-wider bg-slate-950/90 text-teal-300 border border-teal-500/30 backdrop-blur-md shadow-md">
                                        <?= e($s['category_name']) ?>
                                    </span>
                                    <div class="backdrop-blur-md shadow-md">
                                        <?= get_status_badge($s['status']) ?>
                                    </div>
                                </div>

                                <?php if ($s['is_featured']): ?>
                                    <div class="absolute bottom-3 left-3">
                                        <span class="px-2.5 py-1 rounded-xl text-[11px] font-bold bg-amber-500 text-slate-950 inline-flex items-center gap-1 shadow-lg">
                                            <i data-lucide="star" class="w-3 h-3 fill-current"></i> Featured
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Card Body -->
                            <div class="p-5 space-y-3">
                                <h3 class="text-base font-extrabold font-heading text-white line-clamp-1 group-hover:text-teal-400 transition">
                                    <?= e($s['name']) ?>
                                </h3>
                                
                                <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed">
                                    <?= e($s['description']) ?>
                                </p>

                                <!-- Price & Duration Pill -->
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-900/80 border border-slate-800/80 text-xs">
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-slate-500 block">Fixed Rate</span>
                                        <span class="text-base font-mono font-extrabold text-teal-400"><?= format_price($s['price']) ?></span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] uppercase font-bold text-slate-500 block">Duration</span>
                                        <span class="text-xs font-mono font-semibold text-slate-300 flex items-center justify-end gap-1 mt-0.5">
                                            <i data-lucide="clock" class="w-3 h-3 text-teal-400"></i>
                                            <?= e($s['duration']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Footer -->
                        <div class="px-5 pb-5 pt-2 flex items-center gap-2 border-t border-slate-900">
                            <button type="button" 
                                    class="edit-service-btn flex-1 py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-teal-600 text-slate-200 hover:text-white font-bold text-xs transition inline-flex items-center justify-center gap-1.5 shadow"
                                    data-service='<?= json_encode($s, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                                <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                <span>Edit Service</span>
                            </button>
                            <button type="button" 
                                    class="delete-item-btn p-2.5 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white font-bold text-xs transition inline-flex items-center justify-center"
                                    data-action="delete_service"
                                    data-id="<?= $s['id'] ?>"
                                    data-title="<?= e($s['name']) ?>"
                                    title="Delete Service">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full p-12 text-center text-slate-500 bg-slate-950 rounded-3xl border border-slate-800">
                    <i data-lucide="grid" class="w-10 h-10 mx-auto mb-2 text-slate-600"></i>
                    <p>No services cataloged yet. Click "Add New Service" above.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- 2. STREAMLINED ULTRA-COMPACT LIST VIEW (Alternative Clean View) -->
        <div id="servicesListView" class="hidden bg-slate-950 border border-slate-800 rounded-3xl shadow-xl overflow-hidden">
            <div class="divide-y divide-slate-800/80">
                <?php foreach ($services as $s): ?>
                    <div class="service-item-row p-4 sm:p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 hover:bg-slate-900/60 transition"
                         data-name="<?= strtolower(e($s['name'])) ?>" 
                         data-category="<?= strtolower(e($s['category_name'])) ?>">
                        
                        <div class="flex items-center gap-4 min-w-0 flex-1">
                            <img src="<?= asset($s['image'] ?? 'assets/images/services/plumbing_leak.jpg') ?>" 
                                 alt="<?= e($s['name']) ?>" 
                                 class="w-14 h-14 object-cover rounded-2xl border border-slate-800 shrink-0">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase bg-slate-900 text-teal-300 border border-slate-800">
                                        <?= e($s['category_name']) ?>
                                    </span>
                                    <?= get_status_badge($s['status']) ?>
                                    <?php if ($s['is_featured']): ?>
                                        <span class="text-amber-400 text-[10px] font-bold inline-flex items-center gap-0.5">
                                            <i data-lucide="star" class="w-3 h-3 fill-current"></i> Featured
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <h4 class="text-sm font-extrabold font-heading text-white truncate"><?= e($s['name']) ?></h4>
                                <p class="text-xs text-slate-400 truncate max-w-lg"><?= e($s['description']) ?></p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between md:justify-end gap-6 w-full md:w-auto shrink-0 border-t md:border-t-0 pt-3 md:pt-0 border-slate-850">
                            <div class="text-left md:text-right">
                                <span class="text-sm font-mono font-extrabold text-teal-400 block"><?= format_price($s['price']) ?></span>
                                <span class="text-[11px] text-slate-400 font-mono"><?= e($s['duration']) ?></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        class="edit-service-btn px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-teal-600 text-slate-200 hover:text-white text-xs font-bold transition inline-flex items-center gap-1.5"
                                        data-service='<?= json_encode($s, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                                    <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit
                                </button>
                                <button type="button" 
                                        class="delete-item-btn p-2 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white transition inline-block"
                                        data-action="delete_service"
                                        data-id="<?= $s['id'] ?>"
                                        data-title="<?= e($s['name']) ?>"
                                        title="Delete Service">
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

<!-- Add / Edit Service Modal -->
<div id="serviceModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-800 border border-slate-700 rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl space-y-5 text-slate-100 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b border-slate-700 pb-3">
            <h3 id="serviceModalTitle" class="font-heading font-bold text-lg text-white">Add New Service</h3>
            <button type="button" id="closeServiceModal" class="p-1 rounded-lg text-slate-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="serviceForm" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" id="serviceId" name="id" value="0">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Service Name *</label>
                    <input type="text" id="serviceName" name="name" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Category *</label>
                    <select id="serviceCategory" name="category_id" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
                        <option value="" disabled selected>-- Select Category --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= e($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Price (PKR) *</label>
                    <input type="number" id="servicePrice" name="price" step="50" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Estimated Duration</label>
                    <input type="text" id="serviceDuration" name="duration" placeholder="e.g. 1 - 2 Hours" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Short Summary Description *</label>
                <input type="text" id="serviceDesc" name="description" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Detailed Scope</label>
                <textarea id="serviceDetailedDesc" name="detailed_description" rows="3" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">What is Included (One item per line)</label>
                <textarea id="serviceIncludes" name="includes_list" rows="3" placeholder="Inspection of leakages&#10;Pipe fitting replacement&#10;30-day warranty" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white resize-none font-mono"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Service Image</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-600 file:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Status</label>
                    <select id="serviceStatus" name="status" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="pt-4">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="serviceFeatured" name="is_featured" value="1" class="w-4 h-4 text-teal-600 rounded bg-slate-900 border-slate-700">
                        <span class="text-xs font-bold text-slate-300">Featured Service</span>
                    </label>
                </div>
            </div>

            <button type="submit" id="saveServiceSubmitBtn" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm">
                Save Service Details
            </button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Live Search & Category Filter
    function filterServices() {
        const query = ($('#serviceSearchInput').val() || '').toLowerCase().trim();
        const category = ($('#serviceCategoryFilter').val() || 'all').toLowerCase().trim();

        $('.service-item-row, .service-item-card').each(function() {
            const row = $(this);
            const name = (row.data('name') || '').toString();
            const cat = (row.data('category') || '').toString();

            const matchQuery = !query || name.includes(query) || cat.includes(query);
            const matchCategory = (category === 'all') || (cat === category);

            if (matchQuery && matchCategory) {
                row.show();
            } else {
                row.hide();
            }
        });
    }

    $('#serviceSearchInput').on('input', filterServices);
    $('#serviceCategoryFilter').on('change', filterServices);

    // Toggle Grid vs List Views
    $('#viewModeGrid').on('click', function() {
        $(this).addClass('bg-teal-600 text-white').removeClass('text-slate-400');
        $('#viewModeList').removeClass('bg-teal-600 text-white').addClass('text-slate-400');
        $('#servicesGridView').removeClass('hidden').addClass('grid');
        $('#servicesListView').addClass('hidden');
    });

    $('#viewModeList').on('click', function() {
        $(this).addClass('bg-teal-600 text-white').removeClass('text-slate-400');
        $('#viewModeGrid').removeClass('bg-teal-600 text-white').addClass('text-slate-400');
        $('#servicesGridView').addClass('hidden').removeClass('grid');
        $('#servicesListView').removeClass('hidden');
    });

    $('#openAddServiceModal').on('click', function() {
        $('#serviceForm')[0].reset();
        $('#serviceId').val(0);
        $('#serviceModalTitle').text('Add New Service');
        $('#serviceModal').removeClass('hidden').addClass('flex');
    });

    $('#closeServiceModal').on('click', function() {
        $('#serviceModal').addClass('hidden').removeClass('flex');
    });

    $(document).on('click', '.edit-service-btn', function() {
        const s = $(this).data('service');
        $('#serviceId').val(s.id);
        $('#serviceName').val(s.name);
        $('#serviceCategory').val(s.category_id);
        $('#servicePrice').val(s.price);
        $('#serviceDuration').val(s.duration);
        $('#serviceDesc').val(s.description);
        $('#serviceDetailedDesc').val(s.detailed_description || '');
        $('#serviceIncludes').val(s.includes_list || '');
        $('#serviceStatus').val(s.status);
        $('#serviceFeatured').prop('checked', s.is_featured == 1);
        $('#serviceModalTitle').text('Edit Service: ' + s.name);
        $('#serviceModal').removeClass('hidden').addClass('flex');
    });

    $('#serviceForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        formData.append('action', 'save_service');

        const btn = $('#saveServiceSubmitBtn');
        btn.prop('disabled', true).html('Saving Service...');

        $.ajax({
            url: '../ajax/admin.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    Swal.fire({ icon: 'success', title: 'Saved!', text: res.message, timer: 1200, showConfirmButton: false }).then(() => location.reload());
                } else {
                    btn.prop('disabled', false).html('Save Service Details');
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                }
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
