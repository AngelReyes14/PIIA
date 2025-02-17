<?php
// Incluir los archivos necesarios
include('../../models/session.php');
include('../../controllers/db.php'); // Conexión a la base de datos
include('../../models/consultas.php'); // Incluir la clase de consultas
include('aside.php');

// Crear una instancia de la clase Consultas pasando la conexión
$consultas = new Consultas($conn);

// Obtener la imagen del usuario actual
$idusuario = $_SESSION['user_id']; 
$imgUser = $consultas->obtenerImagen($idusuario);

// Obtener datos de la carrera
$carreraData = $consultas->datosCarreraPorId($idusuario);
$carreraId = $carreraData ? $carreraData['carrera_id'] : null;

// Obtener datos de docentes, grupos y turnos
$docentes = $consultas->docentesCarrera($carreraId);
$grupos = $consultas->gruposCarrera($carreraId);
$matutino = $consultas->gruposTurnoMatutino($carreraId);
$vespertino = $consultas->gruposTurnoVespertino($carreraId);
$maestros = $consultas->CarreraMaestros($carreraId);
$incidencia = $consultas->Incidenciausuario($carreraId);

// Verificar y obtener los datos de mujeres y hombres en la carrera
$mujeres = $carreraId ? $consultas->mujeresCarrera($carreraId) : 0;
$hombres = $carreraId ? $consultas->hombresCarrera($carreraId) : 0;

// Obtener certificaciones tipo 1 y tipo 2
$certificacionesTipo1 = $consultas->obtenerCertificacionesTipo2(1);
$certificacionesTipo2 = $consultas->obtenerCertificacionesTipo2(2);

// Obtener certificaciones de todos los usuarios por mes
$certificaciones = $consultas->obtenerCertificacionesPorMes();

// Lista de todos los meses asegurando que la gráfica los muestre
$todosMeses = [
    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
];

// Inicializar arrays para certificaciones en cada mes
$certificaciones1PorMes = array_fill(0, 12, 0); 
$certificaciones2PorMes = array_fill(0, 12, 0);

// Asignar valores desde la consulta SQL a los arrays correspondientes
foreach ($certificaciones as $row) {
    $mesIndex = array_search($row['nombre_mes'], $todosMeses);
    if ($mesIndex !== false) {
        $certificaciones1PorMes[$mesIndex] = (int) $row['cantidad_certificaciones_tipo_1'];
        $certificaciones2PorMes[$mesIndex] = (int) $row['cantidad_certificaciones_tipo_2'];
    }
}

// Obtener incidencias por carrera
$incidenciasCarrera = $consultas->IncidenciasCarreraGrafic();
$carreras = [];
$incidencias = [];
$promedios = [];

// Recorrer las incidencias por carrera y almacenarlas
foreach ($incidenciasCarrera as $row) {
    $carreras[] = $row['nombre_carrera']; 
    
    // Validación para evitar Undefined array key
    $cantidadRegistros = isset($row['cantidad_registros']) ? (int) $row['cantidad_registros'] : 0;
    $porcentaje = isset($row['porcentaje']) ? round($row['porcentaje'], 2) : 0;

    // Agregar los valores al array correspondiente
    $incidencias[] = $cantidadRegistros;
    $promedios[] = $porcentaje;
}

// Obtener incidencias con los nombres de los usuarios
$incidenciasUsuarios = $consultas->obtenerIncidenciasUsuarios();

// Obtener grados académicos de los docentes
$grados = $consultas->obtenerGradosAcademicos();
$labels = [];
$values = [];

foreach ($grados as $grado) {
    $labels[] = $grado['grado_academico'];
    $values[] = $grado['total_usuarios'];
}

// Convertir datos a JSON para pasarlos a JavaScript
$mesesJson = json_encode($todosMeses);
$certificaciones1Json = json_encode($certificaciones1PorMes);
$certificaciones2Json = json_encode($certificaciones2PorMes);
$carrerasJson = json_encode($carreras);
$incidenciasJson = json_encode($incidencias);
$promediosJson = json_encode($promedios);

