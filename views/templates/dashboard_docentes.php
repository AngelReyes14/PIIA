<?php
include('../../models/session.php');
include('../../controllers/db.php'); // Conexión a la base de datos
include('../../models/consultas.php'); // Incluir la clase de consultas
include('aside.php');

// Crear instancia de Consultas
$consultas = new Consultas($conn);

// Obtener el ID del usuario actual y el tipo de usuario desde la sesión
$idusuario = (int) $_SESSION['user_id'];
// Obtener datos de la carrera
$carreraData = $consultas->datosCarreraPorId($idusuario);
$carreraId = $carreraData ? $carreraData['carrera_id'] : null;

$nombreCarrera = 'Sin división';
if ($carreraId) {
    $carreraInfo = $consultas->obtenerNombreCarreraPorId($carreraId);
    $nombreCarrera = $carreraInfo ? htmlspecialchars($carreraInfo['nombre_carrera']) : 'Sin división';
}

$tipoUsuarioId = $consultas->obtenerTipoUsuarioPorId($idusuario);
$imgUser  = $consultas->obtenerImagen($idusuario);


// Validar tipo de usuario
if (!$tipoUsuarioId) {
    die("Error: Tipo de usuario no encontrado para el ID proporcionado.");
}

// Si el tipo de usuario es 1, forzar visualización solo de su perfil
if ($tipoUsuarioId === 1) {
    $_GET['idusuario'] = $idusuario;
}

// Obtener usuario y carrera
$idusuario = isset($_GET['idusuario']) ? intval($_GET['idusuario']) : 1;
$usuario = $consultas->obtenerUsuarioPorId($idusuario);
$nombreDocente = isset($usuario['nombre_usuario']) && isset($usuario['apellido_p']) && isset($usuario['apellido_m']) 
    ? htmlspecialchars($usuario['nombre_usuario'] . ' ' . $usuario['apellido_p'] . ' ' . $usuario['apellido_m']) 
    : 'Nombre no disponible';

$carrera = $consultas->obtenerCarreraPorUsuarioId($idusuario);
$cuerpoColegiado = $consultas->obtenerCuerpoColegiadoPorUsuario($idusuario);

$diasEconomicosTotales = 3; // Máximo permitido
$diasEconomicosTomados = $consultas->obtenerDiasEconomicosTomados($idusuario);
$diasEconomicos = $consultas->obtenerDiasEconomicos($idusuario);


// Redirigir si no se encuentra el usuario
if (!$usuario) {
    header("Location: ?idusuario=1");
    exit;
}

// Fusionar datos de usuario y carrera
if ($carrera) {
    $usuario = array_merge($usuario, $carrera);
}

// Calcular antigüedad del usuario
$fechaContratacionDate = new DateTime($usuario["fecha_contratacion"]);
$fechaActual = new DateTime();
$usuario['antiguedad'] = $fechaContratacionDate->diff($fechaActual)->y;

// Cerrar sesión si se envió el formulario
if (isset($_POST['logout'])) {
    $sessionManager->logoutAndRedirect('../templates/auth-login.php');
}

// Nombre de carrera
$nombreCarrera = isset($carrera['nombre_carrera']) ? htmlspecialchars($carrera['nombre_carrera']) : 'Sin división';

// Obtener listas de carreras y períodos
$carreras = $consultas->obtenerCarreras();
$periodos = $consultas->obtenerPeriodos();
// Obtener el último período
$periodoReciente = $consultas->obtenerPeriodoReciente();
$periodoId = $periodoReciente['periodo_id'];

// Obtener información de tutoría para el usuario actual
$tutoria = $consultas->obtenerTutoriaPorUsuario($idusuario, $periodoId);

if (isset($tutoria['error'])) {
    // Manejar el caso donde no se encontró información de tutoría
    $nombreGrupo = 'No asignado';
    $diaTutoria = 'No asignado';
} else {
    $nombreGrupo = htmlspecialchars($tutoria['nombre_grupo']);
    $diaTutoria = htmlspecialchars($tutoria['nombre_dia']);
}


