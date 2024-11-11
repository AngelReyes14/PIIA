// Obtener el idusuario actual desde la URL
const urlParams = new URLSearchParams(window.location.search);
let idusuario = parseInt(urlParams.get("idusuario")) || 1; // Si no hay idusuario en la URL, empezamos en 1

// Seleccionar los botones de navegación
const anterior = document.getElementById("anterior");
const siguiente = document.getElementById("siguiente");

// Función para actualizar la URL con el nuevo idusuario
function updateUrl(newIdusuario) {
  window.location.href =
    window.location.pathname + "?idusuario=" + newIdusuario;
}

// Evento para el botón "Anterior"
anterior.addEventListener("click", () => {
  if (idusuario > 1) {
    idusuario--; // Restar 1 al idusuario
    updateUrl(idusuario); // Actualizar la URL con el nuevo idusuario
  }
});

// Evento para el botón "Siguiente"
siguiente.addEventListener("click", () => {
  idusuario++; // Sumar 1 al idusuario

  // Aquí podemos hacer una verificación para asegurarnos de que el idusuario existe.
  // Puedes hacer una solicitud AJAX opcional para verificar si el usuario existe antes de cambiar la URL.
  fetch(`../../controllers/verificar_usuario.php?idusuario=${idusuario}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.existe) {
        updateUrl(idusuario); // Si el usuario existe, actualizar la URL
      } else {
        updateUrl(1); // Si no existe, regresar al primer usuario
      }
    });
});