function filtrarHorario() {
    const carreraSelect = document.getElementById('carrera_carrera_id');
    const docenteSelect = document.getElementById('usuario_usuario_id');
    const carreraId = carreraSelect.value;

    if (carreraId) {
        // Guardar la opción seleccionada antes de limpiar el campo
        const usuarioSeleccionado = docenteSelect.value;

        // Llamada AJAX al servidor
        fetch('../../models/filtrar_usuarios.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ carrera_id: carreraId }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    docenteSelect.innerHTML = '<option value="">Seleccione un usuario</option>';
                    return;
                }

                // Limpiar las opciones actuales
                docenteSelect.innerHTML = '<option value="">Seleccione un usuario</option>';
                
                // Agregar las nuevas opciones
                data.forEach(usuario => {
                    const option = document.createElement('option');
                    option.value = usuario.usuario_id;
                    option.textContent = `${usuario.nombre_usuario} ${usuario.apellido_p} ${usuario.apellido_m}`;
                    docenteSelect.appendChild(option);
                });

                // Restablecer la selección previa
                docenteSelect.value = usuarioSeleccionado;
            })
            .catch(error => console.error('Error al filtrar usuarios:', error));
    } else {
        // Limpiar las opciones si no hay carrera seleccionada
        docenteSelect.innerHTML = '<option value="">Seleccione un usuario</option>';
    }
}

document.addEventListener('DOMContentLoaded', function () {
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

    // Detectar cambios en los selectores de filtros
    ['periodo_periodo_id', 'usuario_usuario_id', 'carrera_carrera_id'].forEach(id =>
        document.getElementById(id).addEventListener('change', filtrarHorario)
    );

    async function filtrarHorario() {
        const periodo = document.getElementById('periodo_periodo_id').value;
        const usuarioId = document.getElementById('usuario_usuario_id').value;
        const carrera = document.getElementById('carrera_carrera_id').value;

        // Validar si todos los filtros están seleccionados
        if (!periodo || !usuarioId || !carrera) {
            return; // Salir de la función sin mostrar alerta
        }

        // Guardar el valor actual del campo usuario antes de realizar la solicitud
        const usuarioSeleccionado = usuarioId;

        try {
            const response = await fetch('../../models/cargar_horario.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ periodo, usuarioId, carrera }),
            });

            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            const data = await response.json();

            if (data.length === 0) {
                // Mostrar SweetAlert si no hay datos
                Swal.fire({
                    title: 'Error al filtrar el horario:',
                    text: 'La tabla está disponible para registrar.',
                    icon: 'info',
                    confirmButtonText: 'Aceptar',
                });
                mostrarTablaVacia();
            } else {
                mostrarTabla(data);
            }
        } catch (error) {
            console.error('Error al filtrar el horario:', error);
            Swal.fire({
                title: 'No se encontraron datos',
                text: 'La tabla está disponible para registrar.',
                icon: 'info',
                confirmButtonText: 'Aceptar',
            });
            mostrarTablaVacia();
        }

        // Restablecer el valor del campo usuario después de la carga de la tabla
        document.getElementById('usuario_usuario_id').value = usuarioSeleccionado;
    }

    function generarTablaHTML(data) {
        return `
            <table class="table table-borderless table-striped">
                <thead>
                    <tr>
                        <th>Hora</th>
                        ${dias.map(d => `<th>${d.descripcion}</th>`).join('')}
                    </tr>
                </thead>
                <tbody>
                    ${horas
                        .map(hora => `
                        <tr>
                            <td>${hora.descripcion}</td>
                            ${dias
                                .map(dia => {
const evento = data.find(item => item.horas_horas_id == hora.id && item.dias_id == dia.id);
const contenido = evento ? `${evento.materia}<br>${evento.grupo}<br>${evento.salon}` : '';
const horarioId = evento ? evento.horario_id : ''; // Obtener horario_id

return `<td class="editable-cell" data-horas-id="${hora.id}" data-dia-id="${dia.id}" data-horario-id="${horarioId}">
            ${contenido}
        </td>`;


                                })
                                .join('')}
                        </tr>`)
                        .join('')}
                </tbody>
            </table>`;
    }

    function mostrarTabla(data) {
        const tablaContenedor = document.querySelector('.schedule-container .table-responsive');
        if (tablaContenedor) tablaContenedor.innerHTML = generarTablaHTML(data);
        agregarEventosCeldas();
    }

    function mostrarTablaVacia() {
        mostrarTabla([]);
    }

    function agregarEventosCeldas() {
    document.querySelectorAll('.editable-cell').forEach(cell => {
        cell.addEventListener('click', function () {
            const horaId = this.dataset.horasId;
            const diaId = this.dataset.diaId;
            const horarioId = this.dataset.horarioId;  // Obtener el horario_id de la celda

            const diaTexto = dias.find(d => d.id == diaId).descripcion;
            const horaTexto = horas.find(h => h.id == horaId).descripcion;

            // Asignar valores a los campos del formulario del modal
            document.getElementById('modalContent').innerText = `Día: ${diaTexto}\nHora: ${horaTexto}`;
            document.getElementById('periodo').value = document.getElementById('periodo_periodo_id').value;
            document.getElementById('docente').value = document.getElementById('usuario_usuario_id').value;
            document.getElementById('carrera').value = document.getElementById('carrera_carrera_id').value;
            document.getElementById('hora').value = horaId;
            document.getElementById('dia').value = diaId;
            document.getElementById('horario_id').value = horarioId || '';  // Asignar el horario_id al campo oculto

            // Mostrar el modal con Bootstrap
            const modal = new bootstrap.Modal(document.getElementById('infoModal'));
            modal.show();
        });
    });
}

});

