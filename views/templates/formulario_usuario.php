<?php
include('../../models/session.php');
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
  </head>
  <body class="vertical  light">
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
      <?php
// Incluir la conexión a la base de datos y la clase Usuario
require_once '../../controllers/db.php'; // Asegúrate de que esta ruta sea correcta
require_once '../../models/consultas.php'; // Asegúrate de que esta ruta sea correcta

// Crear una instancia de la clase Usuario
$usuario = new Usuario($conn);

// Obtener datos necesarios
$sexos = $conn->query("SELECT sexo_id, descripcion FROM sexo")->fetchAll(PDO::FETCH_ASSOC);
$statuses = $conn->query("SELECT status_id, descripcion FROM status")->fetchAll(PDO::FETCH_ASSOC);
$tipos_usuario = $conn->query("SELECT tipo_usuario_id, descripcion FROM tipo_usuario")->fetchAll(PDO::FETCH_ASSOC);
$carreras = $conn->query("SELECT carrera_id, nombre_carrera AS descripcion FROM carrera")->fetchAll(PDO::FETCH_ASSOC);
$cuerpos_colegiados = $conn->query("SELECT cuerpo_colegiado_id, descripcion FROM cuerpo_colegiado")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="conteiner p-4">
        <!-- Mensajes de estado -->
        <?php if (isset($_GET['status'])): ?>
        <div class="alert alert-success" role="alert">
          Datos guardados exitosamente.
        </div>
      <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger" role="alert">
          Hubo un error al guardar los datos.
        </div>
      <?php endif; ?>
<!-- Formulario para subir datos de usuario -->
<div class="container-fluid mt-5 bg-white rounded border border-black p-5">
        <form id="formUsuario" method="post" action="../../models/insert.php" enctype="multipart/form-data">
        <input type="hidden" name="form_type" value="usuario"> <!-- Campo oculto para el tipo de formulario -->
        <div class="row">
        <div class="container p-4">
        <h2 class="text-center">Registrar Usuario</h2>
        <div id="smartwizard">
            <ul>
                <li><a href="#step-1">Paso 1<br /><small>Datos Personales</small></a></li>
                <li><a href="#step-2">Paso 2<br /><small>Datos Profecionales</small></a></li>
                <li><a href="#step-3">Paso 3<br /><small>Correo y Contraseña</small></a></li>
                <li><a href="#step-4">Paso 4<br /><small>Datos Finales</small></a></li>
            </ul>
            <div class="mt-4">
                    <!-- Paso 1: Datos Personales -->
                    <div id="step-1" class="step-content" style="display: block;">
    <!-- Fila para Nombre y Apellidos -->
    <div class="row">
        <div class="col-md-4 mt-3">
            <label for="usuario_nombre" class="form-label">Nombre:</label>
            <input type="text" id="usuario_nombre" name="usuario_nombre" class="form-control" required>
            <div class="invalid-feedback">Este campo no puede estar vacío.</div>
        </div>
        <div class="col-md-4 mt-3">
            <label for="usuario_apellido_p" class="form-label">Apellido Paterno:</label>
            <input type="text" id="usuario_apellido_p" name="usuario_apellido_p" class="form-control" required>
            <div class="invalid-feedback">Este campo no puede estar vacío.</div>
        </div>
        <div class="col-md-4 mt-3">
            <label for="usuario_apellido_m" class="form-label">Apellido Materno:</label>
            <input type="text" id="usuario_apellido_m" name="usuario_apellido_m" class="form-control" required>
            <div class="invalid-feedback">Este campo no puede estar vacío.</div>
        </div>
    </div>

    <!-- Fila para Edad y Sexo -->
    <div class="row justify-content-center mt-3">
        <div class="col-md-3 m-3">
            <label for="edad" class="form-label">Edad:</label>
            <input type="number" id="edad" name="edad" class="form-control" required>
            <div class="invalid-feedback">Este campo no puede estar vacío.</div>
        </div>
        <div class="col-md-3 m-3">
            <label for="sexo_sexo_id" class="form-label">Sexo:</label>
            <select id="sexo_sexo_id" name="sexo_sexo_id" class="form-control" required>
                <option value="" disabled selected>Seleccione su sexo</option>
                <?php foreach ($sexos as $sexo): ?>
                    <option value="<?= $sexo['sexo_id']; ?>"><?= $sexo['descripcion']; ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Este campo no puede estar vacío.</div>
        </div>
    </div>
                    </div>
                    <!-- Paso 2: Información Académica -->
                    <div id="step-2" class="step-content" style="display: none;">
                    <div class="row">
                    <div class="col-md-4 mt-3">
                                <label for="numero_empleado" class="form-label">Número de Empleado:</label>
                                <input type="text" id="numero_empleado" name="numero_empleado" class="form-control" required>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label for="grado_academico" class="form-label">Grado Académico:</label>
                                <input type="text" id="grado_academico" name="grado_academico" class="form-control" required>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label for="cedula" class="form-label">Cédula:</label>
                                <input type="text" id="cedula" name="cedula" class="form-control" required>
                            </div>

                            <div class="col-md-4 mt-3">
                      <label for="carrera_carrera_id" class="form-label">Carrera:</label>
                      <select id="carrera_carrera_id" name="carrera_carrera_id" class="form-control" required>
                          <option value="" disabled selected>Seleccione una carrera</option>
                          <?php foreach ($carreras as $carrera): ?>
                              <option value="<?= $carrera['carrera_id']; ?>"><?= $carrera['descripcion']; ?></option>
                          <?php endforeach; ?>
                      </select>
                      <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                        </div>

                        <div class="col-md-4 mt-3">
                      <label for="cuerpo_colegiado_cuerpo_colegiado_id" class="form-label">Cuerpo Colegiado:</label>
                      <select id="cuerpo_colegiado_cuerpo_colegiado_id" name="cuerpo_colegiado_cuerpo_colegiado_id" class="form-control" required>
                          <option value="" disabled selected>Seleccione un cuerpo colegiado</option>
                          <?php foreach ($cuerpos_colegiados as $cuerpo): ?>
                              <option value="<?= $cuerpo['cuerpo_colegiado_id']; ?>"><?= $cuerpo['descripcion']; ?></option>
                          <?php endforeach; ?>
                      </select>
                      <div class="invalid-feedback">Este campo no puede estar vacío.</div>
              </div>
              <div class="col-md-4 mt-3">
                    <label for="fecha_contratacion" class="form-label">Fecha de Contratación:</label>
                    <input type="date" id="fecha_contratacion" name="fecha_contratacion" class="form-control" required>
                    <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                    </div>
                    </div>
                    </div>
    
                    <!-- Paso 3: Detalles Profesionales -->
                    <div id="step-3" class="step-content" style="display: none;">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="correo" class="form-label">Correo:</label>
                <input type="email" id="correo" name="correo" class="form-control" required>
                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                <div class="invalid-feedback">Las contraseñas deben coincidir.</div>
            </div>
        </div>
    </div>
