const daysContainer = document.querySelector(".days"),
  nextBtn = document.querySelector(".next-btn"),
  prevBtn = document.querySelector(".prev-btn"),
  month = document.querySelector(".month"),
  todayBtn = document.querySelector(".today-btn");

const months = [
  "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
  "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
];

const days = ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"];

// get current date
const date = new Date();

// get current month
let currentMonth = date.getMonth();

// get current year
let currentYear = date.getFullYear();

// Array to store economic days
let economicDays = [];

// Function to calculate the date range considering 3 business days
function calculateDateRange() {
  const today = new Date();
  const range = { minDate: new Date(today), maxDate: new Date(today) };
  let forwardDays = 0, backwardDays = 0;

  // Move forward 3 business days
  while (forwardDays < 3) {
    range.maxDate.setDate(range.maxDate.getDate() + 1);
    if (range.maxDate.getDay() !== 0 && range.maxDate.getDay() !== 6) forwardDays++;
  }

  // Move backward 3 business days
  while (backwardDays < 3) {
    range.minDate.setDate(range.minDate.getDate() - 1);
    if (range.minDate.getDay() !== 0 && range.minDate.getDay() !== 6) backwardDays++;
  }

  return {
    minDate: range.minDate,
    maxDate: range.maxDate
  };
}

// Function to render days with validation for the 3-day business range
function renderCalendar() {
  date.setDate(1);
  const firstDay = new Date(currentYear, currentMonth, 1);
  const lastDay = new Date(currentYear, currentMonth + 1, 0);
  const lastDayIndex = lastDay.getDay();
  const lastDayDate = lastDay.getDate();
  const prevLastDay = new Date(currentYear, currentMonth, 0);
  const prevLastDayDate = prevLastDay.getDate();
  const nextDays = 7 - lastDayIndex - 1;

  month.innerHTML = `${months[currentMonth]} ${currentYear}`;

  let daysHtml = "";
  const { minDate, maxDate } = calculateDateRange();

  // Prev days HTML
  for (let x = firstDay.getDay(); x > 0; x--) {
    daysHtml += `<div class="day prev">${prevLastDayDate - x + 1}</div>`;
  }

  // Current month days
  for (let i = 1; i <= lastDayDate; i++) {
    const currentDay = new Date(currentYear, currentMonth, i);
    const isToday = 
      i === new Date().getDate() &&
      currentMonth === new Date().getMonth() &&
      currentYear === new Date().getFullYear();

    const isEconomicDay = economicDays.some(day =>
      day.date.getDate() === i &&
      day.date.getMonth() === currentMonth &&
      day.date.getFullYear() === currentYear
    );

    // Check if the day is within the valid range
    const isWithinRange = currentDay >= minDate && currentDay <= maxDate && 
                          currentDay.getDay() !== 0 && currentDay.getDay() !== 6;
    const disabledClass = isWithinRange ? "" : " disabled-day";

    daysHtml += `<div class="day${isToday ? ' today' : ''}${isEconomicDay ? ' economic-day' : ''}${disabledClass}" data-day="${i}" data-month="${currentMonth}" data-year="${currentYear}">
      ${i}
      ${isEconomicDay ? '<img src="assets/icon/DiaEconomico.png" class="economic-icon" alt="Día Económico">' : ''}
    </div>`;
  }

  // Next month days
  for (let j = 1; j <= nextDays; j++) {
    daysHtml += `<div class="day next">${j}</div>`;
  }

  daysContainer.innerHTML = daysHtml;
  hideTodayBtn();
}

renderCalendar();

// Event Listeners
nextBtn.addEventListener("click", () => {
  currentMonth++;
  if (currentMonth > 11) {
    currentMonth = 0;
    currentYear++;
  }
  renderCalendar();
});

prevBtn.addEventListener("click", () => {
  currentMonth--;
  if (currentMonth < 0) {
    currentMonth = 11;
    currentYear--;
  }
  renderCalendar();
});

todayBtn.addEventListener("click", () => {
  currentMonth = date.getMonth();
  currentYear = date.getFullYear();
  renderCalendar();
});

function hideTodayBtn() {
  if (currentMonth === new Date().getMonth() && currentYear === new Date().getFullYear()) {
    todayBtn.style.display = "none";
  } else {
    todayBtn.style.display = "flex";
  }
}

// Toggle economic day by clicking on a day
function toggleEconomicDay(event) {
  if (event.target.classList.contains('day') && !event.target.classList.contains('prev') && !event.target.classList.contains('next') && !event.target.classList.contains('disabled-day')) {
    const day = parseInt(event.target.getAttribute('data-day'));
    const month = parseInt(event.target.getAttribute('data-month'));
    const year = parseInt(event.target.getAttribute('data-year'));

    const currentDay = new Date(year, month, day);

    // Verificar si el día es hábil antes de abrir el modal
    const { minDate, maxDate } = calculateDateRange();
    if (currentDay >= minDate && currentDay <= maxDate) {
      // Cargar el contenido del modal usando AJAX
      fetch('modal_incidencias.php')
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.text();
        })
        .then(data => {
          document.getElementById('modalContent').innerHTML = data;
          // Mostrar el modal
          const modal = new bootstrap.Modal(document.getElementById('incidenciasModal'));
          modal.show();
        })
        .catch(error => {
          console.error('Error al cargar el contenido del modal:', error);
        });
    }
  }
}


// Add event listener for clicking on days
daysContainer.addEventListener('click', toggleEconomicDay);