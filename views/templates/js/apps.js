"use strict";
$("#modeSwitcher").on("click", function (e) {
  e.preventDefault(), modeSwitch(), location.reload();
}),
  $(".collapseSidebar").on("click", function (e) {
    $(".vertical").hasClass("narrow")
      ? $(".vertical").toggleClass("open")
      : ($(".vertical").toggleClass("collapsed"),
        $(".vertical").hasClass("hover") &&
          $(".vertical").removeClass("hover")),
      e.preventDefault();
  }),
  $(".sidebar-left").hover(
    function () {
      $(".vertical").hasClass("collapsed") && $(".vertical").addClass("hover"),
        $(".narrow").hasClass("open") || $(".vertical").addClass("hover");
    },
    function () {
      $(".vertical").hasClass("collapsed") &&
        $(".vertical").removeClass("hover"),
        $(".narrow").hasClass("open") || $(".vertical").removeClass("hover");
    }
  ),
  $(".toggle-sidebar").on("click", function () {
    $(".navbar-slide").toggleClass("show");
  }),
  (function (a) {
    a(".dropdown-menu a.dropdown-toggle").on("click", function (e) {
      return (
        a(this).next().hasClass("show") ||
          a(this)
            .parents(".dropdown-menu")
            .first()
            .find(".show")
            .removeClass("show"),
        a(this).next(".dropdown-menu").toggleClass("show"),
        a(this)
          .parents("li.nav-item.dropdown.show")
          .on("hidden.bs.dropdown", function (e) {
            a(".dropdown-submenu .show").removeClass("show");
          }),
        !1
      );
    });
  })(jQuery),
  $(".navbar .dropdown").on("hidden.bs.dropdown", function () {
    $(this).find("li.dropdown").removeClass("show open"),
      $(this).find("ul.dropdown-menu").removeClass("show open");
  }),
  $(".file-panel .card").on("click", function () {
    $(this).hasClass("selected")
      ? ($(this).removeClass("selected"),
        $(this).find("bg-light").removeClass("shadow-lg"),
        $(".file-container").removeClass("collapsed"))
      : ($(this).addClass("selected"),
        $(this).addClass("shadow-lg"),
        $(".file-panel .card").not(this).removeClass("selected"),
        $(".file-container").addClass("collapsed"));
  }),
  $(".close-info").on("click", function () {
    $(".file-container").hasClass("collapsed") &&
      ($(".file-container").removeClass("collapsed"),
      $(".file-panel").find(".selected").removeClass("selected"));
  }),
  $(function () {
    $(".info-content").stickOnScroll({ topOffset: 0, setWidthOnStick: !0 });
  });
var basic_wizard = $("#example-basic");
basic_wizard.length &&
  basic_wizard.steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    autoFocus: !0,
  });
var vertical_wizard = $("#example-vertical");
vertical_wizard.length &&
  vertical_wizard.steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    stepsOrientation: "vertical",
  });
var form = $("#example-form");
form.length &&
  (form.validate({
    errorPlacement: function (e, a) {
      a.before(e);
    },
    rules: { confirm: { equalTo: "#password" } },
  }),
  form.children("div").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    onStepChanging: function (e, a, o) {
      return (
        (form.validate().settings.ignore = ":disabled,:hidden"), form.valid()
      );
    },
    onFinishing: function (e, a) {
      return (form.validate().settings.ignore = ":disabled"), form.valid();
    },
    onFinished: function (e, a) {
      alert("Submitted!");
    },
  }));
