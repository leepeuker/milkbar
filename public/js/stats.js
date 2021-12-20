let sessionCount = '-'

document.addEventListener('DOMContentLoaded', async function () {
  flatpickr.l10ns.default.firstDayOfWeek = 1
  let currentDate = new Date()
  let currentDateFormatted = currentDate.getDate() + '.' + (currentDate.getMonth() + 1) + '.' + currentDate.getFullYear()
  let firstSessionDate = document.getElementById('firstSessionDate').value
  let defaultDateRange = []

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
      onValueUpdate: function () {
        refreshSessionCount()
      },
    }
  )

  refreshSessionCount()
})

async function refreshSessionCount () {
  await syncSessionCount()

  document.getElementById('sessionCount').innerHTML = sessionCount
}

function syncSessionCount () {
  var startDateInputData = document.getElementById('startTime').value.match(/(\d{1,2})\.(\d{1,2})\.(\d{4})/)
  var endDateInputData = document.getElementById('startTime').value.match(/\d{1,2}\.\d{1,2}\.\d{4} to (\d{1,2})\.(\d{1,2})\.(\d{4})/)

  if (startDateInputData === null || endDateInputData === null) {
    sessionCount = '-'
    return
  }

  var startDate = startDateInputData[1] + '.' + startDateInputData[2] + '.' + startDateInputData[3]
  var endDate = endDateInputData[1] + '.' + endDateInputData[2] + '.' + endDateInputData[3]

  return fetchData('/api/session/count?startDate=' + startDate + '&endDate=' + endDate)
    .then((sessionData) => {
      sessionCount = sessionData
    })
}

function fetchData (url) {
  return fetch(url)
    .then(response => response.json())
    .then(data => data)
}
