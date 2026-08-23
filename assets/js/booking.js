/**
 * HomeFix Quetta - Booking Workflow & AJAX Handler
 * Uses HF interaction engine for button states, validation, and toasts
 */

$(document).ready(function () {
  // Service Selector sync if preselected via URL
  const urlParams = new URLSearchParams(window.location.search);
  const preselectedService = urlParams.get('service');
  const preselectedArea = urlParams.get('area');

  if (preselectedService && $('#serviceSelect').length) {
    $('#serviceSelect').val(preselectedService).trigger('change');
  }

  if (preselectedArea && $('#areaSelect').length) {
    $('#areaSelect').val(preselectedArea);
  }

  // Update Estimated Price & Details when Service changes
  $('#serviceSelect').on('change', function () {
    const selected = $(this).find(':selected');
    const price = selected.data('price') || 0;
    const duration = selected.data('duration') || '1 - 2 Hours';
    const category = selected.data('category') || 'General';

    $('#serviceEstimatePrice').text('Rs. ' + Number(price).toLocaleString());
    $('#serviceEstimateDuration').text(duration);
    $('#serviceEstimateCategory').text(category);
    $('#summaryServiceTitle').text(selected.text().split('(')[0].trim() || 'Select a Service');
    $('#summaryTotalPrice').text('Rs. ' + Number(price).toLocaleString());
  });

  // Photo Upload Live Preview with animation
  $('#problemImageInput').on('change', function (e) {
    const file = e.target.files[0];
    if (file) {
      if (file.size > 5 * 1024 * 1024) {
        if (typeof HF !== 'undefined') {
          HF.toast('warning', 'Please upload an image smaller than 5MB.');
        }
        $(this).val('');
        $('#imagePreviewBox').addClass('hidden');
        return;
      }

      const reader = new FileReader();
      reader.onload = function (event) {
        const box = $('#imagePreviewBox');
        const img = $('#imagePreviewImg');
        img.attr('src', event.target.result);
        box.removeClass('hidden');
        // Animate preview in
        box.css({ opacity: 0, transform: 'scale(0.95)' });
        setTimeout(() => {
          box.css({ opacity: 1, transform: 'scale(1)', transition: 'all 0.3s ease' });
        }, 10);
      };
      reader.readAsDataURL(file);
    } else {
      $('#imagePreviewBox').addClass('hidden');
    }
  });

  // Remove previewed image with animation
  $('#removeImageBtn').on('click', function () {
    const box = $('#imagePreviewBox');
    box.css({ opacity: 0, transform: 'scale(0.95)', transition: 'all 0.2s ease' });
    setTimeout(() => {
      $('#problemImageInput').val('');
      box.addClass('hidden').css({ opacity: '', transform: '', transition: '' });
    }, 200);
  });

  // Inline validation on blur for required fields
  const requiredFields = [
    { id: '#customerName', msg: 'Full name is required' },
    { id: '#customerPhone', msg: 'Phone number is required' },
    { id: '#customerAddress', msg: 'Address is required' },
    { id: '#serviceSelect', msg: 'Please select a service' },
    { id: '#areaSelect', msg: 'Please select your area' },
    { id: '#preferredDate', msg: 'Please choose a date' },
    { id: '#preferredTime', msg: 'Please choose a time slot' },
    { id: '#problemDescription', msg: 'Please describe the issue' }
  ];

  requiredFields.forEach(function(field) {
    $(field.id).on('blur change', function() {
      if (typeof HF === 'undefined') return;
      const val = $(this).val();
      if (!val || (typeof val === 'string' && !val.trim())) {
        HF.showFieldError(this, field.msg);
      } else {
        HF.clearFieldError(this);
      }
    });
  });

  // Phone validation
  $('#customerPhone').on('blur', function() {
    if (typeof HF === 'undefined') return;
    const val = $(this).val().trim();
    if (val && !HF.validatePhone(val)) {
      HF.showFieldError(this, 'Enter a valid Pakistani phone number');
    }
  });

  // Booking Form Submission via AJAX
  $('#bookingForm').on('submit', function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const submitBtn = document.getElementById('submitBookingBtn');

    // Frontend validation
    const name = $('#customerName').val().trim();
    const phone = $('#customerPhone').val().trim();
    const area = $('#areaSelect').val();
    const address = $('#customerAddress').val().trim();
    const serviceId = $('#serviceSelect').val();
    const date = $('#preferredDate').val();
    const time = $('#preferredTime').val();
    const problem = $('#problemDescription').val().trim();

    // Inline field validation
    let hasError = false;
    if (typeof HF !== 'undefined') {
      HF.clearAllFieldErrors(form);
      if (!name) { HF.showFieldError('#customerName', 'Full name is required'); hasError = true; }
      if (!phone) { HF.showFieldError('#customerPhone', 'Phone number is required'); hasError = true; }
      else if (!HF.validatePhone(phone)) { HF.showFieldError('#customerPhone', 'Enter a valid phone number'); hasError = true; }
      if (!area) { HF.showFieldError('#areaSelect', 'Please select your area'); hasError = true; }
      if (!address) { HF.showFieldError('#customerAddress', 'Address is required'); hasError = true; }
      if (!serviceId) { HF.showFieldError('#serviceSelect', 'Please select a service'); hasError = true; }
      if (!date) { HF.showFieldError('#preferredDate', 'Please choose a date'); hasError = true; }
      if (!time) { HF.showFieldError('#preferredTime', 'Please choose a time slot'); hasError = true; }
      if (!problem) { HF.showFieldError('#problemDescription', 'Please describe the issue'); hasError = true; }
    } else {
      if (!name || !phone || !area || !address || !serviceId || !date || !time || !problem) {
        hasError = true;
      }
    }

    if (hasError) {
      if (typeof HF !== 'undefined') {
        HF.toast('warning', 'Please fill in all required fields.');
      }
      // Scroll to first error
      const firstError = $(form).find('.is-invalid').first();
      if (firstError.length) {
        $('html, body').animate({ scrollTop: firstError.offset().top - 120 }, 300);
      }
      return;
    }

    // Set loading state
    if (typeof HF !== 'undefined') {
      HF.btnLoading(submitBtn, 'Confirming Booking...');
    } else {
      $(submitBtn).prop('disabled', true).text('Confirming...');
    }

    $.ajax({
      url: 'ajax/bookings.php',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (res) {
        if (res.success) {
          const bookingRef = res.data.booking_reference;

          // Show success state on button
          if (typeof HF !== 'undefined') {
            HF.btnSuccess(submitBtn, 'Booking Confirmed!');
          }

          Swal.fire({
            icon: 'success',
            title: 'Booking Confirmed!',
            html: `
              <div class="text-left space-y-3 pt-2 text-sm text-slate-600">
                <p>Your service request has been received and assigned to our Quetta team.</p>
                <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                  <div class="text-xs text-emerald-700 uppercase font-semibold">Booking Reference</div>
                  <div class="text-xl font-bold font-mono text-emerald-900 tracking-wider">${bookingRef}</div>
                </div>
                <p class="text-xs text-slate-500">We have sent confirmation details to your phone and email. A verified technician will arrive during your chosen time slot.</p>
              </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Track Booking Status →',
            cancelButtonText: 'View Dashboard',
            customClass: {
              popup: 'homefix-swal',
              confirmButton: 'homefix-confirm-btn',
              cancelButton: 'homefix-cancel-btn'
            }
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = 'tracking.php?ref=' + encodeURIComponent(bookingRef);
            } else {
              window.location.href = 'dashboard.php';
            }
          });

          form.reset();
          $('#imagePreviewBox').addClass('hidden');
        } else {
          if (typeof HF !== 'undefined') {
            HF.btnReset(submitBtn);
            HF.toast('error', res.message || 'Booking failed. Please try again.');
          }
        }
      },
      error: function (xhr) {
        let errorMsg = 'Server connection error. Please check your connection and try again.';
        try {
          const json = JSON.parse(xhr.responseText);
          if (json.message) errorMsg = json.message;
        } catch (e) {}

        if (typeof HF !== 'undefined') {
          HF.btnReset(submitBtn);
          HF.toast('error', errorMsg);
        }
      }
    });
  });
});
