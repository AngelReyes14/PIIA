<?php
include('../../controllers/db.php');
include('../../models/consultas.php');

// Inicializa la respuesta por defecto
$response = ['status' => 'error', 'message' => ''];

// Intenta conectar a la base de datos
try {
    // Inicializa las consultas
    $consultas = new Consultas($conn);

    // Obtén los sexos
    $sexo = $consultas->obtenerSexos();

    // Obtén las carreras
    $carreras = $consultas->obtenerCarreras();

    // Obtén los cuerpos colegiados
    $cuerposColegiados = $consultas->obtenerCuerposColegiados();

    // Obtén los tipos de usuario
    $tiposUsuario = $consultas->obtenerTiposDeUsuario();

} catch (Exception $e) {
  // Si falla la conexión, retorna un error
  $response['message'] = 'Error al conectar con la base de datos: ' . $e->getMessage();
  echo json_encode($response);
  exit();  // Finaliza la ejecución si no hay conexión
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>Tiny Dashboard - A Bootstrap Dashboard Template</title>
        <!-- Bootstrap CSS (para estilos y modales) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SmartWizard CSS -->
    <link href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/smart_wizard.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/smart_wizard_theme_arrows.min.css" rel="stylesheet" type="text/css" />
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
      <main role="main" class="main-content mt-5">

<!-- Formulario para subir datos de usuario -->
<div class="container mt-5 bg-white rounded border border-black p-4">
    <form id="formUsuario" method="post" action="../../models/insert.php" enctype="multipart/form-data">
        <input type="hidden" name="form_type" value="usuario">
        <div class="container p-4">
            <h2 class="text-center">Registrar Usuario</h2>
            <div id="smartwizard">
            <ul class="nav nav-pills justify-content-center flex-wrap flex-md-nowrap">
                    <li class="nav-item"><a href="#step-1" class="nav-link">Paso 1<br><small>Datos Personales</small></a></li>
                    <li class="nav-item"><a href="#step-2" class="nav-link">Paso 2<br><small>Datos Profesionales</small></a></li>
                    <li class="nav-item"><a href="#step-3" class="nav-link">Paso 3<br><small>Correo y Contraseña</small></a></li>
                    <li class="nav-item"><a href="#step-4" class="nav-link">Paso 4<br><small>Datos Finales</small></a></li>
                </ul>
                <div class="mt-4">
                    <!-- Paso 1: Datos Personales -->
                    <div id="step-1" class="step-content" style="display: block;">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 mt-3">
                                <label for="usuario_nombre" class="form-label">Nombre:</label>
                                <input type="text" id="usuario_nombre" name="usuario_nombre" class="form-control" required>
                                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                            </div>
                            <div class="col-sm-12 col-md-4 mt-3">
                                <label for="usuario_apellido_p" class="form-label">Apellido Paterno:</label>
                                <input type="text" id="usuario_apellido_p" name="usuario_apellido_p" class="form-control" required>
                                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                            </div>
                            <div class="col-sm-12 col-md-4 mt-3">
                                <label for="usuario_apellido_m" class="form-label">Apellido Materno:</label>
                                <input type="text" id="usuario_apellido_m" name="usuario_apellido_m" class="form-control" required>
                                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-sm-12 col-md-3 mt-3 mr-3">
                                <label for="edad" class="form-label">Edad:</label>
                                <input type="number" id="edad" name="edad" class="form-control" required>
                                <div class="invalid-feedback">La edad debe estar entre 18 y 90 años.</div>
                            </div>
                            <div class="col-sm-12 col-md-4 mt-3">
                                <label for="sexo_sexo_id" class="form-label">Sexo:</label>
                                <select id="sexo_sexo_id" name="sexo_sexo_id" class="form-control" required>
                                    <option value="" disabled selected>Seleccione una opcion.</option>
                                    <?php foreach ($sexo as $sexo): ?>
                        <option value="<?php echo $sexo['sexo_id']; ?>"><?php echo htmlspecialchars($sexo['descripcion']); ?></option>
                      <?php endforeach; ?>

                                </select>
                                <div class="invalid-feedback">Seleccione alguna opcion.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 2: Datos Profesionales -->
                    <div id="step-2" class="step-content" style="display: none;">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 mt-3">
                                <label for="numero_empleado" class="form-label">Número de Empleado:</label>
                                <input type="text" id="numero_empleado" name="numero_empleado" class="form-control" required>
                                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                            </div>
                            <div class="col-sm-12 col-md-4 mt-3">
                                <label for="grado_academico" class="form-label">Grado Académico:</label>
                                <input type="text" id="grado_academico" name="grado_academico" class="form-control" required>
                                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                            </div>
                            <div class="col-sm-12 col-md-4 mt-3">
                                <label for="cedula" class="form-label">Cédula:</label>
                                <input type="text" id="cedula" name="cedula" class="form-control" required>
                                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-4 mt-3">
                                <label for="carrera_carrera_id" class="form-label">Carrera:</label>
                                <select id="carrera_carrera_id" name="carrera_carrera_id" class="form-control" required>
                                    <option value="" disabled selected>Seleccione una carrera</option>
                                    <?php foreach ($carreras as $carrera): ?>
                        <option value="<?php echo $carrera['carrera_id']; ?>"><?php echo htmlspecialchars($carrera['nombre_carrera']); ?></option>
                      <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                            </div>
                            <div class="col-sm-12 col-md-4 mt-3">
                                <label for="cuerpo_colegiado_cuerpo_colegiado_id" class="form-label">Cuerpo Colegiado:</label>
                                <select id="cuerpo_colegiado_cuerpo_colegiado_id" name="cuerpo_colegiado_cuerpo_colegiado_id" class="form-control" required>
                                <?php foreach ($cuerposColegiados as $cuerpo): ?>
        <option value="<?php echo $cuerpo['cuerpo_colegiado_id']; ?>"><?php echo htmlspecialchars($cuerpo['descripcion']); ?></option>
    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                            </div>

                            <div class="col-sm-12 col-md-4 mt-3">
                                <label for="fecha_contratacion" class="form-label">Fecha de Contratación:</label>
                                <input type="date" id="fecha_contratacion" name="fecha_contratacion" class="form-control" required>
                                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                            </div>
                    </div>
                    </div>

                    <!-- Paso 3: Correo y Contraseña -->
                    <div id="step-3" class="step-content" style="display: none;">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 mt-3">
                                <label for="correo" class="form-label">Correo:</label>
                                <input type="email" id="correo" name="correo" class="form-control" required>
                                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                            </div>
                            <div class="col-sm-12 col-md-6 mt-3">
                                <label for="password" class="form-label">Contraseña:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                                <div class="invalid-feedback">Minimo 8 Caracteres.</div>
                            </div>
                            <div class="col-sm-12 col-md-6 mt-3">
                                <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                <div class="invalid-feedback">Las contraseñas deben coincidir.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 4: Datos Finales -->
                    <div id="step-4" class="step-content" style="display: none;">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 mt-3">
                                <label for="tipo_usuario_tipo_usuario_id" class="form-label">Tipo de Usuario:</label>
                                <select id="tipo_usuario_tipo_usuario_id" name="tipo_usuario_tipo_usuario_id" class="form-control" required>
                                    <option value="" disabled selected>Seleccione un tipo de usuario</option>
                                    <?php foreach ($tiposUsuario as $tipo): ?>
                           <option value="<?php echo $tipo['tipo_usuario_id']; ?>"><?php echo htmlspecialchars($tipo['descripcion']); ?></option>
    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                            </div>
                            <div class="col-sm-12 col-md-6 mt-3">
                                <label for="fileInput" class="form-label">Subir Imagen:</label>
                                <input type="file" id="fileInput" class="form-control" name="imagen_url" accept="image/*" required onchange="previewImage()">
                            </div>
                            <div class="col-sm-12 mt-3 d-flex justify-content-center">
                                <img id="imagePreview" src="#" alt="Vista previa de la imagen" style="display:none; width: 200px; height: 200px;">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <span>Revise todos sus datos antes de confirmar el registro.</span>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success btn-lg">Registrar Usuario</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
</div>

  </main>
  <!-- Incluir SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- SmartWizard JS -->
    <script src="js/jquery.smartWizard.min.js"></script>
    <script src="js/form_usuario.js"></script>
    <script src="js/apps.js"></script>
  </body>
</html>