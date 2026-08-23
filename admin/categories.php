<?php
/**
 * HomeFix Quetta - Admin Categories Management
 */
$adminPageTitle = 'Manage Categories | HomeFix Quetta Admin';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

$categories = Database::fetchAll(
    "SELECT c.*, COUNT(s.id) as service_count 
     FROM categories c 
     LEFT JOIN services s ON c.id = s.category_id 
     GROUP BY c.id 
     ORDER BY c.sort_order ASC, c.name ASC"
);
?>

<div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-900">
    
    <header class="h-16 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
        <div class="flex items-center gap-4">
            <button id="adminSidebarToggle" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h2 class="text-lg font-bold font-heading text-white">Service Categories</h2>
        </div>
        <button type="button" id="openAddCategoryModal" class="btn-primary px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-md">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add New Category</span>
        </button>
    </header>

    <main class="flex-1 overflow-y-auto p-6 space-y-6">
        
        <div class="bg-slate-800/90 border border-slate-700/80 rounded-3xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm text-slate-300">
                    <thead class="bg-slate-950 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4">Services Linked</th>
                            <th class="px-6 py-4">Icon Name</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/60 text-slate-200">
                        <?php foreach ($categories as $c): ?>
                            <tr class="hover:bg-slate-750 transition">
                                <td class="px-6 py-4 font-bold flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-teal-900/60 text-teal-400 flex items-center justify-center border border-teal-500/30">
                                        <i data-lucide="<?= e($c['icon'] ?? 'wrench') ?>" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <span class="text-white block"><?= e($c['name']) ?></span>
                                        <span class="text-[10px] text-slate-400 font-mono"><?= e($c['slug']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400 max-w-sm">
                                    <?= e($c['description']) ?>
                                </td>
                                <td class="px-6 py-4 font-bold text-teal-400 font-mono">
                                    <?= $c['service_count'] ?> Services
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-400">
                                    <?= e($c['icon']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= get_status_badge($c['status']) ?>
                                </td>
                                <td class="px-6 py-4 text-right space-x-1">
                                    <button type="button" 
                                            class="edit-category-btn p-2 rounded-lg bg-slate-700 hover:bg-teal-600 text-white inline-block"
                                            data-cat='<?= json_encode($c, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
                                            title="Edit">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button type="button" 
                                            class="delete-item-btn p-2 rounded-lg bg-slate-700 hover:bg-rose-600 text-slate-300 hover:text-white inline-block"
                                            data-action="delete_category"
                                            data-id="<?= $c['id'] ?>"
                                            data-title="<?= e($c['name']) ?>"
                                            title="Delete">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<!-- Add/Edit Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-800 border border-slate-700 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl space-y-5 text-slate-100">
        <div class="flex justify-between items-center border-b border-slate-700 pb-3">
            <h3 id="categoryModalTitle" class="font-heading font-bold text-lg text-white">Add Category</h3>
            <button type="button" id="closeCategoryModal" class="p-1 rounded-lg text-slate-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="categoryForm" class="space-y-4">
            <input type="hidden" id="categoryId" name="id" value="0">
            <input type="hidden" name="action" value="save_category">

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Category Name *</label>
                <input type="text" id="categoryName" name="name" required placeholder="e.g. Plumbing Services" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Lucide Icon Key</label>
                <input type="text" id="categoryIcon" name="icon" placeholder="droplet, zap, wind, hammer, etc." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Short Description</label>
                <textarea id="categoryDesc" name="description" rows="3" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Status</label>
                <select id="categoryStatus" name="status" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm">
                Save Category
            </button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#openAddCategoryModal').on('click', function() {
        $('#categoryForm')[0].reset();
        $('#categoryId').val(0);
        $('#categoryModalTitle').text('Add Category');
        $('#categoryModal').removeClass('hidden').addClass('flex');
    });

    $('#closeCategoryModal').on('click', function() {
        $('#categoryModal').addClass('hidden').removeClass('flex');
    });

    $(document).on('click', '.edit-category-btn', function() {
        const c = $(this).data('cat');
        $('#categoryId').val(c.id);
        $('#categoryName').val(c.name);
        $('#categoryIcon').val(c.icon || 'wrench');
        $('#categoryDesc').val(c.description || '');
        $('#categoryStatus').val(c.status);
        $('#categoryModalTitle').text('Edit Category: ' + c.name);
        $('#categoryModal').removeClass('hidden').addClass('flex');
    });

    $('#categoryForm').on('submit', function(e) {
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
