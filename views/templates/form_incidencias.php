<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="assets/images/PIIA_oscuro 1.png">
  <title>Reporte de Incidencias</title>
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Include DataTables CSS and JS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

  <script src="js/form_carrera.js"></script>
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
              <a class="nav-link pl-3" href="form_incidencias.php"><span class="ml-1 item-text">Incidencias</span></a>
            </li>
            <li class="nav-item w-100">
              <a class="nav-link pl-3" href="form_usuarios-carreras.php"><span class="ml-1 item-text">Asigancion de Carreras</span></a>
            </li>
          </ul>
          </ul>
        </ul>
      </nav>
    </aside>
      <!-- Incluir SweetAlert2 -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

      <main role="main" class="main-content">
        
        <div class="col-md-12">
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="logo-container mb-3">
                <!-- Logo en la parte superior izquierda -->
                <img class="form-logo-left" src="assets/images/logo-teschi.png" alt="Logo Izquierda">
                
                <!-- Logo en la parte superior derecha -->
                <img class="form-logo-right" src="assets/icon/icon_piia.png" alt="Logo Derecha">
              </div>
              <div class="d-flex justify-content-center align-items-center mb-3 col">
                <p class="titulo-grande"><strong>AVISO DE JUSTIFICACION DE PUNTUALIDAD Y ASISTENCIA</strong></p>
              </div>
              <div class="conteiner p-4 mb-4 box-shadow-div">
              <div class="row mb-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="area" class="form-label">Área:</label>
                    <input class="form-control" id="area" name="area" type="text" required>
                    <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="fecha" class="form-label">Fecha:</label>
                    <input class="form-control" id="fecha" type="date" name="fecha" required>
                    <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                  </div>
                </div>
              </div>
              </div>

              <div class="conteiner p-4 mb-4 box-shadow-div form-group mb-3">
                <div class="titulos">Por este conducto se notifica que el trabajador está autorizado a:</div>
                <div id="justificacion-error" class="text-danger d-none">¡Por favor, seleccione al menos una opción.!</div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="consulta-medica" name="justificacion" value="consulta-medica">
                      <label class="form-check-label" for="consulta-medica">
                        Asistencia a Consulta Médica
                        <span class="description">(Constancia de permanencia ISSEMYM)</span>
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="lactancia" name="justificacion" value="lactancia">
                      <label class="form-check-label" for="lactancia">
                        Lactancia
                        <span class="description">(Por 6 meses después del parto)</span>
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="dia-economico" name="justificacion" value="dia-economico">
                      <label class="form-check-label" for="dia-economico">
                        Día Económico
                        <span class="description">(Cuatro días por semestre)</span>
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="llegar-tarde" name="justificacion" value="llegar-tarde">
                      <label class="form-check-label" for="llegar-tarde">
                        Llegar tarde
                        <span class="description">(Hasta 60 min)</span>
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="retirarse-antes" name="justificacion" value="retirarse-antes">
                      <label class="form-check-label" for="retirarse-antes">
                        Retirarse Antes de la Hora
                        <span class="description">(Hasta 60 min)</span>
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="permiso-sin-sueldo" name="justificacion" value="permiso-sin-sueldo">
                      <label class="form-check-label" for="permiso-sin-sueldo">
                        Permiso Sin Goce de Sueldo
                      </label>
                    </div>
                  </div>
                </div>
                <div id="justificacion-error" class="text-danger d-none">Por favor, seleccione al menos una opción.</div>
                <div id="dias-restantes" class="mt-2 d-none">
                  <strong>Días restantes de Día Económico:</strong> <span id="count-down">4</span>
                </div>
              </div>
              
              <div class="conteiner p-3 box-shadow-div">
              <div class="form-group mb-3">
                <label for="motivo">Motivo</label>
                <input class="form-control" id="motivo" name="motivo" type="text" required>
                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
              </div>
              
              <div class="d-flex flex-wrap mb-3">
                <div class="form-group mr-3 flex-fill mb-3">
                  <label class="horario-label me-2">Horario:</label>
                  <div class="d-flex">
                    <input type="time" id="start-time" name="start-time" required class="me-1 form-control">
                    <span class="me-1">a</span>
                    <input type="time" id="end-time" name="end-time" required class="form-control">
                  </div>
                  <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
                
                <div class="form-group mr-3 flex-fill mb-3">
                  <label for="hora-incidencia" class="me-2">Hora de Incidencia:</label>
                  <input class="form-control" id="example-time" type="time" name="time" required>
                  <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
                
                <div class="form-group mr-3 flex-fill mb-3">
                  <label for="dia-incidencia" class="me-2">Día de la incidencia:</label>
                  <input class="form-control" id="dia-incidencia" type="date" name="dia-incidencia" required>
                  <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
              </div>
              
              <div class="d-flex flex-column mb-3">
                <div class="mb-2">
                  <label for="nombre-servidor-publico" class="form-label">Nombre del Servidor Público:</label>
                  <input type="text" class="form-control" id="nombre-servidor-publico" name="nombre-servidor-publico" required>
                  <div class="invalid-feedback">Este campo no puede estar vacío ni contener solo espacios.</div>
                </div>
                
                <div>
                  <label for="numero-empleado" class="form-label">Número del Empleado:</label>
                  <input type="text" class="form-control" id="numero-empleado" name="numero-empleado" required>
                  <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
              </div>
            </div>
            <!-- Botón para enviar el formulario -->
            <div class="text-center mt-4">
              <button type="button" class="btn btn-primary" id="submit-button">Enviar</button>
            </div>
<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="customModal" tabindex="-1" aria-labelledby="customModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customModalLabel">AVISO DE JUSTIFICACION DE PUNTUALIDAD Y ASISTENCIA</h5>
      </div>
      <div class="modal-body">
        DATOS ENVIADOS.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="closeModal">Cerrar</button>
      </div>
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
  