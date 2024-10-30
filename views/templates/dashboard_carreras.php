<?php
include('../../models/session.php');
include('aside.php');
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
  <title>Plataforma Integradora de Informaciión Academica</title>
  <!-- Simple bar CSS -->
  <link rel="stylesheet" href="css/simplebar.css">
  <!-- Fonts CSS -->
  <link
    href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
  <!-- Icons CSS -->
  <link rel="stylesheet" href="css/feather.css">
  <link rel="stylesheet" href="css/select2.css">
  <link rel="stylesheet" href="css/dropzone.css">
  <link rel="stylesheet" href="css/uppy.min.css">
  <link rel="stylesheet" href="css/jquery.steps.css">
  <link rel="stylesheet" href="css/jquery.timepicker.css">
  <link rel="stylesheet" href="css/quill.snow.css">
  <!-- Date Range Picker CSS -->
  <link rel="stylesheet" href="css/daterangepicker.css">
  <!-- App CSS -->
  <link rel="stylesheet" href="css/app-light.css" id="lightTheme">
  <link rel="stylesheet" href="css/app-dark.css" id="darkTheme" disabled>
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
            <!-- Formulario oculto para cerrar sesión -->
            <form method="POST" action="" id="logoutForm">
              <button class="dropdown-item" type="submit" name="logout">Cerrar sesión</button>
            </form>
          </div>
        </li>
      </ul>
    </nav>
    <main role="main" class="main-content mt-5">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-12">
            <div class="row">
              <div class="col">

            </div>
              <div class="col-auto">
                <form class="form-inline">
                  <div class="form-group d-none d-lg-inline">
                    <label for="reportrange" class="sr-only">Date Ranges</label>
                    <div id="reportrange" class="px-2 py-2 text-muted">
                      <span class="small"></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <button type="button" class="btn btn-sm"><span class="fe fe-refresh-ccw fe-16 text-muted"></span></button>
                    <button type="button" class="btn btn-sm mr-2"><span class="fe fe-filter fe-16 text-muted"></span></button>
                  </div>
                </form>
              </div>
            </div>

<!-- Contenedor blanco con borde redondeado negro -->
<div class="container-fluid mt-5 bg-white rounded border border-black p-5">
  <div class="row">
    <!-- Columna Izquierda (División y Promedio evaluación docente) -->
    <div class="col-md-4">
      <!-- División de Sistemas Computacionales -->
      <div class="card p-5 text-center box-shadow-div mb-3 custom-card">
        <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div cont-div">
          División de Sistemas Computacionales
        </div>
        <!-- Contenedor para centrar la imagen -->
        <div class="d-flex justify-content-center">
          <img src="assets/images/Logo_ISC.png" alt="División de Sistemas Computacionales" class="division-img">
        </div>
      </div>

      <!-- Promedio evaluación docente -->
      <div class="card p-5 text-center box-shadow-div mb-3 custom-card">
        <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div cont-div">
          Promedio evaluación docente
        </div>
        <div class="d-flex justify-content-center" >
          <div id="radialbarWidget"></div>
        </div>
        <p>Ciclo 2024 </p> <!-- Aquí se agregará la  -->
      </div>
    </div>

<!-- Columna Derecha (Información de la carrera, texto centrado) -->
<div class="col-md-8 carta_Informacion">
  <div class="card p-5 box-shadow-div large-text h-100">
    <!-- Card verde para el título -->
    <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 ">
      <h4 class="text-center text-white mb-0">Información de la carrera</h4>
    </div>

    <!-- Contenido de la información de la carrera -->
    <div class="row">
      <div class="col-md-6">
        <p><strong class="text-green">Año de Validación:</strong><br> 2010</p>
        <p><strong class="text-green">Número de Docentes Mujeres:</strong><br> 10</p>
        <p><strong class="text-green">Número de Docentes Hombres:</strong><br> 22</p>
        <p><strong class="text-green">Número de Docentes:</strong><br> 32</p>
      </div>
      <div class="col-md-6">
        <p><strong class="text-green">Promedio de Evaluación:</strong><br> 85%</p>
        <p><strong class="text-green">Turno de Grupos:</strong><br> 20 Matutino, 10 Vespertino</p>
        <p><strong class="text-green">Grupos en la carrera:</strong><br> 30</p>
        <p><strong class="text-green">Organismo certificador:</strong><br> CACEI</p>
      </div>
    </div>

    <p><strong class="text-green">Acreditación:</strong><br> 10 Noviembre de 2020 - 10 Noviembre de 2025</p>
  </div>
