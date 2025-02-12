<?php
include('../../controllers/db.php');
include('../../models/consultas.php');
include('../../models/session.php');
include('aside.php');

$idusuario = $_SESSION['user_id']; // Asumimos que el ID ya está en la sesión

$imgUser  = $consultas->obtenerImagen($idusuario);

// Inicializa la respuesta por defecto
$response = ['status' => 'error', 'message' => ''];

// Obtén los parámetros de filtro
$periodo_id = $_GET['periodo_id'] ?? null;  // Usamos GET o POST, según sea el caso
$carrera_id = $_GET['carrera_id'] ?? null;
$docente_id = $_GET['docente_id'] ?? null;
$dia_id = $_GET['dia_id'] ?? null;
$hora_id = $_GET['hora_id'] ?? null;

try {
    // Inicializa las consultas
    $consultas = new Consultas($conn);

    // Obtén las opciones de los formularios
    $edificios = $consultas->obtenerEdificio();
    $salones = $consultas->obtenerSalones();
    $periodos = $consultas->obtenerPeriodo();
    $carreras = $consultas->obtenerCarreras();
    $usuarios = $consultas->obtenerUsuariosDocentes();
    $materias = $consultas->verMaterias();
    $grupos = $consultas->obtenerGrupos();
    $jefes = $consultas->obtenerUsuariosJefesdeDivision();

    // Obtén el horario filtrado
    $horario = $consultas->obtenerHorarioPorFiltros($periodo_id, $carrera_id, $docente_id, $dia_id, $hora_id);

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
  <title>Formulario de grupos</title>
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

  <!-- Incluir SweetAlert CSS y JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <!-- Date Range Picker CSS -->
  <link rel="stylesheet" href="css/daterangepicker.css">
  <!-- App CSS -->
  <link rel="stylesheet" href="css/app-light.css" id="lightTheme">
  <link rel="stylesheet" href="css/app-dark.css" id="darkTheme" disabled>
  <!-- Incluye jQuery primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Luego incluye SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Finalmente tu script personalizado -->
<script>
    // Tu código JavaScript aquí
</script>

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
            <!-- Formulario oculto para cerrar sesión -->
            <form method="POST" action="" id="logoutForm">
              <button class="dropdown-item" type="submit" name="logout">Cerrar sesión</button>
            </form>
          </div>

        </li>
      </ul>
    </nav>

    <main role="main" class="main-content">
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
            <form method="POST" action="../../models/insert.php">
                    <input type="hidden" name="form_type" value="horario"> <!-- Indicamos el tipo de formulario -->

            <div class="form-group">
                <label for="periodo_periodo_id" class="form-label-custom">Periodo:</label>
                <select class="form-control" id="periodo_periodo_id" name="periodo_periodo_id" required onchange="filtrarHorario()">
                  <option value="">Selecciona un periodo</option>
                  <?php foreach ($periodos as $periodo): ?>
                    <option value="<?php echo $periodo['periodo_id']; ?>"><?php echo htmlspecialchars($periodo['descripcion']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              
        </div>
        <div class="row">
        <div class="col-md-6">
        <div class="form-group  mt-2">
              <label for="carrera_carrera_id" class="form-label">Carrera:</label>
              <select class="form-control" id="carrera_carrera_id" name="carrera_carrera_id" required onchange="filtrarHorario()">
                <option value="">Selecciona una carrera</option>
                <?php foreach ($carreras as $carrera): ?>
                  <option value="<?php echo $carrera['carrera_id']; ?>"><?php echo htmlspecialchars($carrera['nombre_carrera']); ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Este campo no puede estar vacío.</div>
            </div>
                </div>  
                <!-- Docente-->
 <div class="col-md-6">
        <div class="form-group mt-2">
    <label for="usuario_usuario_id">Docente:</label>
    <select class="form-control" id="usuario_usuario_id" name="usuario_usuario_id" required onchange="filtrarHorario()">
        <option value="">Seleccione un usuario</option>
        <?php foreach ($usuarios as $usuario): ?>
            <option value="<?php echo $usuario['usuario_id']; ?>">
                <?php echo $usuario['nombre_usuario'] . ' ' . $usuario['apellido_p'] . ' ' . $usuario['apellido_m']; ?>
            </option>
        <?php endforeach; ?>
    </select>
    
</div>
</div>

                </div>

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


    <div class="pdf-container no-print">
    <button id="downloadPDF" onclick="generatePDF()">Descargar PDF</button>
    </div>
     </div>
    </div>
    
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel">Información Seleccionada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modalContent" class="modal-info">Día y hora seleccionados.</p>

                <!-- Formulario para asignar horario -->
                <form method="POST" action="../../models/insert.php">
                    <input type="hidden" name="form_type" value="horario"> <!-- Indicamos el tipo de formulario -->
                    
                    <input type="hidden" id="periodo" name="periodo_periodo_id">
                    <input type="hidden" id="docente" name="usuario_usuario_id">
                    <input type="hidden" id="carrera" name="carrera_carrera_id">
                    <input type="hidden" id="dia" name="dias_dias_id">
                    <input type="hidden" id="hora" name="horas_horas_id">
                    

                    <!-- Selección de Materia -->
                    <div class="row ">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="materia_materia_id">Materia:</label>
                                <select class="form-control" id="materia_materia_id" name="materia_materia_id" required>
                                    <option value="">Seleccione una materia</option>
                                    <?php foreach ($materias as $materia): ?>
                                        <option value="<?php echo $materia['materia_id']; ?>">
                                            <?php echo $materia['descripcion']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Selección de Grupo -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="grupo_grupo_id">Grupo:</label>
                                <select class="form-control" id="grupo_grupo_id" name="grupo_grupo_id" required>
                                    <option value="">Seleccione un grupo</option>
                                    <?php foreach ($grupos as $grupo): ?>
                                        <option value="<?php echo $grupo['grupo_id']; ?>">
                                            <?php echo $grupo['descripcion']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Selección de Salón -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="salon_salon_id">Salón:</label>
                                <select class="form-control" id="salon_salon_id" name="salon_salon_id" required>
                                    <option value="">Seleccione un Salón</option>
                                    <?php foreach ($salones as $salon): ?>
                                        <option value="<?php echo $salon['salon_id']; ?>">
                                            <?php echo $salon['descripcion']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
    <button type="submit" class="btn btn-success" name="action" value="asignar">Asignar</button>
    </form>
                <form method="POST" action="../../models/insert.php">
    <input type="hidden" name="form_type" value="borrar-horario">
    <input type="hidden" name="horario_id" id="horario_id">
    <button type="submit" class="btn btn-danger" name="action" value="eliminar" style="display: none;">Eliminar</button>
</form>
</div>

                </form>
                <form method="POST" action="../../models/insert.php">
    <input type="hidden" name="form_type" value="borrar-horario">
    <input type="hidden" name="horario_id" id="horario_id">
    <button type="submit" class="btn btn-danger" name="action" value="eliminar" style="display: none;">Eliminar</button>
</form>

            </div>
        </div>
    </div>
</div>

<script src="js/horario.js"></script>



<?php
// Verificar si existe un mensaje de estado en la URL
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $action = $_GET['action'] ?? '';
    $message = isset($_GET['message']) ? $_GET['message'] : '';
    $title = '';
    $text = '';
    $icon = '';
    
    // Definir los mensajes según el tipo de operación (insertar, actualizar, eliminar)
    if ($status === 'success') {
        switch ($action) {
            case 'insert':
                $title = '¡Horario registrado!';
                $text = 'Datos registrado correctamente.';
                $icon = 'success';
                break;
            case 'update':
                $title = '¡Horario actualizado!';
                $text = 'El horario se ha actualizado correctamente.';
                $icon = 'success';
                break;
            case 'delete':
                $title = '¡Horario eliminado!';
                $text = 'Datos eliminados correctamente.';
                $icon = 'success';
                break;
            default:
                $title = '¡Operación exitosa!';
                $text = 'La operación se completó correctamente.';
                $icon = 'success';
        }
    } elseif ($status === 'error') {
        switch ($action) {
            case 'delete':
                $title = '¡Error al eliminar!';
                $text = 'No se encontró el horario o hubo un problema al eliminarlo. ' . htmlspecialchars($message);
                $icon = 'error';
                break;
            default:
                $title = '¡Error!';
                $text = 'Hubo un problema con la operación. ' . htmlspecialchars($message);
                $icon = 'error';
        }
    }
?>
    <script>
        Swal.fire({
            title: "<?php echo $title; ?>",
            text: "<?php echo $text; ?>",
            icon: "<?php echo $icon; ?>",
            confirmButtonText: 'Aceptar'
        });
    </script>
<?php
}
?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>>


        <div class="modal fade modal-notif modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
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
        <div class="modal fade modal-shortcut modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
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
      </div> <!-- / fin de card de registros -->
    </main> <!-- main -->
<!-- jQuery (necesario para DataTables) -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!-- DataTables Bootstrap4 JS -->
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<!-- Otros scripts -->
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/simplebar.min.js"></script>
<script src='js/daterangepicker.js'></script>
<script src='js/jquery.stickOnScroll.js'></script>
<script src="js/tinycolor-min.js"></script>
<script src="js/config.js"></script>
<script src='js/jquery.mask.min.js'></script>
<script src='js/select2.min.js'></script>
<script src='js/jquery.steps.min.js'></script>
<script src='js/jquery.validate.min.js'></script>
<script src='js/jquery.timepicker.js'></script>|
<script src='js/dropzone.min.js'></script>
<script src='js/uppy.min.js'></script>
<script src='js/quill.min.js'></script>
<script src='js/apps.js'></script>




<!-- Google Analytics -->
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