$certificaciones = $consultas->obtenerCertificaciones();
$certificacionesusuarios = $consultas->obtenerCertificacionesPorUsuario($idusuario);
$meses = $consultas->obtenerMeses();

// Validar que realmente haya un período disponible
if (!isset($periodoReciente['periodo_id'])) {
    die("Error: No se encontró un período activo.");
}

// Extraer solo el ID del período
$periodoId = $periodoReciente['periodo_id'];

// Obtener horas del usuario autenticado solo del último período
$horas = $consultas->obtenerHorasMaterias($idusuario, $periodoId);
$horas_tutorias = $horas['horas_tutorias'];
$horas_apoyo = $horas['horas_apoyo'];
$horas_frente_grupo = $horas['horas_frente_grupo'];

// Consultar incidencias del usuario
$query = "SELECT motivo, dia_incidencia FROM incidencia_has_usuario WHERE usuario_usuario_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $idusuario);
$stmt->execute();
$avisos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

// Crear una instancia de GraficaEvaluacion
$evaluacionDocente = new GraficaEvaluacion($conn);

// Obtener el promedio general de evaluaciones
$promedio = $evaluacionDocente->obtenerPromedioEvaluaciones();
$promedioGeneral = number_format($promedio['promedio_general'], 2); // Formatear a 2 decimales

// Obtener las evaluaciones según el tipo de usuario
if ($tipoUsuarioId === 1) {
  $resultados = $evaluacionDocente->obtenerEvaluacionesUsuario($idusuario);
} elseif ($tipoUsuarioId === 2) {
  // Obtener la carrera del usuario
  $carreraUsuario = $carrera['carrera_id'] ?? null;
  $resultados = $evaluacionDocente->obtenerEvaluacionesTodosLosDocentes($carreraUsuario);
} else {
  $resultados = [];
}

$resultados_json = json_encode($resultados);

?>

<?php
// Si tipoUsuario es 2 y aún no tiene idusuario en la URL, lo establecemos antes de renderizar la página.
if ($tipoUsuarioId === 2 && !isset($_GET['idusuario']) && !empty($idusuario)) {
    header("Location: dashboard_docentes.php?idusuario=" . urlencode($idusuario));
    exit();
}
?>
<script>
  economicDays = <?php echo json_encode($diasEconomicos, JSON_PRETTY_PRINT); ?>;
  console.log("Días Económicos desde PHP:", economicDays);
