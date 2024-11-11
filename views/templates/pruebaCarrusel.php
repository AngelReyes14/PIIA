<?php
include('../../models/session.php');
include('../../controllers/db.php'); // Asegúrate de que este archivo incluya la conexión a la base de datos.
include('../../models/consultas.php'); // Incluir la clase de consultas

// Crear una instancia de la clase Consultas
$consultas = new Consultas($conn);

// Obtenemos el idusuario actual (si no está definido, iniciamos en 1)
$idusuario = isset($_GET['idusuario']) ? intval($_GET['idusuario']) : 1;

// Llamamos al método para obtener el usuario actual
$usuario = $consultas->obtenerUsuarioPorId($idusuario);

// Llamamos al método para obtener la carrera del usuario
$carrera = $consultas->obtenerCarreraPorUsuarioId($idusuario);
$carreras = $consultas->obtenerCarreras();

// Si no se encuentra el usuario, redirigimos al primer usuario (idusuario = 1)
if (!$usuario) {
    header("Location: ?idusuario=1");
    exit;
}

// Fusionar los arrays de $usuario y $carrera (si $carrera devuelve un array asociativo)
if ($carrera) {
    $usuario = array_merge($usuario, $carrera);
}

// Supongamos que la fecha de contratación viene del array $usuario
$fechaContratacion = $usuario["fecha_contratacion"];

// Convertimos la fecha de contratación en un objeto DateTime
$fechaContratacionDate = new DateTime($fechaContratacion);

// Obtenemos la fecha actual
$fechaActual = new DateTime();

// Calculamos la diferencia en años entre la fecha de contratación y la fecha actual
$antiguedad = $fechaContratacionDate->diff($fechaActual)->y; // .y nos da solo los años

// Almacenamos la antigüedad en el array $usuario para que sea fácil de mostrar
$usuario['antiguedad'] = $antiguedad;
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="assets/images/PIIA_oscuro 1.png">
  <title>Desarrollo académico</title>
  <!-- Simple bar CSS -->
  <link rel="stylesheet" href="css/dashboard-prof.css">
  <link rel="stylesheet" href="css/simplebar.css">
  <!-- Fonts CSS -->
  <link
    href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
  <!-- Icons CSS -->
  <link rel="stylesheet" href="css/fullcalendar.css" />
  <link rel="stylesheet" href="css/feather.css">
  <link rel="stylesheet" href="css/select2.css">
  <link rel="stylesheet" href="css/dropzone.css">
  <link rel="stylesheet" href="css/uppy.min.css">
  <link rel="stylesheet" href="css/jquery.steps.css">
  <link rel="stylesheet" href="css/jquery.timepicker.css">
  <link rel="stylesheet" href="css/quill.snow.css">
  <!-- Date Range Picker CSS -->
  <link rel="stylesheet" href="css/daterangepicker.css" />
  <!-- App CSS -->
  <link rel="stylesheet" href="css/app-light.css" id="lightTheme">
  <link rel="stylesheet" href="css/app-dark.css" id="darkTheme" disabled>
  <link src="js/apps.js">
  </link>
  </link>

</head>

<body class="vertical  light  ">


    

    <div class="card text-center">
  <div class="card-body">
    <h5 class="card-title">Filtrado por División</h5>
    <div class="filter-container" style="position: relative; display: inline-block;">
      <button id="filterBtn" class="btn btn-primary">Seleccionar División</button>
      <div id="filterOptions" class="filter-options d-none">
        <?php foreach ($carreras as $carrera): ?>
          <div class="dropdown-item" data-value="<?= htmlspecialchars($carrera['carrera_id']) ?>">
            <?= htmlspecialchars($carrera['nombre_carrera']) ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

