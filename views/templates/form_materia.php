<?php
include('../../models/session.php');
include('../../controllers/db.php');
include('../../models/consultas.php');
include('aside.php');

$idusuario = $_SESSION['user_id']; // Asumimos que el ID ya está en la sesión

$imgUser  = $consultas->obtenerImagen($idusuario);

// Inicializa la respuesta por defecto
$response = ['status' => 'error', 'message' => ''];

// Intenta conectar a la base de datos
try {
  // Inicializa las consultas
  $consultas = new Consultas($conn);

  // Obtén las carreras y semestres
  $semestres = $consultas->obtenerSemestres();

  $materias = $consultas->obtenerMaterias();
  
  $grupos = $consultas->obtenerGrupos();

  $periodos = $consultas ->obtenerPeriodo();

  $materias = $consultas->verMaterias();

  $materiagrupo = $consultas->verMateriasGrupo();

} catch (Exception $e) {
  // Si falla la conexión, retorna un error
  $response['message'] = 'Error al conectar con la base de datos: ' . $e->getMessage();
  echo json_encode($response);
  exit();  // Finaliza la ejecución si no hay conexión
}

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
  <title>Registro de Materias</title>
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
    <main role="main" class="main-content">

    <div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-10">
      <h2 class="page-title">Alta de materias</h2>
      <div class="card my-4">
        <div class="card-header">
        </div>
        <div class="card-body">
          <div id="example-basic">
            <h3>Registro de materias</h3>
            <section>
              <form method="POST" action="../../models/insert.php" enctype="multipart/form-data" id="formRegistroMateria">
                <input type="hidden" name="form_type" value="materia">
                <div class="row mb-3">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="nombre_materia" class="form-label-custom">Nombre de la materia:</label>
                      <input class="form-control" id="nombre_materia" name="nombre_materia" type="text" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="credito_materia" class="form-label-custom">Créditos de la materia:</label>
                      <input type="number" class="form-control" id="credito_materia" name="credito_materia" required>
                    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="hora_teorica" class="form-label-custom">Horas teóricas:</label>
                      <input type="number" class="form-control" id="hora_teorica" name="hora_teorica" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="hora_practica" class="form-label-custom">Horas prácticas:</label>
                      <input type="number" class="form-control" id="hora_practica" name="hora_practica" required>
                    </div>
                  </div>
                </div>
                
                <div class="text-center mt-4">
                  <input type="submit" id="submit-materia" class="btn btn-primary" value="Registrar Materia">
                </div>
              </form>
            </section>

            <h3>Asignar materias a grupos</h3>
            <section>
            <form method="POST" action="../../models/insert.php" enctype="multipart/form-data" id="formAsignarMateria">
            <input type="hidden" name="form_type" value="materia-grupo">
              <div class="form-group">
                <label for="semestre" class="form-label-custom">Materia:</label>
                <select class="form-control" id="materia" name="materia" required>
                  <option value="">Selecciona una materia</option>
                  <?php foreach ($materias as $materia): ?>
                    <option value="<?php echo $materia['materia_id']; ?>"><?php echo htmlspecialchars($materia['descripcion']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="grupo" class="form-label-custom">Grupo:</label>
                <select class="form-control" id="grupo" name="grupo" required>
                  <option value="">Selecciona un Grupo</option>
                  <?php foreach ($grupos as $grupo): ?>
                    <option value="<?php echo $grupo['grupo_id']; ?>"><?php echo htmlspecialchars($grupo['descripcion']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label for="periodo" class="form-label-custom">Periodo:</label>
                <select class="form-control" id="periodo" name="periodo" required>
                  <option value="">Selecciona un periodo</option>
                  <?php foreach ($periodos as $periodo): ?>
                    <option value="<?php echo $periodo['periodo_id']; ?>"><?php echo htmlspecialchars($periodo['descripcion']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="text-center mt-4">
                  <input type="submit" id="submit-materia-grupo" class="btn btn-primary" value="Asignar materia">
                </div>
              </form>

            </section>

          </div>
        </div> <!-- .card-body -->
      </div> <!-- .card -->
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->

<script>
 $(document).ready(function() {
  $("#formAsignarMateria").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "fade", // Cambiar el efecto de transición a "fade"
    transitionEffectSpeed: 1000, // Aumentar la duración de la transición (en milisegundos)
    autoFocus: true,
    enablePagination: false, // Desactivar los botones Next y Previous
    enableAllSteps: true, // Hacer clickeables los encabezados
    saveState: false, // No guardar el estado, permitir el cambio de pestañas sin validación
    onStepChanged: function(event, currentIndex) {
      // Acciones a realizar cuando cambie la pestaña, si es necesario
    }
  });
});

</script>

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

      <div class="container-fluid">
  <div class="row">
    <!-- Primera tabla -->
    <div class="col-6">
      <h2 class="mb-2 page-title">Materias registradas</h2>
      <div class="row my-4">
        <!-- Table -->
        <div class="col-md-12">
          <div class="card shadow">
            <div class="card-body">
              <!-- Table -->
              <table class="table datatables" id="tabla-materias-1">
                <thead class="thead-dark">
                  <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Créditos</th>
                    <th>Horas Teóricas</th>
                    <th>Horas Prácticas</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($materias): ?>
                    <?php foreach ($materias as $materia): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($materia['materia_id']); ?></td>
                        <td><?php echo htmlspecialchars($materia['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($materia['credito']); ?></td>
                        <td><?php echo htmlspecialchars($materia['hora_teorica']); ?></td>
                        <td><?php echo htmlspecialchars($materia['hora_practica']); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="text-center">No hay materias registradas.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div> <!-- End table -->
      </div> <!-- End section -->
    </div> <!-- End .col-6 -->

    <!-- Segunda tabla -->
    <div class="col-6">
      <h2 class="mb-2 page-title">Materias asignadas a grupos</h2>
      <div class="row my-4">
        <!-- Table -->
        <div class="col-md-12">
          <div class="card shadow">
            <div class="card-body">
              <!-- Table -->
              <table class="table datatables" id="tabla-materias-2">
                <thead class="thead-dark">
                  <tr>
                    <th>Nombre de la materia</th>
                    <th>Nombre del grupo</th>
                    <th>Período</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($materiagrupo): ?>
                    <?php foreach ($materiagrupo as $materiasgrupos): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($materiasgrupos['materia_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($materiasgrupos['grupo_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($materiasgrupos['periodo_nombre']); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="text-center">No hay materias registradas.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div> <!-- End table -->
      </div> <!-- End section -->
    </div> <!-- End .col-6 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->


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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src='js/jquery.steps.min.js'></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/gauge.min.js"></script>
<script src="js/jquery.sparkline.min.js"></script>
<script src="js/apexcharts.min.js"></script>
<script src="js/apexcharts.custom.js"></script>
<script src='js/jquery.mask.min.js'></script>
<script src='js/select2.min.js'></script>
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