var ChartOptions = {
    maintainAspectRatio: !1,
    responsive: !0,
    legend: { display: !1 },
    scales: {
      xAxes: [{ gridLines: { display: !1 } }],
      yAxes: [
        {
          gridLines: {
            display: !1,
            color: colors.borderColor,
            zeroLineColor: colors.borderColor,
          },
        },
      ],
    },
  },
  ChartData = {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"],
    datasets: [
      {
        label: "Visitors",
        barThickness: 10,
        backgroundColor: base.primaryColor,
        borderColor: base.primaryColor,
        pointRadius: !1,
        pointColor: "#3b8bba",
        pointStrokeColor: "rgba(60,141,188,1)",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(60,141,188,1)",
        data: [28, 48, 40, 19, 64, 27, 90, 85, 92],
        fill: "",
        lineTension: 0.1,
      },
      {
        label: "Orders",
        barThickness: 10,
        backgroundColor: "rgba(210, 214, 222, 1)",
        borderColor: "rgba(210, 214, 222, 1)",
        pointRadius: !1,
        pointColor: "rgba(210, 214, 222, 1)",
        pointStrokeColor: "#c1c7d1",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [65, 59, 80, 42, 43, 55, 40, 36, 68],
        fill: "",
        borderWidth: 2,
        lineTension: 0.1,
      },
    ],
  },
  lineChartData = {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"],
    datasets: [
      {
        label: "Visitors",
        barThickness: 10,
        borderColor: base.primaryColor,
        pointRadius: !1,
        pointColor: "#3b8bba",
        pointStrokeColor: "rgba(60,141,188,1)",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(60,141,188,1)",
        data: [28, 48, 30, 19, 64, 27, 90, 85, 92],
        fill: "",
        lineTension: 0.2,
      },
      {
        label: "Sales",
        barThickness: 10,
        borderColor: "rgba(40, 167, 69, 0.8)",
        pointRadius: !1,
        pointColor: "#3b8bba",
        pointStrokeColor: "rgba(60,141,188,1)",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(60,141,188,1)",
        data: [8, 18, 20, 29, 26, 7, 30, 25, 48],
        fill: "",
        borderWidth: 2,
        lineTension: 0.2,
      },
      {
        label: "Orders",
        backgroundColor: "rgba(210, 214, 222, 1)",
        borderColor: "rgba(210, 214, 222, 1)",
        pointRadius: !1,
        pointColor: "rgba(210, 214, 222, 1)",
        pointStrokeColor: "#c1c7d1",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [65, 59, 80, 42, 43, 55, 40, 36, 68],
        fill: "",
        borderWidth: 2,
        lineTension: 0.2,
      },
    ],
  },
  pieChartData = {
    labels: ["Clothing", "Shoes", "Electronics", "Books", "Cosmetics"],
    datasets: [
      {
        data: [18, 30, 42, 12, 7],
        backgroundColor: chartColors,
        borderColor: colors.borderColor,
      },
    ],
  },
  areaChartData = {
    labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [
      {
        label: "Visitors",
        barThickness: 10,
        backgroundColor: base.primaryColor,
        borderColor: base.primaryColor,
        pointRadius: !1,
        pointColor: "#3b8bba",
        pointStrokeColor: "rgba(60,141,188,1)",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(60,141,188,1)",
        data: [19, 64, 37, 76, 68, 88, 54, 46, 58],
        lineTension: 0.1,
      },
      {
        label: "Orders",
        barThickness: 10,
        backgroundColor: "rgba(210, 214, 222, 1)",
        borderColor: "rgba(255, 255, 255, 1)",
        pointRadius: !1,
        pointColor: "rgba(210, 214, 222, 1)",
        pointStrokeColor: "#c1c7d1",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [42, 43, 55, 40, 36, 68, 22, 66, 49],
        lineTension: 0.1,
      },
    ],
  },
  barChartjs = document.getElementById("barChartjs");
barChartjs &&
  new Chart(barChartjs, {
    type: "bar",
    data: ChartData,
    options: ChartOptions,
  });
var lineChartjs = document.getElementById("lineChartjs");
lineChartjs &&
  new Chart(lineChartjs, {
    type: "line",
    data: lineChartData,
    options: ChartOptions,
  });
var pieChartjs = document.getElementById("pieChartjs");
pieChartjs &&
  new Chart(pieChartjs, {
    type: "pie",
    data: pieChartData,
    options: { maintainAspectRatio: !1, responsive: !0 },
  });
var areaChartjs = document.getElementById("areaChartjs");
areaChartjs &&
  new Chart(areaChartjs, {
    type: "line",
    data: areaChartData,
    options: ChartOptions,
  }),
  $(".sparkline").length &&
    ($(".inlinebar").sparkline([3, 2, 7, 5, 4, 6, 8], {
      type: "bar",
      width: "100%",
      height: "32",
      barColor: base.primaryColor,
      barWidth: 4,
      barSpacing: 2,
    }),
    $(".inlineline").sparkline([2, 0, 5, 7, 4, 6, 8], {
      type: "line",
      width: "100%",
      height: "32",
      defaultPixelsPerValue: 5,
      lineColor: base.primaryColor,
      fillColor: "transparent",
      minSpotColor: !1,
      spotColor: !1,
      highlightSpotColor: "",
      maxSpotColor: !1,
      lineWidth: 2,
    }),
    $(".inlinepie").sparkline([5, 7, 4, 6, 8], {
      type: "pie",
      height: "32",
      width: "32",
      sliceColors: chartColors,
    }));
var gauge1,
  svgg1 = document.getElementById("gauge1");