</script>




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
<?php if ($tipoUsuarioId === 5 || $tipoUsuarioId == 3 || $tipoUsuarioId == 4): ?>
    <!-- Filtro de carreras -->
    <div class="card text-center">
        <div class="card-body">
            <h5 class="card-title">Filtrado por carrera</h5>
            <div class="filter-container">
                <select id="carreraSelect" class="form-control" style="max-width: 300px; margin: auto;">
                    <option selected disabled>Seleccionar carrera</option>
                    <?php foreach ($carreras as $carrera): ?>
                        <option value="<?= htmlspecialchars($carrera['carrera_id']) ?>">
                            <?= htmlspecialchars($carrera['nombre_carrera']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
<?php endif; ?>

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
                                    <strong class="name-line">Número de Empleado:</strong> <?= htmlspecialchars($usuario["carrera_carrera_id"]) ?> <br>
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
function toggleCampos() {
  var selectElement = document.getElementById('incidencias');
  var incidenciasDiv = document.getElementById('incidenciasDiv');
  var otroDiv = document.getElementById('otroDiv');
  var otroInput = document.getElementById('otro');
  var documentDiv = document.getElementById('documentDiv');
  var archivoInput = document.getElementById('documentInput');
  var selectedValue = selectElement.value;

  // Manejo del campo "Otro"
  if (selectedValue == "7") {
    incidenciasDiv.classList.remove("col-md-12");
    incidenciasDiv.classList.add("col-md-6");
    otroDiv.style.display = "block";
    otroInput.disabled = false;
  } else {
    incidenciasDiv.classList.remove("col-md-6");
    incidenciasDiv.classList.add("col-md-12");
    otroDiv.style.display = "none";
    otroInput.disabled = true;
    otroInput.value = "";
  }

  // Manejo del campo "Seleccionar documento"
  if (selectedValue == "1") {
    documentDiv.style.display = "block";
    archivoInput.disabled = false;
  } else {
    documentDiv.style.display = "none";
    archivoInput.disabled = true;
    archivoInput.value = "";
  }
}
let usuarios = []; // Define la variable en un ámbito accesible

document.addEventListener("DOMContentLoaded", function () {
    const idcarrera = <?= json_encode($carreraId) ?>;
    const carouselContent = document.getElementById('carouselContent');
    const btnSiguiente = document.getElementById('siguiente');
    const btnAnterior = document.getElementById('anterior');
    let currentIndex = 0;

    function cargarUsuariosPorCarrera() {
    $.ajax({
        url: '../templates/filtrarPorCarrera.php',
        type: 'POST',
        data: { carrera_id: idcarrera },
        dataType: 'json',
        success: function(response) {
            if (response && response.length > 0) {
                usuarios = response; // Asigna la respuesta a la variable global
                currentIndex = 0;

                // Primero llenamos el select
                llenarSelectDocente();

                // Luego actualizamos el carrusel para que se muestre el primer usuario
                actualizarCarrusel();
            } else {
                carouselContent.innerHTML = `<p class="text-center text-danger">No hay docentes registrados en esta carrera.</p>`;
                btnSiguiente.disabled = true;
                btnAnterior.disabled = true;
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al obtener datos de los docentes:", error);
        }
    });
}

function llenarSelectDocente() {
    const selectDocente = document.getElementById('usuario_usuario_id');
    selectDocente.innerHTML = ''; // Limpiar opciones previas

    // Agregar una opción por defecto si no hay usuarios
    if (usuarios.length === 0) {
        selectDocente.innerHTML += '<option value="">Selecciona un docente</option>';
    }

    usuarios.forEach(usuario => {
        const option = document.createElement('option');
        option.value = usuario.id_usuario || usuario.usuario_id; // Ajusta según la estructura de la respuesta AJAX
        option.textContent = `${usuario.nombre_usuario} ${usuario.apellido_p} ${usuario.apellido_m}`; // Nombre completo
        selectDocente.appendChild(option);
    });

    // Si hay usuarios, selecciona el primero automáticamente
    if (usuarios.length > 0) {
        selectDocente.value = usuarios[currentIndex].id_usuario || usuarios[currentIndex].usuario_id; // Selecciona el primer usuario
    }
}


    function actualizarCarrusel() {
    carouselContent.innerHTML = ''; // Limpiar contenido previo


    if (usuarios.length === 0) return;

    const usuario = usuarios[currentIndex];

    // Obtener el ID correcto del usuario
    const userId = usuario.id_usuario || usuario.usuario_id; // Ajusta según la estructura de la respuesta AJAX

    // Actualizar el valor del select de docentes para que coincida con el usuario actual
    const selectDocente = document.getElementById('usuario_usuario_id');
    selectDocente.value = userId; // Seleccionar automáticamente el docente actual

    // Lógica para actualizar el contenido del carrusel (igual que antes)
    const carouselItem = `
        <div class="carousel-item active">
            <div class="row">
                <div class="col-12 col-md-5 col-xl-3 text-center">
                    <strong class="name-line">Foto del Docente:</strong> <br>
                    <img src="../${usuario.imagen_url}" alt="Imagen del docente" class="img-fluid tamanoImg rounded">
                </div>
                <div class="col-12 col-md-7 col-xl-9 data-teacher mb-0">
                    <p class="teacher-info h4">
                        <strong class="name-line">Docente:</strong> ${usuario.nombre_usuario} ${usuario.apellido_p} ${usuario.apellido_m}<br>
                        <strong class="name-line">Edad:</strong> ${usuario.edad} años <br>
                        <strong class="name-line">Fecha de contratación:</strong> ${usuario.fecha_contratacion} <br>
                        <strong class="name-line">División Adscrita:</strong> <?= $nombreCarrera ?> <br>
                        <strong class="name-line">Número de Empleado:</strong> ${usuario.numero_empleado} <br>
                        <strong class="name-line">Grado académico:</strong> ${usuario.grado_academico} <br>
                        <strong class="name-line">Cédula:</strong> ${usuario.cedula} <br>
                        <strong class="name-line">Correo:</strong> ${usuario.correo} <br>
                    </p>
                </div>

            </div>
        </div>
    `;

    carouselContent.innerHTML = carouselItem;

    // Habilitar/deshabilitar botones según el índice actual
    btnAnterior.disabled = currentIndex === 0;
    btnSiguiente.disabled = currentIndex === usuarios.length - 1;
}


    // Evento para avanzar al siguiente docente
    btnSiguiente.addEventListener("click", function () {
        if (currentIndex < usuarios.length - 1) {
            currentIndex++;
            actualizarCarrusel();
        }
    });

    // Evento para retroceder al docente anterior
    btnAnterior.addEventListener("click", function () {
        if (currentIndex > 0) {
            currentIndex--;
            actualizarCarrusel();
        }
    });

    // Cargar los docentes al cargar la página
    cargarUsuariosPorCarrera();
});

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
        <h3 class="h5">DÍAS ECONÓMICOS TOTALES</h3>
        <div class="text-verde"><?php echo $diasEconomicosTotales; ?></div>
    </div>
    <div class="card-body-calendar box-shadow-div">
        <h3 class="h5">DÍAS ECONÓMICOS TOMADOS</h3>
        <div class="text-verde"><?php echo $diasEconomicosTomados; ?></div>
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


      
<?php if ($usuario && $usuario['tipo_usuario_tipo_usuario_id'] == 2): ?>
  <div class="container-fluid">
    <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile flag-div">
        DESARROLLO ACADÉMICO
    </div>
    <div class="card box-shadow-div p-4">
        <h2 class="text-center">Evaluación Docente de Todos los Usuarios</h2>

        <!-- Filtro por período -->
        <div class="row justify-content-center my-2">
            <div class="col-auto">
                <label for="filtroPeriodo">Filtrar por período:</label>
                <select id="filtroPeriodo" class="form-control">
                    <option value="todos">Todos</option>
                </select>
            </div>
        </div>

        <!-- Contenedor del gráfico -->
        <div class="my-4">
            <div class="chart-container" style="position: relative; width: 100%; height: 400px;">
                <canvas id="evaluacionChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const datosEvaluacion = <?php echo $resultados_json; ?>;
    console.log('Datos de evaluación:', datosEvaluacion);

    const selectPeriodo = document.getElementById('filtroPeriodo');

    const periodosUnicos = [...new Set(datosEvaluacion.map(item => item.periodo_descripcion))];

    periodosUnicos.forEach(periodo => {
        const option = document.createElement('option');
        option.value = periodo;
        option.textContent = periodo;
        selectPeriodo.appendChild(option);
    });

    let evaluacionChart = null; // Variable global para la gráfica

    function actualizarGrafico(periodoSeleccionado) {
        const datosFiltrados = periodoSeleccionado === "todos"
            ? datosEvaluacion
            : datosEvaluacion.filter(item => item.periodo_descripcion === periodoSeleccionado);

        if (datosFiltrados.length === 0) {
            console.warn('No hay datos para este período.');
            return;
        }

        // Separar nombre y apellidos y organizarlos en líneas
        const labels = datosFiltrados.map(item => {
            const nombreCompleto = item.nombre_completo.trim().split(" ");
            const nombre = nombreCompleto[0]; // Primer nombre
            const apellidoPaterno = nombreCompleto[1] || ""; // Segundo elemento como apellido paterno
            const apellidoMaterno = nombreCompleto[2] || ""; // Tercer elemento como apellido materno

            return `${nombre}\n${apellidoPaterno}\n${apellidoMaterno}`; // Formato en 3 líneas
        });

        const evaluacionTecnicaData = datosFiltrados.map(item => parseFloat(item.evaluacionTECNM));
        const evaluacionEstudiantilData = datosFiltrados.map(item => parseFloat(item.evaluacionEstudiantil));

        if (evaluacionChart) {
            evaluacionChart.destroy();
        }

        const ctx = document.getElementById('evaluacionChart').getContext('2d');
        evaluacionChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Evaluación Técnica',
                        data: evaluacionTecnicaData,
                        backgroundColor: 'rgba(17, 194, 56, 0.95)',
                        borderColor: 'rgb(54, 235, 111)',
                        borderWidth: 1,
                        borderRadius: 15,
                    },
                    {
                        label: 'Evaluación Estudiantil',
                        data: evaluacionEstudiantilData,
                        backgroundColor: 'rgb(16, 117, 36)',
                        borderColor: 'rgb(16, 117, 36)',
                        borderWidth: 1,
                        borderRadius: 15,
                    }
                ]
            },
            options: {
                responsive: true, // Hace el gráfico responsivo
                maintainAspectRatio: false, // Esto asegura que el gráfico se adapte al contenedor
                layout: {
                    padding: {
                        bottom: 30 // Ajusta el espacio inferior del gráfico
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Docentes'
                        },
                        ticks: {
                            autoSkip: false,
                            font: {
                                size: 8 // Reduce el tamaño de fuente
                            },
                            maxRotation: 0, // Evita la rotación de los nombres
                            minRotation: 0, // Mantiene los nombres horizontales
                            padding: 10 // Agrega espacio entre el texto y el eje
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Puntaje'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Evaluación Docente por Período'
                    }
                }
            }
        });
    }

    actualizarGrafico("todos");

    selectPeriodo.addEventListener("change", function () {
        actualizarGrafico(this.value);
    });
