<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
<script>
    // Any form with data-confirm="..." asks before submitting (delete, logout)
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: form.dataset.confirm,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: form.dataset.confirmButton || 'Yes, delete it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                focusCancel: true,
            }).then(function (result) {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // Success message after save / delete / logout
    @if (session('status'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: @json(session('status')),
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    @endif

    // Validation errors (fields are also marked red in the form)
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Please check the form',
            text: @json($errors->first()),
            confirmButtonColor: '#4338ca',
        });
    @endif
</script>
