<?php
include('../../models/session.php');
include('../../controllers/db.php'); // Asegúrate de que este archivo incluya la conexión a la base de datos.
include('../../models/consultas.php'); // Incluir la clase de consultas
include('aside.php');
// Crear una instancia de la clase Consultas
$consultas = new Consultas($conn);

// Obtenemos el idusuario actual (si no está definido, iniciamos en 1)
$idusuario = isset($_GET['idusuario']) ? intval($_GET['idusuario']) : 1;

// Llamamos al método para obtener el usuario actual
$usuario = $consultas->obtenerUsuarioPorId($idusuario);

// Llamamos al método para obtener la carrera del usuario
$carrera = $consultas->obtenerCarreraPorUsuarioId($idusuario);
$carreras = $consultas->obtenerCarreras();


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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+g6Y1Ch6JvWc1R6FddRZnYf4M4w3LTpVj1q9Vkp8" crossorigin="anonymous"></script>

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

<<<<<<< HEAD

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
          <p class="text-muted nav-heading mt-4 mb-1">
            <span>Desarrollo Académico</span>
          </p>
          <li class="nav-item w-100">
            <a class="nav-link" href="desarrollo_academico_docentes.php">
              <i class="fe fe-calendar fe-16"></i>
              <span class="ml-3 item-text">Docentes</span>
            </a>
          </li>
          <p class="text-muted nav-heading mt-4 mb-1">
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
              <a class="nav-link pl-3" href="formulario_usuario.php"><span class="ml-1 item-text">Usuarios</span></a>
            </li>

            <li class="nav-item w-100">
              <a class="nav-link pl-3" href="form_usuarios-carreras.php"><span class="ml-1 item-text">Asigancion de Carreras</span></a>
            </li>
          </ul>
        </ul>
      </nav>
    </aside>

    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">Filtrado por División</h5>
        <div class="filter-container" style="position: relative; display: inline-block;">
          <button id="filterBtn" class="btn btn-primary">Seleccionar División</button>
          <div id="filterOptions" class="filter-options d-none">
            <div class="dropdown-item" data-value="Ingeniería en Sistemas Computacionales"> Sistemas Computacionales</div>
            <div class="dropdown-item" data-value="Administración">Administración</div>
            <div class="dropdown-item" data-value="Química">Química</div>
          </div>
        </div>
      </div>
    </div>

    <main role="main" class="main-content">
      <!---Div de imagen de perfil (falta darle estilos a las letras)----------------------->
      <div class="container-fluid mb-3">
        <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile flag-div ">
          PERFIL DOCENTE
        </div>
        <div class="row justify-content-center mb-0">
          <div class="col-12">
            <div class="row">
              <div class="col-md-12 col-xl-12 mb-0">
                <div class="card box-shadow-div text-red rounded-lg">
                  <div class="row align-items-center">
                    <button class="carousel-control-next col-1" type="button" data-bs-target="#teacherCarousel" data-bs-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">></span>
                    </button>
                    <div class="col-12 col-md-5 col-xl-3 text-center">
                      <img src="../templates/assets/images/Perfil_ejemplo.png" alt="img-perfil" class="img-perfil">
                    </div>
                    <button class="carousel-control-prev col-1" type="button" data-bs-target="#teacherCarousel" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">
                        << /span>
                    </button>
                    <div class="col-12 col-md-7 col-xl-9 data-teacher mb-0">
                      <p class="teacher-info h4">
                        <strong class="name-line">Docente:</strong> Ignacio Gómez Gómez <br>
                        <strong class="name-line">Edad:</strong> 35 años <br>
                        <strong class="name-line">Fecha de contratación:</strong> 02/04/2020 <br>
                        <strong class="name-line">Antigüedad:</strong> 4 años <br>
                        <strong class="name-line">División Adscrita:</strong> Ingeniería en Sistemas Computacionales <br>
                        <strong class="name-line">Número de Empleado:</strong> 202045200981A <br>
                        <strong class="name-line">Grado académico:</strong> Ing. en Sistemas Computacionales <br>
                        <strong class="name-line">Cédula:</strong> 16416AECQ411
                      </p>
                    </div>
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
          <h2 class="text-center">Evaluación Estudiantil</h2>
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

          <div class="card box-shadow-div p-4">
            <h2 class="text-center">Evaluación TECNM</h2>
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
            <!-- charts-->
            <div class="container-fluid">
              <div class="row my-4">
                <div class="col-md-12">
                  <div class="chart-box rounded">
                    <div id="columnChart2"></div>
                  </div>
                </div> <!-- .col -->
              </div> <!-- end section -->
            </div>

            <div class="container-fluid mt-0">
              <div class="row">
                <div class="col-lg-4">
                  <div class="d-flex flex-column">
                    <div class="card box-shadow-div text-center border-5 mt-1 mb-1">
                      <div class="card-body">
                        <h2 class="font-weight-bold mb-4">Calificación promedio</h2>
                        <h1 class="text-success mb-0">85.30</h1>
                      </div>
                    </div>

                    <div class="card box-shadow-div text-center border-5 mt-5 mb-5">
                      <div class="card-body">
                        <h2 class="font-weight-bold mb-4">Grupo tutor</h2>
                        <h1 class="text-success mb-0">8ISC22</h1>
                      </div>
                    </div>

                    <div class="card box-shadow-div text-center border-5 mt-3 mb-3">
                      <div class="card-body">
                        <h2 class="font-weight-bold mb-4">Día de tutoría</h2>
                        <h1 class="text-success mb-0">Lunes</h1>
