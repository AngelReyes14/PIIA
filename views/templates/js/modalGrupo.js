function previewImage() {
    const file = document.getElementById('fileInput').files[0];
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('imagePreview');
        img.src = e.target.result;
        img.style.display = 'block';
    }
    reader.readAsDataURL(file);
}

// Limpiar campos al cargar la página
window.onload = function() {
    document.getElementById('formRegistroGrupo').reset();
};

$(document).ready(function() {
    // Reemplaza caracteres no permitidos en el campo de texto
    $('#nombre_semestre').on('input', function() {
        this.value = this.value.replace(/[^A-Za-z\s]/g, '');
    });

    // Función para validar y manejar el formulario
    $('#formRegistroGrupo').on('submit', function(event) {
        event.preventDefault(); // Prevenir el envío del formulario

        // Validaciones
        const nombreGrupo = $('#grupo').val();
        const semestre = $('#semestre').val();
        const turno = $('#turno').val();
        const periodo = $('#periodo').val();

        // Validación de campos vacíos
    if (!nombreGrupo || !semestre || !turno || !periodo ) {
            Swal.fire({
                title: 'Error!',
                text: 'Todos los campos son obligatorios.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        // Si todas las validaciones son correctas, envía el formulario
        $('#formRegistroGrupo')[0].submit(); // Envía el formulario

        // Después de enviar el formulario, esperamos a que se recargue la página y mostramos las alertas
        checkForMessages();
    });

    // Función para comprobar mensajes de la URL
    function checkForMessages() {
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');
        const error = urlParams.get('error');

        if (success === 'true') {
            Swal.fire({
                title: 'Éxito!',
                text: 'Formulario enviado con éxito!',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        } else if (error) {
            let errorMessage = '';

            switch (error) {
                case 'duplicate':
                    errorMessage = 'Este registro ya existe.';
                    break;
                default:
                    errorMessage = 'Error desconocido.';
            }

            Swal.fire({
                title: 'Error!',
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    }

    // Llamamos a la función al cargar la página para verificar si hay mensajes en la URL
    checkForMessages();
});
