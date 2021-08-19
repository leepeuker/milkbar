if ('serviceWorker' in navigator) {
  window.addEventListener('load', function () {
    navigator.serviceWorker
      .register('/serviceWorker.js')
      .then(res => console.log('service worker registered'))
      .catch(err => console.log('service worker not registered', err))
  })
}

let sessions
let latestSessionTime
let hr = 0
let min = 0
let sec = 0

document.addEventListener('DOMContentLoaded', async function () {
  await refreshSessionData()

  timerCycle()

  document.getElementById('addNursingTime').addEventListener('click', function () {
    postData('/api/session').then(() => refreshSessionData())
  })

  document.getElementById('sessionModal').addEventListener('hidden.bs.modal', function () {
    resetSessionModal()
  })

  document.getElementById('deleteButton').addEventListener('click', function (event) {
    let deletionConfirmed = confirm('Are you sure you want to delete this session?')

    if (deletionConfirmed === false) {
      return
    }

    deleteData('/api/session/' + document.getElementById('sessionIdModalInput').value).then(session => refreshSessionData())
  })

  document.getElementById('leftBoob').addEventListener('click', function (event) {
    if (this.checked === false) {
      document.getElementById('minutesLeftInput').value = ''
    } else {
      event.preventDefault()
    }
  })

  document.getElementById('minutesLeftInput').addEventListener('change', function (event) {
    document.getElementById('leftBoob').checked = true
  })

  document.getElementById('sessionMaxAge').addEventListener('change', function (event) {
    refreshSessionData()
  })

  document.getElementById('rightBoob').addEventListener('click', function (event) {
    if (this.checked === false) {
      document.getElementById('minutesRightInput').value = ''
    } else {
      event.preventDefault()
    }
  })

  document.getElementById('minutesRightInput').addEventListener('change', function (event) {
    document.getElementById('rightBoob').checked = true
  })

  document.getElementById('updateButton').addEventListener('click', function (event) {
    var matches = document.getElementById('sessionTimeModalInput').value.match(/(\d{1,2})\.(\d{1,2})\.(\d{4}), (\d{2}):(\d{2})/)
    let date = new Date(`${matches[3]}-${matches[2].padStart(2, '0')}-${matches[1].padStart(2, '0')} ${matches[4]}:${matches[5]}`)

    let minutesLeft = document.getElementById('minutesLeftInput').value
    let minutesRight = document.getElementById('minutesRightInput').value

    putData(
      '/api/session/' + document.getElementById('sessionIdModalInput').value,
      {
        'time': date.toISOString(),
        'minutesLeft': minutesLeft,
        'minutesRight': minutesRight
      }
    ).then(() => refreshSessionData())
  })
})

function timerCycle () {
  setTimeout('timerCycle()', 1000)

  if (sessions.length === 0) {
    document.getElementById('stopwatch').innerHTML = '---'

    return
  }

  sec = parseInt(sec)
  min = parseInt(min)
  hr = parseInt(hr)

  sec = sec + 1

  if (sec == 60) {
    min = min + 1
    sec = 0
  }
  if (min == 60) {
    hr = hr + 1
    min = 0
    sec = 0
  }

  if (sec < 10 || sec == 0) {
    sec = '0' + sec
  }
  if (min < 10 || min == 0) {
    min = '0' + min
  }
  if (hr < 10 || hr == 0) {
    hr = '0' + hr
  }

  document.getElementById('stopwatch').innerHTML = hr + ':' + min + ':' + sec
}

function resetSessionModal () {
  document.getElementById('sessionTimeModalInput').value = null
  document.getElementById('leftBoob').checked = false
  document.getElementById('minutesLeftInput').value = ''
  document.getElementById('rightBoob').checked = false
  document.getElementById('minutesRightInput').value = ''
}

