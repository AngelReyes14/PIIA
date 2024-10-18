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
  <link src="js/apps.js">
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
          <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="avatar avatar-sm mt-2">
              <img src="./assets/avatars/face-1.jpg" alt="..." class="avatar-img rounded-circle">
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#">Profile</a>
            <a class="dropdown-item" href="#">Settings</a>
            <a class="dropdown-item" href="#">Activities</a>
            <form method="POST" action="" id="logoutForm">
              <button class="dropdown-item" type="submit" name="logout">Cerrar sesión</button>
            </form>
          </div>
        </li>
      </ul>
    </nav>

    
    <aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
          <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
            <i class="fe fe-x"><span class="sr-only"></span></i>
          </a>
          <nav class="vertnav navbar navbar-light">
            <!-- nav bar -->
            <div class="w-100 mb-4 d-flex">
              <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="./index.php">
                <img src="../templates/assets/icon/icon_piia.png" class="imgIcon">
              </a>
            </div>
            <ul class="navbar-nav flex-fill w-100 mb-2">
              <li class="nav-item w-100">
                <a class="nav-link" href="index.php">
                  <i class="fe fe-calendar fe-16"></i>
                  <span class="ml-3 item-text">Inicio</span>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a href="#dashboard" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                  <i class="fe fe-home fe-16"></i>
                  <span class="ml-3 item-text">Dashboard</span><span class="sr-only">(current)</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="dashboard">
                  <li class="nav-item">
                    <a class="nav-link pl-3" href="./dashboard_docentes.php"><span
                        class="ml-1 item-text">Docentes</span></a>
                  </li>
                  <li class="nav-item active">
                    <a class="nav-link pl-3" href="./dashboard_carreras.php"><span class="ml-1 item-text">Carrera</span></a>
                  </li>
                  
                </ul>
              </li>
            </ul>
            <p class="text-muted nav-heading mt-4 mb-1">
              <span>Recursos humanos</span>
            </p>
            <ul class="navbar-nav flex-fill w-100 mb-2">
              <li class="nav-item w-100">
                <a class="nav-link" href="recursos_humanos_empleados.php">
                  <i class="fe fe-calendar fe-16"></i>
                  <span class="ml-3 item-text">Empleados</span>
                </a>
              </li>
              <p class ="text-muted nav-heading mt-4 mb-1">
                <span>Desarrollo Académico</span>
              </p>
              <li class="nav-item w-100">
                <a class="nav-link" href="desarrollo_academico_docentes.php">
                  <i class="fe fe-calendar fe-16"></i>
                  <span class="ml-3 item-text">Docentes</span>
                </a>
              </li>
              <p class ="text-muted nav-heading mt-4 mb-1">
                <span>Registros</span>
              </p>
              <ul class="navbar-nav flex-fill w-100 mb-2">
                  <li class="nav-item w-100">
                    <a class="nav-link pl-3" href="form_materia.php"><span
                        class="ml-1 item-text">Materias</span></a>
                  </li>
                  <li class="nav-item w-100">
                    <a class="nav-link pl-3" href="formulario_grupo.php"><span class="ml-1 item-text">Grupos</span></a>
                  </li>
                  <li class="nav-item w-100">
                    <a class="nav-link pl-3" href="form_carrera.php"><span class="ml-1 item-text">Carreras</span></a>
                  </li>
                  <li class="nav-item w-100">
                    <a class ="nav-link pl-3" href="formulario_usuario.php"><span class="ml-1 item-text">Usuarios</span></a>
                  </li>
                </ul>
              </ul>
          </nav>
        </aside>

     <!---Div de imagen de perfil (falta darle estilos a las letras)----------------------->
     <div class="card text-center">
  <div class="card-body">
    <h5 class="card-title">Filtrado por División</h5>
    <div class="filter-container" style="position: relative; display: inline-block;">
      <button id="filterBtn" class="btn btn-primary">Seleccionar División</button>
      <div id="filterOptions" class="filter-options d-none">
        <div class="dropdown-item" data-value="Ingeniería en Sistemas Computacionales">Ingeniería en Sistemas Computacionales</div>
        <div class="dropdown-item" data-value="Administración">Administración</div>
        <div class="dropdown-item" data-value="Química">Química</div>
      </div>
    </div>
  </div>