</div>




<!-- Donut Chart Card -->
<div class="col-12 col-md-6 carta_Informacion">
  <div class="card shadow mb-4 box-shadow-div h-100 carta_Informacion">
    <div class="card-header carta_Informacion">
      <strong class="card-title text-green mb-0 carta_Informacion">
        Grado académico de docentes en la división
      </strong>
    </div>
    <div class="card-body text-center">
      <div id="donutChart"></div> <!-- Contenedor de la gráfica -->
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->


      <!-- Tabla de Docentes -->
      <div class="col-12 col-md-6 mt-5 carta_Informacion">
        <div class="table-section p-6 border rounded box-shadow-div h-100 carta_Informacion">
          <div class="d-flex justify-content-between align-items-center mb-3 carta_Informacion">
            <h4 class="mb-0 text-green carta_Informacion">Docentes</h4>
          </div>
          <table class="table table-striped carta_Informacion">
            <thead>
              <tr>
                <th>Nombre Docente</th>
                <th>N. Empleado</th>
                <th>Grado académico</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Juan Carlos Tinoco Villagran</td>
                <td>E6748521394</td>
                <td>Licenciatura</td>
              </tr>
              <tr>
                <td>Jose Luis Orozco Garcia</td>
                <td>E6748521393</td>
                <td>Maestría</td>
              </tr>
              <tr>
                <td>Eden Muñoz Lopez</td>
                <td>E6748521392</td>
                <td>Ingeniería</td>
              </tr>
              <tr>
                <td>Iker Ruiz Lopez</td>
                <td>E6748522192</td>
                <td>Ingeniería</td>
              </tr>
              <tr>
                <td>Abel Gallardo Garcia</td>
                <td>E6748521006</td>
                <td>Ingeniería</td>
              </tr>
              <tr>
                <td>Carlos Roberto Chia</td>
                <td>E67485213711</td>
                <td>Ingeniería</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div> <!-- /.col -->
    </div> <!-- /.row -->
  </div> <!-- /.container-fluid -->
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
            <!-- Gráfico de dona para incidencias -->
            <div id="donutChart3"></div>
            
            <!-- Tabla de incidencias -->
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
        </div> <!-- /.card -->
      </div> <!-- /.col -->
    </div> <!-- /.row -->
  </div> <!-- /.container-fluid -->
</div> <!-- /.container-fluid -->

            

<!-- Contenedor de Cursos Pedagógicos -->
<div class="container-fluid mt-5  box-shadow-div p-5">
  <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile cont-div">
    Cursos Diciplinarios
  </div>
  <div class="container-fluid p-3">
    <div class="row">
      <!-- Gráfico de Cursos Pedagógicos -->
      <div class="col-md-12">
        <div class="chart-box box-shadow-div mb-4">
          <div id="columnChart"></div> <!-- Gráfico de Cursos Pedagógicos -->
        </div>
      </div> <!-- /.col -->

      <!-- Tabla de Cursos Pedagógicos -->
      <div class="col-md-12 carta_Informacion">
        <div class="table-section p-6 border rounded box-shadow-div h-100 carta_Informacion">
          <div class="d-flex justify-content-between align-items-center mb-3 carta_Informacion">
            <h4 class="mb-0 text-green carta_Informacion">Cursos Diciplinarios</h4>
          </div>
          <table class="table table-striped carta_Informacion">
            <thead>
              <tr>
                <th>Curso</th>
                <th>Fecha</th>
                <th>Docente</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Curso de Metodologías de Enseñanza</td>
                <td>05/10/2024</td>
                <td>María López Pérez</td>
              </tr>
              <tr>
                <td>Curso de Evaluación Pedagógica</td>
                <td>12/10/2024</td>
                <td>Carlos García Martínez</td>
              </tr>
              <tr>
                <td>Curso de Innovación Educativa</td>
                <td>20/10/2024</td>
                <td>Lucía Rodríguez Sánchez</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div> <!-- /.col -->
    </div> <!-- /.row -->
  </div> <!-- /.container-fluid -->