</script>

<?php endif; ?>



<?php if ($usuario && $usuario['tipo_usuario_tipo_usuario_id'] == 1): ?>
  <div class="container-fluid">
        <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile flag-div">
            DESARROLLO ACADÉMICO
        </div>
        <div class="card box-shadow-div p-4">
            <h2 class="text-center">Mi Evaluación Docente</h2>
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
                            <button type="button" class="btn btn-sm"><span class="fe fe-refresh-ccw fe-12 text-muted"></span></button>
                            <button type="button" class="btn btn-sm"><span class="fe fe-filter fe-12 text-muted"></span></button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contenedor del gráfico -->
            <div class="my-4">
                <canvas id="evaluacionChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Incluir Chart.js -->

    <script>
        // Convertir el JSON de PHP a un objeto JavaScript
        const datosEvaluacion = <?php echo $resultados_json; ?>;

        // Verificar en la consola los datos recibidos
        console.log('Datos de evaluación:', datosEvaluacion);

        if (datosEvaluacion.length === 0) {
            console.error('No hay datos para mostrar.');
        } else {
            // Agrupar datos por período
            const datosPorPeriodo = {};
            datosEvaluacion.forEach(item => {
                const periodo = item.periodo_descripcion; // Usamos la descripción del período
                if (!datosPorPeriodo[periodo]) {
                    datosPorPeriodo[periodo] = { nombres: [], evaluacionTecnica: [], evaluacionEstudiantil: [] };
                }
                datosPorPeriodo[periodo].nombres.push(item.nombre_completo);
                datosPorPeriodo[periodo].evaluacionTecnica.push(parseFloat(item.evaluacionTECNM));
                datosPorPeriodo[periodo].evaluacionEstudiantil.push(parseFloat(item.evaluacionEstudiantil));
            });

            console.log('Datos por período:', datosPorPeriodo);

            // Colores fijos para las evaluaciones
            const colorEvaluacionTecnica = 'rgba(17, 194, 56, 0.95)'; // Verde claro
            const colorEvaluacionEstudiantil = 'rgb(16, 117, 36)'; // Verde oscuro

            // Preparar los datos para la gráfica
            const labels = [];
            const evaluacionTecnicaData = [];
            const evaluacionEstudiantilData = [];

            Object.keys(datosPorPeriodo).forEach(periodo => {
                datosPorPeriodo[periodo].nombres.forEach((nombre, index) => {
                    labels.push(`${nombre} (${periodo})`); // Combinar nombre y período
                    evaluacionTecnicaData.push(datosPorPeriodo[periodo].evaluacionTecnica[index]);
                    evaluacionEstudiantilData.push(datosPorPeriodo[periodo].evaluacionEstudiantil[index]);
                });
            });

            // Crear la gráfica
            const ctx = document.getElementById('evaluacionChart').getContext('2d');
            const evaluacionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels, // Nombres de los docentes con el período
                    datasets: [
                        {
                            label: 'Evaluación Técnica',
                            data: evaluacionTecnicaData,
                            backgroundColor: colorEvaluacionTecnica,
                            borderColor: 'rgb(54, 235, 111)',
                            borderWidth: 1,
                            borderRadius: 15,
                        },
                        {
                            label: 'Evaluación Estudiantil',
                            data: evaluacionEstudiantilData,
                            backgroundColor: colorEvaluacionEstudiantil,
                            borderColor: 'rgb(16, 117, 36)',
                            borderWidth: 1,
                            borderRadius: 15,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Puntaje'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Docentes (Período)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Evaluación Docente por Período'
                        }
                    }
                }
            });
        }
    </script>