?>








<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="assets/images/PIIA_oscuro 1.png">
  <title>Plataforma Integradora de Informaciión Academica</title>
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
    <main role="main" class="main-content mt-5">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-12">
            <div class="row">
              <div class="col">

              </div>
              <div class="col-auto">
                <form class="form-inline">
                  <div class="form-group d-none d-lg-inline">
                    <label for="reportrange" class="sr-only">Date Ranges</label>
                    <div id="reportrange" class="px-2 py-2 text-muted">
                      <span class="small"></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <button type="button" class="btn btn-sm"><span class="fe fe-refresh-ccw fe-16 text-muted"></span></button>
                    <button type="button" class="btn btn-sm mr-2"><span class="fe fe-filter fe-16 text-muted"></span></button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Contenedor blanco con borde redondeado negro -->
            <div class="container-fluid mt-5 bg-white rounded border border-black p-5">
              <div class="row">
                <!-- Columna Izquierda (División y Promedio evaluación docente) -->
                <div class="col-md-4">
                  <!-- División de Sistemas Computacionales -->
                  <div class="card p-5 text-center box-shadow-div mb-3 custom-card">
                    <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div cont-div">
                      <strong class="text-white"> <?= $carreraData['nombre_carrera'] ?> </strong>
                    </div>


                    <!-- Contenedor para centrar la imagen -->
                    <div class="d-flex justify-content-center">
                      <img src="<?= '../' . htmlspecialchars($carreraData["imagen_url"]) ?>" alt="Imagen del docente" class="img-fluid">
                    </div>
                  </div>

                  <!-- Promedio evaluación docente -->
                  <div class="card p-5 text-center box-shadow-div mb-3 custom-card">
                    <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div cont-div">
                      Promedio evaluación docente
                    </div>
                    <div class="d-flex justify-content-center">
                      <div id="radialbarWidget"></div>
                    </div>
                    <p>Ciclo 2024</p>
                  </div>
                </div>

                <!-- Columna Derecha (Información de la carrera, texto centrado) -->
                <div class="col-md-8 carta_Informacion">
                  <div class="card p-5 box-shadow-div large-text h-100">
                    <!-- Card verde para el título -->
                    <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 ">
                      <h4 class="text-center text-white mb-0">Información de la carrera</h4>
                    </div>

                    <!-- Contenido de la información de la carrera -->
                    <div class="row">
                      <div class="col-md-6">
                        <p><strong class="text-green">Año de Validación:</strong><br> <?= $carreraData['fecha_validacion'] ?></p>
                        <p><strong class="text-green">Número de Docentes Mujeres:</strong><br> <?= $mujeres ?></p>
                        <p><strong class="text-green">Número de Docentes hombres:</strong><br> <?= $hombres ?></p>
                        <p><strong class="text-green">Número de Docentes:</strong><br> <?= $docentes ?></p>
                      </div>
                      <div class="col-md-6">
                        <p><strong class="text-green">Turno de Grupos:</strong><br> Matutino: <?= $matutino ?>
                          <br> Vespertino: <strong class="text-green"></strong> <?= $vespertino ?>
                        </p>
                        <p><strong class="text-green">Grupos en la carrera:</strong><br> <?= $grupos ?></p>
                        <p><strong class="text-green">Organismo certificador:</strong><br> <?= $carreraData['organismo_auxiliar'] ?? 'N/A' ?></p>
                      </div>
                    </div>

                    <p><strong class="text-green">Acreditación:</strong><br> <?= $carreraData['fecha_inicio_validacion']  ?>
                      al <strong class="text-green"></strong> <?= $carreraData['fecha_fin_validacion'] ?>
                    </p>
                  </div>
                </div>




                <!-- Donut Chart Card -->
                <div class="col-12 col-md-4 carta_Informacion">
                  <div class="card shadow mb-4 box-shadow-div h-100 carta_Informacion">
                    <div class="card-header carta_Informacion">
                      <strong class="card-title text-green mb-0 carta_Informacion">
                        Grado académico de docentes en la división
                      </strong>
                    </div>
                    <!-- Incluye Chart.js -->


                    <div class="card-body text-center">
    <!-- Incluir Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Contenedor de la gráfica tipo donut -->
    <canvas id="donutChart8"></canvas> 
