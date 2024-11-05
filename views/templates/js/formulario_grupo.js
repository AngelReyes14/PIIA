$(document).ready(function() {
  // Función para mostrar la vista previa de la imagen
  function previewImage() {
      const file = document.getElementById('fileInput').files[0];
      const reader = new FileReader();
      reader.onload = function(e) {
          const img = document.getElementById('imagePreview');
          img.src = e.target.result;
          img.style.display = 'block';
      }
      if (file) {
          reader.readAsDataURL(file);
      }
  }

  // Llamar a la función de vista previa al seleccionar un archivo
  $('#fileInput').on('change', previewImage);

  // Reemplaza caracteres no permitidos en el campo de texto del grupo
  $('#grupo').on('input', function() {
      this.value = this.value.replace(/[^A-Za-z0-9\s]/g, ''); // Aceptar letras y números
  });

  // Función para validar y manejar el formulario
  $('#submitForm').on('click', function(event) {
      event.preventDefault(); // Evitar el envío del formulario hasta que se confirme

      const fields = ['grupo', 'semestre', 'turno','periodo'];
      let allValid = true;

      // Validar campos de texto
      fields.forEach(id => {
          const field = $('#' + id);
          if (field.val().trim() === '') {
              field.addClass('is-invalid');
              allValid = false;
          } else {
              field.removeClass('is-invalid');
          }
      });

      // Si todos los campos son válidos, mostrar SweetAlert
      if (allValid) {
          Swal.fire({
              title: 'Confirmación',
              text: "¿Estás seguro de que deseas enviar el formulario?",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Sí, enviar',
              cancelButtonText: 'Cancelar'
          }).then((result) => {
              if (result.isConfirmed) {
                  $('#formRegistroGrupo').submit(); // Enviar el formulario
                  // Mostrar alerta de éxito después de enviar el formulario
                  Swal.fire({
                      title: 'Grupo Registrado',
                      text: 'El grupo se ha registrado correctamente!',
                      icon: 'success',
                      confirmButtonText: 'Aceptar'
                  });
              }
          });
      }
  });

  // Cerrar la validación de campos al cambiar el valor
  fields.forEach(id => {
      $('#' + id).on('input', function() {
          $(this).removeClass('is-invalid');
      });
  });

  // Limpiar campos al cargar la página
  window.onload = function() {
      document.getElementById('formRegistroGrupo').reset();
  };

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
