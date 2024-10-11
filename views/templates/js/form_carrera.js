// Función para mostrar la vista previa de la imagen
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
  
  // Función para validar el formulario (opcional)
  function validarFormulario() {
    // Aquí puedes agregar validaciones personalizadas si es necesario
    return true; // Permite el envío del formulario
  }
  
  // Limpiar campos al cargar la página
  window.onload = function() {
    document.getElementById('formCarrera').reset();
  };
  
  $(document).ready(function() {
    // Reemplaza caracteres no permitidos en el campo de texto
    $('#nombre_carrera').on('input', function() {
        this.value = this.value.replace(/[^A-Za-z\s]/g, '');
    });

    // Función para validar y manejar el formulario
    $('#formCarrera').on('submit', function(event) {
        event.preventDefault(); // Prevenir el envío del formulario

        // Aquí solo se envía el formulario sin validaciones previas
        $('#formCarrera')[0].submit(); // Envía el formulario

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
                case 'upload':
                    errorMessage = 'Error al subir la imagen.';
                    break;
                case 'duplicate':
                    errorMessage = 'Este registro ya existe.';
                    break;
                default:
                    errorMessage = 'Ocurrió un error inesperado. Intenta nuevamente.';
                    break;
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

