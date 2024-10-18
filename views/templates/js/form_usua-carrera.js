function getParameterByName(name) {
    const url = new URL(window.location.href);
    return url.searchParams.get(name);
}

// Verificar si hay un parámetro 'success' o 'error' en la URL
document.addEventListener('DOMContentLoaded', function() {
    const success = getParameterByName('success');
    const error = getParameterByName('error');

    if (success) {
        Swal.fire({
            title: 'Registrado',
            text: 'El usuario ha sido registrado exitosamente.',
            icon: 'success'
        });
    }

    if (error) {
        let errorMessage = 'Hubo un problema al registrar los datos.';
        if (error === 'duplicate') {
            errorMessage = 'El usuario ya está registrado en esta carrera y período.';
        } else if (error === 'insert') {
            errorMessage = 'Error al registrar los datos. Intenta nuevamente.';
        } else if (error === 'database') {
            errorMessage = 'Error en la base de datos. Intenta más tarde.';
        }

        Swal.fire({
            title: 'Error',
            text: errorMessage,
            icon: 'error'
        });
    }
});