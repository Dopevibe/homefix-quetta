/**
 * HomeFix Quetta - Dynamic Services Filtering & Search Module
 * Uses HF interaction engine for smooth transitions and states
 */

$(document).ready(function () {
  let searchTimeout = null;

  // Filter Trigger with smooth transition
  function fetchFilteredServices() {
    const search = $('#serviceSearchInput').val() || '';
    const category = $('.category-filter-btn.active').data('category') || 'all';
    const sort = $('#serviceSortSelect').val() || 'default';
    const container = $('#servicesGridContainer');

    // Fade out current content
    container.css({ opacity: 0.4, transition: 'opacity 0.2s ease', pointerEvents: 'none' });

    $.ajax({
      url: 'ajax/services.php',
      type: 'GET',
      dataType: 'json',
      data: {
        action: 'filter',
        search: search,
        category: category,
        sort: sort
      },
      success: function (res) {
        if (res.success && res.data.html) {
          container.html(res.data.html);
          $('#servicesCountBadge').text(res.data.count + ' Services Available');
        } else {
          container.html(`
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center page-enter">
              <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="search-x" class="w-8 h-8"></i>
              </div>
              <h3 class="text-lg font-bold text-slate-800 mb-1">No services found</h3>
              <p class="text-slate-500 text-sm max-w-md mx-auto mb-6">We couldn't find any services matching your criteria. Try adjusting your search or category filter.</p>
              <button onclick="resetServiceFilters()" class="px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium transition">
                Reset Filters
              </button>
            </div>
          `);
          $('#servicesCountBadge').text('0 Services Found');
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();

        // Fade content back in
        requestAnimationFrame(() => {
          container.css({ opacity: 1, pointerEvents: '' });
        });
      },
      error: function () {
        container.html(`
          <div class="col-span-full bg-rose-50 text-rose-700 p-6 rounded-2xl text-center">
            <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-3">
              <i data-lucide="wifi-off" class="w-6 h-6"></i>
            </div>
            <p class="font-medium mb-1">Failed to load services</p>
            <p class="text-sm text-rose-600">Please check your connection and try again.</p>
            <button onclick="fetchFilteredServices()" class="mt-4 px-4 py-2 bg-rose-600 text-white rounded-lg text-sm font-medium hover:bg-rose-700 transition">
              Retry
            </button>
          </div>
        `);
        if (typeof lucide !== 'undefined') lucide.createIcons();
        container.css({ opacity: 1, pointerEvents: '' });
      }
    });
  }

  // Make fetchFilteredServices accessible globally for retry buttons
  window.fetchFilteredServices = fetchFilteredServices;

  // Search Input with Debounce + subtle loading indicator
  $('#serviceSearchInput').on('input', function () {
    clearTimeout(searchTimeout);
    const input = this;
    searchTimeout = setTimeout(function() {
      fetchFilteredServices();
    }, 350);
  });

  // Category Button Click with active state transition
  $(document).on('click', '.category-filter-btn', function () {
    if ($(this).hasClass('active')) return; // Don't re-fetch same category
    
    $('.category-filter-btn').removeClass('active bg-emerald-600 text-white').addClass('bg-white text-slate-700 hover:bg-slate-50');
    $(this).addClass('active bg-emerald-600 text-white').removeClass('bg-white text-slate-700 hover:bg-slate-50');
    fetchFilteredServices();
  });

  // Sort dropdown
  $('#serviceSortSelect').on('change', function () {
    fetchFilteredServices();
  });

  window.resetServiceFilters = function() {
    $('#serviceSearchInput').val('');
    $('#serviceSortSelect').val('default');
    $('.category-filter-btn').removeClass('active bg-emerald-600 text-white').addClass('bg-white text-slate-700');
    $('.category-filter-btn[data-category="all"]').addClass('active bg-emerald-600 text-white').removeClass('bg-white text-slate-700');
    fetchFilteredServices();
  };
});