</div> <!-- /.container-fluid -->

<!-- Contenedor de Cursos Pedagógicos -->
<div class="container-fluid mt-5  box-shadow-div p-5">
  <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile cont-div">
    Cursos Pedagógicos
  </div>
  <div class="container-fluid p-3">
    <div class="row">
      <!-- Gráfico de Cursos Pedagógicos -->
      <div class="col-md-12">
        <div class="chart-box box-shadow-div mb-4">
          <div id="columnChart2"></div> <!-- Gráfico de Cursos Pedagógicos -->
        </div>
      </div> <!-- /.col -->

      <!-- Tabla de Cursos Pedagógicos -->
      <div class="col-md-12 carta_Informacion">
        <div class="table-section p-6 border rounded box-shadow-div h-100 carta_Informacion">
          <div class="d-flex justify-content-between align-items-center mb-3 carta_Informacion">
            <h4 class="mb-0 text-green carta_Informacion ">Cursos Pedagógicos</h4>
          </div>
          <table class="table table-striped carta_Informacion">
            <thead>
              <tr>
                <th>Curso</th>
                <th>Fecha</th>
                <th>Docente</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Curso de Metodologías de Enseñanza</td>
                <td>05/10/2024</td>
                <td>María López Pérez</td>
              </tr>
              <tr>
                <td>Curso de Evaluación Pedagógica</td>
                <td>12/10/2024</td>
                <td>Carlos García Martínez</td>
              </tr>
              <tr>
                <td>Curso de Innovación Educativa</td>
                <td>20/10/2024</td>
                <td>Lucía Rodríguez Sánchez</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div> <!-- /.col -->
    </div> <!-- /.row -->
  </div> <!-- /.container-fluid -->
</div> <!-- /.container-fluid -->

<!-- Contenedor de Promedio de Calificaciones -->
<div class="container-fluid mt-5  box-shadow-div p-5">
  <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile cont-div">
    Promedio de Calificaciones
  </div>
  <div class="container-fluid p-3">
    <div class="row">
      <!-- Tabla de Promedio de Calificaciones -->
      <div class="col-md-12 carta_Informacion">
        <div class="table-section p-6 border rounded box-shadow-div h-100 carta_Informacion">
          <div class="d-flex justify-content-between align-items-center mb-3 carta_Informacion">
            <h4 class="mb-0 text-green carta_Informacion">Promedio de Calificaciones</h4>
          </div>
          <table class="table table-striped carta_Informacion">
            <thead>
              <tr>
                <th>Docentes</th>
                <th>Evaluación Estudiantil</th>
                <th>Evaluación TECNM</th>
                <th>Promedio por semestre</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Juan Carlos Tinoco Villagran</td>
                <td>80.0</td>
                <td>80.0</td>
                <td>80.0</td>
              </tr>
              <tr>
                <td>Jose Luis Orozco Garcia</td>
                <td>70.0</td>
                <td>70.0</td>
                <td>70.0</td>
              </tr>
              <tr>
                <td>Eden Muñoz Lopez</td>
                <td>75.0</td>
                <td>75.0</td>
                <td>75.0</td>
              </tr>
              <tr>
                <td>Edwin Luna Castillo</td>
                <td>60.5</td>
                <td>60.5</td>
                <td>60.5</td>
              </tr>
              <tr>
                <td>Alfredo Olivas Ruiz</td>
                <td>82.0</td>
                <td>82.0</td>
                <td>82.0</td>
              </tr>
              <tr>
                <td>Cosme Tadeo Lopez Varela</td>
                <td>90.2</td>
                <td>90.2</td>
                <td>90.2</td>
              </tr>
              <tr>
                <td>Virlán García Nuñez</td>
                <td>98.3</td>
                <td>98.3</td>
                <td>98.3</td>
              </tr>
              <tr>
                <td>Cornelio Vega Chairez</td>
                <td>87.4</td>
                <td>87.4</td>
                <td>87.4</td>
              </tr>
              <tr>
                <td>Julion Alvarez Buendla</td>
                <td>74.1</td>
                <td>74.1</td>
                <td>74.1</td>
              </tr>
              <tr>
                <td>Ariel Camacho Torres</td>
                <td>84.2</td>
                <td>84.2</td>
                <td>84.2</td>
              </tr>
              <tr>
                <td>Amanda Rivera de Miguel</td>
                <td>98.2</td>
                <td>98.2</td>
                <td>98.2</td>
              </tr>
              <tr>
                <td>Jenifer Espinoza German</td>
                <td>95.2</td>
                <td>95.2</td>
                <td>95.2</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div> <!-- /.col -->
    </div> <!-- /.row -->
  </div> <!-- /.container-fluid -->
