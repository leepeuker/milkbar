document.addEventListener('DOMContentLoaded', async function () {
  flatpickr.l10ns.default.firstDayOfWeek = 1
  let currentDate = new Date()
  let currentDateFormatted = currentDate.getDate() + '.' + (currentDate.getMonth()+1) + '.' + currentDate.getFullYear()
  let firstSessionDate = document.getElementById('firstSessionDate').value
  let defaultDateRange = [];

  if (firstSessionDate !== '') {
    defaultDateRange = [firstSessionDate, currentDateFormatted]
  }

  flatpickr('#startTime', {
      mode: 'range',
      defaultDate: defaultDateRange,
      altInput: true,
      dateFormat: 'd.m.Y',
      altFormat: 'd.m.Y',
      allowInput: true,
      minuteIncrement: 1,
    }
  )
})
