<?php
include('../../models/session.php');  
include('../../controllers/db.php'); 
include('../../models/consultas.php'); 
include('aside.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
  header("Location: ../templates/auth-login.php");
  exit();
}

$idusuario = $_SESSION['user_id'];

// Crear una instancia de la clase Consultas
$consultas = new Consultas($conn);

// Obtener la imagen del usuario
$imgUser = $consultas->obtenerImagen($idusuario);
$certificaciones = $consultas->obtenerCertificaciones();
$certificacionesusuarios = $consultas->obtenerCertificacionesPorUsuario($idusuario);



// Obtener datos del usuario
$usuario = $consultas->obtenerUsuarioPorId($idusuario);
if (!$usuario) {
  die("Error: No se pudo obtener la información del usuario.");
}

$carreraUsuario = $usuario['carrera_carrera_id'];

// Obtener la carrera del usuario
$carrera = $consultas->obtenerCarreraPorUsuarioId($idusuario);
if (is_array($carrera) && !empty($carrera)) {
  $usuario = array_merge($usuario, $carrera);
}

// Obtener la lista de profesores
$profesores = $consultas->obtenerProfesoresconCertificado();

// Calcular antigüedad
$fechaContratacion = $usuario["fecha_contratacion"];
$fechaContratacionDate = new DateTime($fechaContratacion);
$fechaActual = new DateTime();
$usuario['antiguedad'] = $fechaContratacionDate->diff($fechaActual)->y;

