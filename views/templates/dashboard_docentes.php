<?php
include('../../models/session.php');
include('../../controllers/db.php'); // Conexión a la base de datos
include('../../models/consultas.php'); // Incluir la clase de consultas
include('aside.php');

// Crear instancia de Consultas
$consultas = new Consultas($conn);

// Obtener el ID del usuario actual y el tipo de usuario desde la sesión
$idusuario = (int) $_SESSION['user_id'];
$tipoUsuarioId = $consultas->obtenerTipoUsuarioPorId($idusuario);
$imgUser  = $consultas->obtenerImagen($idusuario);
$periodoReciente = $consultas->obtenerPeriodoReciente(); // 🔥 Se agregó esta línea

// Validar tipo de usuario
if (!$tipoUsuarioId) {
    die("Error: Tipo de usuario no encontrado para el ID proporcionado.");
}

// Si el tipo de usuario es 1, forzar visualización solo de su perfil
if ($tipoUsuarioId === 1) {
    $_GET['idusuario'] = $idusuario;
}

// Obtener usuario y carrera
$idusuario = isset($_GET['idusuario']) ? intval($_GET['idusuario']) : $idusuario;
$usuario = $consultas->obtenerUsuarioPorId($idusuario);
$carrera = $consultas->obtenerCarreraPorUsuarioId($idusuario);
$carreras = $consultas->obtenerCarreras();

// Fusionar datos de usuario y carrera
if ($carrera) {
    $usuario = array_merge($usuario, $carrera);
}

// Calcular antigüedad del usuario
if (isset($usuario["fecha_contratacion"])) {
    $fechaContratacionDate = new DateTime($usuario["fecha_contratacion"]);
    $fechaActual = new DateTime();
    $usuario['antiguedad'] = $fechaContratacionDate->diff($fechaActual)->y;
}

// Obtener incidencias con los nombres de los usuarios
$incidenciasUsuarios = $consultas->obtenerIncidenciasUsuarios();

// Obtener incidencias por carrera para la gráfica
$incidenciasCarrera = $consultas->IncidenciasCarreraGrafic();
$carrerasGrafic = [];
$incidenciasGrafic = [];

// Recorrer las incidencias por carrera y almacenarlas
foreach ($incidenciasCarrera as $row) {
    $carrerasGrafic[] = $row['nombre_carrera']; 
    
    // Validación para evitar Undefined array key
    $cantidadRegistros = isset($row['cantidad_registros']) ? (int) $row['cantidad_registros'] : 0;

    // Agregar los valores al array correspondiente
    $incidenciasGrafic[] = $cantidadRegistros;
}

// Convertir datos a JSON para pasarlos a JavaScript
$carrerasJson = json_encode($carrerasGrafic);
$incidenciasJson = json_encode($incidenciasGrafic);

$horas = $consultas->obtenerHorasMaterias();

// Guardar los valores en variables
$horas_tutorias = $horas['horas_tutorias'];
$horas_apoyo = $horas['horas_apoyo'];
$horas_frente_grupo = $horas['horas_frente_grupo'];

// Consultar incidencias del usuario
$query = "SELECT motivo, dia_incidencia FROM incidencia_has_usuario WHERE usuario_usuario_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $idusuario);
$stmt->execute();
$avisos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nombre de carrera
$nombreCarrera = isset($carrera['nombre_carrera']) ? htmlspecialchars($carrera['nombre_carrera']) : 'Sin división';

// Obtener listas de períodos
$periodos = $consultas->obtenerPeriodos();

// Verificar si se ha enviado el formulario de cerrar sesión
if (isset($_POST['logout'])) {
    $sessionManager->logoutAndRedirect('../templates/auth-login.php');
}
?>







<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="assets/images/PIIA_oscuro 1.png">
  <title>Recursos humanos</title>
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


  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+g6Y1Ch6JvWc1R6FddRZnYf4M4w3LTpVj1q9Vkp8" crossorigin="anonymous"></script>

  </link>
  </link>

</head>

<body class="vertical  light  ">
  <div class="wrapper">
    <nav class="topnav navbar navbar-light">
      <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
        <i class="fe fe-menu navbar-toggler-icon"></i>
      </button>
      <form class="form-inline mr-auto searchform text-muted">
        <input class="form-control mr-sm-2 bg-transparent border-0 pl-4 text-muted" type="search"
          placeholder="Type something..." aria-label="Search">
      </form>
      <ul class="nav">
        <li class="nav-item">
          <a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="light">
            <i class="fe fe-sun fe-16"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-shortcut">
            <span class="fe fe-grid fe-16"></span>
          </a>
        </li>
        <li class="nav-item nav-notif">
          <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-notif">
            <span class="fe fe-bell fe-16"></span>
            <span class="dot dot-md bg-success"></span>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="avatar avatar-sm mt-2">
                  <img src="<?= htmlspecialchars($imgUser['imagen_url'] ?? './assets/avatars/default.jpg') ?>" 
                      alt="Avatar del usuario" 
                      class="avatar-img rounded-circle" 
                      style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
              </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="Perfil.php">Profile</a>
            <a class="dropdown-item" href="#">Settings</a>
            <a class="dropdown-item" href="#">Activities</a>
            <form method="POST" action="" id="logoutForm">
              <button class="dropdown-item" type="submit" name="logout">Cerrar sesión</button>
            </form>
          </div>
        </li>
      </ul>
    </nav>
  </div>
