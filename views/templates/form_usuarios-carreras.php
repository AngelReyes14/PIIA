<?php
include('../../controllers/db.php');
include('../../models/consultas.php');
include('../../models/session.php');
include('aside.php');
// Inicializa la respuesta por defecto
$response = ['status' => 'error', 'message' => ''];

// Intenta conectar a la base de datos
try {
  // Inicializa las consultas
  $consultas = new Consultas($conn);
  // Obtén las carreras y semestres
  $carreras = $consultas->obtenerCarreras();
  $usuarios = $consultas->obtenerDatosUsuario();
  $periodos = $consultas->obtenerPeriodo();
  $usuarioCarrera = $consultas->verUsuariosCarreras();
} catch (Exception $e) {
  // Si falla la conexión, retorna un error
  $response['message'] = 'Error al conectar con la base de datos: ' . $e->getMessage();
  echo json_encode($response);
  exit();  // Finaliza la ejecución si no hay conexión
}
if (isset($_POST['logout'])) {
  $sessionManager->logoutAndRedirect('../templates/auth-login.php');
}

$idusuario = $_SESSION['user_id']; // Asumimos que el ID ya está en la sesión

$imgUser  = $consultas->obtenerImagen($idusuario);
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
              <span class="avatar avatar-sm mt-2" style="width: 35px; height: 35px; display: inline-block;">
                  <img src="<?= htmlspecialchars($imgUser['imagen_url'] ?? './assets/avatars/default.jpg') ?>" alt="Avatar del usuario" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
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

    <div class="col-md-12">
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="d-flex justify-content-center align-items-center mb-3 col">
        <p class="titulo-grande"><strong>Registro de Usuario y Carrera</strong></p>
      </div>
      <form method="POST" action="../../models/insert.php" enctype="multipart/form-data" id="formRegistroUsuarioCarrera">
        <input type="hidden" name="form_type" value="usuario-carrera">

        <div class="row mb-3">
          <div class="col-md-4">
            <div class="form-group">
              <label for="usuario" class="form-label">Usuario:</label>
              <select class="form-control" id="usuario" name="usuario" required>
                <option value="">Selecciona un usuario</option>
                <?php foreach ($usuarios as $usuario): ?>
                  <option value="<?php echo $usuario['usuario_id']; ?>"><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Este campo no puede estar vacío.</div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label for="carrera" class="form-label">Carrera:</label>
              <select class="form-control" id="carrera" name="carrera" required>
                <option value="">Selecciona una carrera</option>
                <?php foreach ($carreras as $carrera): ?>
                  <option value="<?php echo $carrera['carrera_id']; ?>"><?php echo htmlspecialchars($carrera['nombre_carrera']); ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Este campo no puede estar vacío.</div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label for="periodo" class="form-label">Período:</label>
              <select class="form-control" id="periodo" name="periodo" required>
                <option value="">Selecciona un período</option>
                <?php foreach ($periodos as $periodo): ?>
                  <option value="<?php echo $periodo['periodo_id']; ?>"><?php echo htmlspecialchars($periodo['descripcion']); ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Este campo no puede estar vacío.</div>
            </div>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-12 text-center mb-3">
            <button type="submit" class="btn btn-success btn-lg">Registrar Usuario</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="d-flex justify-content-center align-items-center mb-3 col">
          <p class="titulo-grande"><strong>Asignacion de datos Usuario</strong></p>
        </div>
        <div class="row my-4">
          <!-- Small table -->
          <div class="col-md-12">
            <div class="card shadow">
              <div class="card-body">
                <!-- table -->
                <table class="table datatables" id="dataTable-1">
                  <thead>
                    <tr>
                      <th>Nombre Usuario</th>
                      <th>Nombre Carrera</th>
                      <th>Periodo</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($usuarioCarrera as $usuarioCarrera): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($usuarioCarrera['nombre_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($usuarioCarrera['nombre_carrera']); ?></td>
                        <td><?php echo htmlspecialchars($usuarioCarrera['descripcion']); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div> <!-- simple table -->
        </div> <!-- end section -->
      </div> <!-- .col-12 -->
    </div> <!-- .row -->
  </div> <!-- .container-fluid -->
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
  </body>
  
  </html>
  