svgg1 &&
  ((gauge1 = Gauge(svgg1, {
    max: 100,
    dialStartAngle: -90,
    dialEndAngle: -90.001,
    value: 100,
    showValue: !1,
    label: function (e) {
      return Math.round(100 * e) / 100;
    },
    color: function (e) {
      return e < 20
        ? base.primaryColor
        : e < 40
        ? base.successColor
        : e < 60
        ? base.warningColor
        : base.dangerColor;
    },
  })),
  (function e() {
    gauge1.setValue(90),
      gauge1.setValueAnimated(30, 1),
      window.setTimeout(e, 6e3);
  })());
var gauge2,
  svgg2 = document.getElementById("gauge2");
svgg2 &&
  ((gauge2 = Gauge(svgg2, {
    max: 100,
    value: 46,
    dialStartAngle: -0,
    dialEndAngle: -90.001,
  })),
  (function e() {
    gauge2.setValue(40),
      gauge2.setValueAnimated(30, 1),
      window.setTimeout(e, 6e3);
  })());
var gauge3,
  svgg3 = document.getElementById("gauge3");
svgg3 &&
  (gauge3 = Gauge(svgg3, {
    max: 100,
    dialStartAngle: -90,
    dialEndAngle: -90.001,
    value: 80,
    showValue: !1,
    label: function (e) {
      return Math.round(100 * e) / 100;
    },
  }));
var gauge4,
  svgg4 = document.getElementById("gauge4");
svgg4 &&
  (gauge4 = Gauge(document.getElementById("gauge4"), {
    max: 500,
    dialStartAngle: 90,
    dialEndAngle: 0,
    value: 50,
  }));

  

  document.addEventListener('DOMContentLoaded', (event) => {
    // Número inicial de días económicos
    let diasEconomicosRestantes = 4;
  
    // Obtener el elemento que muestra los días restantes
    const countDownElement = document.getElementById('count-down');
    const diasRestantesElement = document.getElementById('dias-restantes');
  
    // Función para actualizar el contador de días
    function actualizarContador() {
      countDownElement.textContent = diasEconomicosRestantes;
    }
  
    // Función para mostrar/ocultar el mensaje de días restantes
    function mostrarMensajeDiasRestantes(mostrar) {
      if (mostrar) {
        diasRestantesElement.classList.remove('d-none');
      } else {
        diasRestantesElement.classList.add('d-none');
      }
    }
  
    // Evento que se ejecuta cuando se selecciona una opción de radio
    document.querySelectorAll('input[name="justificacion"]').forEach((radio) => {
      radio.addEventListener('change', (event) => {
        if (event.target.value === 'dia-economico') {
          mostrarMensajeDiasRestantes(true); // Mostrar el mensaje
          if (diasEconomicosRestantes > 0) {
            diasEconomicosRestantes -= 1; // Restar un día disponible
            actualizarContador(); // Actualizar el contador en la interfaz
          }
        } else {
          mostrarMensajeDiasRestantes(false); // Ocultar el mensaje si no es "Día Económico"
        }
      });
    });
  
    // Inicializar el contador y el mensaje al cargar la página
    actualizarContador();
  });
  
  document.querySelectorAll('.form-check-input').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      if (this.checked) {
        document.querySelectorAll('.form-check-input').forEach(cb => {
          if (cb !== this) {
            cb.checked = false;
          }
        });
      }
    });
  });
  
  
  document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('nombre-servidor-publico');
  
    input.addEventListener('input', function() {
      // Reemplaza cualquier caracter que no sea letra o espacio
      this.value = this.value.replace(/[^A-Za-z\s]/g, '');
    });
  
    document.getElementById('submit-button').addEventListener('click', function() {
      // Obtener todos los campos del formulario
      const fields = [
        'folio', 'area', 'fecha', 'motivo',
        'start-time', 'end-time', 'hora-incidencia', 'dia-incidencia',
        'nombre-servidor-publico', 'numero-empleado'
      ];
  
      let allValid = true;
  
      // Validar campos de texto
      fields.forEach(id => {
        const field = document.getElementById(id);
        if (field) {
          // Verificar si el campo está vacío o contiene solo espacios
          if (!field.value.trim()) {
            field.classList.add('is-invalid');
            allValid = false;
          } else {
            field.classList.remove('is-invalid');
          }
        }
      });
  
      // Validar checkboxes
      const checkboxes = document.querySelectorAll('input[name="justificacion"]');
      let isCheckboxChecked = false;
  
      checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
          isCheckboxChecked = true;
        }
      });
  
      // Mostrar error si no se seleccionó ninguna opción
      const errorElement = document.getElementById('justificacion-error');
      if (!isCheckboxChecked) {
        errorElement.classList.remove('d-none');
        allValid = false;
      } else {
        errorElement.classList.add('d-none');
      }
  
      // Agregar clases de error a checkboxes si ninguno está seleccionado
      checkboxes.forEach(checkbox => {
        if (!isCheckboxChecked) {
          checkbox.classList.add('is-invalid');
        } else {
          checkbox.classList.remove('is-invalid');
        }
      });
  
      // Mostrar mensaje de formulario enviado si todo es válido
      if (allValid) {
        document.getElementById('form-message').classList.remove('d-none');
      }
    });

  // Validación al enviar el formulario
  document.querySelector('form').addEventListener('submit', function(event) {
    const value = input.value;
    const pattern = /^[A-Za-z\s]+$/;

    if (!pattern.test(value)) {
      input.classList.add('is-invalid');
      event.preventDefault();
    } else {
      input.classList.remove('is-invalid');
    }
  });
});