<!---Div de imagen de perfil (con espacio debajo del botón para separarlo)----------------------->
<div class="card text-center">
    <div class="card-body">
        <h5 class="card-title">Filtrado por División</h5>
        <div class="filter-container" style="position: relative; display: inline-block;">
            <button id="filterBtn" class="btn btn-primary" style="margin-bottom: 10px;">Seleccionar División</button>
            <div id="filterOptions" class="filter-options d-none">
                <select class="form-control">
                    <?php foreach ($carreras as $carrera): ?>
                        <option class="dropdown-item" data-value="<?= htmlspecialchars($carrera['carrera_id']) ?>">
                            <?= htmlspecialchars($carrera['nombre_carrera']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>
  


  <div role="main" class="main-content">
    <!---Div de imagen de perfil (falta darle estilos a las letras)----------------------->
    <div id="teacherCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="container-fluid mb-3">
        <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile flag-div">
          RECURSOS HUMANOS
        </div>
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

                    <div id="miCarrusel" class="carousel slide col-10">
                      <div class="carousel-inner" id="carouselContent">
                        <div class="carousel-item active animate" data-id="<?= htmlspecialchars($idusuario) ?>">
                          <div class="row">
                            <div class="col-12 col-md-5 col-xl-3 text-center">
                              <strong class="name-line">Foto del Docente:</strong> <br>
                              <img src="<?= '../' . htmlspecialchars($usuario["imagen_url"]) ?>" alt="Imagen del docente" class="img-fluid tamanoImg">
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


      <script>
        document.addEventListener("DOMContentLoaded", function() {
          // Inicializar el carrusel con interval en false para desactivar el auto avance
          var myCarousel = document.getElementById('miCarrusel');
          var carousel = new bootstrap.Carousel(myCarousel, {
            interval: false // Desactiva el desplazamiento automático
          });

          // Controlar el botón "anterior"
          document.getElementById('anterior').addEventListener('click', function() {
            carousel.prev();
          });

          // Controlar el botón "siguiente"
          document.getElementById('siguiente').addEventListener('click', function() {
            carousel.next();
          });

          // Código para el filtro de carreras
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
              const carreraNombre = this.textContent.trim(); // Obtener el nombre de la carrera

              // Actualizar el texto del botón con el nombre de la carrera seleccionada
              filterBtn.textContent = carreraNombre;

              // Enviar el carrera_id seleccionado al servidor mediante AJAX
              $.ajax({
                url: '../templates/filtrarPorCarrera.php',
                type: 'POST',
                data: {
                  carrera_id: carreraId
                },
                dataType: 'json',
                success: function(response) {
                  if (response && response.length > 0) {
                    actualizarCarrusel(response);
                  } else {
                    console.error("No se recibieron usuarios.");
                  }
                },
                error: function() {
                  console.error('Error al obtener los usuarios por carrera.');
                }
              });

              // Ocultar las opciones de carrera después de la selección
              filterOptions.classList.add('d-none');
            });
          });
        });

        function actualizarCarrusel(usuarios) {
          const carouselContent = document.getElementById('carouselContent');

          // Limpiar el contenido anterior
          carouselContent.innerHTML = '';

          // Iterar sobre los usuarios y generar nuevas entradas del carrusel
          usuarios.forEach((usuario, index) => {
            const activeClass = index === 0 ? 'active' : ''; // Solo la primera entrada será activa

            // Convertir la fecha de contratación en un objeto Date
            const fechaContratacion = new Date(usuario.fecha_contratacion);
            const fechaActual = new Date();

            // Calcular la diferencia en años entre la fecha actual y la fecha de contratación
            let antiguedad = fechaActual.getFullYear() - fechaContratacion.getFullYear();
            const mesActual = fechaActual.getMonth();
            const mesContratacion = fechaContratacion.getMonth();

            // Ajustar la antigüedad si el mes actual es anterior al mes de contratación
            // O si es el mismo mes pero el día actual es anterior al día de contratación
            if (mesActual < mesContratacion || (mesActual === mesContratacion && fechaActual.getDate() < fechaContratacion.getDate())) {
              antiguedad--;
            }

            const carouselItem = `
            <div class="carousel-item ${activeClass}">
                <div class="row">
                    <div class="col-12 col-md-5 col-xl-3 text-center">
                        <strong class="name-line">Foto del Docente:</strong> <br>
                        <img src="../${usuario.imagen_url}" alt="Imagen del docente" class="img-fluid tamanoImg">
                    </div>
                    <div class="col-12 col-md-7 col-xl-9 data-teacher mb-0">
                        <p class="teacher-info h4">
                            <strong class="name-line">Docente:</strong> ${usuario.nombre_usuario} ${usuario.apellido_p} ${usuario.apellido_m}<br>
                            <strong class="name-line">Edad:</strong> ${usuario.edad} años <br>
                            <strong class="name-line">Fecha de contratación:</strong> ${usuario.fecha_contratacion} <br>
                            <strong class="name-line">Antigüedad:</strong> ${antiguedad} años <br>
                            <strong class="name-line">División Adscrita:</strong> ${usuario.nombre_carrera}<br>
                            <strong class="name-line">Número de Empleado:</strong> ${usuario.numero_empleado} <br>
                            <strong class="name-line">Grado académico:</strong> ${usuario.grado_academico} <br>
                            <strong class="name-line">Cédula:</strong> ${usuario.cedula} <br>
                            <strong class="name-line">Correo:</strong> ${usuario.correo} <br>
                        </p>
                    </div>
                </div>
            </div>
        `;

            // Insertar el nuevo elemento en el carrusel
            carouselContent.innerHTML += carouselItem;
          });
        }
      </script>


      <!-- Parte de recursos humanos -->
      <div class="container-fluid mt-0">
  <div class="mb-3 mt-0 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile flag-div ">
    RECURSOS HUMANOS
  </div>
  
  <!-- Tarjeta principal -->
  <div class="card shadow-lg p-4 mb-3">
    <div class="wrapper">
      <div class="container-fluid">
        <!-- Filtros -->
        <div class="container-filter mb-3 d-flex justify-content-center flex-wrap">
          <!-- Filtro de Periodo -->
          <div class="card-body-filter period-filter box-shadow-div mx-2 mb-0 mt-0 position-relative">
            <span class="fe fe-24 fe-filter me-2"></span>
            <label class="filter-label">Periodo:</label>
            <div class="filter-options position-relative">
              <select class="form-select" id="periodoSelect">
                <option value="">Selecciona un periodo</option>
                <?php foreach ($periodos as $periodo): ?>
                  <option value="<?php echo $periodo['periodo_id']; ?>">
                    <?php echo htmlspecialchars($periodo['descripcion']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- Filtro de División -->
          <div class="card-body-filter division-filter box-shadow-div mx-2 mb-0 position-relative">
            <button class="btn-filter d-flex align-items-center">
              <span class="fe fe-24 fe-filter me-2"></span>
              <span class="filter-label" data-placeholder="División">
                <?php echo $nombreCarrera; ?>
              </span>
            </button>
            <div class="filter-options position-absolute top-100 start-0 bg-white border shadow-sm d-none">
              <ul class="list-unstyled m-0 p-2">
                <li><a href="#" class="d-block py-1"><?php echo $nombreCarrera; ?></a></li>
              </ul>
            </div>
          </div>

        </div>

        <!-- Sección de Incidencias -->
        <h2 class="titulo text-center my-3">INCIDENCIAS</h2>
        <div class="row d-flex justify-content-center">
          <!-- Bloque de Días Económicos -->
          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card-body-calendar box-shadow-div mb-3">
              <h3 class="h5">DIAS ECONOMICOS TOTALES</h3>
              <div class="text-verde">4</div>
            </div>
            <div class="card-body-calendar box-shadow-div">
              <h3 class="h5">DIAS ECONOMICOS TOMADOS</h3>
              <div class="text-verde">1</div>
            </div>
          </div>

          <!-- Calendario -->
          <div class="col-xl-6 col-lg-8 col-md-12 col-sm-12 mb-3">
            <div class="calendar-new box-shadow-div">
              <div class="header d-flex align-items-center">
                <div class="month"></div>
                <div class="btns d-flex justify-content-center">
                  <div class="btn today-btn mx-1">
                    <i class="fe fe-24 fe-calendar"></i>
                  </div>
                  <div class="btn prev-btn mx-1">
                    <i class="fe fe-24 fe-arrow-left"></i>
                  </div>
                  <div class="btn next-btn mx-1">
                    <i class="fe fe-24 fe-arrow-right"></i>
                  </div>
                </div>
              </div>
              <div class="weekdays d-flex">
                <div class="day">Dom</div>
                <div class="day">Lun</div>
                <div class="day">Mar</div>
                <div class="day">Mie</div>
                <div class="day">Jue</div>
                <div class="day">Vie</div>
                <div class="day">Sab</div>
              </div>
              <div class="days">
                <!-- días agregados dinámicamente -->
              </div>
            </div>
          </div>

          <!-- Bloque de Avisos -->
          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card-body-calendar box-shadow-div mb-3">
              <h3 class="h5">AVISOS</h3>
              <div class="text-verde"><?php echo count($avisos); ?></div>
            </div>
            <div class="card-body-calendar">
              <?php foreach ($avisos as $aviso): ?>
                <div class="card-avisos mb-2">
                  <strong>Motivo:</strong> <?php echo htmlspecialchars($aviso['motivo']); ?><br>
                  <strong>Fecha de incidencia:</strong> <?php echo htmlspecialchars($aviso['dia_incidencia']); ?>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Modal de Incidencias -->
        <div class="modal fade" id="incidenciasModal" tabindex="-1" aria-labelledby="incidenciasModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="incidenciasModalLabel">Formulario de Incidencias</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" id="modalContent">
                <!-- Contenido cargado dinámicamente -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('periodoSelect').addEventListener('change', function() {
    const selectedPeriodId = this.value;
    console.log("Periodo seleccionado:", selectedPeriodId);
    
    if (selectedPeriodId) {
        fetch(`get_period_dates.php?id=${selectedPeriodId}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.fecha_inicio && data.fecha_termino) {
                    const fechaInicio = new Date(data.fecha_inicio);
                    const fechaTermino = new Date(data.fecha_termino);
                    actualizarCalendario(fechaInicio, fechaTermino);
                }
            })
            .catch(error => console.error("Error al obtener las fechas del periodo:", error));
    }
});

function actualizarCalendario(fechaInicio, fechaTermino) {
    currentMonth = fechaInicio.getMonth();
    currentYear = fechaInicio.getFullYear();
    renderCalendar();
}
</script>

<<<<<<< HEAD
      <div class="container-fluid ">
        <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile flag-div mt-1 mb-2">
          DATOS DE INCIDENCIAS
        </div>
        <div class="row">
          <!-- Tarjeta de Gráfica de Incidencias -->
=======

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="container-fluid">
        <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile flag-div ">
          DESARROLLO ACADÉMICO
        </div>
        <div class="card box-shadow-div p-4">
          <h2 class="text-center">Evaluación Docente</h2>
          <div class="row justify-content-center my-2">
            <div class="col-auto ml-auto">
              <form class="form-inline">
                <div class="form-group">
                  <label for="reportrange" class="sr-only">Date Ranges</label>
                  <div id="reportrange" class="px-2 py-2 text-muted">
                    <i class="fe fe-calendar fe-16 mx-2"></i>
                    <span class="small"></span>
                  </div>
                </div>
                <div class="form-group">
                  <button type="button" class="btn btn-sm"><span
                      class="fe fe-refresh-ccw fe-12 text-muted"></span></button>
                  <button type="button" class="btn btn-sm"><span class="fe fe-filter fe-12 text-muted"></span></button>
                </div>
              </form>
            </div>
          </div>
          <!-- charts-->
          <div class="container-fluid">
            <div class="row my-4">
              <div class="col-md-12">
                <div class="chart-box rounded">
                  <div id="columnChart"></div>
                </div>
              </div> <!-- .col -->
            </div> <!-- end section -->
          </div>
          <div class="container-fluid mt-0">
            <div class="row">
              <div class="col-lg-6">
                <div class="d-flex flex-column">
                  <div class="card box-shadow-div text-center border-5 mt-1 mb-1">
                    <div class="card-body">
                      <h2 class="font-weight-bold mb-4">Calificación promedio</h2>
                      <h1 class="text-success mb-3">85.30</h1>
                    </div>
                  </div>

                  <div class="card box-shadow-div text-center border-5 mt-5 mb-5">
                    <div class="card-body">
                      <h2 class="font-weight-bold mb-4">Grupo tutor</h2>
                      <h1 class="text-success mb-3">8ISC22</h1>
                    </div>
                  </div>

                  <div class="card box-shadow-div text-center border-5 mt-3 mb-3">
                    <div class="card-body">
                      <h2 class="font-weight-bold mb-4">Día de tutoría</h2>
                      <h1 class="text-success mb-3">Lunes</h1>
                    </div>
                  </div>
                </div>
              </div>

              <!--------Inicio de la tabla ---------->
              <!-- Columna para la tabla -->
              <div class="col-lg-6">
                <div class="card box-shadow-div text-center border-5 mt-1">
                  <div class="card-body">
                    <div class="row">
                      <!-- Recent orders -->
                      <div class="col-12">
                        <h6 class="mb-3">Capacitación disciplinaria</h6>
                        <div class="table-responsive">
                          <table class="table table-borderless table-striped">
                            <thead>
                              <tr role="row">
                                <th>ID</th>
                                <th>Purchase Date</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <th scope="col">1331</th>
                                <td>2020-12-26 01:32:21</td>
                                <td>Kasimir Lindsey</td>
                                <td>(697) 486-2101</td>
                                <td>996-3523 Et Ave</td>
                                <td>$3.64</td>
                                <td> Paypal</td>
                                <td>Shipped</td>
                                <td>
                                  <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle more-vertical" type="button"
                                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <span class="text-muted sr-only">Action</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                      <a class="dropdown-item" href="#">Edit</a>
                                      <a class="dropdown-item" href="#">Remove</a>
                                      <a class="dropdown-item" href="#">Assign</a>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <th scope="col">1156</th>
                                <td>2020-04-21 00:38:38</td>
                                <td>Melinda Levy</td>
                                <td>(748) 927-4423</td>
                                <td>Ap #516-8821 Vitae Street</td>
                                <td>$4.18</td>
                                <td> Paypal</td>
                                <td>Pending</td>
                                <td>
                                  <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle more-vertical" type="button"
                                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <span class="text-muted sr-only">Action</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                      <a class="dropdown-item" href="#">Edit</a>
                                      <a class="dropdown-item" href="#">Remove</a>
                                      <a class="dropdown-item" href="#">Assign</a>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <th scope="col">1038</th>
                                <td>2019-06-25 19:13:36</td>
                                <td>Aubrey Sweeney</td>
                                <td>(422) 405-2736</td>
                                <td>Ap #598-7581 Tellus Av.</td>
                                <td>$4.98</td>
                                <td>Credit Card </td>
                                <td>Processing</td>
                                <td>
                                  <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle more-vertical" type="button"
                                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <span class="text-muted sr-only">Action</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                      <a class="dropdown-item" href="#">Edit</a>
                                      <a class="dropdown-item" href="#">Remove</a>
                                      <a class="dropdown-item" href="#">Assign</a>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <th scope="col">1227</th>
                                <td>2021-01-22 13:28:00</td>
                                <td>Timon Bauer</td>
                                <td>(690) 965-1551</td>
                                <td>840-2188 Placerat, Rd.</td>
                                <td>$3.46</td>
                                <td> Paypal</td>
                                <td>Processing</td>
                                <td>
                                  <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle more-vertical" type="button"
                                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <span class="text-muted sr-only">Action</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                      <a class="dropdown-item" href="#">Edit</a>
                                      <a class="dropdown-item" href="#">Remove</a>
                                      <a class="dropdown-item" href="#">Assign</a>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <th scope="col">1956</th>
                                <td>2019-11-11 16:23:17</td>
                                <td>Kelly Barrera</td>
                                <td>(117) 625-6737</td>
                                <td>816 Ornare, Street</td>
                                <td>$4.16</td>
                                <td>Credit Card </td>
                                <td>Shipped</td>
                                <td>
                                  <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle more-vertical" type="button"
                                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <span class="text-muted sr-only">Action</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                      <a class="dropdown-item" href="#">Edit</a>
                                      <a class="dropdown-item" href="#">Remove</a>
                                      <a class="dropdown-item" href="#">Assign</a>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <th scope="col">1669</th>
                                <td>2021-04-12 07:07:13</td>
                                <td>Kellie Roach</td>
                                <td>(422) 748-1761</td>
                                <td>5432 A St.</td>
                                <td>$3.53</td>
                                <td> Paypal</td>
                                <td>Shipped</td>
                                <td>
                                  <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle more-vertical" type="button"
                                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <span class="text-muted sr-only">Action</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                      <a class="dropdown-item" href="#">Edit</a>
                                      <a class="dropdown-item" href="#">Remove</a>
                                      <a class="dropdown-item" href="#">Assign</a>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <th scope="col">1909</th>
                                <td>2020-05-14 00:23:11</td>
                                <td>Lani Diaz</td>
                                <td>(767) 486-2253</td>
                                <td>3328 Ut Street</td>
                                <td>$4.29</td>
                                <td> Paypal</td>
                                <td>Pending</td>
                                <td>
                                  <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle more-vertical" type="button"
                                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <span class="text-muted sr-only">Action</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                      <a class="dropdown-item" href="#">Edit</a>
                                      <a class="dropdown-item" href="#">Remove</a>
                                      <a class="dropdown-item" href="#">Assign</a>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                              <!-- Fin de las filas de la tabla -->
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Columna para las tarjetas -->
            </div>
          </div>

        </div> <!-- .container-fluid -->
      </div> <!---- fin de la card principál------>
      <div class="container-fluid">
  <div id="contenedor">
    <!-- Tarjeta principal -->
    <div class="card box-shadow-div p-4 mb-3">
      <div class="logo-container">
        <div class="logo-institucional">
          <!-- Espacio para el logo institucional -->
          <img src="assets/images/logo.png" alt="Logo Institucional">
        </div>
        <div class="titulo-container">
          <h1>TECNOLÓGICO DE ESTUDIOS SUPERIORES DE CHIMALHUACÁN</h1>
        </div>
        <div class="form-group">
          <label for="periodo_periodo_id" class="form-label-custom">Periodo:</label>
          <select class="form-control" id="periodo_periodo_id" name="periodo_periodo_id" required 
                  <?php if (!empty($periodoReciente)): ?> disabled <?php endif; ?>>
            <?php if (!empty($periodoReciente)): ?>
              <option value="<?php echo $periodoReciente['periodo_id']; ?>" selected>
                <?php echo htmlspecialchars($periodoReciente['descripcion']); ?>
              </option>
            <?php endif; ?>
            <?php foreach ($periodos as $periodo): ?>
              <option value="<?php echo $periodo['periodo_id']; ?>" 
                      <?php if ($periodo['periodo_id'] == $periodoReciente['periodo_id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($periodo['descripcion']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>       
      </div>

      <!-- Contenido principal -->
      <div class="row">
        <div class="col-md-6">
          <!-- Docente -->
          <div class="form-group mt-2">
            <label for="usuario_usuario_id">Docente:</label>
            <select class="form-control" id="usuario_usuario_id" name="usuario_usuario_id" required onchange="filtrarCarreras()" <?= ($tipoUsuarioId === 1) ? 'disabled' : ''; ?>>
              <?php if ($tipoUsuarioId === 1): ?>
                <option value="<?php echo $idusuario; ?>" selected>
                  <?php echo htmlspecialchars($usuario['nombre_usuario'] . ' ' . $usuario['apellido_p'] . ' ' . $usuario['apellido_m']); ?>
                </option>
              <?php else: ?>
                <option value="">Seleccione un usuario</option>
                <?php foreach ($usuarios as $user): ?>
                  <option value="<?php echo $user['usuario_id']; ?>" <?= ($user['usuario_id'] == $idusuario) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($user['nombre_usuario'] . ' ' . $user['apellido_p'] . ' ' . $user['apellido_m']); ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <!-- Carrera -->
          <div class="form-group mt-2">
            <label for="carrera_carrera_id" class="form-label">Carrera:</label>
            <select class="form-control" id="carrera_carrera_id" name="carrera_carrera_id" required onchange="filtrarCarreras()">
              <option value="">Selecciona una carrera</option>
              <?php foreach ($carreras as $carrera): ?>
                <option value="<?php echo $carrera['carrera_id']; ?>"><?php echo htmlspecialchars($carrera['nombre_carrera']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>  
      </div>

      <!-- Tabla -->
      <div class="row">
        <div class="col-12 mb-0">
          <div class="schedule-container">
            <div class="table-responsive">
              <table class="table table-borderless table-striped">
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Botón de descarga PDF -->
      <div class="pdf-container no-print">
        <button id="downloadPDF" onclick="generatePDF()">Descargar PDF</button>
      </div>

    </div>
  </div>
</div>

      <!-- Incluir la librería html2pdf.js antes de tu archivo de script personalizado -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
<div id="barChart" 
     data-tutorias="<?php echo $horas_tutorias; ?>" 
     data-apoyo="<?php echo $horas_apoyo; ?>" 
     data-frente="<?php echo $horas_frente_grupo; ?>">
</div>

    <script src="js/horario_vista.js"></script>


>>>>>>> 42ae625e0ecb6377fd8f62efe9d61a995851b68a
          <div class="col-12 mb-4">
            <div class="card shadow box-shadow-div h-100 carta_Informacion">
              <div class="card-header carta_Informacion">
                <strong class="card-title text-green mb-0 carta_Informacion">Gráfica de Incidencias</strong>
              </div>
              <div class="card-body">
                <!-- Donut Chart de Incidencias -->
                <div id="donutChart3" style="height: 300px;"></div> <!-- Ajusta la altura según sea necesario -->
              </div> <!-- /.card-body -->
              
              <script>
    var barChartOptions = {
        series: [
            {
                name: "Horas",
                data: [
                    <?php echo $horas_tutorias; ?>, 
                    <?php echo $horas_apoyo; ?>, 
                    <?php echo $horas_frente_grupo; ?>
                ]
            }
        ],
        chart: {
            type: "bar",
            height: 350,
            stacked: false,
            toolbar: { enabled: false },
            zoom: { enabled: false }
        },
        dataLabels: { enabled: true },
        plotOptions: {
            bar: { 
                horizontal: true, 
                columnWidth: "50%" 
            }
        },
        xaxis: {
            categories: ["Tutorías", "Horas de Apoyo", "Horas Frente al Grupo"],
            labels: { style: { colors: "#6c757d", fontFamily: "Arial" } }
        },
        yaxis: {
            labels: { style: { colors: "#6c757d", fontFamily: "Arial" } }
        },
        fill: { opacity: 1, colors: ["#ff4560", "#008ffb", "#00e396"] }
    };

    var barChart = new ApexCharts(document.querySelector("#barChart"), barChartOptions);
    barChart.render();
</script>


            </div> <!-- /.card -->
          </div> <!-- /.col -->

          <!-- Tarjeta de Tabla de Incidencias -->
          <div class="col-12">
            <div class="card box-shadow-div p-4 mb-3">
              <div class="card-header carta_Informacion">
                <strong class="card-title text-green mb-0 carta_Informacion">Tabla de Incidencias</strong>
              </div>
              <div class="card-body">
                <!-- Ejemplo de tabla para incidencias -->
                <table class="table table-striped mt-3">
                  <thead>
                    <tr>
                      <th>ID Incidencia</th>
                      <th>Descripción</th>
                      <th>Fecha</th>
                      <th>Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>001</td>
                      <td>Incidencia de ejemplo 1</td>
                      <td>12/09/2024</td>
                      <td>Resuelta</td>
                    </tr>
                    <tr>
                      <td>002</td>
                      <td>Incidencia de ejemplo 2</td>
                      <td>13/09/2024</td>
                      <td>Pendiente</td>
                    </tr>
                    <!-- Añadir más filas según sea necesario -->
                  </tbody>
                </table>
              </div> <!-- /.card-body -->
            </div> <!-- /.card-body -->

          </div> <!-- /.col -->
        </div> <!-- /.row -->
      </div> <!-- /.container-fluid -->

      


        

          <!-- Nuevo Contenedor Principal: Incidencias -->
          <div class="container-fluid mt-5 box-shadow-div p-5">
            <!-- Título de Incidencias -->
            <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile cont-div">
              Incidencias
            </div>

            <!-- Contenedor de la Tarjeta de Incidencias -->
            <div class="container-fluid p-3">
              <div class="row">
                <!-- Columna completa para la tarjeta -->
                <div class="col-12">
                  <div class="card shadow mb-4 box-shadow-div h-100 carta_Informacion">
                    <!-- Encabezado de la Tarjeta -->
                    <div class="card-header">
                      <strong class="card-title text-green mb-0">Resumen de Incidencias</strong>
                    </div>

                    <!-- Cuerpo de la tarjeta -->
                    <div class="card-body text-center">
                      <!-- Incluye Chart.js -->
                   





                      <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

                      <!-- Nuevo contenedor para el gráfico de pastel -->
                    <div id="donutChart4"></div>



<script>
document.addEventListener("DOMContentLoaded", function() {
    // Obtener datos desde PHP
    var carreras = <?php echo $carrerasJson; ?>;
    var incidencias = <?php echo $incidenciasJson; ?>;

    console.log("Carreras:", carreras);
    console.log("Incidencias:", incidencias);

    // Verificar si hay datos
    if (carreras.length === 0 || incidencias.length === 0) {
        console.warn("No hay datos para mostrar en la gráfica.");
        return;
    }

    // Verificar si ya existe un gráfico en #donutChart4 y destruirlo
    if (typeof chart !== 'undefined' && chart !== null) {
        chart.destroy(); // Destruir el gráfico anterior si existe
    }

    // Configuración del gráfico de dona (donut)
    var options = {
        series: incidencias, // Datos de incidencias
        chart: {
            type: 'donut', // Cambiar a tipo 'donut'
            height: 350
        },
        labels: carreras, // Etiquetas de las carreras
        colors: [
            '#66BB6A', // Verde claro
            '#43A047', // Verde medio
            '#2C6B2F', // Verde más oscuro
            '#1B5E20', // Verde oscuro
            '#81C784', // Verde pastel
            '#388E3C', // Verde fuerte
            '#4CAF50'  // Verde más brillante
        ], // Colores verdes
        legend: {
            position: 'bottom'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '60%' // Controlar el tamaño del agujero en el centro
                }
            }
        }
    };

    // Renderizar el gráfico en el div con ID 'donutChart4'
    var chart = new ApexCharts(document.querySelector("#donutChart4"), options);
    chart.render();
});
</script>





<div id="incidencias-container" style="overflow-y: auto;">
    <table class="table table-striped table-bordered" id="tabla-incidencias">
        <thead class="thead-dark" style="position: sticky; top: 0; background-color: #343a40; color: white; z-index: 2;">
            <tr>
                <th>Número de incidencia</th>
                <th>Usuario</th>
                <th>Fecha solicitada</th>
                <th>Motivo</th>
                <th>Hora de inicio</th>
                <th>Hora de término</th>
                <th>Horario de incidencia</th>
                <th>Día de la incidencia</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($incidenciasUsuarios)): ?>
                <?php foreach ($incidenciasUsuarios as $incidencia): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($incidencia['numero_incidencia']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['usuario']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['fecha_solicitada']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['motivo']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['hora_inicio']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['hora_termino']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['horario_incidencia']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['dia_incidencia']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No hay incidencias registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let table = document.getElementById("tabla-incidencias");
        let container = document.getElementById("incidencias-container");
        let rowCount = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;

        if (rowCount > 5) {
            container.style.maxHeight = "400px"; // Agrega el scroll si hay más de 5 registros
        } else {
            container.style.maxHeight = "auto"; // Sin scroll si hay 5 o menos
        }
    });
</script>


                    </div> <!-- /.card-body -->
                  </div> <!-- /.card -->
                </div> <!-- /.col -->
              </div> <!-- /.row -->
            </div> <!-- /.container-fluid -->
          </div> <!-- /.container-fluid -->





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
      <script src="js/apps.js"></script>

      <script>
        $('.select2').select2({
          theme: 'bootstrap4',
        });
        $('.select2-multi').select2({
          multiple: true,
          theme: 'bootstrap4',
        });
        $('.drgpicker').daterangepicker({
          singleDatePicker: true,
          timePicker: false,
          showDropdowns: true,
          locale: {
            format: 'MM/DD/YYYY'
          }
        });
        $('.time-input').timepicker({
          'scrollDefault': 'now',
          'zindex': '9999' /* fix modal open */
        });
        /** date range picker */
        if ($('.datetimes').length) {
          $('.datetimes').daterangepicker({
            timePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
              format: 'M/DD hh:mm A'
            }
          });
        }
        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        $('#reportrange').daterangepicker({
          startDate: start,
          endDate: end,
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          }
        }, cb);
        cb(start, end);
        $('.input-placeholder').mask("00/00/0000", {
          placeholder: "__/__/____"
        });
        $('.input-zip').mask('00000-000', {
          placeholder: "____-___"
        });
        $('.input-money').mask("#.##0,00", {
          reverse: true
        });
        $('.input-phoneus').mask('(000) 000-0000');
        $('.input-mixed').mask('AAA 000-S0S');
        $('.input-ip').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
          translation: {
            'Z': {
              pattern: /[0-9]/,
              optional: true
            }
          },
          placeholder: "___.___.___.___"
        });
        // editor
        var editor = document.getElementById('editor');
        if (editor) {
          var toolbarOptions = [
            [{
              'font': []
            }],
            [{
              'header': [1, 2, 3, 4, 5, 6, false]
            }],
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block'],
            [{
                'header': 1
              },
              {
                'header': 2
              }
            ],
            [{
                'list': 'ordered'
              },
              {
                'list': 'bullet'
              }
            ],
            [{
                'script': 'sub'
              },
              {
                'script': 'super'
              }
            ],
            [{
                'indent': '-1'
              },
              {
                'indent': '+1'
              }
            ], // outdent/indent
            [{
              'direction': 'rtl'
            }], // text direction
            [{
                'color': []
              },
              {
                'background': []
              }
            ], // dropdown with defaults from theme
            [{
              'align': []
            }],
            ['clean'] // remove formatting button
          ];
          var quill = new Quill(editor, {
            modules: {
              toolbar: toolbarOptions
            },
            theme: 'snow'
          });
        }
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
          'use strict';
          window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
              form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                  event.preventDefault();
                  event.stopPropagation();
                }
                form.classList.add('was-validated');
              }, false);
            });
          }, false);
        })();
      </script>
      <script>
        var uptarg = document.getElementById('drag-drop-area');
        if (uptarg) {
          var uppy = Uppy.Core().use(Uppy.Dashboard, {
            inline: true,
            target: uptarg,
            proudlyDisplayPoweredByUppy: false,
            theme: 'dark',
            width: 770,
            height: 210,
            plugins: ['Webcam']
          }).use(Uppy.Tus, {
            endpoint: 'https://master.tus.io/files/'
          });
          uppy.on('complete', (result) => {
            console.log('Upload complete! We’ve uploaded these files:', result.successful)
          });
        }
      </script>
      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
      <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
          dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-56159088-1');
      </script>
</body>

</html>