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

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
        
        <!-- Search & Filter Controls -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-slate-950 p-4 rounded-2xl border border-slate-800">
            <div class="relative flex-1">
                <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="serviceSearchInput" placeholder="Search service name, category, price..." 
                       class="w-full bg-slate-900 border border-slate-700/80 rounded-xl pl-10 pr-4 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-teal-500">
            </div>
            <div class="flex items-center gap-2">
                <select id="serviceCategoryFilter" class="bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-slate-300 focus:outline-none focus:border-teal-500">
                    <option value="all">All Categories</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= strtolower(e($c['name'])) ?>"><?= e($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="text-xs font-mono font-bold text-teal-400 bg-slate-900 px-3 py-2 rounded-xl border border-slate-800 whitespace-nowrap">
                    <?= count($services) ?> Total
                </span>
            </div>
        </div>

        <!-- 1. Desktop Responsive Table View (Hidden on mobile/small screens) -->
        <div class="hidden md:block bg-slate-950 border border-slate-800 rounded-3xl shadow-xl overflow-hidden">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/80 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5">Service Details</th>
                        <th class="px-3 py-3.5">Category</th>
                        <th class="px-3 py-3.5">Price</th>
                        <th class="px-3 py-3.5">Duration</th>
                        <th class="px-3 py-3.5 text-center">Featured</th>
                        <th class="px-3 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-slate-200" id="servicesTableBody">
                    <?php if (!empty($services)): ?>
                        <?php foreach ($services as $s): ?>
                            <tr class="hover:bg-slate-900/60 transition service-item-row" 
                                data-name="<?= strtolower(e($s['name'])) ?>" 
                                data-category="<?= strtolower(e($s['category_name'])) ?>">
                                <td class="px-4 py-3.5 font-medium">
                                    <div class="flex items-center gap-3">
                                        <img src="<?= asset($s['image'] ?? 'assets/images/services/plumbing_leak.jpg') ?>" alt="<?= e($s['name']) ?>" class="w-10 h-10 object-cover rounded-xl border border-slate-800 shrink-0">
                                        <div class="min-w-0 max-w-xs">
                                            <span class="font-bold text-white block truncate"><?= e($s['name']) ?></span>
                                            <span class="text-[11px] text-slate-400 block truncate"><?= e($s['description']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3.5 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-900 border border-slate-700/80 text-teal-300">
                                        <?= e($s['category_name']) ?>
                                    </span>
                                </td>
                                <td class="px-3 py-3.5 whitespace-nowrap font-mono font-bold text-teal-400 text-xs">
                                    <?= format_price($s['price']) ?>
                                </td>
                                <td class="px-3 py-3.5 whitespace-nowrap text-xs font-mono text-slate-300">
                                    <?= e($s['duration']) ?>
                                </td>
                                <td class="px-3 py-3.5 whitespace-nowrap text-center">
                                    <?= ($s['is_featured']) ? '<span class="text-amber-400 font-bold text-xs inline-flex items-center gap-1"><i data-lucide="star" class="w-3 h-3 fill-current"></i> Yes</span>' : '<span class="text-slate-500 text-xs">No</span>' ?>
                                </td>
                                <td class="px-3 py-3.5 whitespace-nowrap text-center">
                                    <?= get_status_badge($s['status']) ?>
                                </td>
                                <td class="px-4 py-3.5 text-right whitespace-nowrap space-x-1">
                                    <button type="button" 
                                            class="edit-service-btn p-2 rounded-xl bg-slate-800 hover:bg-teal-600 text-white transition inline-block"
                                            data-service='<?= json_encode($s, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
                                            title="Edit Service">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button type="button" 
                                            class="delete-item-btn p-2 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white transition inline-block"
                                            data-action="delete_service"
                                            data-id="<?= $s['id'] ?>"
                                            data-title="<?= e($s['name']) ?>"
                                            title="Delete Service">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="p-8 text-center text-slate-500">No services found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- 2. Mobile & Tablet Card Layout (Visible on screens < 768px, zero overflow) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 md:hidden" id="servicesMobileCards">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $s): ?>
                    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-4 space-y-3 service-item-card shadow-lg"
                         data-name="<?= strtolower(e($s['name'])) ?>" 
                         data-category="<?= strtolower(e($s['category_name'])) ?>">
                        <div class="flex items-start gap-3">
                            <img src="<?= asset($s['image'] ?? 'assets/images/services/plumbing_leak.jpg') ?>" alt="<?= e($s['name']) ?>" class="w-12 h-12 object-cover rounded-xl border border-slate-800 shrink-0">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-slate-900 border border-slate-700/80 text-teal-300">
                                        <?= e($s['category_name']) ?>
                                    </span>
                                    <?= get_status_badge($s['status']) ?>
                                </div>
                                <h3 class="text-sm font-bold text-white mt-1 leading-snug"><?= e($s['name']) ?></h3>
                            </div>
                        </div>

                        <p class="text-xs text-slate-400 line-clamp-2"><?= e($s['description']) ?></p>

                        <div class="flex items-center justify-between pt-2 border-t border-slate-800 text-xs">
                            <div>
                                <span class="text-[10px] text-slate-500 block uppercase font-semibold">Standard Rate</span>
                                <span class="font-mono font-bold text-teal-400 text-sm"><?= format_price($s['price']) ?></span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-slate-500 block uppercase font-semibold">Duration</span>
                                <span class="font-mono text-slate-300"><?= e($s['duration']) ?></span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-800">
                            <div>
                                <?= ($s['is_featured']) ? '<span class="text-amber-400 font-bold text-[11px] inline-flex items-center gap-1"><i data-lucide="star" class="w-3 h-3 fill-current"></i> Featured</span>' : '<span class="text-slate-500 text-[11px]">Standard</span>' ?>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" 
                                        class="edit-service-btn px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-teal-600 text-slate-200 hover:text-white transition inline-flex items-center gap-1 text-xs font-semibold"
                                        data-service='<?= json_encode($s, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                                    <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit
                                </button>
                                <button type="button" 
                                        class="delete-item-btn p-1.5 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white transition inline-block"
                                        data-action="delete_service"
                                        data-id="<?= $s['id'] ?>"
                                        data-title="<?= e($s['name']) ?>"
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
