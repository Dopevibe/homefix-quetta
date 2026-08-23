<?php
/**
 * HomeFix Quetta - Admin Layout Footer & Global Modals
 */
$allTechnicians = Database::fetchAll("SELECT * FROM technicians WHERE status = 'active' ORDER BY name ASC");
?>

<!-- Technician Assignment Global Modal -->
<div id="assignTechModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-800 border border-slate-700 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl space-y-5 text-slate-100">
        <div class="flex justify-between items-center border-b border-slate-700 pb-3">
            <div>
                <h3 class="font-heading font-bold text-lg text-white">Assign Technician</h3>
                <p class="text-xs text-teal-400 font-mono mt-0.5">Booking: <span id="assignModalBookingRef"></span></p>
            </div>
            <button type="button" id="closeAssignModal" class="p-1 rounded-lg text-slate-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="assignTechForm" class="space-y-4">
            <input type="hidden" id="assignModalBookingId" name="booking_id">

            <div>
                <label for="assignTechSelect" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Select On-Duty Technician in Quetta</label>
                <select id="assignTechSelect" name="technician_id" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="" disabled selected>-- Select Professional --</option>
                    <?php foreach ($allTechnicians as $tech): ?>
                        <option value="<?= $tech['id'] ?>">
                            <?= e($tech['name']) ?> — <?= e($tech['specialty']) ?> (<?= $tech['rating'] ?>★ - <?= ucfirst($tech['availability']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm">
                Confirm & Dispatch Technician
            </button>
        </form>
    </div>
</div>

<!-- Admin Scripts -->
<script>
  window.openAdminSidebar = function() {
    var sidebar = document.getElementById('adminSidebar');
    var backdrop = document.getElementById('adminSidebarBackdrop');
    if (sidebar) sidebar.classList.remove('-translate-x-full');
    if (backdrop) backdrop.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  };

  window.closeAdminSidebar = function() {
    var sidebar = document.getElementById('adminSidebar');
    var backdrop = document.getElementById('adminSidebarBackdrop');
    if (sidebar) sidebar.classList.add('-translate-x-full');
    if (backdrop) backdrop.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  };

  document.addEventListener('DOMContentLoaded', function() {
    var toggleBtn = document.getElementById('adminSidebarToggle');
    if (toggleBtn) {
      toggleBtn.onclick = function(e) {
        e.stopPropagation();
        var sidebar = document.getElementById('adminSidebar');
        if (sidebar && sidebar.classList.contains('-translate-x-full')) {
          window.openAdminSidebar();
        } else {
          window.closeAdminSidebar();
        }
      };
    }
  });
</script>
<script src="<?= asset('assets/js/admin.js') ?>?v=<?= time() ?>"></script>
<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
</body>
</html>
