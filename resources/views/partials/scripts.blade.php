{{-- ========================================
    JAVASCRIPT GLOBAL
    - Vendor JS
    - Plugin JS
    - Layout JS
    Berlaku untuk SEMUA halaman
======================================== --}}

<!-- plugins:js -->
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
<!-- endinject -->

<!-- Global: Submit with spinner -->
<script>
function submitWithSpinner(formId, btn) {
    var form = document.getElementById(formId);
    if (!form) return;
    // Check validity menggunakan HTML5
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    // Ubah button menjadi spinner, disable untuk hindari double submit
    var originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memproses...';
    // Submit form
    form.submit();
}
</script>