document.getElementById("downloadPDF").addEventListener("click", () => {
  
    function generatePDF() {
      // Selecciona el contenedor de firmas
      const firmasContainer = document.querySelector('.firmas');
      const scheduleContainer = document.querySelector('.schedule-container');
  
      // Almacenamos los estilos originales
      const originalFirmasClass = firmasContainer.className;
      const originalScheduleStyles = {
        backgroundColor: scheduleContainer.style.backgroundColor,
        color: scheduleContainer.style.color
      };
      const originalCellsStyles = [];
  
      // Aplicar el modo claro solo para el PDF
      firmasContainer.classList.add('pdf-mode');
      scheduleContainer.style.backgroundColor = "#ffffff";
      scheduleContainer.style.color = "#333333";
      const cells = scheduleContainer.querySelectorAll('td');
      cells.forEach(cell => {
        originalCellsStyles.push({
          backgroundColor: cell.style.backgroundColor,
          color: cell.style.color
        });
        cell.style.backgroundColor = "#f9f9f9";
        cell.style.color = "#555555";
      });
  
      // Configuración del PDF
      const pdfOptions = {
        margin: [10, 10, 10, 10],
        filename: 'documento.pdf',
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
      };
  
      html2pdf()
        .set(pdfOptions)
        .from(document.body) // O selecciona un contenedor específico
        .save()
        .then(() => {
          // Restaurar estilos después de generar el PDF
          firmasContainer.className = originalFirmasClass;
          scheduleContainer.style.backgroundColor = originalScheduleStyles.backgroundColor;
          scheduleContainer.style.color = originalScheduleStyles.color;
          
          cells.forEach((cell, index) => {
            cell.style.backgroundColor = originalCellsStyles[index].backgroundColor;
            cell.style.color = originalCellsStyles[index].color;
          });
        });
    }
  
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
  

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('.schedule-container').addEventListener('click', function (event) {
        const cell = event.target.closest('.editable-cell');
        if (!cell) return;

        const horarioId = cell.dataset.horarioId;
        document.getElementById('horario_id').value = horarioId || '';

        if (horarioId) {
            document.querySelector('.btn-danger[name="action"][value="eliminar"]').style.display = 'block';
        } else {
            document.querySelector('.btn-danger[name="action"][value="eliminar"]').style.display = 'none';
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const closeModalButton = document.querySelector("#infoModal .btn-close");
    const closeFooterButton = document.querySelector("#infoModal .btn-secondary"); // Botón en el footer
    const modal = document.getElementById("infoModal");

    function cerrarModal() {
        modal.classList.remove("show");
        modal.setAttribute("aria-hidden", "true");
        modal.style.display = "none";

        // Remueve la clase 'modal-backdrop' de Bootstrap si existe
        document.querySelectorAll(".modal-backdrop").forEach(el => el.remove());
    }

    closeModalButton.addEventListener("click", cerrarModal);
    closeFooterButton.addEventListener("click", cerrarModal);
});