</div> <!-- /.card-body -->

<script>
    // Pasar los datos desde PHP a JavaScript
    var labels = <?php echo json_encode($labels); ?>;
    var values = <?php echo json_encode($values); ?>;

    window.onload = function() {
        var ctx = document.getElementById('donutChart8').getContext('2d'); // Obtener el contexto

        // Crear la gráfica tipo donut
        var donutChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels, 
                datasets: [{
                    label: 'Total de Usuarios',
                    data: values,
                    backgroundColor: ['#006400', '#228B22', '#32CD32'], // Colores verde oscuro, medio y claro
                    hoverBackgroundColor: ['#004d00', '#1e7e1e', '#28a745'] // Colores de hover más oscuros
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' usuarios'; 
                            }
                        }
                    }
                }
            }
        });
    }
</script>

<style>
    /* Asegurarse de que el gráfico sea más pequeño y redondo */
    #donutChart8 {
        width: 100px;  /* Ajustar el tamaño más pequeño */
        height: 100px; /* Mantener la proporción redonda */
    }
</style>

                  </div> <!-- /.card -->
                </div> <!-- /.col -->



                <!-- Tabla de Docentes -->
                

<!-- Contenedor de la tabla con scroll y encabezado fijo -->
<div class="col-12 col-md-8 mt-5 carta_Informacion">
    <div class="table-section p-6 border rounded box-shadow-div h-100 carta_Informacion">
        <div class="d-flex justify-content-between align-items-center mb-3 carta_Informacion">
            <h4 class="mb-0 text-green carta_Informacion">Docentes</h4>
        </div>

        <!-- Contenedor con scroll -->
        <div class="table-responsive" style="max-height: 350px; overflow-y: auto; position: relative;">
            <table class="table datatables" id="tabla-materias-2">
                <thead class="thead-dark" style="position: sticky; top: 0; background-color: #343a40; color: white; z-index: 2;">
                    <tr>
                        <th>Nombre</th>
                        <th>Edad</th>
                        <th>Fecha de contratación</th>
                        <th>Número de empleado</th>
                        <th>Cédula</th>
                        <th>Correo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($maestros): ?>
                        <?php foreach ($maestros as $maestroscarrera): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($maestroscarrera['nombre_usuario'] . ' ' . $maestroscarrera['apellido_p'] . ' ' . $maestroscarrera['apellido_m']); ?></td>
                                <td><?php echo htmlspecialchars($maestroscarrera['edad']); ?></td>
                                <td><?php echo htmlspecialchars($maestroscarrera['fecha_contratacion']); ?></td>
                                <td><?php echo htmlspecialchars($maestroscarrera['numero_empleado']); ?></td>
                                <td><?php echo htmlspecialchars($maestroscarrera['cedula']); ?></td>
                                <td><?php echo htmlspecialchars($maestroscarrera['correo']); ?></td>
                            </tr>                  
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay maestros registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div> <!-- Fin del contenedor con scroll -->
    </div>
</div> <!-- /.col -->


              

          <!-- Nuevo Contenedor Principal: Incidencias -->
          <div class="container-fluid mt-5 box-shadow-div p-5">
            <!-- Título de Incidencias -->
            <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile cont-div">
              Incidencias
            </div>

            <!-- Contenedor de la Tarjeta de Incidencias -->
            <div class="container-fluid p-3">
              <div class="row">
                <!-- Columna completa para la tarjeta -->
                <div class="col-12">
                  <div class="card shadow mb-4 box-shadow-div h-100 carta_Informacion">
                    <!-- Encabezado de la Tarjeta -->
                    <div class="card-header">
                      <strong class="card-title text-green mb-0">Resumen de Incidencias</strong>
                    </div>

                    <!-- Cuerpo de la tarjeta -->
                    <div class="card-body text-center">
                      <!-- Incluye Chart.js -->
                   





                      <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

                      <!-- Nuevo contenedor para el gráfico de pastel -->
