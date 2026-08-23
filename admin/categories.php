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

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
        
        <!-- 1. Desktop Responsive Table View -->
        <div class="hidden md:block bg-slate-950 border border-slate-800 rounded-3xl shadow-xl overflow-hidden">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/80 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5">Category</th>
                        <th class="px-3 py-3.5">Description</th>
                        <th class="px-3 py-3.5">Services Linked</th>
                        <th class="px-3 py-3.5">Icon</th>
                        <th class="px-3 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-slate-200">
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $c): ?>
                            <tr class="hover:bg-slate-900/60 transition">
                                <td class="px-4 py-3.5 font-bold">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-teal-900/60 text-teal-400 flex items-center justify-center border border-teal-500/30 shrink-0">
                                            <i data-lucide="<?= e($c['icon'] ?? 'wrench') ?>" class="w-4 h-4"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-white block truncate"><?= e($c['name']) ?></span>
                                            <span class="text-[10px] text-slate-400 font-mono"><?= e($c['slug']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3.5 text-xs text-slate-400 max-w-sm">
                                    <p class="truncate"><?= e($c['description']) ?></p>
                                </td>
                                <td class="px-3 py-3.5 font-bold text-teal-400 font-mono whitespace-nowrap">
                                    <?= $c['service_count'] ?> Services
                                </td>
                                <td class="px-3 py-3.5 text-xs font-mono text-slate-400 whitespace-nowrap">
                                    <?= e($c['icon']) ?>
                                </td>
                                <td class="px-3 py-3.5 whitespace-nowrap text-center">
                                    <?= get_status_badge($c['status']) ?>
                                </td>
                                <td class="px-4 py-3.5 text-right whitespace-nowrap space-x-1">
                                    <button type="button" 
                                            class="edit-category-btn p-2 rounded-xl bg-slate-800 hover:bg-teal-600 text-white transition inline-block"
                                            data-cat='<?= json_encode($c, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
                                            title="Edit Category">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button type="button" 
                                            class="delete-item-btn p-2 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white transition inline-block"
                                            data-action="delete_category"
                                            data-id="<?= $c['id'] ?>"
                                            data-title="<?= e($c['name']) ?>"
                                            title="Delete Category">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="p-8 text-center text-slate-500">No categories found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- 2. Mobile Responsive Card View -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 md:hidden">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $c): ?>
                    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-4 space-y-3 shadow-lg">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-teal-900/60 text-teal-400 flex items-center justify-center border border-teal-500/30 shrink-0">
                                    <i data-lucide="<?= e($c['icon'] ?? 'wrench') ?>" class="w-5 h-5"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-bold text-white truncate"><?= e($c['name']) ?></h3>
                                    <span class="text-xs font-mono font-bold text-teal-400"><?= $c['service_count'] ?> Services</span>
                                </div>
                            </div>
                            <div>
                                <?= get_status_badge($c['status']) ?>
                            </div>
                        </div>

                        <p class="text-xs text-slate-400 line-clamp-2"><?= e($c['description']) ?></p>

                        <div class="flex items-center justify-between pt-2 border-t border-slate-800">
                            <span class="text-[10px] font-mono text-slate-500">slug: <?= e($c['slug']) ?></span>
                            <div class="flex items-center gap-1.5">
                                <button type="button" 
                                        class="edit-category-btn px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-teal-600 text-slate-200 hover:text-white transition inline-flex items-center gap-1 text-xs font-semibold"
                                        data-cat='<?= json_encode($c, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                                    <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit
                                </button>
                                <button type="button" 
                                        class="delete-item-btn p-1.5 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white transition inline-block"
                                        data-action="delete_category"
                                        data-id="<?= $c['id'] ?>"
                                        data-title="<?= e($c['name']) ?>"
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
