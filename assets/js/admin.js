/**
 * HomeFix Quetta - Admin Dashboard Controller
 * Uses HF interaction engine for button states, toasts, and confirmations
 */

$(document).ready(function () {
  // Mobile Sidebar Toggle
  $('#adminSidebarToggle').on('click', function () {
    $('#adminSidebar').toggleClass('-translate-x-full');
  });

  // Assign Technician to Booking
  $(document).on('click', '.assign-tech-btn', function () {
    const bookingId = $(this).data('id');
    const currentTechId = $(this).data('tech-id') || '';
    const bookingRef = $(this).data('ref');

    $('#assignModalBookingId').val(bookingId);
    $('#assignModalBookingRef').text(bookingRef);
    $('#assignTechSelect').val(currentTechId);
    $('#assignTechModal').removeClass('hidden').addClass('flex');
  });

  $('#closeAssignModal').on('click', function () {
    $('#assignTechModal').addClass('hidden').removeClass('flex');
  });

  // Click outside modal to close
  $('#assignTechModal').on('click', function(e) {
    if (e.target === this) {
      $(this).addClass('hidden').removeClass('flex');
    }
  });

  // Submit Technician Assignment
  $('#assignTechForm').on('submit', function (e) {
    e.preventDefault();
    const bookingId = $('#assignModalBookingId').val();
    const techId = $('#assignTechSelect').val();
    const submitBtn = this.querySelector('button[type="submit"]');

    if (!techId) {
      if (typeof HF !== 'undefined') {
        HF.toast('warning', 'Please select a technician to assign.');
      }
      return;
    }

    if (typeof HF !== 'undefined' && submitBtn) {
      HF.btnLoading(submitBtn, 'Assigning...');
    }

    $.ajax({
      url: '../ajax/admin.php',
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'assign_technician',
        booking_id: bookingId,
        technician_id: techId
      },
      success: function (res) {
        if (res.success) {
          if (typeof HF !== 'undefined') {
            HF.btnSuccess(submitBtn, 'Assigned!');
            HF.toast('success', res.message);
          }
          setTimeout(() => location.reload(), 1000);
        } else {
          if (typeof HF !== 'undefined') {
            HF.btnReset(submitBtn);
            HF.toast('error', res.message);
          }
        }
      },
      error: function() {
        if (typeof HF !== 'undefined') {
          HF.btnReset(submitBtn);
          HF.toast('error', 'Connection error. Please try again.');
        }
      }
    });
  });

  // Update Booking Status
  $(document).on('change', '.booking-status-select', function () {
    const select = $(this);
    const bookingId = select.data('id');
    const newStatus = select.val();
    const originalStatus = select.data('current');

    Swal.fire({
      title: 'Update Booking Status?',
      text: `Change status to "${newStatus.toUpperCase()}"? The customer can view this update immediately.`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, Update',
      cancelButtonText: 'Cancel',
      customClass: {
        popup: 'homefix-swal',
        confirmButton: 'homefix-confirm-btn',
        cancelButton: 'homefix-cancel-btn'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        // Show loading state on the select area
        select.prop('disabled', true).css('opacity', '0.6');

        $.ajax({
          url: '../ajax/admin.php',
          type: 'POST',
          dataType: 'json',
          data: {
            action: 'update_booking_status',
            booking_id: bookingId,
            status: newStatus
          },
          success: function (res) {
            if (res.success) {
              select.data('current', newStatus);
              select.prop('disabled', false).css('opacity', '');
              if (typeof HF !== 'undefined') {
                HF.toast('success', 'Status updated to ' + newStatus.toUpperCase());
              }
            } else {
              select.val(originalStatus).prop('disabled', false).css('opacity', '');
              if (typeof HF !== 'undefined') {
                HF.toast('error', res.message || 'Failed to update status.');
              }
            }
          },
          error: function () {
            select.val(originalStatus).prop('disabled', false).css('opacity', '');
            if (typeof HF !== 'undefined') {
              HF.toast('error', 'Network error. Please try again.');
            }
          }
        });
      } else {
        select.val(originalStatus);
      }
    });
  });

  // Generic Delete Item Confirmation with processing state
  $(document).on('click', '.delete-item-btn', function () {
    const action = $(this).data('action');
    const id = $(this).data('id');
    const title = $(this).data('title') || 'this item';
    const btn = this;

    Swal.fire({
      title: 'Delete ' + title + '?',
      text: 'This action cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, Delete',
      confirmButtonColor: '#EF4444',
      cancelButtonText: 'Keep It',
      customClass: {
        popup: 'homefix-swal',
        cancelButton: 'homefix-cancel-btn'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        // Show processing state on the delete button
        const origHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';

        $.ajax({
          url: '../ajax/admin.php',
          type: 'POST',
          dataType: 'json',
          data: { action: action, id: id },
          success: function (res) {
            if (res.success) {
              // Animate the row removal
              const row = $(btn).closest('tr, .admin-card, .grid-item');
              if (row.length) {
                row.css({ transition: 'all 0.3s ease', opacity: 0, transform: 'translateX(-10px)' });
                setTimeout(() => {
                  row.css({ maxHeight: row.outerHeight(), overflow: 'hidden' });
                  row.animate({ maxHeight: 0, paddingTop: 0, paddingBottom: 0, marginTop: 0, marginBottom: 0 }, 200, function() {
                    row.remove();
                  });
                }, 300);
              }
              if (typeof HF !== 'undefined') {
                HF.toast('success', res.message || 'Item deleted successfully.');
              }
            } else {
              btn.disabled = false;
              btn.innerHTML = origHtml;
              if (typeof HF !== 'undefined') {
                HF.toast('error', res.message || 'Failed to delete.');
              }
            }
          },
          error: function() {
            btn.disabled = false;
            btn.innerHTML = origHtml;
            if (typeof HF !== 'undefined') {
              HF.toast('error', 'Connection error. Please try again.');
            }
          }
        });
      }
    });
  });

  // Review Status Toggle (Approve / Hide) with inline feedback
  $(document).on('click', '.review-status-btn', function () {
    const reviewId = $(this).data('id');
    const newStatus = $(this).data('status');
    const btn = this;
    const origHtml = btn.innerHTML;

    btn.disabled = true;
    btn.style.opacity = '0.6';

    $.ajax({
      url: '../ajax/admin.php',
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'moderate_review',
        review_id: reviewId,
        status: newStatus
      },
      success: function (res) {
        if (res.success) {
          if (typeof HF !== 'undefined') {
            HF.toast('success', 'Review ' + (newStatus === 'approved' ? 'approved' : 'hidden') + ' successfully.');
          }
          setTimeout(() => location.reload(), 800);
        } else {
          btn.disabled = false;
          btn.style.opacity = '';
          if (typeof HF !== 'undefined') {
            HF.toast('error', res.message || 'Failed to update review.');
          }
        }
      },
      error: function() {
        btn.disabled = false;
        btn.style.opacity = '';
        if (typeof HF !== 'undefined') {
          HF.toast('error', 'Connection error.');
        }
      }
    });
  });
});