</div>
        
    <main role="main" class="main-content">
      <!---Div de imagen de perfil (falta darle estilos a las letras)----------------------->
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

      <!---Parte de recursos humanos --->
      <div class="container-fluid mt-2">
        <div class="mb-3 mt-0 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile flag-div ">
          RECURSOS HUMANOS
        </div>
        <!-- Tarjeta principal -->
        <div class="card shadow-lg p-4 mb-3">
          <div class="wrapper">
            <div class="container-fluid">
              <div class="row">
                <div class="col-12">
                  <!-- Título principal -->
                  <div class="row align-items-center my-3">
                  </div>
    <!-- Filtros -->
    <div class="container-filter mb-3 d-flex flex-wrap justify-content-center">
      <!-- Filtro de Periodo -->
      <div class="card-body-filter period-filter box-shadow-div mx-1 mb-2 position-relative">
        <button class="btn-filter d-flex align-items-center">
          <span class="fe fe-24 fe-filter me-2"></span>
          <span class="filter-label" data-placeholder="Periodo">Periodo</span>
        </button>
        <div class="filter-options position-absolute top-100 start-0 bg-white border shadow-sm d-none">
          <ul class="list-unstyled m-0 p-2">
            <li><a href="#" data-month="8" data-year="2024" class="d-block py-1">2024-2</a></li>
            <li><a href="#" data-month="2" data-year="2024" class="d-block py-1">2024-1</a></li>
            <li><a href="#" data-month="8" data-year="2023" class="d-block py-1">2023-2</a></li>
            <li><a href="#" data-month="2" data-year="2023" class="d-block py-1">2023-1</a></li>
            <li><a href="#" data-month="8" data-year="2022" class="d-block py-1">2022-2</a></li>
            <li><a href="#" data-month="2" data-year="2022" class="d-block py-1">2022-1</a></li>
          </ul>
        </div>
      </div>
    
      <!-- Filtro de División -->
      <div class="card-body-filter division-filter box-shadow-div mx-1 mb-2 position-relative">
        <button class="btn-filter d-flex align-items-center">
          <span class="fe fe-24 fe-filter me-2"></span>
          <span class="filter-label" data-placeholder="División">División</span>
        </button>
        <div class="filter-options position-absolute top-100 start-0 bg-white border shadow-sm d-none">
          <ul class="list-unstyled m-0 p-2">
            <li><a href="#" class="d-block py-1">ISC</a></li>
            <li><a href="#" class="d-block py-1">Administracion</a></li>
            <li><a href="#" class="d-block py-1">Quimica</a></li>
          </ul>
        </div>
      </div>
    </div>   
    <!-- Sección de Incidencias -->
                  <h2 class="titulo text-center my-3 text-green">INCIDENCIAS</h2>
                  <div class="row">
                    <!-- Bloque de Días Económicos -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 order-xl-1 order-lg-2 order-md-2 order-sm-2 order-2">
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
                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 order-xl-2 order-lg-1 order-md-1 order-sm-1 order-1">
                      <div class="calendar-new box-shadow-div">
                        <div class="header d-flex justify-content-between align-items-center">
                          <div class="month"></div>
                          <div class="btns d-flex">
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
                    <!-- Avisos -->
                    <div
                      class="col-xl-3 col-lg-6 col-md-6 col-sm-12 order-xl-3 order-lg-3 order-md-3 order-sm-3 order-3"
                    >
                      <div class="card-body-calendar box-shadow-div mb-3">
                        <h3 class="h5">AVISOS</h3>
                        <div class="text-verde">3</div>
                      </div>
                      <div class="card-body-calendar ">
                        <div class="card-avisos">Faltó el día 14/02/24</div>
                        <div class="card-avisos">Faltó el día 14/03/24</div>
                        <div class="card-avisos">No dio 2 horas de clase al grupo 8ISC22</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>
      </div>

  <div class="container-fluid">
    
    
  </div> <!---- fin de la card principál------>
  <div class="container-fluid ">
    <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile flag-div mt-1 mb-2">
      DIRECCIÓN ACADÉMICA
    </div>
      <div class="row">
        <!-- Tarjeta de Gráfica de Incidencias -->
        <div class="col-12 mb-4">
          <div class="card shadow box-shadow-div h-100 carta_Informacion">
            <div class="card-header carta_Informacion">
              <strong class="card-title text-green mb-0 carta_Informacion">Gráfica de Incidencias</strong>
            </div>
            <div class="card-body">
              <!-- Donut Chart de Incidencias -->
              <div id="donutChart3" style="height: 300px;"></div> <!-- Ajusta la altura según sea necesario -->
            </div> <!-- /.card-body -->
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

        </div> <!-- /.col -->
      </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
  
  <!----Parte de dirección academica---->
  <div class="container-fluid">
    <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile flag-div mt-1 mb-2">
      DIRECCIÓN ACADÉMICA
    </div>

    <!-- Tarjeta principal -->
    <div class="card box-shadow-div p-4 mb-3">
      <h2 class="text-center text-green">Ingenieria en sistemas computacionales</h2>
      <div class="row">
        <div class="col-6 mb-0">
          <img src="" alt="Horario" class="calendar">
        </div>

        <div class="col-12 mb-4">
          <div class="card shadow">
            <div class="card-header">
              <strong class="card-title mb-0 text-green">Desglose de horas</strong>
            </div>
            <div class="card-body">
              <div id="barChart"></div>
            </div> <!-- /.card-body -->
          </div> <!-- /.card -->
          <div class="card shadow mt-3">
            <h2 class="col-md-12 mt-4 mb-4 text-center text-green">Total de horas: 40</h2>
          </div>
        </div> <!-- /. col -->
      </div>
      <!---------------- Termina la parte de direccion academica -------------->

     

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

  <script>
  // Referencias a los elementos
  // Referencias a los elementos
const filterBtn = document.getElementById('filterBtn');
const filterOptions = document.getElementById('filterOptions');

// Mostrar/Ocultar la lista al hacer clic en el botón
filterBtn.addEventListener('click', function() {
  // Alternar la clase d-none (mostrar/ocultar)
  filterOptions.classList.toggle('d-none');
});

// Manejar clics en las opciones de filtrado
filterOptions.addEventListener('click', function(event) {
  const selectedOption = event.target;
  const division = selectedOption.getAttribute('data-value');

  if (division) {
    // Cambia el texto del botón al nombre de la opción seleccionada
    filterBtn.textContent = selectedOption.textContent;

    // Ocultar el menú de opciones después de la selección
    filterOptions.classList.add('d-none');
  }
});

// Cerrar el menú si se hace clic fuera de él
document.addEventListener('click', function(e) {
  if (!filterBtn.contains(e.target) && !filterOptions.contains(e.target)) {
    filterOptions.classList.add('d-none'); // Oculta las opciones si se hace clic fuera del área
  }
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
  <script src="js/apps.js"></script>
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