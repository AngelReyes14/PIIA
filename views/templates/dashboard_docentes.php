<?php
include('../../models/session.php');
include('../../controllers/db.php'); // Asegúrate de que este archivo incluya la conexión a la base de datos.
include('../../models/consultas.php'); // Incluir la clase de consultas
include('aside.php');

$idusuario = $_SESSION['user_id']; // Asumimos que el ID ya está en la sesión

$imgUser  = $consultas->obtenerImagen($idusuario);


// Crear instancia de Consultas y obtener tipo de usuario
$consultas = new Consultas($conn);
$idusuario = (int) $sessionManager->getUserId();
$tipoUsuarioId = $consultas->obtenerTipoUsuarioPorId($idusuario);

// Validar si el tipo de usuario fue correctamente obtenido
if (!$tipoUsuarioId) {
    die("Error: Tipo de usuario no encontrado para el ID proporcionado.");
}

// Si el tipo de usuario es 1, forzar que solo se muestre su propio perfil
if ($tipoUsuarioId === 1) {
  // Sobrescribir el idusuario para mostrar solo la información del usuario autenticado
  $_GET['idusuario'] = $idusuario;
}

// Crear una instancia de la clase Consultas
$consultas = new Consultas($conn);

// Obtenemos el idusuario actual (si no está definido, iniciamos en 1)
$idusuario = isset($_GET['idusuario']) ? intval($_GET['idusuario']) : 1;

// Llamamos al método para obtener el usuario actual
$usuario = $consultas->obtenerUsuarioPorId($idusuario);

// Llamamos al método para obtener la carrera del usuario
$carrera = $consultas->obtenerCarreraPorUsuarioId($idusuario);

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

// Verificar si se ha enviado el formulario de cerrar sesión
if (isset($_POST['logout'])) {
  $sessionManager->logoutAndRedirect('../templates/auth-login.php');
}

// Obtener el nombre de la carrera del usuario
$nombreCarrera = isset($carrera['nombre_carrera']) ? htmlspecialchars($carrera['nombre_carrera']) : 'Sin división';
$periodos = $consultas->obtenerPeriodos();
$query = "SELECT motivo, dia_incidencia 
          FROM incidencia_has_usuario 
          WHERE usuario_usuario_id = :user_id";

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $idusuario);
$stmt->execute();
$avisos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Recupera todos los registros

?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="assets/images/PIIA_oscuro 1.png">
  <title>Dashboard docente</title>
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
  <link rel="stylesheet" href="css/dataTables.bootstrap4.css">

  <!-- Date Range Picker CSS -->
  <link rel="stylesheet" href="css/daterangepicker.css" />
  <!-- App CSS -->
  <link rel="stylesheet" href="css/app-light.css" id="lightTheme">
  <link rel="stylesheet" href="css/app-dark.css" id="darkTheme">
  </link>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


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
  
  <!-- Código HTML del carrusel -->
