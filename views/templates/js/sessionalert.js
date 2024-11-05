document.addEventListener('DOMContentLoaded', function() {
    const sessionExpired = <?php echo json_encode(isset($_SESSION['session_expired'])); ?>;
    if (sessionExpired) {
        Swal.fire({
            icon: 'warning',
            title: 'Sesión expirada',
            text: 'Tu sesión ha expirado. Te redirigiremos al login.',
            confirmButtonText: 'Aceptar',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?php echo $redirectPath; ?>';
            }
        });
    }
});
