<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <title>Document</title>
</head>

<body>
     <!-- Tarjeta principal -->
     <div class="card box-shadow-div p-4 mb-3">
          <h2 class="text-center">Ingeniería en Sistemas Computacionales</h2>
          <div class="row">
            <div class="col-12 mb-0">
              <div class="schedule-container">
                <div class="table-responsive">
                  <table class="table table-borderless table-striped">
                    <thead>
                      <tr  role="row">
                        <th>Hora</th>
                        <th>Lunes</th>
                        <th>Martes</th>
                        <th>Miércoles</th>
                        <th>Jueves</th>
                        <th>Viernes</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr  scope="col">
                        <td>07:00 - 08:00</td>
                        <td>Clase A</td>
                        <td></td>
                        <td>Clase B</td>
                        <td></td>
                        <td>Clase C</td>
                      </tr>
                      <tr>
                        <td>08:00 - 09:00</td>
                        <td>Clase A</td>
                        <td>Clase A</td>
                        <td>Clase A</td>
                        <td>Clase A</td>
                        <td>Clase E</td>
                      </tr>
                      <tr  scope="col">
                        <td>09:00 - 10:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>
                      <tr>
                        <td>10:00 - 11:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>
                      <tr  scope="col">
                        <td>11:00 - 12:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr>
                        <td>12:00 - 13:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr  scope="col">
                        <td>13:00 - 14:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr>
                        <td>14:00 - 15:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr  scope="col">
                        <td>15:00 - 16:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr>
                        <td>16:00 - 17:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr  scope="col">
                        <td>17:00 - 18:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr>
                        <td>18:00 - 19:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr  scope="col">
                        <td>19:00 - 20:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>

                      <tr>
                        <td>20:00 - 21:00</td>
                        <td>Clase D</td>
                        <td>Clase D</td>
                        <td></td>
                        <td></td>
                        <td>Clase E</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
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
                    <!-- Aquí se mostrará la información -->
                    <p id="modalContent">Día y hora seleccionados.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Seleccionar todas las celdas que no son la primera columna (hora)
            const cells = document.querySelectorAll("tbody tr td:not(:first-child)");

            cells.forEach((cell) => {
                cell.addEventListener("click", function () {
                    // Obtener el índice de la columna (día)
                    const columnIndex = this.cellIndex;

                    // Obtener el día desde el encabezado
                    const day = document.querySelector(`thead th:nth-child(${columnIndex + 1})`).innerText;

                    // Obtener la hora desde la primera columna de la fila
                    const time = this.parentElement.querySelector("td:first-child").innerText;

                    // Mostrar la información en el modal
                    document.getElementById("modalContent").innerText = `Día: ${day}\nHora: ${time}`;

                    // Abrir el modal
                    const myModal = new bootstrap.Modal(document.getElementById("infoModal"));
                    myModal.show();
                });
            });
        });
    </script>
</body>

</html>
