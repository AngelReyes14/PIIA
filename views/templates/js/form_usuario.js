

function previewImage() {
    const fileInput = document.getElementById('fileInput');
    const imagePreview = document.getElementById('imagePreview');

    const file = fileInput.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}


$(document).ready(function () {
    // Inicializa el wizard
    $('#smartwizard').smartWizard({
        selected: 0, // Inicia en el primer paso (índice 0)
        theme: 'arrows',
        autoAdjustHeight: true,
        transitionEffect: 'fade',
        showStepURLhash: false,
        toolbarSettings: {
            toolbarPosition: 'bottom',
            showNextButton: true,
            showPreviousButton: true
        },
    });

    // Validación al cambiar de paso
    $('#smartwizard').on("leaveStep", function (e, anchorObject, stepNumber, stepDirection) {
        if (stepDirection === 'forward' && !validateStep($("#step-" + (stepNumber + 1)))) {
            return false; // Cancela el avance si hay errores
        }
        return true; // Si no hay errores, continúa
    });


    // Función de validación para cada paso
    function validateStep(step) {
        let isValid = true;
        $(step).find('input, select').each(function () {
            let value = $(this).val().trim();
            $(this).val(value); // Actualiza el valor sin espacios
    
            // Verifica la validez del campo
            if (!this.checkValidity() || value === "") {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
    
            // Validación específica para el campo de contraseña
            if ($(this).attr('type') === 'password') {
                if (value.length < 8) {
                    $(this).addClass('is-invalid');
                    $(this).get(0).setCustomValidity('La contraseña debe tener al menos 8 caracteres.');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).get(0).setCustomValidity(''); // Resetea el mensaje de error
                }
            }
    
            // Validación de la edad
            if ($(this).attr('id') === 'edad') {
                const ageNum = parseInt(value, 10);
                if (isNaN(ageNum) || ageNum < 18 || ageNum > 90) {
                    $(this).addClass('is-invalid');
                    $(this).get(0).setCustomValidity('La edad debe estar entre 18 y 90 años.');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).get(0).setCustomValidity(''); // Resetea el mensaje de error
                }
            }
    
            // Validación del número de empleado
            if ($(this).attr('id') === 'numero_empleado') {
                if (value.length !== 4) {
                    $(this).addClass('is-invalid');
                    $(this).get(0).setCustomValidity('El número de empleado debe tener exactamente 4 caracteres.');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).get(0).setCustomValidity(''); // Resetea el mensaje de error
                }
            }
        });
        return isValid;
    }


    // Validación de contraseñas
    document.getElementById('password').addEventListener('input', function () {
        const password = this.value.trim();
        if (password.length < 8) {
            this.setCustomValidity('La contraseña debe tener al menos 8 caracteres.');
            $(this).addClass('is-invalid');
        } else {
            this.setCustomValidity('');
            $(this).removeClass('is-invalid');
        }
    });

    document.getElementById('confirm_password').addEventListener('input', function () {
        const password = document.getElementById('password').value.trim();
        const confirmPassword = this.value.trim();
        if (password !== confirmPassword) {
            this.setCustomValidity('Las contraseñas deben coincidir.');
            $(this).addClass('is-invalid');
        } else {
            this.setCustomValidity('');
            $(this).removeClass('is-invalid');
        }
    });
    

    // Filtra caracteres no permitidos
    $('#usuario_nombre, #usuario_apellido_p, #usuario_apellido_m, #grado_academico').on('input', function () {
        this.value = this.value.replace(/[^A-Za-z\s]/g, ''); // Solo permite letras y espacios
    });

    // Validación de la edad
    function isValidAge(age) {
        const ageNum = parseInt(age, 10);
        return !isNaN(ageNum) && ageNum >= 18 && ageNum <= 90; // Cambié 89 por 90
    }

    // Función para enviar el formulario
    $('#formUsuario').on('submit', function (event) {
        event.preventDefault(); // Previene el envío del formulario

        const nombre = $('#usuario_nombre').val();
        const apellidoP = $('#usuario_apellido_p').val();
        const apellidoM = $('#usuario_apellido_m').val();
        const edad = $('#edad').val();
        const gradoAcademico = $('#grado_academico').val();
        const cedula = $('#cedula').val();
        const password = $('#password').val();

        
        // Validar campos vacíos o solo espacios
        if ([nombre, apellidoP, apellidoM, gradoAcademico, cedula].some(containsOnlySpaces)) {
            showAlert('Error', 'Los campos no pueden estar vacíos o contener solo espacios.', 'error');
            return; // No envía el formulario si hay campos inválidos
        }

        // Validar la edad
        if (!isValidAge(edad)) {
            showAlert('Error', 'La edad debe ser un número entre 18 y 90 años.', 'error'); // Cambié 89 por 90
            return; // No envía el formulario si la edad es inválida
        }

        // Validar la contraseña
        if (!isValidPassword(password)) {
            showAlert('Error', 'La contraseña debe tener al menos 8 caracteres.', 'error');
            return; // No envía el formulario si la contraseña es inválida
        }

        this.submit(); // Envía el formulario
        checkForMessages(); // Comprueba mensajes después de enviar
    });

    // Verifica y muestra mensajes de éxito o error
function checkForMessages() {
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    const error = urlParams.get('error');

    if (success === 'true') {
        showAlert('¡Éxito!', 'Usuario registrado con éxito.', 'success');
    } else if (error) {
        let errorMessage = {
            'upload': 'Error al subir la imagen.',
            'duplicate_email': 'El correo ya está registrado.',
            'duplicate_employee': 'El número de empleado ya está registrado.',
            'duplicate': 'Este usuario ya está registrado.'
        }[error] || 'Ocurrió un error inesperado. Intenta nuevamente.';

        showAlert('Error', errorMessage, 'error');
    }
}

// Muestra alertas usando SweetAlert
function showAlert(title, text, icon) {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        confirmButtonText: 'Aceptar'
    });
}

// Verifica los mensajes al cargar la página
checkForMessages();
});

// Función para verificar si el campo contiene solo espacios
function containsOnlySpaces(value) {
    return value.trim() === '';
}

// Función para validar la contraseña
function isValidPassword(password) {
    return password.length >= 8; // Verifica que la contraseña tenga al menos 8 caracteres
}