</div>
                    <!-- Paso 4: Confirmar -->
                    <div id="step-4" class="step-content" style="display: none;">
                        <div class="row">
                        <div class="col-md-6 mt-3">
                      <label for="tipo_usuario_tipo_usuario_id" class="form-label">Tipo de Usuario:</label>
                      <select id="tipo_usuario_tipo_usuario_id" name="tipo_usuario_tipo_usuario_id" class="form-control" required>
                          <option value="" disabled selected>Seleccione un tipo de usuario</option>
                          <?php foreach ($tipos_usuario as $tipo): ?>
                              <option value="<?= $tipo['tipo_usuario_id']; ?>"><?= $tipo['descripcion']; ?></option>
                          <?php endforeach; ?>
                      </select>
                      <div class="invalid-feedback">Este campo no puede estar vacío.</div>
              </div>
                            <div class="col-md-6 mt-3">
                                <label for="fileInput" class="form-label">Subir Imagen:</label>
                                <input type="file" id="fileInput" class="form-control" name="imagen_url" accept="image/*" required onchange="previewImage()">
                            </div>
                            <div class="col-md-12 mt-3 d-flex justify-content-center">
                                <img id="imagePreview" src="#" alt="Vista previa de la imagen" style="display:none; width: 200px; height: 200px;" />
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12 mt-3 text-center">
                                <span>Revise todos sus datos antes de confirmar el registro.</span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success btn-lg">Registrar Usuario</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/jquery.smartWizard.min.js"></script>
    <script src="js/form_usuario.js"></script>
  </body>
</html>