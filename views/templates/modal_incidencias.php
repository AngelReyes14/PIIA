<?php
include('../../models/session.php');
include('../../controllers/db.php');
include('../../models/consultas.php');
include('aside.php');
if (isset($_POST['logout'])) {
  $sessionManager->logoutAndRedirect('../templates/auth-login.php');
}

$conn = $database->getConnection();
$consultas = new Consultas($conn);

// Obtener las carreras
$carreras = $consultas->obtenerCarreras();
$incidencias = $consultas->obtenerIncidencias();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" href="assets/images/PIIA_oscuro 1.png">
  <title>Reporte de Incidencias</title>
  
  <!-- CSS Imports -->
  <link rel="stylesheet" href="css/simplebar.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Overpass:wght@400&display=swap">
  <link rel="stylesheet" href="css/app-light.css" id="lightTheme">
  <link rel="stylesheet" href="css/app-dark.css" id="darkTheme" disabled>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/form_carrera.js"></script>
  
  <style>
    .modal-dialog {
      max-width: 80%; /* Asegura que el modal no ocupe más del 80% del ancho de la pantalla */
      margin: auto; /* Centra el modal */
    }

    .card {
      overflow: auto; /* Permite el desplazamiento si el contenido es demasiado grande */
      max-height: 80vh; /* Limita la altura de la card */
    }

    .modal-body {
      overflow-y: auto; /* Desplazamiento vertical si el contenido es demasiado grande */
    }

    .form-label {
      font-weight: bold; /* Resalta las etiquetas del formulario */
    }
  </style>
</head>