</div> <!-- /.container-fluid -->

<!-- Nuevo Contenedor Principal: PERSONAL -->
<div class="container-fluid mt-5 box-shadow-div p-5">
  <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile cont-div">
    DIRECCIÓN ACADÉMICA 
  </div>
  <div class="container-fluid p-3">
    <div class="row">
      <!-- Donut Chart Card -->
      <div class="col-12 col-md-6 carta_Informacion">
        <div class="card shadow mb-4 box-shadow-div h-100 carta_Informacion">
          <div class="card-header carta_Informacion">
            <strong class="card-title text-green mb-0 carta_Informacion">Grado académico de docentes en la división</strong>
          </div>
          <div class="card-body text-center">
            <div id="donutChart2"></div>
          </div> <!-- /.card-body -->
        </div> <!-- /.card -->
      </div> <!-- /.col -->
<!-- Tabla de Docentes -->
<div class="col-12 col-md-6 mt-5 carta_Informacion">
  <div class="table-section p-6 border rounded box-shadow-div h-100 carta_Informacion">
    <div class="d-flex justify-content-between align-items-center mb-3 carta_Informacion">
      <h4 class="mb-0 text-green carta_Informacion">Docentes</h4>
    </div>
    <table class="table table-striped carta_Informacion">
      <thead>
        <tr>
          <th>Nombre Docente</th>
          <th>N. Empleado</th>
          <th>Grado académico</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Juan Carlos Tinoco Villagran</td>
          <td>E6748521394</td>
          <td>Licenciatura</td>
        </tr>
        <tr>
          <td>Jose Luis Orozco Garcia</td>
          <td>E6748521393</td>
          <td>Maestría</td>
        </tr>
        <tr>
          <td>Eden Muñoz Lopez</td>
          <td>E6748521392</td>
          <td>Ingeniería</td>
        </tr>
        <tr>
          <td>Iker Ruiz Lopez</td>
          <td>E6748522192</td>
          <td>Ingeniería</td>
        </tr>
        <tr>
          <td>Abel Gallardo Garcia</td>
          <td>E6748521006</td>
          <td>Ingeniería</td>
        </tr>
        <tr>
          <td>Carlos Roberto Chia</td>
          <td>E67485213711</td>
          <td>Ingeniería</td>
        </tr>
      </tbody>
    </table>
    <!-- Card para TOTAL DE HORAS MAXIMAS -->
    <div class="card mt-3 text-center">
      <div class="card-body  box-shadow-div">
        <h5 class="card-title font-weight-bold">TOTAL DE HORAS MÁXIMAS</h5>
        <p class="card-text font-weight-bold">40</p>
      </div>
    </div>
  </div>
</div> <!-- /.col -->


          </div>
        </div>
        
      </div>
    </main>
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