// Depuración (desactivar en producción)
$debug = true;
if ($debug) {
  echo "<script>console.log('Usuario:', " . json_encode($usuario) . ");</script>";
  echo "<script>console.log('Carrera:', " . json_encode($carrera) . ");</script>";
  echo "<script>console.log('Usuario con Carrera:', " . json_encode($usuario) . ");</script>";
}

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
  <title>Registro de Materias</title>
  <!-- Simple bar CSS -->
  <link rel="stylesheet" href="css/simplebar.css">
  <!-- Fonts CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <!-- Icons CSS -->
  <link rel="stylesheet" href="css/feather.css">
  <link rel="stylesheet" href="css/dataTables.bootstrap4.css">
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


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- CSS del Date Range Picker -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

  <!-- JS del Date Range Picker -->
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

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

    <main role="main" class="main-content">
      <div class="card p-4 mb-4 box-shadow-div">
        <div class="row mb-3"> 
        <div class="container p-4 mb-4 box-shadow-div">
          <div class="card-header" style="border:none;">
            <h2>Perfil del Usuario</h2>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-md-4 text-center">
                <strong class="name-line text-start">Foto del Docente:</strong>
                <br>
                <img src="<?= '../' . (!empty($usuario["imagen_url"]) ? htmlspecialchars($usuario["imagen_url"]) : 'default-image.png') ?>" alt="Imagen del docente" class="img-fluid tamanoImg">

                <div class="mt-3">
                  <button class="btn btn-primary" id="changeProfilePictureBtn">Cambiar Imagen</button>
                </div>
              </div>

              <div class="modal fade" id="changeImageModal" tabindex="-1" aria-labelledby="changeImageModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="changeImageModalLabel">Cambiar Imagen de Perfil</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form id="changeProfilePictureForm" action="subir_imagen.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                          <label for="profilePictureInput" class="form-label">Selecciona una nueva imagen</label>
                          <input class="form-control" type="file" name="profile_picture" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-8 filter-container" >
                <div class="row mb-3">
                  <div class="col-sm-6 col-md-6">
                    <label class="form-label">Nombre:</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre_usuario']) . ' ' . htmlspecialchars($usuario['apellido_p']) . ' ' . htmlspecialchars($usuario['apellido_m']); ?>" readonly>                  </div>
                  <div class="col-sm-6 col-md-6">
                    <label class="form-label">Correo Electrónico:</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['correo']); ?>" readonly>
                  </div>

                  <div class="col-sm-6 col-md-6 mt-3">
                    <label class="form-label">Edad:</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['edad']); ?>" readonly>
                  </div>
                  <div class="col-sm-6 col-md-6 mt-3">
                    <label class="form-label">Cédula:</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['cedula']); ?>" readonly>
                  </div>


                  <div class="col-sm-6 col-md-6 mt-3">
                    <label class="form-label">Fecha de Contratación:</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['fecha_contratacion']); ?>" readonly>
                  </div>
                  <div class="col-sm-6 col-md-6 mt-3">
                    <label class="form-label">Grado Académico:</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['grado_academico']); ?>" readonly>
                  </div>


                  <div class="col-sm-6 col-md-12 mt-3">
                    <label class="form-label">Antigüedad:</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['antiguedad']); ?> años" readonly>
                  </div>
                </div>

                  

                </div>
            </div>
            </div>
          </div>
        </div>
        <?php if ($usuario['tipo_usuario_tipo_usuario_id'] == 1): ?>
    <div class="row mt-4">
      <div class="container p-4 mb-4 box-shadow-div">
        <div class="col-md-12">
            <h3>Registrar Certificación</h3>
            <form method="POST" action="../../models/insert.php" enctype="multipart/form-data">
    <input type="hidden" name="form_type" value="certificacion-usuario">
    <input type="hidden" name="usuario_usuario_id" value="<?= htmlspecialchars($idusuario) ?>">

    <div class="row">
        <!-- Certificación -->
        <div class="col-md-4">
            <label for="certificaciones_certificaciones_id" class="form-label">Certificación:</label>
            <select class="form-control" id="certificaciones_certificaciones_id" name="certificaciones_certificaciones_id" required>
                <option value="" disabled selected>Selecciona una certificación</option>
                <?php if ($certificaciones): ?>
                    <?php foreach ($certificaciones as $certificacion): ?>
                        <option value="<?= htmlspecialchars($certificacion['certificaciones_id']) ?>">
                            <?= htmlspecialchars($certificacion['descripcion']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No hay certificaciones disponibles</option>
                <?php endif; ?>
            </select>
            <div class="invalid-feedback">Este campo no puede estar vacío.</div>
        </div>

        <!-- Nombre del Certificado -->
        <div class="col-md-4 ">
            <label for="nombre_certificado" class="form-label">Nombre del Certificado:</label>
            <input type="text" class="form-control" name="nombre_certificado" id="nombre_certificado" required>
            <div class="invalid-feedback">Este campo no puede estar vacío.</div>
        </div>

        <!-- Selección de archivo PDF -->
        <div class="col-md-4" id="documentDiv">
            <label for="documentInput" class="form-label">Selecciona el archivo PDF:</label>
            <input class="form-control" id="documentInput" name="certificado" type="file" accept=".pdf" required>
            <div class="invalid-feedback">Este campo no puede estar vacío.</div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Registrar Certificación</button>
</form>


        </div>
      </div>
    </div>
<!-- Mostrar las certificaciones en una tabla -->
<div class="col-lg-12 col-md-12">
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-center align-items-center mb-3 col">
                <p class="titulo-grande"><strong>Certificaciones del Usuario</strong></p>
            </div>
            <div class="row my-4">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <table class="table datatables" id="dataTable-certificaciones">
                                <thead>
                                    <tr>
                                        <th>Certificación</th>
                                        <th>Nombre del Certificado</th>
                                        <th>Certificado</th>
                                        <th>Acciones</th> <!-- Nueva columna para las acciones -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($certificacionesusuarios as $certificacionusuario): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($certificacionusuario['certificacion_descripcion']); ?></td>
                                            <td><?php echo htmlspecialchars($certificacionusuario['nombre_certificado']); ?></td>
                                            <td class="text-center">
                                                <?php if (!empty($certificacionusuario['url'])): ?>
                                                    <?php 
                                                    $correctFilePath = str_replace('views/', '', $certificacionusuario['url']);
                                                    ?>
                                                    <a href="<?php echo $correctFilePath; ?>" target="_blank" class="btn btn-sm btn-primary">Ver Certificado</a>
                                                <?php else: ?>
                                                    No disponible
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <!-- Botón de actualizar certificado -->
    <button class="btn btn-sm btn-warning update-cert-btn" 
            data-bs-toggle="modal" 
            data-bs-target="#updateCertificacionModal"
            data-certificacion-id="<?= htmlspecialchars($certificacionusuario['certificados_id']) ?>"
            data-certificaciones-id="<?= htmlspecialchars($certificacionusuario['certificaciones_certificaciones_id']) ?>"
            data-nombre-certificado="<?= htmlspecialchars($certificacionusuario['nombre_certificado']) ?>"
            data-url-antigua="<?= htmlspecialchars($certificacionusuario['url']) ?>">
        Actualizar
    </button>


                                                <!-- Botón de eliminar certificado -->
                                            
                                                <form method="POST" action="../../models/insert.php">
    <input type="hidden" name="form_type" value="eliminar-certificacion-usuario">
    <input type="hidden" name="certificados_id" id="certificados_id" value="<?= htmlspecialchars($certificacionusuario['certificados_id']) ?>">
    <button class="btn btn-danger" data-id="<?php echo $certificacionusuario['certificados_id']; ?>">Eliminar</button>

</form>


                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="updateCertificacionModal" tabindex="-1" aria-labelledby="updateCertificacionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateCertificacionModalLabel">Actualizar Certificación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Formulario de actualización -->
        <form method="POST" action="../../models/insert.php" enctype="multipart/form-data">
          <input type="hidden" name="form_type" value="actualizar-certificacion-usuario">
          <input type="hidden" name="usuario_usuario_id" value="<?= htmlspecialchars($idusuario) ?>">
          <input type="hidden" name="certificacion_usuario_id" id="certificacion_usuario_id" value="">

          <div class="row">
            <!-- Certificación -->
            <div class="col-md-4">
              <label for="certificaciones_certificaciones_id" class="form-label">Certificación:</label>
              <select class="form-control" id="certificaciones_certificaciones_id" name="certificaciones_certificaciones_id" required>
                <option value="" disabled selected>Selecciona una certificación</option>
                <?php if ($certificaciones): ?>
                  <?php foreach ($certificaciones as $certificacion): ?>
                    <option value="<?= htmlspecialchars($certificacion['certificaciones_id']) ?>">
                      <?= htmlspecialchars($certificacion['descripcion']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="">No hay certificaciones disponibles</option>
                <?php endif; ?>
              </select>
              <div class="invalid-feedback">Este campo no puede estar vacío.</div>
            </div>

            <!-- Nombre del Certificado -->
            <div class="col-md-4 ">
              <label for="nombre_certificado" class="form-label">Nombre del Certificado:</label>
              <input type="text" class="form-control" name="nombre_certificado" id="nombre_certificado" required>
              <div class="invalid-feedback">Este campo no puede estar vacío.</div>
            </div>

            <!-- Selección de archivo PDF -->
            <div class="col-md-4" id="documentDiv">
              <label for="documentInput" class="form-label">Selecciona el archivo PDF:</label>
              <input class="form-control" id="documentInput" name="certificado" type="file" accept=".pdf">
              <input type="hidden" name="url_antigua" id="url_antigua" value="">
              <div class="invalid-feedback">Este campo no puede estar vacío.</div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary mt-3">Actualizar Certificación</button>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[data-bs-target='#updateCertificacionModal']").forEach(button => {
        button.addEventListener("click", function () {
// Obtener el modal
            let modalElement = document.getElementById('updateCertificacionModal');
            let modal = new bootstrap.Modal(modalElement);
            modal.show(); // Mostrar el modal manualmente

            // Obtener datos del botón
            let certificacionId = this.getAttribute("data-certificacion-id") || "";
            let certificacionesId = this.getAttribute("data-certificaciones-id") || "";
            let nombreCertificado = this.getAttribute("data-nombre-certificado") || "";
            let urlAntigua = this.getAttribute("data-url-antigua") || "";

            // Asignar valores a los campos del modal
            document.getElementById("certificacion_usuario_id").value = certificacionId;
            document.getElementById("nombre_certificado").value = nombreCertificado;
            document.getElementById("url_antigua").value = urlAntigua;

            // Seleccionar la certificación correspondiente en el <select>
            let selectCertificaciones = document.getElementById("certificaciones_certificaciones_id");
            if (selectCertificaciones) {
                for (let option of selectCertificaciones.options) {
                    if (option.value === certificacionesId) {
                        option.selected = true;
                        break;
                    }
                }
            }
        });
    });
});

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteButtons = document.querySelectorAll('.btn-danger'); // Asegúrate de que el botón de eliminar tenga esta clase

    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            // Obtener el ID del certificado al que pertenece el botón de eliminar
            var certificadoId = button.getAttribute('data-id'); 
            // Asignar el ID al campo oculto de certificados_id
            document.getElementById('certificados_id').value = certificadoId;
        });
    });
});