<main role="main" class="main-content">
<div id="teacherCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="container-fluid mb-3">
          <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile flag-div">
            PERFIL DOCENTE
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
  // Pasar el tipo de usuario desde PHP a JavaScript
  const tipoUsuarioId = <?= json_encode($tipoUsuarioId) ?>;

  // Obtener el idusuario actual desde la URL o forzar el usuario autenticado si el tipo es 1
  const urlParams = new URLSearchParams(window.location.search);
  let idusuario = parseInt(urlParams.get("idusuario")) || 1; 

  // Seleccionar los botones de navegación
  const anterior = document.getElementById("anterior");
  const siguiente = document.getElementById("siguiente");

  // Deshabilitar botones y forzar la información del usuario actual si el tipo de usuario no permite mover el carrusel
  if (tipoUsuarioId === 1) {
    anterior.disabled = true;
    siguiente.disabled = true;
    
    // Sobrescribir el idusuario con el id del usuario autenticado
    idusuario = <?= json_encode($idusuario) ?>;
  } else if (tipoUsuarioId === 2) {

    // Función para actualizar la URL con el nuevo idusuario
    function updateUrl(newIdusuario) {
      window.location.href = `?idusuario=${newIdusuario}`;
    }

    // Cargar un nuevo usuario al hacer clic en el botón "Siguiente"
    siguiente.addEventListener("click", () => {
      idusuario++; 
      updateUrl(idusuario); 
    });

    // Lógica para ir al usuario anterior 
    anterior.addEventListener("click", () => {
      if (idusuario > 1) { 
        idusuario--; 
        updateUrl(idusuario); 
      }
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

          <!-- <div class="row">
        <div class="col-md-4">
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="chart-widget">
                <div id="gradientRadial"></div>
              </div>
              <div class="row">
                <div class="col-6 text-center">
                  <p class="text-muted mb-0">Yesterday</p>
                  <h4 class="mb-1">126</h4>
                  <p class="text-muted mb-2">+5.5%</p>
                </div>
                <div class="col-6 text-center">
                  <p class="text-muted mb-0">Today</p>
                  <h4 class="mb-1">86</h4>
                  <p class="text-muted mb-2">-5.5%</p>
                </div>
              </div>
            </div>
          </div> 
        </div> 


        <div class="col-md-4">
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="chart-widget mb-2">
                <div id="radialbar"></div>
              </div>
              <div class="row items-align-center">
                <div class="col-4 text-center">
                  <p class="text-muted mb-1">Cost</p>
                  <h6 class="mb-1">$1,823</h6>
                  <p class="text-muted mb-0">+12%</p>
                </div>
                <div class="col-4 text-center">
                  <p class="text-muted mb-1">Revenue</p>
                  <h6 class="mb-1">$6,830</h6>
                  <p class="text-muted mb-0">+8%</p>
                </div>
                <div class="col-4 text-center">
                  <p class="text-muted mb-1">Earning</p>
                  <h6 class="mb-1">$4,830</h6>
                  <p class="text-muted mb-0">+8%</p>
                </div>
              </div>
            </div> 
          </div> 
        </div> 


        <div class="col-md-4">
          <div class="card shadow mb-4">
            <div class="card-body">
              <p class="mb-0"><strong class="mb-0 text-uppercase text-muted">Today</strong></p>
              <h3 class="mb-0">$2,562.30</h3>
              <p class="text-muted">+18.9% Last week</p>
              <div class="chart-box mt-n5">
                <div id="lineChartWidget"></div>
              </div>
              <div class="row">
                <div class="col-4 text-center mt-3">
                  <p class="mb-1 text-muted">Completions</p>
                  <h6 class="mb-0">26</h6>
                  <span class="small text-muted">+20%</span>
                  <span class="fe fe-arrow-up text-success fe-12"></span>
                </div>
                <div class="col-4 text-center mt-3">
                  <p class="mb-1 text-muted">Goal Value</p>
                  <h6 class="mb-0">$260</h6>
                  <span class="small text-muted">+6%</span>
                  <span class="fe fe-arrow-up text-success fe-12"></span>
                </div>
                <div class="col-4 text-center mt-3">
                  <p class="mb-1 text-muted">Conversion</p>
                  <h6 class="mb-0">6%</h6>
                  <span class="small text-muted">-2%</span>
                  <span class="fe fe-arrow-down text-danger fe-12"></span>
                </div>
              </div>
            </div> 
          </div> 
        </div> 


        <div class="col-md-6">
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="card-title">
                <strong>Products</strong>
                <a class="float-right small text-muted" href="#!">View all</a>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div id="chart-box">
                    <div id="donutChartWidget"></div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="row align-items-center my-3">
                    <div class="col">
                      <strong>Cloud Server</strong>
                      <div class="my-0 text-muted small">Global, Services</div>
                    </div>
                    <div class="col-auto">
                      <strong>+85%</strong>
                    </div>
                    <div class="col-3">
                      <div class="progress" style="height: 4px;">
                        <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85"
                          aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                  <div class="row align-items-center my-3">
                    <div class="col">
                      <strong>CDN</strong>
                      <div class="my-0 text-muted small">Global, Services</div>
                    </div>
                    <div class="col-auto">
                      <strong>+75%</strong>
                    </div>
                    <div class="col-3">
                      <div class="progress" style="height: 4px;">
                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75"
                          aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                  <div class="row align-items-center my-3">
                    <div class="col">
                      <strong>Databases</strong>
                      <div class="my-0 text-muted small">Local, DC</div>
                    </div>
                    <div class="col-auto">
                      <strong>+62%</strong>
                    </div>
                    <div class="col-3">
                      <div class="progress" style="height: 4px;">
                        <div class="progress-bar" role="progressbar" style="width: 62%" aria-valuenow="62"
                          aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div> 
              </div> 
            </div>
          </div> 
        </div> 


        <div class="col-md-6">
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="card-title">
                <strong>Region</strong>
                <a class="float-right small text-muted" href="#!">View all</a>
              </div>
              <div class="map-box" style="position: relative; width: 350px; min-height: 130px; margin:0 auto;">
                <div id="dataMapUSA"></div>
              </div>
              <div class="row align-items-center h-100 my-2">
                <div class="col">
                  <p class="mb-0">France</p>
                  <span class="my-0 text-muted small">+10%</span>
                </div>
                <div class="col-auto text-right">
                  <span>118</span><br />
                  <div class="progress mt-2" style="height: 4px;">
                    <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0"
                      aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
              <div class="row align-items-center my-2">
                <div class="col">
                  <p class="mb-0">Netherlands</p>
                  <span class="my-0 text-muted small">+0.6%</span>
                </div>
                <div class="col-auto text-right">
                  <span>1008</span><br />
                  <div class="progress mt-2" style="height: 4px;">
                    <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0"
                      aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
              <div class="row align-items-center my-2">
                <div class="col">
                  <p class="mb-0">Italy</p>
                  <span class="my-0 text-muted small">+1.6%</span>
                </div>
                <div class="col-auto text-right">
                  <span>67</span><br />
                  <div class="progress mt-2" style="height: 4px;">
                    <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0"
                      aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
              <div class="row align-items-center my-2">
                <div class="col">
                  <p class="mb-0">Spain</p>
                  <span class="my-0 text-muted small">+118%</span>
                </div>
                <div class="col-auto text-right">
                  <span>186</span><br />
                  <div class="progress mt-2" style="height: 4px;">
                    <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0"
                      aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> 
      </div>  -->
        </div> <!-- .container-fluid -->
      </div> <!---- fin de la card principál------>

      <!----Parte de dirección academica---->
      <div class="container-fluid">
        <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile flag-div mt-1 mb-2">
          DIRECCIÓN ACADÉMICA
        </div>

        <!-- Tarjeta principal -->
        <div class="card box-shadow-div p-4 mb-3">
          <h2 class="text-center">Ingeniería en Sistemas Computacionales</h2>
          <div class="row">
            <div class="col-12 mb-0">
              <div class="schedule-container">
                <div class="table-responsive">
                  <table class="table table-borderless table-striped">
                    <thead>
                      <tr  role="row">
                        <th>Hora</th>
                        <th>Lunes</th>
                        <th>Martes</th>
                        <th>Miércoles</th>
                        <th>Jueves</th>
                        <th>Viernes</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr  scope="col">
                        <td>07:00 - 08:00</td>
                        <td>Clase A</td>
                        <td></td>
                        <td>Clase B</td>
                        <td></td>
                        <td>Clase C</td>
                      </tr>
                      <tr>
                        <td>08:00 - 09:00</td>
                        <td>Clase A</td>
                        <td>Clase A</td>
                        <td>Clase A</td>
                        <td>Clase A</td>
                        <td>Clase E</td>
                      </tr>
                      <tr  scope="col">
                        <td>09:00 - 10:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>
                      <tr>
                        <td>10:00 - 11:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>
                      <tr  scope="col">
                        <td>11:00 - 12:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr>
                        <td>12:00 - 13:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr  scope="col">
                        <td>13:00 - 14:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr>
                        <td>14:00 - 15:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr  scope="col">
                        <td>15:00 - 16:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr>
                        <td>16:00 - 17:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr  scope="col">
                        <td>17:00 - 18:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr>
                        <td>18:00 - 19:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr  scope="col">
                        <td>19:00 - 20:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr>
                        <td>20:00 - 21:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>


          <div class="col-12 mb-4">
            <div class="card shadow">
              <div class="card-header">
                <strong class="card-title mb-0">Desglose de horas</strong>
              </div>
              <div class="card-body">
                <div id="barChart"></div>
              </div> <!-- /.card-body -->
            </div> <!-- /.card -->
            <h2 class="col-12 col-lg-6 mt-4 mb-4">Total de horas: 40</h2>
          </div> <!-- /. col -->
        <!---------------- Termina la parte de direccion academica -------------->

        <div class="row mb-3">
          <!-- Card de Días Económicos Totales y Tomados -->
          <div class="col-lg-6 mb-3">
            <!-- Card Días Económicos Totales -->
            <div class="card box-shadow-div text-center border-9">
              <div class="card-body">
                <h3 class="font-weight-bold mb-0">CUERPO COLEGIADO</h3>
                <h1 class="text-success">DESARROLLO CCAI</h1>
              </div>
            </div>
          </div>

          <div class="col-lg-6 mb-3">
            <!-- Card Lista de Avisos (a la derecha) -->
            <div class="card box-shadow-div text-left border-5">
              <div class="card-body mb-3">
                <h1>PRODUCTOS DE INVESTIGACIÓN</h1>
                <ul class="list-group">
                  <li class="list-group-item border-3">
                    <h3 class="text-success">Investigación del conocimiento aplicado a la IA</h3>
                  </li>
                  <li class="list-group-item border-3">(Mayo 2023)</li>
                  <li class="list-group-item border-3 text-success">
                    <h3 class="text-success"> Desarrollo de software para el control de bitacoras </h3>
                  </li>
                  <li class="list-group-item border-3">(Agosto 2023)</li>
                </ul>
              </div>
            </div>
          </div>
        </div>


      </div>
  </div>

  <div class="modal fade modal-notif modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="defaultModalLabel">Notifications</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="list-group list-group-flush my-n3">
            <div class="list-group-item bg-transparent">
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="fe fe-box fe-24"></span>
                </div>
                <div class="col">
                  <small><strong>Package has uploaded successfull</strong></small>
                  <div class="my-0 text-muted small">Package is zipped and uploaded</div>
                  <small class="badge badge-pill badge-light text-muted">1m ago</small>
                </div>
              </div>
            </div>
            <div class="list-group-item bg-transparent">
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="fe fe-download fe-24"></span>
                </div>
                <div class="col">
                  <small><strong>Widgets are updated successfull</strong></small>
                  <div class="my-0 text-muted small">Just create new layout Index, form, table</div>
                  <small class="badge badge-pill badge-light text-muted">2m ago</small>
                </div>
              </div>
            </div>
            <div class="list-group-item bg-transparent">
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="fe fe-inbox fe-24"></span>
                </div>
                <div class="col">
                  <small><strong>Notifications have been sent</strong></small>
                  <div class="my-0 text-muted small">Fusce dapibus, tellus ac cursus commodo</div>
                  <small class="badge badge-pill badge-light text-muted">30m ago</small>
                </div>
              </div> <!-- / .row -->
            </div>
            <div class="list-group-item bg-transparent">
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="fe fe-link fe-24"></span>
                </div>
                <div class="col">
                  <small><strong>Link was attached to menu</strong></small>
                  <div class="my-0 text-muted small">New layout has been attached to the menu</div>
                  <small class="badge badge-pill badge-light text-muted">1h ago</small>
                </div>
              </div>
            </div> <!-- / .row -->
          </div> <!-- / .list-group -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Clear All</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade modal-shortcut modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="defaultModalLabel">Shortcuts</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-5">
          <div class="row align-items-center">
            <div class="col-6 text-center">
              <div class="squircle bg-success justify-content-center">
                <i class="fe fe-cpu fe-32 align-self-center text-white"></i>
              </div>
              <p>Control area</p>
            </div>
            <div class="col-6 text-center">
              <div class="squircle bg-primary justify-content-center">
                <i class="fe fe-activity fe-32 align-self-center text-white"></i>
              </div>
              <p>Activity</p>
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-6 text-center">
              <div class="squircle bg-primary justify-content-center">
                <i class="fe fe-droplet fe-32 align-self-center text-white"></i>
              </div>
              <p>Droplet</p>
            </div>
            <div class="col-6 text-center">
              <div class="squircle bg-primary justify-content-center">
                <i class="fe fe-upload-cloud fe-32 align-self-center text-white"></i>
              </div>
              <p>Upload</p>
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-6 text-center">
              <div class="squircle bg-primary justify-content-center">
                <i class="fe fe-users fe-32 align-self-center text-white"></i>
              </div>
              <p>Users</p>
            </div>
            <div class="col-6 text-center">
              <div class="squircle bg-primary justify-content-center">
                <i class="fe fe-settings fe-32 align-self-center text-white"></i>
              </div>
              <p>Settings</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </main> <!-- main -->
  </div> <!-- .wrapper -->
  <!------>
  
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  $(document).ready(function() {
    // Abrir la modal y cargar el contenido
    $('#openModalButton').on('click', function() {
      $('#modalContent').load('form_incidencias.php', function() {
        $('#incidenciasModal').modal('show');
      });
    });

    // Interceptar el envío del formulario
    $(document).on('submit', '#formincidencias', function(e) {
      e.preventDefault(); // Prevenir el envío normal

      // Crear el objeto FormData para enviar los datos del formulario
      let formData = new FormData(this);

      // Enviar los datos del formulario mediante AJAX
      $.ajax({
        url: '../../models/insert.php', // Cambia la ruta si es necesario
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          // Mostrar el SweetAlert si el envío fue exitoso
          Swal.fire({
            title: '¡Formulario enviado!',
            text: 'Los datos se han enviado correctamente.',
            icon: 'success',
            confirmButtonText: 'Aceptar'
          }).then(() => {
            // Cerrar la modal y recargar la página
            $('#incidenciasModal').modal('hide');
            location.reload(); // Recarga la página
          });
        },
        error: function() {
          // Mostrar SweetAlert en caso de error
          Swal.fire({
            title: 'Error',
            text: 'Hubo un problema al enviar el formulario.',
            icon: 'error',
            confirmButtonText: 'Intentar de nuevo'
          });
        }
      });
    });
  });
</script>
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
  <script src="../js/carrusel.js"></script>
  <script src="js/apps.js"></script>

  <script>



    $('.select2').select2(
      {
        theme: 'bootstrap4',
      });
    $('.select2-multi').select2(
      {
        multiple: true,
        theme: 'bootstrap4',
      });
    $('.drgpicker').daterangepicker(
      {
        singleDatePicker: true,
        timePicker: false,
        showDropdowns: true,
        locale:
        {
          format: 'MM/DD/YYYY'
        }
      });
    $('.time-input').timepicker(
      {
        'scrollDefault': 'now',
        'zindex': '9999' /* fix modal open */
      });
    /** date range picker */
    if ($('.datetimes').length) {
      $('.datetimes').daterangepicker(
        {
          timePicker: true,
          startDate: moment().startOf('hour'),
          endDate: moment().startOf('hour').add(32, 'hour'),
          locale:
          {
            format: 'M/DD hh:mm A'
          }
        });
    }
    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
      $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    $('#reportrange').daterangepicker(
      {
        startDate: start,
        endDate: end,
        ranges:
        {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      }, cb);
    cb(start, end);
    $('.input-placeholder').mask("00/00/0000",
      {
        placeholder: "__/__/____"
      });
    $('.input-zip').mask('00000-000',
      {
        placeholder: "____-___"
      });
    $('.input-money').mask("#.##0,00",
      {
        reverse: true
      });
    $('.input-phoneus').mask('(000) 000-0000');
    $('.input-mixed').mask('AAA 000-S0S');
    $('.input-ip').mask('0ZZ.0ZZ.0ZZ.0ZZ',
      {
        translation:
        {
          'Z':
          {
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
        [
          {
            'font': []
          }],
        [
          {
            'header': [1, 2, 3, 4, 5, 6, false]
          }],
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [
          {
            'header': 1
          },
          {
            'header': 2
          }],
        [
          {
            'list': 'ordered'
          },
          {
            'list': 'bullet'
          }],
        [
          {
            'script': 'sub'
          },
          {
            'script': 'super'
          }],
        [
          {
            'indent': '-1'
          },
          {
            'indent': '+1'
          }], // outdent/indent
        [
          {
            'direction': 'rtl'
          }], // text direction
        [
          {
            'color': []
          },
          {
            'background': []
          }], // dropdown with defaults from theme
        [
          {
            'align': []
          }],
        ['clean'] // remove formatting button
      ];
      var quill = new Quill(editor,
        {
          modules:
          {
            toolbar: toolbarOptions
          },
          theme: 'snow'
        });
    }
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
      'use strict';
      window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
          form.addEventListener('submit', function (event) {
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
      var uppy = Uppy.Core().use(Uppy.Dashboard,
        {
          inline: true,
          target: uptarg,
          proudlyDisplayPoweredByUppy: false,
          theme: 'dark',
          width: 770,
          height: 210,
          plugins: ['Webcam']
        }).use(Uppy.Tus,
          {
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
  <script>
  
        </script>
        <script>
    // Mostrar las opciones al hacer clic en el botón
    document.getElementById('periodoDropdown').addEventListener('click', function() {
        const filterOptions = document.getElementById('filterOptions');
        filterOptions.classList.toggle('d-none'); // Alternar la visibilidad de las opciones
    });

    // Manejar el evento de cambio en el combo box
    document.getElementById('periodoSelect').addEventListener('change', function() {
        const selectedPeriod = this.value;
        console.log("Periodo seleccionado:", selectedPeriod);
        // Aquí puedes realizar más acciones si lo deseas
    });
</script>
</body>

</html>