function addSessionToList (session) {
  let sessionList = document.getElementById('sessionList')
  let li = document.createElement('li')

  li.id = session.id
  li.classList.add('list-group-item', 'list-group-item-main', 'd-flex', 'justify-content-between', 'align-items-center')
  li.appendChild(document.createTextNode(formatDateToLocalTime(session.time)))
  li.addEventListener('click', function () {
    showSessionModal(this.id)
  })
  li.setAttribute('minutesRight', session.minutesRight)
  li.setAttribute('minutesLeft', session.minutesLeft)
  li.setAttribute('time', session.time)
  sessionList.prepend(li)
}

function getLatestSessionTime () {
  latestSessionTime = null
  sec = null
  min = null
  hr = null

  sessions.forEach(function (session) {
    let sessionTime = new Date(session.time)
    if (latestSessionTime === null || sessionTime > latestSessionTime) {
      latestSessionTime = sessionTime
    }
  })

  let diff = Math.abs(new Date() - latestSessionTime)

  let diffString = new Date(diff).toISOString().substr(11, 8)

  sec = diffString.substr(6, 2)
  min = diffString.substr(3, 2)
  hr = diffString.substr(0, 2)
}

async function refreshSessionData () {
  document.getElementById('sessionList').innerHTML = ''
  document.getElementById('loadingSpinner').classList.remove('hidden')
  document.getElementById('stopwatch').innerHTML = ''
  document.getElementById('stopwatchLoadingSpinner').classList.remove('hidden')

  await fetchSessions()

  sessions.forEach(function (session) {
    addSessionToList(session)
  })

  getLatestSessionTime()

  document.getElementById('loadingSpinner').classList.add('hidden')
  document.getElementById('stopwatchLoadingSpinner').classList.add('hidden')
}

function fetchSessions () {
  return fetchData('/api/session?maxAge=' + document.getElementById('sessionMaxAge').value)
    .then((sessionData) => {
      sessions = sessionData
    })
}

function fetchData (url) {
  return fetch(url)
    .then(response => response.json())
    .then(data => data)
}

async function postData (url, data = {}) {
  const response = await fetch(url, {
    method: 'POST',
    cache: 'no-cache',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  })

  return response.json()
}

async function putData (url, data = {}) {
  await fetch(url, {
    method: 'PUT',
    cache: 'no-cache',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  })
}

async function deleteData (url) {
  await fetch(url, {
    method: 'DELETE',
    cache: 'no-cache'
  })
}

function showSessionModal (sessionId) {
  document.getElementById('sessionIdModalInput').value = sessionId
  document.getElementById('sessionTimeModalInput').value = formatDateToLocalTime(document.getElementById(sessionId).getAttribute('time'))

  flatpickr.l10ns.default.firstDayOfWeek = 1
  flatpickr('#sessionTimeModalInput', {
      altInput: true,
      enableTime: true,
      dateFormat: 'd.m.Y, H:i',
      altFormat: 'd.m.Y, H:i',
      time_24hr: true,
      allowInput: true,
      minuteIncrement: 1
    }
  )

  let minutesLeft = parseInt(document.getElementById(sessionId).getAttribute('minutesleft'))
  if (minutesLeft > 0) {
    document.getElementById('leftBoob').checked = true
    document.getElementById('minutesLeftInput').value = minutesLeft
  }
  let minutesRight = parseInt(document.getElementById(sessionId).getAttribute('minutesright'))
  if (minutesRight > 0) {
    document.getElementById('rightBoob').checked = true
    document.getElementById('minutesRightInput').value = minutesRight
  }

  var myModal = new bootstrap.Modal(document.getElementById('sessionModal'), {
    keyboard: false
  })
  myModal.show()
}

function formatDateToLocalTime (dateString) {
  let date = new Date(Date.parse(dateString))

  return `${date.getDate().toString().padStart(2, '0')}.${(date.getMonth() + 1).toString().padStart(2, '0')}.${date.getFullYear()}, ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`
}
