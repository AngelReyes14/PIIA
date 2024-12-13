<?php
include('../../controllers/db.php');
include('../../models/consultas.php');
include('../../models/session.php');
include('aside.php');

$idusuario = $_SESSION['user_id']; // Asumimos que el ID ya está en la sesión

$imgUser  = $consultas->obtenerImagen($idusuario);

// Inicializa la respuesta por defecto
$response = ['status' => 'error', 'message' => ''];



?>

<!doctype html>
<html lang="en">


<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <link rel="icon" href="assets/images/PIIA_oscuro 1.png">
  <title>Asignacion de carreras</title>
  <!-- Simple bar CSS -->
  <link rel="stylesheet" href="css/simplebar.css">
  <!-- Fonts CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <!-- Icons CSS -->
  <link rel="stylesheet" href="css/feather.css">
  <link rel="stylesheet" href="css/select2.css">
  <link rel="stylesheet" href="css/dropzone.css">
  <link rel="stylesheet" href="css/uppy.min.css">
  <link rel="stylesheet" href="css/jquery.steps.css">
  <link rel="stylesheet" href="css/jquery.timepicker.css">
  <link rel="stylesheet" href="css/quill.snow.css">
  <link rel="stylesheet" href="css/dataTables.bootstrap4.css">
  
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
        <input class="form-control mr-sm-2 bg-transparent border-0 pl-4 text-muted" type="search" placeholder="Type something..." aria-label="Search">
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
          </div>
        </li>
      </ul>
    </nav>

    <main role="main" class="main-content">
    <!-- Formulario para registrar un edificio -->
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center mb-3 col">
                    <p class="titulo-grande"><strong>Registro de Edificio</strong></p>
                </div>
                <form id="formEdificio" method="post" action="../../models/insert.php" enctype="multipart/form-data">
                    <input type="hidden" name="form_type" value="agregar-edificio">
                    <div id="smartwizard">
                        <div class="mt-4">
                            <!-- Paso 1: Datos del Edificio -->
                            <div id="step-1" class="step-content" style="display: block;">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 mt-3">
                                        <label for="nombre_edificio" class="form-label">Nombre del Edificio:</label>
                                        <input type="text" id="nombre_edificio" name="nombre_edificio" class="form-control" required>
                                        <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12 text-center">
                                        <!-- Botón de Registro Visible en el Paso 1 -->
                                        <button type="submit" class="btn btn-success btn-lg">Registrar Edificio</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Paso 2: Confirmar Registro (opcional) -->
                            <div id="step-2" class="step-content" style="display: none;">
                                <div class="row">
                                    <div class="col-sm-12 mt-3">
                                        <p>Revise los datos ingresados antes de confirmar el registro del edificio.</p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12 text-center">
                                        <!-- Botón adicional en el paso 2, si es necesario -->
                                        <button type="submit" class="btn btn-success btn-lg">Registrar Edificio</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
    <!-- Incluir SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/form_carrera.js"></script>
  
    <script>
      /* defind global options */
      Chart.defaults.global.defaultFontFamily = base.defaultFontFamily;
      Chart.defaults.global.defaultFontColor = colors.mutedColor;
    </script>
    <script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/simplebar.min.js"></script>
<script src="js/daterangepicker.js"></script>
<script src="js/jquery.mask.min.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/form_carrera.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Iniciando Select2
  $('.select2').select2({
    theme: 'bootstrap4',
  });

  // Iniciando Date Range Picker
  $('.drgpicker').daterangepicker({
    singleDatePicker: true,
    timePicker: false,
    showDropdowns: true,
    locale: {
      format: 'MM/DD/YYYY'
    }
  });

  // Iniciando máscaras
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
</script>
  </body>
  
  </html>
  