<div id="donutChart4"></div>



<script>
document.addEventListener("DOMContentLoaded", function() {
    // Obtener datos desde PHP
    var carreras = <?php echo $carrerasJson; ?>;
    var incidencias = <?php echo $incidenciasJson; ?>;

    console.log("Carreras:", carreras);
    console.log("Incidencias:", incidencias);

    // Verificar si hay datos
    if (carreras.length === 0 || incidencias.length === 0) {
        console.warn("No hay datos para mostrar en la gráfica.");
        return;
    }

    // Verificar si ya existe un gráfico en #donutChart4 y destruirlo
    if (typeof chart !== 'undefined' && chart !== null) {
        chart.destroy(); // Destruir el gráfico anterior si existe
    }

    // Configuración del gráfico de dona (donut)
    var options = {
        series: incidencias, // Datos de incidencias
        chart: {
            type: 'donut', // Cambiar a tipo 'donut'
            height: 350
        },
        labels: carreras, // Etiquetas de las carreras
        colors: [
            '#66BB6A', // Verde claro
            '#43A047', // Verde medio
            '#2C6B2F', // Verde más oscuro
            '#1B5E20', // Verde oscuro
            '#81C784', // Verde pastel
            '#388E3C', // Verde fuerte
            '#4CAF50'  // Verde más brillante
        ], // Colores verdes
        legend: {
            position: 'bottom'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '60%' // Controlar el tamaño del agujero en el centro
                }
            }
        }
    };

    // Renderizar el gráfico en el div con ID 'donutChart4'
    var chart = new ApexCharts(document.querySelector("#donutChart4"), options);
    chart.render();
});
</script>





<div id="incidencias-container" style="overflow-y: auto;">
    <table class="table table-striped table-bordered" id="tabla-incidencias">
        <thead class="thead-dark" style="position: sticky; top: 0; background-color: #343a40; color: white; z-index: 2;">
            <tr>
                <th>Número de incidencia</th>
                <th>Usuario</th>
                <th>Fecha solicitada</th>
                <th>Motivo</th>
                <th>Hora de inicio</th>
                <th>Hora de término</th>
                <th>Horario de incidencia</th>
                <th>Día de la incidencia</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($incidenciasUsuarios)): ?>
                <?php foreach ($incidenciasUsuarios as $incidencia): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($incidencia['numero_incidencia']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['usuario']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['fecha_solicitada']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['motivo']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['hora_inicio']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['hora_termino']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['horario_incidencia']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['dia_incidencia']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No hay incidencias registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let table = document.getElementById("tabla-incidencias");
        let container = document.getElementById("incidencias-container");
        let rowCount = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;

        if (rowCount > 5) {
            container.style.maxHeight = "400px"; // Agrega el scroll si hay más de 5 registros
        } else {
            container.style.maxHeight = "auto"; // Sin scroll si hay 5 o menos
        }
    });
</script>


                    </div> <!-- /.card-body -->
                  </div> <!-- /.card -->
                </div> <!-- /.col -->
              </div> <!-- /.row -->
            </div> <!-- /.container-fluid -->
          </div> <!-- /.container-fluid -->



          <!-- Contenedor de Cursos Pedagógicos -->
          <div class="container-fluid mt-5  box-shadow-div p-5">
            <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile cont-div">
            Acreditaciones Pedagógicas
            </div>

            <div class="container-fluid p-3">
              <div class="row">
                <!-- Gráfico de Cursos Pedagógicos -->
                <!-- Aquí se incluye el gráfico -->
                <div class="container-fluid">
  <div class="row my-4">
    <div class="col-md-12">
      <div class="chart-box rounded">
        <canvas id="columnChartTipo1"></canvas> <!-- Contenedor para el gráfico tipo 1 -->
      </div>
    </div> <!-- .col -->
  </div> <!-- end section -->