<body class="vertical light">
  <div class="wrapper">
    <main role="main" class="main-content">
      <div class="col-md-12">
        <form id="formincidencias" method="POST" action="../../models/insert.php" enctype="multipart/form-data">
          <input type="hidden" name="form_type" value="incidencia-usuario">
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="logo-container mb-3">
                <img class="form-logo-left" src="assets/images/logo-teschi.png" alt="Logo Izquierda">
                <img class="form-logo-right" src="assets/icon/icon_piia.png" alt="Logo Derecha">
              </div>
              <div class="d-flex justify-content-center align-items-center mb-3 col">
                <p class="titulo-grande"><strong>AVISO DE JUSTIFICACION DE PUNTUALIDAD Y ASISTENCIA</strong></p>
              </div>
              <div class="container p-4 mb-4 box-shadow-div">
                <div class="row mb-3">
                  <div class="col-md-12">
                    <div class="form-group p-3 border rounded" style="background-color: #f8f9fa;">
                      <div class="row">
                        <div class="col-md-6">
                          <label for="area" class="form-label">Área:</label>
                          <select class="form-control" id="area" name="area" required>
                            <option value="" disabled>Selecciona una carrera</option>
                            <?php
                            $idusuario = $sessionManager->getUserId();
                            $query = "SELECT carrera.carrera_id, carrera.nombre_carrera 
                                      FROM carrera
                                      JOIN usuario ON usuario.carrera_carrera_id = carrera.carrera_id
                                      WHERE usuario.usuario_id = :user_id";
                            $stmt = $conn->prepare($query);
                            $stmt->bindParam(':user_id', $idusuario);
                            $stmt->execute();

                            if ($stmt->rowCount() > 0) {
                              $row = $stmt->fetch(PDO::FETCH_ASSOC);
                              echo '<option value="' . htmlspecialchars($row['carrera_id']) . '" selected>' . htmlspecialchars($row['nombre_carrera']) . '</option>';
                            } else {
                              echo '<option value="">No hay carreras disponibles para este usuario</option>';
                            }
                            ?>
                          </select>
                          <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                        </div>
                        <div class="col-md-6">
                          <label for="fecha" class="form-label">Fecha:</label>
                          <input class="form-control" id="fecha" type="date" name="fecha" required>
                          <div class="invalid-feedback">Este campo no puede estar vacío.</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="container p-4 mb-4 box-shadow-div form-group mb-3">
            <div class="form-group mb-3">
              <label for="incidencias" class="form-label">Selecciona una Incidencia:</label>
              <select class="form-control" id="incidencias" name="incidencias" required>
                <option value="" disabled selected>Selecciona una incidencia</option>
                <?php foreach ($incidencias as $incidencia): ?>
                  <option value="<?php echo htmlspecialchars($incidencia['incidenciaid']); ?>">
                    <?php echo htmlspecialchars($incidencia['descripcion']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Este campo es obligatorio.</div>
            </div>
          </div>

          <div class="container p-3 box-shadow-div">
            <div class="form-group mb-3">
              <label for="motivo">Motivo</label>
              <input class="form-control" id="motivo" name="motivo" type="text" required>
              <div class="invalid-feedback">Este campo no puede estar vacío.</div>
            </div>

            <div class="d-flex flex-wrap mb-3">
              <div class="form-group mr-3 flex-fill mb-3">
                <label for="start-time" class="horario-label me-2">Horario entrada:</label>
                <input type="time" id="start-time" name="start-time" required class="form-control">
                <div class="invalid-feedback">Este campo es obligatorio.</div>
              </div>
              <div class="form-group mr-3 flex-fill mb-3">
                <label for="end-time" class="horario-label me-2">Horario salida:</label>
                <input type="time" id="end-time" name="end-time" required class="form-control">
                <div class="invalid-feedback">Este campo es obligatorio.</div>
              </div>
              <div class="form-group mr-3 flex-fill mb-3">
                <label for="time" class="me-2">Hora de Incidencia:</label>
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
                <label for="usuario-servidor-publico" class="form-label">Seleccionar Servidor Público:</label>
                <select class="form-control" id="usuario-servidor-publico" name="usuario-servidor-publico" required>
                  <option value="">Seleccione un servidor público</option>
                  <?php
                  $idusuario = $sessionManager->getUserId();
                  $query = "SELECT usuario_id, CONCAT(nombre_usuario, ' ', apellido_p, ' ', apellido_m) AS nombre_completo 
                      FROM usuario 
                      WHERE usuario_id = :user_id";
                  $stmt = $conn->prepare($query);
                  $stmt->bindParam(':user_id', $idusuario);
                  $stmt->execute();

                  if ($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo '<option value="' . htmlspecialchars($row['usuario_id']) . '">' . htmlspecialchars($row['nombre_completo']) . '</option>';
                  } else {
                    echo '<option value="">No hay servidores públicos disponibles</option>';
                  }
                  ?>
                </select>
                <div class="invalid-feedback">Debe seleccionar un servidor público.</div>
              </div>
            </div>

            <div class="text-center mt-4">
              <button type="button" class="btn btn-primary" id="submit-button">Enviar</button>
            </div>
          </div>
        </form>
        
        <!-- Modal -->
        <div class="modal fade" id="customModal" tabindex="-1" aria-labelledby="customModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
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
      </div>
    </main>
  </div>
</body>
</html>


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
    function getNextBusinessDays(date, days) {
      let result = new Date(date);
      let addedDays = 0;

      while (addedDays < days) {
        result.setDate(result.getDate() + 1);
        // Skip Saturdays (6) and Sundays (0)
        if (result.getDay() !== 6 && result.getDay() !== 0) {
          addedDays++;
        }
      }
      return result;
    }

    function getPreviousBusinessDays(date, days) {
      let result = new Date(date);
      let subtractedDays = 0;

      while (subtractedDays < days) {
        result.setDate(result.getDate() - 1);
        // Skip Saturdays (6) and Sundays (0)
        if (result.getDay() !== 6 && result.getDay() !== 0) {
          subtractedDays++;
        }
      }
      return result;
    }

    // Obtenemos la fecha actual
    const today = new Date();

    // Calculamos las fechas mínima y máxima excluyendo fines de semana
    const minDate = getPreviousBusinessDays(today, 3); // 3 días hábiles antes
    const maxDate = getNextBusinessDays(today, 3); // 3 días hábiles después

    // Convertimos las fechas al formato YYYY-MM-DD
    const minDateString = minDate.toISOString().split("T")[0];
    const maxDateString = maxDate.toISOString().split("T")[0];

    // Establecemos los atributos min y max en el input de fecha
    const fechaInput = document.getElementById("fecha");
    fechaInput.min = minDateString;
    fechaInput.max = maxDateString;
  </script>
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
$(document).ready(function() {
    $('#submit-button').on('click', function() {
        // Aquí puedes realizar validaciones antes de enviar
        if ($("#formincidencias")[0].checkValidity()) {
            // Si el formulario es válido, envíalo
            $('#formincidencias').submit(); // Esto enviará el formulario
        } else {
            // Si no es válido, muestra el mensaje de error
            $("#formincidencias")[0].reportValidity();
        }
    });

    $('#closeModal').on('click', function() {
        $('#customModal').modal('hide');
    });
});

</script>
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