<?php endif; ?>
</div>

          <div class="container-fluid mt-0">
            <div class="row">
              <div class="col-lg-6">
                <div class="d-flex flex-column">
                <div class="card box-shadow-div text-center border-5 mt-1 mb-1">
                  <div class="card-body">
                      <h2 class="font-weight-bold mb-4">Calificación promedio</h2>
                      <h1 class="text-success mb-3"><?php echo $promedioGeneral; ?></h1>
                  </div>
              </div>
              <div class="card box-shadow-div text-center border-5 mt-5 mb-5">
    <div class="card-body">
        <h2 class="font-weight-bold mb-4">Grupo tutor</h2>
        <h1 class="text-success mb-3"><?php echo $nombreGrupo; ?></h1>
    </div>
</div>

<div class="card box-shadow-div text-center border-5 mt-3 mb-3">
    <div class="card-body">
        <h2 class="font-weight-bold mb-4">Día de tutoría</h2>
        <h1 class="text-success mb-3"><?php echo $diaTutoria; ?></h1>
    </div>
</div>
              </div>
              </div>

              <!--------Inicio de la tabla ---------->
              <div class="col-lg-6">
    <div class="card box-shadow-div text-center border-5 mt-1">
        <div class="card-body">
              <div class="d-flex justify-content-center align-items-center mb-3">
                <p class="titulo-grande"><strong>Capacitación disciplinaria</strong></p>
              </div>
              <div class="table-responsive">
                <table class="table datatables" id="dataTable-certificaciones">
                  <thead>
                    <tr>
                      <th>Certificación</th>
                      <th>Nombre del Certificado</th>
                      <th>Mes</th> <!-- Nueva columna para los meses -->
                      <th>Certificado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($certificacionesusuarios as $certificacionusuario): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($certificacionusuario['certificacion_descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($certificacionusuario['nombre_certificado']); ?></td>
                        <td><?php echo htmlspecialchars($certificacionusuario['nombre_mes']); ?></td>
                        <!-- Mostrar el mes -->
                        <td class="text-center">
                          <?php if (!empty($certificacionusuario['url'])): ?>
                            <?php
                            $correctFilePath = str_replace('views/', '', $certificacionusuario['url']);
                            ?>
                            <a href="<?php echo $correctFilePath; ?>" target="_blank" class="btn btn-sm btn-primary">Ver
                              Certificado</a>
                          <?php else: ?>
                            No disponible
                          <?php endif; ?>
                        </td>
                        <td class="text-center">
                          <div class="d-flex justify-content-center">
                          </div>
                        </td>

                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>

      </div> <!---- fin de la card principál------>
      <div class="container-fluid">
  <div id="contenedor">
    <!-- Tarjeta principal -->
    <div class="card box-shadow-div p-4 mb-3">
      <div class="logo-container row align-items-center">
        <div class="logo-institucional col-md-2">
          <!-- Espacio para el logo institucional -->
          <img src="assets/images/logo.png" alt="Logo Institucional" class="img-fluid">
        </div>
        <div class="titulo-container col-md-8 text-center text-md-start">
          <h1 class="text-wrap">TECNOLÓGICO DE ESTUDIOS SUPERIORES DE CHIMALHUACÁN</h1>
        </div>
        <div class="form-group col-md-2">
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
    <select class="form-control" id="usuario_usuario_id" name="usuario_usuario_id" required disabled>
        <option value="">Selecciona un docente</option>
        <?php foreach ($usuarios as $usuario): ?>
            <option value="<?php echo $usuario['usuario_id']; ?>" <?= ($usuario['usuario_id'] == $usuarioActualId) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($usuario['nombre_usuario'] . ' ' . $usuario['apellido_p'] . ' ' . $usuario['apellido_m']); ?>
            </option>
        <?php endforeach; ?>

    </select>
</div>

        </div>
        <div class="col-md-6">
    <!-- Carrera -->
    <div class="form-group mt-2">
        <label for="carrera_carrera_id" class="form-label">Carrera:</label>
        <select class="form-control" id="carrera_carrera_id" name="carrera_carrera_id" required>
    <option value="">Selecciona una carrera</option>
    <?php foreach ($carreras as $carrera): ?>
        <option value="<?php echo $carrera['carrera_id']; ?>" <?= ($carrera['carrera_id'] == $carreraId) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($carrera['nombre_carrera']); ?>
        </option>
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
        <button id="downloadPDF" onclick="generatePDF()" class="btn btn-primary">Descargar PDF</button>
      </div>

    </div>
  </div>
</div>

<script>
    const tipoUsuario = <?php echo json_encode($tipoUsuarioId); ?>;
</script>


      <!-- Incluir la librería html2pdf.js antes de tu archivo de script personalizado -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>




    <script src="js/horario_vista.js"></script>
          <div class="col-12 mb-4">
            <div class="card shadow">
              <div class="card-header">
                <strong class="card-title mb-0">Desglose de horas</strong>
              </div>
              <div class="card-body">
              <div id="barChart" 
      data-docente="<?php echo isset($usuario['nombre_usuario']) && isset($usuario['apellido_p']) && isset($usuario['apellido_m']) 
    ? htmlspecialchars($usuario['nombre_usuario'] . ' ' . $usuario['apellido_p'] . ' ' . $usuario['apellido_m']) 
    : 'Nombre no disponible'; ?>"
    data-tutorias="<?php echo $horas_tutorias; ?>" 
    data-apoyo="<?php echo $horas_apoyo; ?>" 
    data-frente="<?php echo $horas_frente_grupo; ?>">
</div>
<div id="total-horas" style="margin-top: 10px; font-weight: bold; text-align: center;"></div>


              </div> <!-- /.card-body -->
            </div> <!-- /.card -->

          </div> <!-- /. col -->
        <!---------------- Termina la parte de direccion academica -------------->

        <div class="row mb-3">
          <!-- Card de Días Económicos Totales y Tomados -->
          <div class="col-lg-12 mb-3">
        <div class="card box-shadow-div text-center border-9">
            <div class="card-body">
                <h3 class="font-weight-bold mb-0">CUERPO COLEGIADO</h3>
                <h1 class="text-success">
                    <?php echo isset($cuerpoColegiado['descripcion']) ? htmlspecialchars($cuerpoColegiado['descripcion']) : 'No disponible'; ?>
                </h1>
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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