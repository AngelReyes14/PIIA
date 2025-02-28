document.addEventListener('DOMContentLoaded', function () {
    const carreraSelect = document.getElementById("carrera_carrera_id");
    const usuarioSelect = document.getElementById("usuario_usuario_id");
    const periodoSelect = document.getElementById("periodo_periodo_id");

    // Carga el horario si ya hay valores predeterminados seleccionados
    if (periodoSelect.value && usuarioSelect.value && carreraSelect.value) {
        filtrarHorario();  // Llama a filtrarHorario() si hay carrera, usuario y periodo seleccionados
    }

    // Escucha los cambios en los selects
    ['periodo_periodo_id', 'usuario_usuario_id', 'carrera_carrera_id'].forEach(id =>
        document.getElementById(id).addEventListener('change', filtrarHorario)
    );

    // Define los horarios y días
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

    async function filtrarHorario() {
        const periodo = periodoSelect.value;
        const usuarioId = usuarioSelect.value;
        const carrera = carreraSelect.value;

        // Solo se procede si hay un periodo, usuario y carrera seleccionados
        if (!periodo || !usuarioId || !carrera) {
            return;
        }

        try {
            const response = await fetch('../../models/cargar_horario.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ periodo, usuarioId, carrera }),
            });

            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            const data = await response.json();

            console.log("Datos recibidos del servidor:", data);

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

document.addEventListener("DOMContentLoaded", function () {
    var barChartCtn = document.querySelector("#barChart");
  
    if (barChartCtn) {
        var nombreDocente = barChartCtn.getAttribute("data-docente") || "No definido";
        console.log("Nombre del docente en JavaScript:", nombreDocente); // 🔍 Debug
  
        var tutorias = parseInt(barChartCtn.getAttribute("data-tutorias") || 0);
        var apoyo = parseInt(barChartCtn.getAttribute("data-apoyo") || 0);
        var frente = parseInt(barChartCtn.getAttribute("data-frente") || 0);
        // ✅ Calculamos el total de horas
        var totalHoras = tutorias + apoyo + frente;
  
        // 🎨 Define los colores de la gráfica y la leyenda
        var chartColors = ["#008FFB", "#00E396", "#FEB019"]; // Azul, Verde, Amarillo
  
        var barChartoptions = {
            series: [
                { name: "Tutorías", data: [tutorias] },
                { name: "Horas de Apoyo", data: [apoyo] },
                { name: "Horas Frente al Grupo", data: [frente] }
            ],
            chart: {
                type: "bar",
                height: 150,
                stacked: true,
                columnWidth: "70%",
                zoom: { enabled: false },
                toolbar: { enabled: false },
            },
            theme: { mode: colors.chartTheme },
            dataLabels: { enabled: true },
            plotOptions: { bar: { horizontal: true, columnWidth: "30%" } },
            xaxis: {
                categories: [nombreDocente], // 🔥 Aquí debería aparecer el nombre correcto
                labels: {
                    colors: colors.mutedColor,
                    fontFamily: base.defaultFontFamily,
                },
                axisBorder: { show: false },
            },
            yaxis: {
                labels: {
                    colors: colors.mutedColor,
                    fontFamily: base.defaultFontFamily,
                },
            },
            legend: {
                position: "bottom",
                fontFamily: base.defaultFontFamily,
                labels: {
                    colors: chartColors, // 🔹 La leyenda usa los mismos colores
                    useSeriesColors: false
                },
            },
            fill: { opacity: 1, colors: chartColors }, // 🔹 Los colores de las barras coinciden con la leyenda
            grid: {
                borderColor: colors.borderColor,
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: false } },
                padding: { left: 10, right: 10 },
            },
        };
  
        var barChart = new ApexCharts(barChartCtn, barChartoptions);
        barChart.render();
               // ✅ Mostrar el total de horas debajo de la gráfica
               document.getElementById("total-horas").innerHTML = `
               Total de Horas: <span style="color: #ff5733;">${totalHoras}</span>
           `;
    }
  });


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
  