document.getElementById('numero-empleado').addEventListener('input', function (e) {
  // Solo permite números en el campo
  e.target.value = e.target.value.replace(/\D/g, '');
});

document.getElementById('submit-button').addEventListener('click', function(event) {
  let isValid = true;
  const fields = document.querySelectorAll('input[required], textarea[required]'); // Selecciona todos los campos requeridos
  
  fields.forEach(field => {
    if (field.value.trim() === '') {
      field.classList.add('is-invalid'); // Marca el campo como inválido
      isValid = false;
    } else {
      field.classList.remove('is-invalid'); // Quita la clase de inválido si es válido
    }
  });

  // Validación adicional para el campo "Número del Empleado"
  const numeroEmpleado = document.getElementById('numero-empleado');
  if (numeroEmpleado.value === '' || !/^\d+$/.test(numeroEmpleado.value)) {
    numeroEmpleado.classList.add('is-invalid');
    isValid = false;
  } else {
    numeroEmpleado.classList.remove('is-invalid');
  }

  if (isValid) {
    // Mostrar el modal si todos los campos son válidos
    var myModal = new bootstrap.Modal(document.getElementById('successModal'));
    myModal.show();
  } else {
    event.preventDefault(); // Evita el envío del formulario si hay campos inválidos
  }
});

$(document).ready(function() {
  // Inicializa el modal
  const modal = new bootstrap.Modal(document.getElementById('customModal'));

  // Mostrar el modal al hacer clic en "Enviar"
  $("#submit-button").on("click", function(event) {
    let allFieldsFilled = true;

    // Validación de campos de entrada
    $('input, textarea').each(function() {
      if ($(this).val().trim() === '') {
        allFieldsFilled = false; // Si encuentra algún campo vacío
        $(this).addClass('is-invalid'); // Agrega clase para marcar el campo como inválido
      } else {
        $(this).removeClass('is-invalid'); // Remueve la clase si el campo está lleno
      }
    });

    // Si no todos los campos están llenos, cancela el envío del formulario
    if (!allFieldsFilled) {
      event.preventDefault(); // Evita que el formulario se envíe
    } else {
      // Si todos los campos están completos, muestra el modal
      modal.show();
    }
  });

  // Agrega evento de clic al botón "Cerrar" dentro del modal
  document.querySelector('#closeModal').addEventListener('click', function() {
    modal.hide(); // Cierra el modal
  });
});



//form_advanced js

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
/** date range picker **/
  if ($('.datetimes').length)
  {
    $('.datetimes').daterangepicker(
    {
      timePicker: true,
      startDate: moment().startOf('hour'),
      endDate: moment().startOf('hour').add(32, 'hour'),
      locale:
      {
        format: 'hh:mm A'
      }
    });
  }
  var start = moment().subtract(29, 'days');
  var end = moment();

  function cb(start, end)
  {
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
  if (editor)
  {
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
  (function()
  {
    'use strict';
    window.addEventListener('load', function()
    {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName('needs-validation');
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function(form)
      {
        form.addEventListener('submit', function(event)
        {
          if (form.checkValidity() === false)
          {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    }, false);
  })();

  var uptarg = document.getElementById('drag-drop-area');
  if (uptarg)
  {
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
    uppy.on('complete', (result) =>
    {
      console.log('Upload complete! We’ve uploaded these files:', result.successful)
    });
  }

  window.dataLayer = window.dataLayer || [];

  function gtag()
  {
    dataLayer.push(arguments);
  }
  gtag('js', new Date());
  gtag('config', 'UA-56159088-1');