</script>
<?php endif; ?>

      </div>

      <?php if ($usuario && $usuario['tipo_usuario_tipo_usuario_id'] == 2): ?>
        <div class="card p-4 mb-4 box-shadow-div">
      <div class="container p-4 mb-4 box-shadow-div">
      <div id="cardProfesores" class="card p-4 mb-4 box-shadow-div text-center">
  <div class="conteiner">
    <h5 class="card-title mt-3">Profesores</h5>
    <div class="filter-container" style="position: relative; display: inline-block;">
      <!-- Label para el select -->
      <select class="form-control" id="profesorSelect">
        <option value="" selected>Selecciona profesor</option>
        <?php foreach ($profesores as $profesor): ?>
          <?php if ($profesor['carrera_carrera_id'] == $carreraUsuario): // Filtrar profesores por carrera ?>
            <?php
            $fechaContratacion = $profesor["fecha_contratacion"];
            $fechaContratacionDate = new DateTime($fechaContratacion);
            $fechaActual = new DateTime();
            $antiguedad = $fechaContratacionDate->diff($fechaActual)->y;
            $profesor['antiguedad'] = $antiguedad;
            ?>
<option
    data-nombre="<?= htmlspecialchars($profesor['nombre_usuario']) ?>"
    data-apellido="<?= htmlspecialchars($profesor['apellido_p'] . ' ' . $profesor['apellido_m']) ?>"
    data-correo="<?= htmlspecialchars($profesor['correo']) ?>"
    data-edad="<?= htmlspecialchars($profesor['edad']) ?>"
    data-cedula="<?= htmlspecialchars($profesor['cedula']) ?>"
    data-fecha="<?= htmlspecialchars($profesor['fecha_contratacion']) ?>"
    data-grado="<?= htmlspecialchars($profesor['grado_academico']) ?>"
    data-imagen="<?= htmlspecialchars($profesor['imagen_url']) ?>"
    data-certificaciones='<?= json_encode($profesor['certificaciones']) ?>'> <!-- Aquí estamos pasando las certificaciones -->
    <?= htmlspecialchars($profesor['nombre_usuario'] . ' ' . $profesor['apellido_p'] . ' ' . $profesor['apellido_m']) ?>