=======
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
>>>>>>> c47c6c9d8efab812a955ffc565da9b1a5ee6145a
                      </div>
                    </div>
                    <!-- Más elementos del carrusel se generarán dinámicamente -->
                  </div>
                </div>
<<<<<<< HEAD

                <!--------Inicio de la tabla ---------->
                <!-- Columna para la tabla -->
                <div class="col-lg-8">
                  <div class="card box-shadow-div text-center border-5 mt-1">
                    <div class="card-body">
                      <div class="row">
                        <!-- Recent orders -->
                        <div class="col-12">
                          <h4 class="mb-3">Capacitación disciplinaria</h4>
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


                <!--------Inicio de la tabla ---------->
                <!-- Columna para la tabla -->
                <div class="col-lg-12">
                  <div class="card box-shadow-div text-center border-5 mt-1">
                    <div class="card-body">
                      <div class="row">
                        <!-- Recent orders -->
                        <div class="col-12">
                          <h4 class="mb-3">Capacitación pédagogica</h4>
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
=======
                <button class="carousel-control-next col-1 btn btn-primary" type="button" id="siguiente">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden"></span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!------>
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




      <div class="container-fluid">
        <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile flag-div ">
          DESARROLLO ACADÉMICO
        </div>
        <div class="card box-shadow-div p-4">
          <h2 class="text-center">Evaluación Estudiantil</h2>
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

          <div class="card box-shadow-div p-4">
            <h2 class="text-center">Evaluación TECNM</h2>
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
            <!-- charts-->
            <div class="container-fluid">
              <div class="row my-4">
                <div class="col-md-12">
                  <div class="chart-box rounded">
                    <div id="columnChart2"></div>
                  </div>
                </div> <!-- .col -->
              </div> <!-- end section -->
            </div>

            <div class="container-fluid mt-0">
              <div class="row">
                <div class="col-lg-4">
                  <div class="d-flex flex-column">
                    <div class="card box-shadow-div text-center border-5 mt-1 mb-1">
                      <div class="card-body">
                        <h2 class="font-weight-bold mb-4">Calificación promedio</h2>
                        <h1 class="text-success mb-0">85.30</h1>
                      </div>
                    </div>

                    <div class="card box-shadow-div text-center border-5 mt-5 mb-5">
                      <div class="card-body">
                        <h2 class="font-weight-bold mb-4">Grupo tutor</h2>
                        <h1 class="text-success mb-0">8ISC22</h1>
                      </div>
                    </div>

                    <div class="card box-shadow-div text-center border-5 mt-3 mb-3">
                      <div class="card-body">
                        <h2 class="font-weight-bold mb-4">Día de tutoría</h2>
                        <h1 class="text-success mb-0">Lunes</h1>
                      </div>
                    </div>
                  </div>
                </div>

                <!--------Inicio de la tabla ---------->
                <!-- Columna para la tabla -->
                <div class="col-lg-8">
                  <div class="card box-shadow-div text-center border-5 mt-1">
                    <div class="card-body">
                      <div class="row">
                        <!-- Recent orders -->
                        <div class="col-12">
                          <h4 class="mb-3">Capacitación disciplinaria</h4>
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


                <!--------Inicio de la tabla ---------->
                <!-- Columna para la tabla -->
                <div class="col-lg-12">
                  <div class="card box-shadow-div text-center border-5 mt-1">
                    <div class="card-body">
                      <div class="row">
                        <!-- Recent orders -->
                        <div class="col-12">
                          <h4 class="mb-3">Capacitación pédagogica</h4>
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
          </div>
        </div>
      </div>
    </div>


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
>>>>>>> c47c6c9d8efab812a955ffc565da9b1a5ee6145a
</body>

</html>