</div> 

<div class="col-md-12 carta_Informacion">
    <div class="table-section p-6 border rounded box-shadow-div h-100 carta_Informacion">
        <div class="d-flex justify-content-between align-items-center mb-3 carta_Informacion">
            <h4 class="mb-0 text-green carta_Informacion">Acreditaciones Pedagógicas</h4> <!-- Título actualizado -->
        </div>
        <table class="table table-striped carta_Informacion">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Mes</th>
                    <th>Docente</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($certificacionesTipo1)) : ?> <!-- Usamos $certificacionesTipo1 para certificados tipo 1 -->
                    <?php foreach ($certificacionesTipo1 as $certificacion) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($certificacion['nombre_certificado']); ?></td>
                            <td><?php echo htmlspecialchars($certificacion['nombre_mes']); ?></td>
                            <td><?php echo htmlspecialchars($certificacion['nombre_completo']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="text-center">No hay cursos disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> <!-- /.col -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Obtener los datos desde PHP
    var mesesTipo1 = <?php echo $mesesJson; ?>; // Meses para el gráfico tipo 1
    var certificaciones1 = <?php echo $certificaciones1Json; ?>; // Certificaciones tipo 1

    // Configuración del gráfico para certificaciones tipo 1
    var ctxTipo1 = document.getElementById('columnChartTipo1').getContext('2d');
    var columnChartTipo1 = new Chart(ctxTipo1, {
        type: 'bar', // Tipo de gráfico: 'bar' para barras
        data: {
            labels: mesesTipo1, // Los meses
            datasets: [{
                label: 'Certificaciones Pedagógicas', 
                data: certificaciones1, // Cantidades de certificaciones tipo 1
                backgroundColor: 'rgba(59, 204, 23)', // Azul transparente
                borderColor: 'rgb(105, 215, 109)', // Azul fuerte
                borderWidth: 1,
                barThickness: 40, // Hacer las barras más delgadas
                borderRadius: 20, // Esquinas redondeadas
            }]
        },
        options: {
            responsive: true,
            layout: {
                padding: {
                    top: 10,
                    right: 10,
                    bottom: 10,
                    left: 10
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad de Certificaciones'
                    },
                    ticks: {
                        padding: 10
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Mes'
                    },
                    ticks: {
                        padding: 10
                    }
                }
            }
        }
    });
</script>




              </div> <!-- /.row -->
            </div> <!-- /.container-fluid -->
          </div> <!-- /.container-fluid -->

          <!-- Contenedor de Cursos Pedagógicos -->
          <div class="container-fluid mt-5  box-shadow-div p-5">
            <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile cont-div">
            Acreditaciones Profesionales
            </div>
            <div class="container-fluid p-3">
              <div class="row">
                <!-- Gráfico de Cursos Pedagógicos -->
               
                <div class="container-fluid">
  <div class="row my-4">
    <div class="col-md-12">
      <div class="chart-box rounded">
        <canvas id="columnChartTipo2"></canvas> <!-- Contenedor para el gráfico -->
      </div>
    </div> <!-- .col -->
  </div> <!-- end section -->
