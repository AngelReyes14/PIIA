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
    document.getElementById('formRegistroMateria').reset();
};

$(document).ready(function() {
    // Reemplaza caracteres no permitidos en el campo de texto
    $('#nombre_materia').on('input', function() {
        this.value = this.value.replace(/[^A-Za-z\s]/g, '');
    });

    // Función para validar y manejar el formulario
    $('#formRegistroMateria').on('submit', function(event) {
        event.preventDefault(); // Prevenir el envío del formulario

        // Validaciones
        const nombreMateria = $('#nombre_materia').val();
        const creditoMateria = parseInt($('#credito_materia').val(), 10);
        const horaTeorica = parseInt($('#hora_teorica').val(), 10);
        const horaPractica = parseInt($('#hora_practica').val(), 10);
        const grupo = $('#grupo').val();

        // Validación de campos vacíos
        if (!nombreMateria || !creditoMateria || !horaTeorica || !horaPractica || !grupo) {
            Swal.fire({
                title: 'Error!',
                text: 'Todos los campos son obligatorios.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        // Validar que los créditos, horas teóricas y horas prácticas sean enteros mayores que 0
        if (creditoMateria <= 0 || horaTeorica <= 0 || horaPractica <= 0) {
            Swal.fire({
                title: 'Error!',
                text: 'Los créditos y horas deben ser números mayores a 0.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        // Validar que la suma de horas teóricas y prácticas sea igual a los créditos
        if (horaTeorica + horaPractica !== creditoMateria) {
            Swal.fire({
                title: 'Error!',
                text: 'La suma de horas teóricas y prácticas debe ser igual a los créditos.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        // Si todas las validaciones son correctas, envía el formulario
        $('#formRegistroMateria')[0].submit(); // Envía el formulario

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

$('#formAsignarMateria').on('submit', function(event) {
    event.preventDefault(); // Prevenir el envío del formulario

    // Validaciones y lógica específica para este formulario
    const materia = $('#materia').val();
    const grupo = $('#grupo').val();
    const periodo = $('#periodo').val();

    if (!materia || !grupo || !periodo) {
        Swal.fire({
            title: 'Error!',
            text: 'Todos los campos son obligatorios.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return;
    }

    // Si todo es correcto, enviar el formulario
    $('#formAsignarMateria')[0].submit(); 
});
