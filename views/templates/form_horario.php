<?php
include('../../controllers/db.php');
include('../../models/consultas.php');
include('../../models/session.php');
include('aside.php');

$idusuario = $_SESSION['user_id']; // Asumimos que el ID ya está en la sesión

// Inicializa la respuesta por defecto
$response = ['status' => 'error', 'message' => ''];

// Intenta conectar a la base de datos
try {
    // Inicializa las consultas
    $consultas = new Consultas($conn);
    // Obtén las carreras
    $edificios = $consultas->obtenerEdificio();
    $salones = $consultas->obtenerSalones();
    $periodos = $consultas->obtenerPeriodo();
    $carreras = $consultas->obtenerCarreras(); 
    $usuarios = $consultas->obtenerUsuariosDocentes();
    $materias = $consultas->verMaterias();
    $grupos = $consultas->obtenerGrupos();
    $salones = $consultas->obtenerSalon();

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
            <div class="form-group">
                <label for="periodo" class="form-label-custom">Periodo:</label>
                <select class="form-control" id="periodo" name="periodo" required onchange="filtrarHorario()">
                  <option value="">Selecciona un periodo</option>
                  <?php foreach ($periodos as $periodo): ?>
                    <option value="<?php echo $periodo['periodo_id']; ?>"><?php echo htmlspecialchars($periodo['descripcion']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              
        </div>
        <div class="row">
          
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
<div class="col-md-6">
        <div class="form-group  mt-2">
              <label for="carrera" class="form-label">Carrera:</label>
              <select class="form-control" id="carrera" name="carrera" required onchange="filtrarHorario()">
                <option value="">Selecciona una carrera</option>
                <?php foreach ($carreras as $carrera): ?>
                  <option value="<?php echo $carrera['carrera_id']; ?>"><?php echo htmlspecialchars($carrera['nombre_carrera']); ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Este campo no puede estar vacío.</div>
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

          <div class="firmas">
        <div class="firma">
            <p>M. ISC. MARTHA AMPARO SOTO RODRÍGUEZ</p>
            <p>__________________________</p>
        </div>
        <div class="firma">
            <p>MTRA. MICOL EDITH GENIS LÓPEZ</p>
            <p>__________________________</p>
        </div>
    </div>

    <div class="pdf-container no-print">
        <button id="downloadPDF">Descargar como PDF</button>
    </div>
<!-- Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Información Seleccionada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalContent">Día y hora seleccionados.</p>


                    <input type="hidden" id="hora" name="hora"> <!-- Campo oculto para horas_id -->
                    <input type="hidden" id="dia" name="dia">   <!-- Campo oculto para dias_id -->

                    <div class="row">
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

                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModalBtn" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button> <!-- Botón de envío -->
                </div>
            </div>
    </div>
</div>

     </div>
    </div>
    
    
  
<script>
  document.addEventListener("DOMContentLoaded", function () {
    let myModal;
    
    const cells = document.querySelectorAll("tbody tr td:not(:first-child)");
    
    cells.forEach((cell) => {
      cell.addEventListener("click", function () {
        const columnIndex = this.cellIndex;
        const day = document.querySelector(`thead th:nth-child(${columnIndex + 1})`).innerText;
        const time = this.parentElement.querySelector("td:first-child").innerText;
        document.getElementById("modalContent").innerText = `Día: ${day}\nHora: ${time}`;
        myModal = new bootstrap.Modal(document.getElementById("infoModal"));
        myModal.show();
      });
    });
    
    document.getElementById("closeModalBtn").addEventListener("click", function () {
                if (myModal) {
                    myModal.hide();
                }
              });
            });
    </script>

<script>
async function filtrarHorario() {
    const periodo = document.getElementById('periodo').value;
    const usuarioId = document.getElementById('usuario_usuario_id').value;
    const carrera = document.getElementById('carrera').value;

    if (!periodo || !usuarioId || !carrera) {
        alert('Por favor, completa todos los filtros.');
        return;
    }

    try {
        const response = await fetch(`../../models/cargar_horario.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ periodo, usuarioId, carrera }),
        });

        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

        const data = await response.json();

        if (data.error) {
            alert(data.error);
            return;
        }

        // Define las horas y días
        const horas = [
            { id: 1, descripcion: '07:00 - 08:00' },
            { id: 2, descripcion: '08:00 - 09:00' },
            { id: 3, descripcion: '09:00 - 10:00' },
            { id: 4, descripcion: '10:00 - 11:00' },
            { id: 5, descripcion: '11:00 - 12:00' },
            { id: 6, descripcion: '12:00 - 13:00' },
            { id: 7, descripcion: '13:00 - 14:00' },
            { id: 8, descripcion: '14:00 - 15:00' },
            { id: 9, descripcion: '15:00 - 16:00' },
            { id: 10, descripcion: '16:00 - 17:00' },
            { id: 11, descripcion: '17:00 - 18:00' },
            { id: 12, descripcion: '18:00 - 19:00' },
            { id: 13, descripcion: '19:00 - 20:00' },
            { id: 14, descripcion: '20:00 - 21:00' },
        ];

        const dias = [
            { id: 1, descripcion: 'Lunes' },
            { id: 2, descripcion: 'Martes' },
            { id: 3, descripcion: 'Miércoles' },
            { id: 4, descripcion: 'Jueves' },
            { id: 5, descripcion: 'Viernes' },
        ];

        // Obtener el contenedor de la tabla
        const tablaContenedor = document.querySelector('.schedule-container .table-responsive');
        if (!tablaContenedor) {
            console.error('No se encontró el contenedor de la tabla.');
            return;
        }

        // Construir la tabla HTML
        let tablaHTML = `
            <table class="table table-borderless table-striped">
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Lunes</th>
                        <th>Martes</th>
                        <th>Miércoles</th>
                        <th>Jueves</th>
                        <th>Viernes</th>
                    </tr>
                </thead>
                <tbody>`;

        horas.forEach(hora => {
            tablaHTML += `<tr>`;
            tablaHTML += `<td>${hora.descripcion}</td>`;

            dias.forEach(dia => {
                const evento = data.find(item => item.horas_horas_id == hora.id && item.dias_id == dia.id);
                const contenido = evento
                    ? `${evento.materia}<br>${evento.grupo}<br>${evento.salon}`
                    : '';
                tablaHTML += `<td class="editable-cell" data-horas-id="${hora.id}" data-dia-id="${dia.id}">${contenido}</td>`;
            });

            tablaHTML += `</tr>`;
        });

        tablaHTML += `
                </tbody>
            </table>`;

        // Actualizar el contenido del contenedor
        tablaContenedor.innerHTML = tablaHTML;

        // Agregar eventos de clic a las celdas generadas
        agregarEventosCeldas();

    } catch (error) {
        console.error('Error al filtrar el horario:', error);
        alert('Ocurrió un error al intentar filtrar el horario.');
    }
}

// Función para agregar los eventos de clic a las celdas
function agregarEventosCeldas() {
    const cells = document.querySelectorAll(".editable-cell");

    if (cells.length === 0) {
        console.warn("No se encontraron celdas con la clase 'editable-cell'.");
        return;
    }

    cells.forEach((cell) => {
        cell.addEventListener("click", function () {
            const horaId = this.dataset.horasId; // ID de la hora
            const diaId = this.dataset.diaId;   // ID del día

            const diaTexto = document.querySelector(`thead th:nth-child(${parseInt(diaId) + 1})`).innerText; // Día desde el encabezado
            const horaTexto = this.parentElement.querySelector("td:first-child").innerText; // Hora desde la fila

            // Actualizar el contenido del modal
            document.getElementById("modalContent").innerText = `Día: ${diaTexto}\nHora: ${horaTexto}`;
            document.getElementById("hora").value = horaId; // Asignar hora ID al campo oculto
            document.getElementById("dia").value = diaId;   // Asignar día ID al campo oculto

            // Mostrar el modal
            let myModal = new bootstrap.Modal(document.getElementById("infoModal"));
            myModal.show();
        });
    });
}

</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
  document.getElementById("downloadPDF").addEventListener("click", () => {
    const button = document.querySelector('.pdf-container');
    button.style.display = 'none';
    
    const element = document.getElementById("contenedor");
    
    // Configuración del PDF
    const options = {
      margin: 0.5,
      filename: 'horario_isc.pdf',
      image: { type: 'jpeg', quality: 1 },
      html2canvas: {
        scale: 3, // Alta resolución
        scrollY: 0,
        useCORS: true, // Permitir imágenes externas
      },
      jsPDF: {
        unit: 'px', // Usar píxeles para precisión
        format: [element.scrollWidth, element.scrollHeight], // Tamaño dinámico basado en el contenido
        orientation: 'portrait', // Orientación vertical
        },
    };

    // Ajustar temporalmente el tamaño del contenedor para que encaje en una sola hoja
    const originalStyle = element.getAttribute("style");
    element.style.width = "100%"; // Ajuste dinámico del ancho
    element.style.overflow = "hidden"; // Evitar desbordes

    // Generar el PDF
    html2pdf()
        .set(options)
        .from(element)
        .save()
        .finally(() => {
            // Restaurar estilos originales
            element.setAttribute("style", originalStyle || "");
            button.style.display = 'block';
        });
});
</script>

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