</div> 

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Obtener los datos desde PHP para certificaciones tipo 2
    var mesesTipo2 = <?php echo $mesesJson; ?>;
    var certificacionesTipo2 = <?php echo $certificaciones2Json; ?>;

    // Configuración del gráfico para certificaciones tipo 2
    var ctxTipo2 = document.getElementById('columnChartTipo2').getContext('2d');
    var columnChartTipo2 = new Chart(ctxTipo2, {
        type: 'bar', // Tipo de gráfico: 'bar' para barras
        data: {
            labels: mesesTipo2, // Los meses
            datasets: [{
                label: 'Certificaciones Profesionales',
                data: certificacionesTipo2, // Cantidades de certificaciones tipo 2
                backgroundColor: 'rgba(7, 118, 24)', // Rojo transparente
                borderColor: 'rgb(16, 102, 29)', // Rojo fuerte
                borderWidth: 1,
                barThickness: 40, // Hacer las barras más delgadas
                borderRadius: 20, // Esquinas redondeadas
            }]
        },
        options: {
            responsive: true,
            layout: {
                padding: {
                    top: 10,
                    right: 10,
                    bottom: 10,
                    left: 10
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad de Certificaciones'
                    },
                    ticks: {
                        padding: 10
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Mes'
                    },
                    ticks: {
                        padding: 10
                    }
                }
            }
        }
    });
</script>

