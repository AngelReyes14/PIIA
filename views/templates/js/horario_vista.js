
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById("usuario_usuario_id").value !== "") {
        filtrarCarreras();
    }
});

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

    ['periodo_periodo_id', 'usuario_usuario_id', 'carrera_carrera_id'].forEach(id =>
        document.getElementById(id).addEventListener('change', filtrarHorario)
    );

    async function filtrarHorario() {
        const periodo = document.getElementById('periodo_periodo_id').value;
        const usuarioId = document.getElementById('usuario_usuario_id').value;
        const carrera = document.getElementById('carrera_carrera_id').value;

        if (!periodo || !usuarioId || !carrera) {
            return;
        }

        const usuarioSeleccionado = usuarioId;

        try {
            const response = await fetch('../../models/cargar_horario.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ periodo, usuarioId, carrera }),
            });

            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            const data = await response.json();

            console.log("Datos recibidos del servidor:", data); // Verifica la respuesta en la consola

            if (data.length === 0) {
                Swal.fire({
                    title: 'No se encontraron horarios',
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

        document.getElementById('usuario_usuario_id').value = usuarioSeleccionado;
    }

    function generarTablaHTML(data) {
        let tableHTML = `
            <table class="table table-borderless table-striped">
                <thead>
                    <tr>
                        <th>Hora</th>
                        ${dias.map(d => `<th>${d.descripcion}</th>`).join('')}
                    </tr>
                </thead>
                <tbody>
                    ${horas
                        .map(hora => {
                            return `<tr>
                                <td>${hora.descripcion}</td>
                                ${dias.map(dia => {
                                    const evento = data.find(item => item.horas_horas_id == hora.id && item.dias_id == dia.id);
                                    return `<td>${evento ? `${evento.materia}<br>${evento.grupo}<br>${evento.salon}` : ''}</td>`;
                                }).join('')}
                            </tr>`;
                        })
                        .join('')}
                </tbody>
            </table>`;

        return tableHTML;
    }

    function mostrarTabla(data) {
        const tablaContenedor = document.querySelector('.schedule-container .table-responsive');
        if (tablaContenedor) {
            tablaContenedor.innerHTML = generarTablaHTML(data);
        } else {
            console.error("Error: No se encontró el contenedor de la tabla.");
        }
    }

    function mostrarTablaVacia() {
        mostrarTabla([]);
    }
});

function filtrarCarreras() {
    var usuarioId = document.getElementById("usuario_usuario_id").value;
    var selectCarreras = document.getElementById("carrera_carrera_id");

    // Guardar la carrera seleccionada antes de actualizar
    var carreraSeleccionada = selectCarreras.value;

    console.log("Usuario seleccionado:", usuarioId);

    // Si no hay usuario seleccionado, limpiar el select
    if (usuarioId === "") {
        selectCarreras.innerHTML = '<option value="">Selecciona una carrera</option>';
        return;
    }

    // Petición para obtener las carreras asociadas al usuario
    fetch('../../models/cargar_carreras.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'usuario_usuario_id=' + encodeURIComponent(usuarioId)
    })
    .then(response => response.json())
    .then(data => {
        console.log("Respuesta del servidor:", data);

        // Reiniciar opciones de carrera
        selectCarreras.innerHTML = '<option value="">Selecciona una carrera</option>';

        if (data.error) {
            console.error(data.error);
        } else {
            // Agregar las nuevas opciones de carreras filtradas
            data.forEach(carrera => {
                var option = document.createElement("option");
                option.value = carrera.carrera_id;
                option.textContent = carrera.nombre_carrera;
                selectCarreras.appendChild(option);
            });
        }

        // Restaurar la carrera seleccionada si sigue disponible
        if (carreraSeleccionada && [...selectCarreras.options].some(opt => opt.value === carreraSeleccionada)) {
            selectCarreras.value = carreraSeleccionada;
        }
    })
    .catch(error => console.error('Error:', error));
}


document.getElementById("downloadPDF").addEventListener("click", () => {
    const button = document.querySelector('.pdf-container');
    
    // Ocultar el botón mientras se genera el PDF
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
  
    // Generar el PDF
    html2pdf()
      .set(options)
      .from(element)
      .save()
      .finally(() => {
        // Restaurar la visibilidad del botón después de generar el PDF
        button.style.display = 'block';
      });
  });
  