</div>

    <div role="main" class="main-content">
      <!---Div de imagen de perfil (falta darle estilos a las letras)----------------------->
      <div class="row justify-content-center mb-0">
        <div class="col-12">
          <div class="row">
            <div class="col-md-12 col-xl-12 mb-0">
              <div class="card box-shadow-div text-red rounded-lg">
                <div class="row align-items-center">
                  <button class="carousel-control-prev col-1 btn btn-primary" type="button" id="anterior">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden"></span>
                  </button>

                  <div class="col-10">
                          <div class="carousel-inner" id="carouselContent">
                            <div class="carousel-item active animate" data-id="<?= htmlspecialchars($idusuario) ?>">
                              <div class="row">
                                <div class="col-12 col-md-5 col-xl-3 text-center">
                                  <strong class="name-line">Foto del Docente:</strong> <br>
                                  <img src="<?= '../' . htmlspecialchars($usuario["imagen_url"]) ?>" alt="Imagen del docente" class="img-fluid tamanoImg" >
                                  </div>
                                <div class="col-12 col-md-7 col-xl-9 data-teacher mb-0">
                                  <p class="teacher-info h4" id="teacherInfo">
                                    <strong class="name-line">Docente:</strong> <?= htmlspecialchars($usuario["nombre_usuario"] . ' ' . $usuario["apellido_p"] . ' ' . $usuario["apellido_m"]) ?><br>
                                    <strong class="name-line">Edad:</strong> <?= htmlspecialchars($usuario["edad"]) ?> años <br>
                                    <strong class="name-line">Fecha de contratación:</strong> <?= htmlspecialchars($usuario["fecha_contratacion"]) ?> <br>
                                    <strong class="name-line">Antigüedad:</strong> <?= htmlspecialchars($usuario["antiguedad"]) ?> años <br>
                                    <strong class="name-line">División Adscrita:</strong> <?= htmlspecialchars($usuario['nombre_carrera']) ?><br>
                                    <strong class="name-line">Número de Empleado:</strong> <?= htmlspecialchars($usuario["numero_empleado"]) ?> <br>
                                    <strong class="name-line">Grado académico:</strong> <?= htmlspecialchars($usuario["grado_academico"]) ?> <br>
                                    <strong class="name-line">Cédula:</strong> <?= htmlspecialchars($usuario["cedula"]) ?> <br>
                                    <strong class="name-line">Correo:</strong> <?= htmlspecialchars($usuario["correo"]) ?> <br>
                                  </p>
                                </div>
                              </div>
                            </div>
                            <!-- Más elementos del carrusel se generarán dinámicamente -->
                          </div>
                        </div>
                    <button class="carousel-control-next col-1 btn btn-primary" type="button" id="siguiente">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="visually-hidden"></span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
          <script>
            // Obtener el idusuario actual desde la URL
            const urlParams = new URLSearchParams(window.location.search);
            let idusuario = parseInt(urlParams.get("idusuario")) || 1; // Si no hay idusuario en la URL, empezamos en 1

            // Seleccionar los botones de navegación
            const anterior = document.getElementById("anterior");
            const siguiente = document.getElementById("siguiente");
            const carouselContent = document.getElementById("carouselContent");

            // Función para actualizar la URL con el nuevo idusuario
            function updateUrl(newIdusuario) {
              window.location.href = `?idusuario=${newIdusuario}`;
            }

            // Cargar un nuevo usuario al hacer clic en el botón "Siguiente"
            siguiente.addEventListener("click", () => {
              idusuario++; // Incrementa el ID del usuario
              updateUrl(idusuario); // Actualiza la URL
            });

            // Lógica para ir al usuario anterior (si es necesario)
            anterior.addEventListener("click", () => {
              if (idusuario > 1) { // Asegúrate de que no baje de 1
                idusuario--; // Decrementa el ID del usuario
                updateUrl(idusuario); // Actualiza la URL
              }
            });
          </script>


      

                <!-- Columna para las tarjetas -->
              </div>
            </div>
            <!------>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const filterBtn = document.getElementById('filterBtn');
  const filterOptions = document.getElementById('filterOptions');

  // Toggle la visibilidad de las opciones al hacer clic en el botón
  filterBtn.addEventListener('click', function() {
    filterOptions.classList.toggle('d-none');
  });

  // Agregar evento a cada opción de carrera
  filterOptions.querySelectorAll('.dropdown-item').forEach(function(item) {
    item.addEventListener('click', function() {
      const carreraId = this.getAttribute('data-value');

      // Actualizar la URL sin recargar la página
      const url = new URL(window.location.href);
      url.searchParams.set('id', carreraId); // Cambia el parámetro 'id' por el que quieras usar
      window.history.pushState({}, '', url); // Actualiza la URL en el navegador

      // Cerrar las opciones después de seleccionar
      filterOptions.classList.add('d-none');
      
      // Opcional: aquí puedes hacer algo después de seleccionar (como cargar datos)
      console.log(`Carrera seleccionada: ${carreraId}`); // Solo para depurar
    });
  });
});
</script>



            <script src="js/jquery.min.js"></script>
            <script src="js/popper.min.js"></script>
            <script src="js/moment.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script src="js/simplebar.min.js"></script>
            <script src='js/daterangepicker.js'></script>
            <script src='js/jquery.stickOnScroll.js'></script>
            <script src="js/tinycolor-min.js"></script>
            <script src="js/config.js"></script>
            <script src="js/d3.min.js"></script>
            <script src="js/topojson.min.js"></script>
            <script src="js/datamaps.all.min.js"></script>
            <script src="js/datamaps-zoomto.js"></script>
            <script src="js/datamaps.custom.js"></script>
            <script src="js/Chart.min.js"></script>
            <script>
              /* defind global options */
              Chart.defaults.global.defaultFontFamily = base.defaultFontFamily;
              Chart.defaults.global.defaultFontColor = colors.mutedColor;
            </script>
            <script src="js/gauge.min.js"></script>
            <script src="js/jquery.sparkline.min.js"></script>
            <script src="js/apexcharts.min.js"></script>
            <script src="js/apexcharts.custom.js"></script>
            <script src='js/jquery.mask.min.js'></script>
            <script src='js/select2.min.js'></script>
            <script src='js/jquery.steps.min.js'></script>
            <script src='js/jquery.validate.min.js'></script>
            <script src='js/jquery.timepicker.js'></script>
            <script src='js/dropzone.min.js'></script>
            <script src='js/uppy.min.js'></script>
            <script src='js/quill.min.js'></script>
            <script src="js/fullcalendar.custom.js"></script>
            <script src="js/fullcalendar.js"></script>
           
</body>

</html>