<div class="col-md-12 carta_Informacion">
    <div class="table-section p-6 border rounded box-shadow-div h-100 carta_Informacion">
        <div class="d-flex justify-content-between align-items-center mb-3 carta_Informacion">
            <h4 class="mb-0 text-green carta_Informacion">Acreditaciones Profesionales</h4>
        </div>
        <table class="table table-striped carta_Informacion">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Mes</th>
                    <th>Docente</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($certificacionesTipo2)) : ?>
                    <?php foreach ($certificacionesTipo2 as $certificacion) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($certificacion['nombre_certificado']); ?></td>
                            <td><?php echo htmlspecialchars($certificacion['nombre_mes']); ?></td>
                            <td><?php echo htmlspecialchars($certificacion['nombre_completo']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="text-center">No hay cursos disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> <!-- /.col -->


              </div> <!-- /.row -->
            </div> <!-- /.container-fluid -->
          </div> <!-- /.container-fluid -->

          <!-- Contenedor de Promedio de Calificaciones -->
          <div class="container-fluid mt-5  box-shadow-div p-5">
            <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile cont-div">
              Promedio de Calificaciones
            </div>
            <div class="container-fluid p-3">
              <div class="row">
                <!-- Tabla de Promedio de Calificaciones -->
                <div class="col-md-12 carta_Informacion">
                  <div class="table-section p-6 border rounded box-shadow-div h-100 carta_Informacion">
                    <div class="d-flex justify-content-between align-items-center mb-3 carta_Informacion">
                      <h4 class="mb-0 text-green carta_Informacion">Promedio de Calificaciones</h4>
                    </div>
                    <table class="table table-striped carta_Informacion">
                      <thead>
                        <tr>
                          <th>Docentes</th>
                          <th>Evaluación Estudiantil</th>
                          <th>Evaluación TECNM</th>
                          <th>Promedio por semestre</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Juan Carlos Tinoco Villagran</td>
                          <td>80.0</td>
                          <td>80.0</td>
                          <td>80.0</td>
                        </tr>
                        <tr>
                          <td>Jose Luis Orozco Garcia</td>
                          <td>70.0</td>
                          <td>70.0</td>
                          <td>70.0</td>
                        </tr>
                        <tr>
                          <td>Eden Muñoz Lopez</td>
                          <td>75.0</td>
                          <td>75.0</td>
                          <td>75.0</td>
                        </tr>
                        <tr>
                          <td>Edwin Luna Castillo</td>
                          <td>60.5</td>
                          <td>60.5</td>
                          <td>60.5</td>
                        </tr>
                        <tr>
                          <td>Alfredo Olivas Ruiz</td>
                          <td>82.0</td>
                          <td>82.0</td>
                          <td>82.0</td>
                        </tr>
                        <tr>
                          <td>Cosme Tadeo Lopez Varela</td>
                          <td>90.2</td>
                          <td>90.2</td>
                          <td>90.2</td>
                        </tr>
                        <tr>
                          <td>Virlán García Nuñez</td>
                          <td>98.3</td>
                          <td>98.3</td>
                          <td>98.3</td>
                        </tr>
                        <tr>
                          <td>Cornelio Vega Chairez</td>
                          <td>87.4</td>
                          <td>87.4</td>
                          <td>87.4</td>
                        </tr>
                        <tr>
                          <td>Julion Alvarez Buendla</td>
                          <td>74.1</td>
                          <td>74.1</td>
                          <td>74.1</td>
                        </tr>
                        <tr>
                          <td>Ariel Camacho Torres</td>
                          <td>84.2</td>
                          <td>84.2</td>
                          <td>84.2</td>
                        </tr>
                        <tr>
                          <td>Amanda Rivera de Miguel</td>
                          <td>98.2</td>
                          <td>98.2</td>
                          <td>98.2</td>
                        </tr>
                        <tr>
                          <td>Jenifer Espinoza German</td>
                          <td>95.2</td>
                          <td>95.2</td>
                          <td>95.2</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div> <!-- /.col -->
              </div> <!-- /.row -->
            </div> <!-- /.container-fluid -->
          </div> <!-- /.container-fluid -->

          <!-- Nuevo Contenedor Principal: PERSONAL -->
          <div class="container-fluid mt-5 box-shadow-div p-5">
            <div class="mb-3 font-weight-bold bg-success text-white rounded p-3 box-shadow-div-profile cont-div">
              DIRECCIÓN ACADÉMICA
            </div>
            <div class="container-fluid p-3">
              <div class="row">
                <!-- Donut Chart Card -->
                <div class="col-12 col-md-6 carta_Informacion">
                  <div class="card shadow mb-4 box-shadow-div h-100 carta_Informacion">
                    <div class="card-header carta_Informacion">
                      <strong class="card-title text-green mb-0 carta_Informacion">Grado académico de docentes en la división</strong>
                    </div>
                    <div class="card-body text-center">
                      <div id="donutChart2"></div>
                    </div> <!-- /.card-body -->
                  </div> <!-- /.card -->
                </div> <!-- /.col -->
                <!-- Tabla de Docentes -->
                <div class="col-12 col-md-6 mt-5 carta_Informacion">
                  <div class="table-section p-6 border rounded box-shadow-div h-100 carta_Informacion">
                    <div class="d-flex justify-content-between align-items-center mb-3 carta_Informacion">
                      <h4 class="mb-0 text-green carta_Informacion">Docentes</h4>
                    </div>
                    <table class="table datatables" id="tabla-materias-2">
                      <thead class="thead-dark">
                        <tr>
                          <th>Nombre</th>
                          <th>Edad</th>
                          <th>Fecha de contratacion</th>
                          <th>Numero de empleado</th>
                          <th>cedula</th>
                          <th>correo</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if ($maestros): ?>
                          <?php foreach ($maestros as $maestroscarrera): ?>
                            <tr>
                            <tr>
                              <td><?php echo htmlspecialchars($maestroscarrera['nombre_usuario'] . ' ' . $maestroscarrera['apellido_p'] . ' ' . $maestroscarrera['apellido_m']); ?></td>
                              <td><?php echo htmlspecialchars($maestroscarrera['edad']); ?></td>
                              <td><?php echo htmlspecialchars($maestroscarrera['fecha_contratacion']); ?></td>
                              <td><?php echo htmlspecialchars($maestroscarrera['numero_empleado']); ?></td>
                              <td><?php echo htmlspecialchars($maestroscarrera['cedula']); ?></td>
                              <td><?php echo htmlspecialchars($maestroscarrera['correo']); ?></td>
                            </tr>                  
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="5" class="text-center">No hay maestros registrados.</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                    <!-- Card para TOTAL DE HORAS MAXIMAS -->
                    <div class="card mt-3 text-center">
                      <div class="card-body  box-shadow-div">
                        <h5 class="card-title font-weight-bold">TOTAL DE HORAS MÁXIMAS</h5>
                        <p class="card-text font-weight-bold">40</p>
                      </div>
                    </div>
                  </div>
                </div> <!-- /.col -->


              </div>
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