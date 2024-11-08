window.onload = function() {
    // Obtener el mensaje de error desde el atributo de datos
    var errorMessage = document.body.getAttribute('data-error');

    if (errorMessage) {
        Swal.fire({
            title: 'Error',
            text: errorMessage,
            icon: 'error',
            confirmButtonText: 'Aceptar',
            customClass: {
                confirmButton: 'custom-button' // Aplica la clase personalizada al botón de confirmación
            }
        });
    }
}
