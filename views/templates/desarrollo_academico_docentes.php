<?php
// Incluye las dependencias necesarias
include('../../models/session.php');
include('../../controllers/db.php'); // Asegúrate de que este archivo incluya la conexión a la base de datos.
include('../../models/consultas.php'); // Incluir la clase de consultas que ya existe

// Inicializa la respuesta por defecto para posibles errores
$response = ['status' => 'error', 'message' => ''];

try {
    // Crear una instancia de la clase Consultas
    $consultas = new Consultas($conn);

    // Obtén las carreras
    $carreras = $consultas->obtenerCarreras();

    // Obtén la carrera seleccionada desde los parámetros GET
    $carreraSeleccionada = isset($_GET['carrera_id']) ? intval($_GET['carrera_id']) : null;

    // Si se selecciona una carrera, modificar la consulta para filtrar por carrera
    if ($carreraSeleccionada) {
        $usuarios = $consultas->obtenerUsuariosPorCarrera($carreraSeleccionada);
    } else {
        $usuarios = $consultas->obtenerTodosLosUsuarios();
    }

    // Obtenemos el idusuario actual (si no está definido, usamos el primero de la lista de usuarios)
    $idusuario = isset($_GET['idusuario']) ? intval($_GET['idusuario']) : $usuarios[0]['usuario_id'];

    // Verificamos si el idusuario actual pertenece a la lista filtrada
    $usuarioActual = array_filter($usuarios, function($usuario) use ($idusuario) {
        return $usuario['usuario_id'] == $idusuario;
    });

    // Si el usuario actual no está en la lista, usa el primer usuario del filtro
    if (!$usuarioActual) {
        $idusuario = $usuarios[0]['usuario_id'];
        $usuarioActual = $usuarios[0];
    } else {
        $usuarioActual = array_values($usuarioActual)[0]; // Reindexar array
    }

    // Procesamos cada usuario para añadirle antigüedad y carrera
    foreach ($usuarios as &$usuario) {
        // Llamamos al método para obtener la carrera del usuario
        $carrera = $consultas->obtenerCarreraPorUsuarioId($usuario['usuario_id']);
        if ($carrera) {
            $usuario = array_merge($usuario, $carrera); // Agregamos la carrera al array del usuario
        } else {
            $usuario['nombre_carrera'] = 'N/A'; // Si no tiene carrera asignada, ponemos 'N/A'
        }

        // Calculamos la antigüedad solo si tiene fecha de contratación
        if (isset($usuario["fecha_contratacion"])) {
            $fechaContratacionDate = new DateTime($usuario["fecha_contratacion"]);
            $fechaActual = new DateTime();
            $antiguedad = $fechaContratacionDate->diff($fechaActual)->y;
            $usuario['antiguedad'] = $antiguedad;
        } else {
            $usuario['antiguedad'] = 'N/A'; // Si no hay fecha de contratación, se asigna 'N/A'
        }
    }

    // Actualiza el estado de respuesta a éxito
    $response['status'] = 'success';
    $response['usuarios'] = $usuarios; // Almacena la información de los usuarios filtrados
    $response['carreras'] = $carreras; // Almacena la lista de carreras en la respuesta
    $response['usuario'] = $usuarioActual; // El usuario actual en la respuesta

    // Si la solicitud es AJAX, devuelve la respuesta en formato JSON
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

} catch (Exception $e) {
    // Si falla la conexión, retorna un error
    $response['message'] = 'Error al conectar con la base de datos: ' . $e->getMessage();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
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
  </link>
  </link>

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



        <div class="card text-center">
  <div class="card-body">
    <h5 class="card-title">Filtrado por División</h5>
    <div class="filter-container" style="position: relative; display: inline-block;">
      <button id="filterBtn" class="btn btn-primary">Seleccionar División</button>
      <div id="filterOptions" class="filter-options d-none" style="position: absolute; z-index: 100; background-color: white; border: 1px solid #ccc;">
        <?php foreach ($carreras as $carrera): ?>
          <div class="dropdown-item" data-value="<?php echo $carrera['carrera_id']; ?>">
            <?php echo htmlspecialchars($carrera['nombre_carrera']); ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<script>
  // Obtener el botón y las opciones
  const filterBtn = document.getElementById('filterBtn');
  const filterOptions = document.getElementById('filterOptions');
  const dropdownItems = document.querySelectorAll('.dropdown-item');

  // Alternar la visibilidad de las opciones
  filterBtn.addEventListener('click', () => {
    filterOptions.classList.toggle('d-none');
  });

 // Cambiar el texto del botón cuando se selecciona una opción y actualizar la URL con la carrera seleccionada
dropdownItems.forEach(item => {
  item.addEventListener('click', () => {
    const selectedCarreraId = item.getAttribute('data-value');
    const selectedText = item.textContent;
    
    filterBtn.textContent = selectedText; // Cambiar el texto del botón
    filterOptions.classList.add('d-none'); // Ocultar las opciones después de seleccionar

    // Actualizar la URL para incluir el filtro por carrera
    const newUrl = new URL(window.location.href);
    newUrl.searchParams.set('carrera_id', selectedCarreraId);
    window.location.href = newUrl.toString(); // Recargar la página con el filtro de carrera
  });
});


  // Ocultar el menú si se hace clic fuera del contenedor
  document.addEventListener('click', (event) => {
    if (!filterBtn.contains(event.target) && !filterOptions.contains(event.target)) {
      filterOptions.classList.add('d-none');
    }
  });
</script>


  <!-- Código HTML del carrusel -->
<main role="main" class="main-content">
<div id="teacherCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="container-fluid mb-3">
          <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile flag-div">
            PERFIL DOCENTE
          </div>
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

                      <div class="col-10">
                      <div class="carousel-inner" id="carouselContent">
  <?php foreach ($usuarios as $index => $usuario): ?>
    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" data-id="<?= htmlspecialchars($usuario['usuario_id']) ?>">
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
      </div>
    </div>
  <?php endforeach; ?>
</div>

                      <button class="carousel-control-next col-1 btn btn-primary" type="button" id="siguiente">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden"></span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <script>
  // Obtener el idusuario y carrera_id actuales desde la URL
  const urlParams = new URLSearchParams(window.location.search);
  let idusuario = parseInt(urlParams.get("idusuario")) || 1; // Si no hay idusuario en la URL, empezamos en 1
  let carreraId = urlParams.get("carrera_id"); // Obtiene la carrera seleccionada, si la hay

  // Seleccionar los botones de navegación
  const anterior = document.getElementById("anterior");
  const siguiente = document.getElementById("siguiente");

  // Función para actualizar el contenido del carrusel sin recargar la página
  async function loadUserData(newIdusuario) {
    try {
      // Realizar una petición AJAX al servidor para obtener los datos del nuevo usuario
      const response = await fetch(`desarrollo_academico_docentes.php?idusuario=${newIdusuario}&carrera_id=${carreraId}`);
      const data = await response.json();

      // Verificar si los datos fueron correctamente obtenidos
      if (data.status === 'success') {
        console.log('Datos recibidos:', data); // Añadir esto para verificar si los datos están llegando correctamente
        const usuario = data.usuario;

        // Actualizar los elementos dentro del carrusel con los nuevos datos
        document.querySelector(".tamanoImg").src = '../' + usuario.imagen_url;
        document.getElementById("teacherInfo").innerHTML = `
          <strong class="name-line">Docente:</strong> ${usuario.nombre_usuario} ${usuario.apellido_p} ${usuario.apellido_m}<br>
          <strong class="name-line">Edad:</strong> ${usuario.edad} años <br>
          <strong class="name-line">Fecha de contratación:</strong> ${usuario.fecha_contratacion} <br>
          <strong class="name-line">Antigüedad:</strong> ${usuario.antiguedad} años <br>
          <strong class="name-line">División Adscrita:</strong> ${usuario.nombre_carrera} <br>
          <strong class="name-line">Número de Empleado:</strong> ${usuario.numero_empleado} <br>
          <strong class="name-line">Grado académico:</strong> ${usuario.grado_academico} <br>
          <strong class="name-line">Cédula:</strong> ${usuario.cedula} <br>
          <strong class="name-line">Correo:</strong> ${usuario.correo} <br>
        `;
      } else {
        console.error('Error al cargar los datos del usuario:', data.message);
      }
    } catch (error) {
      console.error('Error al cargar los datos del usuario:', error);
    }
  }

  // Función para actualizar la URL sin recargar la página
  function updateUrl(newIdusuario) {
    let url = `?idusuario=${newIdusuario}`;
    if (carreraId) {
      url += `&carrera_id=${carreraId}`; // Mantener la carrera seleccionada en la URL
    }
    // Actualizar el parámetro idusuario en la URL sin recargar la página
    window.history.pushState(null, '', url);

    // Cargar los nuevos datos del usuario
    loadUserData(newIdusuario);
  }

  // Cargar un nuevo usuario al hacer clic en el botón "Siguiente"
  siguiente.addEventListener("click", () => {
    idusuario++; // Incrementa el ID del usuario
    updateUrl(idusuario); // Actualiza la URL y carga los nuevos datos
  });

  // Lógica para ir al usuario anterior (si es necesario)
  anterior.addEventListener("click", () => {
    if (idusuario > 1) { // Asegúrate de que no baje de 1
      idusuario--; // Decrementa el ID del usuario
      updateUrl(idusuario); // Actualiza la URL y carga los nuevos datos
    }
  });

  // Cargar los datos iniciales del usuario cuando la página se carga
  loadUserData(idusuario);
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
  <!------>
  <script>
  // Referencias a los elementos
  // Referencias a los elementos
let filterBtn = document.getElementById('filterBtn');
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