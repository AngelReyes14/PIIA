$(document).ready(function() {
    $('#smartwizard').smartWizard({
        selected: 0, // Inicia en el primer paso (índice 0)
        theme: 'arrows',
        autoAdjustHeight: true,
        transitionEffect: 'fade',
        showStepURLhash: false,
        toolbarSettings: {
            toolbarPosition: 'bottom',
            showNextButton: true, // Muestra el botón "Siguiente"
            showPreviousButton: true // Muestra el botón "Anterior"
        },
    });
    $('#smartwizard').on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
    // Si estás yendo hacia adelante
    if (stepDirection === 'forward') {
        // Valida el formulario del paso actual
        var currentStep = $("#step-" + (stepNumber + 1)); // stepNumber es 0-indexed
        if (!validateStep(currentStep)) {
            return false; // Cancela el avance al siguiente paso si hay errores
        }
    }
    return true; // Si no hay errores, continúa
});

function validateStep(step) {
    var isValid = true;
    $(step).find('input, select').each(function() {
        if (!this.checkValidity()) {
            $(this).addClass('is-invalid');
            isValid = false;
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    return isValid;
}
});


document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if (password !== confirmPassword) {
        document.getElementById('confirm_password').setCustomValidity('Las contraseñas no coinciden.');
    } else {
        document.getElementById('confirm_password').setCustomValidity('');
    }
});
// Función para previsualizar la imagen seleccionada
    function previewImage() {
        const fileInput = document.getElementById('fileInput');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        
        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }

$(document).ready(function() {
    // Reemplaza caracteres no permitidos en los campos de texto específicos
    $('#usuario_nombre, #usuario_apellido_p, #usuario_apellido_m, #grado_academico').on('input', function() {
        this.value = this.value.replace(/[^A-Za-z\s]/g, ''); // Solo permite letras y espacios
    });



    // Función para verificar si el campo contiene solo espacios
    function containsOnlySpaces(value) {
        return value.trim() === '';
    }

    // Función para validar la edad
    function isValidAge(age) {
        const ageNum = parseInt(age, 10); // Convierte la edad a un número entero
        return !isNaN(ageNum) && ageNum >= 18 && ageNum <= 89; // Verifica que sea un número válido en el rango de 18 a 89
    }

    // Función para validar la contraseña
    function isValidPassword(password) {
        return password.length >= 8; // Verifica que la contraseña tenga al menos 8 caracteres
    }

    // Función para enviar el formulario
    $('#formUsuario').on('submit', function(event) {
        event.preventDefault(); // Prevenir el envío del formulario para manejarlo manualmente

        // Validar que los campos no estén vacíos ni contengan solo espacios
        const nombre = $('#usuario_nombre').val();
        const apellidoP = $('#usuario_apellido_p').val();
        const apellidoM = $('#usuario_apellido_m').val();
        const edad = $('#edad').val();
        const gradoAcademico = $('#grado_academico').val();
        const cedula = $('#cedula').val();
        const password = $('#password').val(); // Asegúrate de tener un campo de contraseña en tu formulario

        if (containsOnlySpaces(nombre) || containsOnlySpaces(apellidoP) || containsOnlySpaces(apellidoM) || containsOnlySpaces(gradoAcademico) || containsOnlySpaces(cedula)) {
            Swal.fire({
                title: 'Error',
                text: 'Los campos no pueden estar vacíos o contener solo espacios.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return; // No envía el formulario si hay campos inválidos
        }

        // Validar la edad
        if (!isValidAge(edad)) {
            Swal.fire({
                title: 'Error',
                text: 'La edad debe ser un número entre 18 y 89 años.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return; // No envía el formulario si la edad es inválida
        }

        // Validar la contraseña
        if (!isValidPassword(password)) {
            Swal.fire({
                title: 'Error',
                text: 'La contraseña debe tener al menos 8 caracteres.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return; // No envía el formulario si la contraseña es inválida
        }

        // Aquí enviamos el formulario directamente
        this.submit();

        // Después de enviar, comprobamos los mensajes de éxito o error en la URL
        checkForMessages();
    });

    // Función para mostrar mensajes de éxito o error
    function checkForMessages() {
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');
        const error = urlParams.get('error');

        if (success === 'true') {
            Swal.fire({
                title: '¡Éxito!',
                text: 'Usuario registrado con éxito.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        } else if (error) {
            let errorMessage = '';

            switch (error) {
                case 'upload':
                    errorMessage = 'Error al subir la imagen.';
                    break;
                case 'duplicate':
                    errorMessage = 'Este usuario ya está registrado.';
                    break;
                default:
                    errorMessage = 'Ocurrió un error inesperado. Intenta nuevamente.';
            }

            Swal.fire({
                title: 'Error',
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    }

    // Llamada a la función para verificar los mensajes en la URL al cargar la página
    checkForMessages();
});