</option>

          <?php endif; ?>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
          <div class="card-header" style="border:none;">
            <h2>Perfil de docentes</h2>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-md-4  text-center hidden" id="profileContainer">
                <strong class="name-line text-start">Foto del Docente:</strong>
                <br>
                <img src="./assets/avatars/default.jpg" alt="Imagen del docente" class="img-fluid tamanoImg" id="profesorImagen">
              </div>
              <div class="col-md-8">
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" value="" readonly>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Correo Electrónico:</label>
                    <input type="text" class="form-control" id="correo" value="" readonly>
                  </div>

                </div>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Edad:</label>
                    <input type="text" class="form-control" id="edad" value="" readonly>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Cédula:</label>
                    <input type="text" class="form-control" id="cedula" value="" readonly>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Fecha de Contratación:</label>
                    <input type="text" class="form-control" id="fechaContratacion" value="" readonly>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Grado Académico:</label>
                    <input type="text" class="form-control" id="gradoAcademico" value="" readonly>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-12">
                    <label class="form-label">Antigüedad:</label>
                    <input type="text" class="form-control" id="antiguedad" value="" readonly>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <div class="col-lg-12 col-md-12">
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-center align-items-center mb-3 col">
                <p class="titulo-grande"><strong>Certificaciones del Profesor</strong></p>
            </div>
            <div class="row my-4">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <table class="table datatables" id="dataTable-certificaciones">
                                <thead>
                                    <tr>
                                        <th>Certificación</th>
                                        <th>Nombre del Certificado</th>
                                        <th>Certificado</th>
                                    </tr>
                                </thead>
                                <tbody id="certificacionesBody">
                                    <!-- Aquí se insertarán las certificaciones dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

          </div>
          
        </div>
        </div>
        
        <?php endif; ?>
        <script>
