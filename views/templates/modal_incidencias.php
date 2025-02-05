<?php
include('../../models/session.php');
include('../../controllers/db.php');
include('../../models/consultas.php');

// Variables y consultas necesarias
$consultas = new Consultas($database->getConnection());
$carreras = $consultas->obtenerCarreras();
$incidencias = $consultas->obtenerIncidencias();
$idusuario = $_SESSION['user_id'];
$imgUser = $consultas->obtenerImagen($idusuario);

// Obtén la carrera del usuario autenticado
$carreraManager = new CarreraManager($database->getConnection());
$carrera = $carreraManager->obtenerCarreraPorUsuario($idusuario);

// Obtén el servidor público del usuario autenticado
$usuarioManager = new UsuarioManager($database->getConnection());
$servidorPublico = $usuarioManager->obtenerServidorPublicoPorUsuario($idusuario);
?>

<!-- Tu formulario HTML aquí -->
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
                <!-- Caja contenedora para los campos de "Área" y "Fecha" en la misma fila -->
                <div class="col-md-12">
                  <div class="form-group p-3 border rounded" >
                    <div class="row">
                      <!-- Campo de Área alineado a la izquierda -->
             <div class="col-md-6">
                <label for="area" class="form-label">Área:</label>
                <select class="form-control" id="area" name="area" required>
                    <option value="" disabled>Selecciona una carrera</option>
                    <?php if ($carrera): ?>
                        <option value="<?= htmlspecialchars($carrera['carrera_id']) ?>" selected>
                            <?= htmlspecialchars($carrera['nombre_carrera']) ?>
                        </option>
                    <?php else: ?>
                        <option value="">No hay carreras disponibles para este usuario</option>
                    <?php endif; ?>
                </select>
                <div class="invalid-feedback">Este campo no puede estar vacío.</div>
            </div>

                      <!-- Campo de Fecha alineado a la derecha -->
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




  <div class="card p-4 mb-4 box-shadow-div form-group mb-3">
    <div class="row">
    <div class="col-md-12 mb-3" id="incidenciasDiv">
  <label for="incidencias" class="form-label">Selecciona una Incidencia:</label>
  <select class="form-control" id="incidencias" name="incidencias" required onchange="toggleCampos()">
    <option value="" disabled selected>Selecciona una incidencia</option>
    <?php foreach ($incidencias as $incidencia): ?>
      <option value="<?php echo htmlspecialchars($incidencia['incidenciaid']); ?>">
        <?php echo htmlspecialchars($incidencia['descripcion']); ?>
      </option>
    <?php endforeach; ?>
  </select>
  <div class="invalid-feedback">Este campo es obligatorio.</div>
</div>

<div class="col-md-6 mb-3" id="otroDiv" style="display: none;">
  <label for="otro">Otro</label>
  <input class="form-control" id="otro" name="otro" type="text" required disabled>
  <div class="invalid-feedback">Este campo no puede estar vacío.</div>
</div>


  </div>
  </div>


        <div class="card p-3 box-shadow-div">
          <div class="form-group mb-3">
            <label for="motivo">Motivo</label>
            <input class="form-control" id="motivo" name="motivo" type="text" required>
            <div class="invalid-feedback">Este campo no puede estar vacío.</div>
          </div>

 <div class="mb-3" id="documentDiv" style="display: none;">
            <label for="documentInput">Selecciona un documento</label>
            <input class="form-control" id="documentInput" name="documento" type="file" required disabled>
            <div class="invalid-feedback">Este campo no puede estar vacío.</div>
        </div>
        
          <div class="d-flex flex-wrap mb-3">
            <div class="form-group mr-3 flex-fill mb-3">
              <label for="start-time" class="horario-label me-2">Horario entrada:</label>
              <div class="d-flex">
                <input type="time" id="start-time" name="start-time" required class="me-1 form-control">
              </div>
              <div class="invalid-feedback">Este campo es obligatorio.</div>
            </div>

            <div class="form-group mr-3 flex-fill mb-3">
              <label for="end-time" class="horario-label me-2">Horario salida:</label>
              <div class="d-flex">
                <input type="time" id="end-time" name="end-time" required class="form-control">
              </div>
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
            if ($servidorPublico) {
                echo '<option value="' . htmlspecialchars($servidorPublico['usuario_id']) . '">' . htmlspecialchars($servidorPublico['nombre_completo']) . '</option>';
            } else {
                echo '<option value="">No hay servidores públicos disponibles</option>';
            }
            ?>
        </select>
        <div class="invalid-feedback">Debe seleccionar un servidor público.</div>
    </div>
</div>
          <!-- Botón para enviar el formulario -->
          <div class="text-center mt-4">
          <button type="submit" class="btn btn-primary" id="submit-button">Enviar</button>
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
        </div>
      </form>
