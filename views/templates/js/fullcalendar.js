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

// Function to render days
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

  // Prev days HTML
  for (let x = firstDay.getDay(); x > 0; x--) {
    daysHtml += `<div class="day prev">${prevLastDayDate - x + 1}</div>`;
  }

  // Current month days
  for (let i = 1; i <= lastDayDate; i++) {
    const isToday = 
      i === new Date().getDate() &&
      currentMonth === new Date().getMonth() &&
      currentYear === new Date().getFullYear();

    const isEconomicDay = economicDays.some(day =>
      day.date.getDate() === i &&
      day.date.getMonth() === currentMonth &&
      day.date.getFullYear() === currentYear
    );

    daysHtml += `<div class="day${isToday ? ' today' : ''}${isEconomicDay ? ' economic-day' : ''}" data-day="${i}" data-month="${currentMonth}" data-year="${currentYear}">
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

function setCalendarMonthYear(monthIndex, year) {
  currentMonth = monthIndex;
  currentYear = year;
  renderCalendar();
}

// Toggle economic day by clicking on a day
function toggleEconomicDay(event) {
  if (event.target.classList.contains('day') && !event.target.classList.contains('prev') && !event.target.classList.contains('next')) {
    const day = parseInt(event.target.getAttribute('data-day'));
    const month = parseInt(event.target.getAttribute('data-month'));
    const year = parseInt(event.target.getAttribute('data-year'));

    const isEconomicDay = economicDays.some(dayObj =>
      dayObj.date.getDate() === day &&
      dayObj.date.getMonth() === month &&
      dayObj.date.getFullYear() === year
    );

    if (isEconomicDay) {
      // Remove the economic day
      economicDays = economicDays.filter(dayObj =>
        !(dayObj.date.getDate() === day && dayObj.date.getMonth() === month && dayObj.date.getFullYear() === year)
      );
    } else {
      // Add a new economic day
      economicDays.push({ date: new Date(year, month, day), type: 'DiaEconomico' });
    }

    renderCalendar(); // Re-render calendar to reflect changes
  }
}

// Add event listener for clicking on days
daysContainer.addEventListener('click', toggleEconomicDay);

// Handle filter option clicks
document.querySelectorAll('.filter-options a').forEach(link => {
  link.addEventListener('click', function(event) {
    event.preventDefault();
    const button = this.closest('.filter-options').previousElementSibling;
    const filterType = button.querySelector('.filter-label').dataset.placeholder;

    // Update button text and hide options
    button.querySelector('.filter-label').textContent = this.textContent;
    this.closest('.filter-options').classList.add('d-none');

    // Update calendar only if the selected filter is 'Periodo'
    if (filterType === 'Periodo') {
      const monthIndex = parseInt(this.getAttribute('data-month'));
      const year = parseInt(this.getAttribute('data-year'));
      if (!isNaN(monthIndex) && !isNaN(year)) {
        setCalendarMonthYear(monthIndex, year);
      }
    }
    // If the filter is 'División', just update the button text
  });
});