document.getElementById('profesorSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const certificacionesBody = document.getElementById('certificacionesBody');

    if (!selectedOption.value) {
        document.getElementById('profesorImagen').src = '../default-image.png';
        document.getElementById('nombre').value = '';
        document.getElementById('correo').value = '';
        document.getElementById('edad').value = '';
        document.getElementById('cedula').value = '';
        document.getElementById('fechaContratacion').value = '';
        document.getElementById('gradoAcademico').value = '';
        certificacionesBody.innerHTML = '<tr><td colspan="3" class="text-center">No hay certificaciones disponibles</td></tr>';
    } else {
        document.getElementById('profesorImagen').src = '../' + selectedOption.getAttribute('data-imagen');
        document.getElementById('nombre').value = selectedOption.getAttribute('data-nombre') + ' ' + selectedOption.getAttribute('data-apellido');
        document.getElementById('correo').value = selectedOption.getAttribute('data-correo');
        document.getElementById('edad').value = selectedOption.getAttribute('data-edad');
        document.getElementById('cedula').value = selectedOption.getAttribute('data-cedula');
        document.getElementById('fechaContratacion').value = selectedOption.getAttribute('data-fecha');
        document.getElementById('gradoAcademico').value = selectedOption.getAttribute('data-grado');

        // Obtener certificaciones
        const certificaciones = JSON.parse(selectedOption.getAttribute('data-certificaciones') || "[]");
        certificacionesBody.innerHTML = ''; // Limpiar la tabla antes de agregar nuevas filas

        if (certificaciones.length > 0) {
            certificaciones.forEach(cert => {
                const filePath = cert.url.replace('views/', ''); // Ajustar ruta del archivo
                const row = `
                    <tr>
                        <td>${cert.certificacion_nombre}</td> <!-- Cambié de 'certificacion_descripcion' a 'certificacion_nombre' si es necesario -->
                        <td>${cert.nombre_certificado}</td>
                        <td class="text-center">
                            ${cert.url ? `<a href="${filePath}" target="_blank" class="btn btn-sm btn-primary">Ver Certificado</a>` : 'No disponible'}
                        </td>
                    </tr>
                `;
                certificacionesBody.innerHTML += row;
            });
        } else {
            certificacionesBody.innerHTML = '<tr><td colspan="3" class="text-center">No hay certificaciones disponibles</td></tr>';
        }
    }
});

</script>


      

        <script>
                $(document).ready(function() {
                  $('#profesorSelect').change(function() {
                    const selectedOption = $(this).find('option:selected');
                    const imagenUrl = selectedOption.data('imagen') || './assets/avatars/default.jpg';

                    $('#profesorImagen').attr('src', '../' + imagenUrl); // Asegúrate de que la URL es correcta
                  });
                });
              </script>
      </div>

    </main>
      <!-- Contenido de la página -->

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
      <!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- DataTables Bootstrap4 JS -->
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
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
        document.addEventListener("DOMContentLoaded", function() {
          const filterBtn = document.getElementById('filterBtn');
          const filterOptions = document.getElementById('filterOptions');
          const selectElement = filterOptions.querySelector('select');

          // Toggle la visibilidad de las opciones al hacer clic en el botón de filtro
          filterBtn.addEventListener('click', function() {
            filterOptions.classList.toggle('d-none');
          });

          // Detectar selección en el menú de profesores y actualizar el botón con el nombre seleccionado
          selectElement.addEventListener('change', function() {
            const profesorSeleccionado = selectElement.options[selectElement.selectedIndex].text.trim();

            // Actualizar el texto del botón con el nombre seleccionado
            filterBtn.textContent = profesorSeleccionado;

            // Ocultar el menú de opciones después de la selección
            filterOptions.classList.add('d-none');
          });
        });
      </script>

      <script>
        document.getElementById('changeProfilePictureBtn').addEventListener('click', function() {
          var myModal = new bootstrap.Modal(document.getElementById('changeImageModal'));
          myModal.show();
        });

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
          placeholder: "_//_"
        });
        $('.input-zip').mask('00000-000', {
          placeholder: "_-__"
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
          placeholder: "_._._._"
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