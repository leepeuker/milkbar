if ('serviceWorker' in navigator) {
  window.addEventListener('load', function () {
    navigator.serviceWorker
      .register('/serviceWorker.js')
      .then(res => console.log('service worker registered'))
      .catch(err => console.log('service worker not registered', err))
  })
}

let sessions

document.addEventListener('DOMContentLoaded', async function () {
  await refreshSessionData()

  document.getElementById('addNursingTime').addEventListener('click', function () {
    postData('/api/session').then(() => refreshSessionData())
  })

  document.getElementById('sessionModal').addEventListener('hidden.bs.modal', function () {
    resetSessionModal()
  })

  document.getElementById('deleteButton').addEventListener('click', function (event) {
    deleteData('/api/session/' + document.getElementById('sessionIdModalInput').value).then(session => refreshSessionData())
  })

  document.getElementById('leftBoob').addEventListener('click', function (event) {
    document.getElementById('minutesLeftInput').disabled = this.checked === false

    if (this.checked === false) {
      document.getElementById('minutesLeftInput').value = ''
    }
  })

  document.getElementById('sessionMaxAge').addEventListener('click', function (event) {
    refreshSessionData()
  })

  document.getElementById('rightBoob').addEventListener('click', function (event) {
    document.getElementById('minutesRightInput').disabled = this.checked === false

    if (this.checked === false) {
      document.getElementById('minutesRightInput').value = ''
    }
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

function resetSessionModal () {
  document.getElementById('sessionTimeModalInput').value = null
  document.getElementById('leftBoob').checked = false
  document.getElementById('minutesLeftInput').value = ''
  document.getElementById('minutesLeftInput').disabled = true
  document.getElementById('rightBoob').checked = false
  document.getElementById('minutesRightInput').value = ''
  document.getElementById('minutesRightInput').disabled = true
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

async function refreshSessionData () {
  document.getElementById('sessionList').innerHTML = ''
  document.getElementById('loadingSpinner').classList.remove('hidden')
  document.getElementById('feedingTime').innerHTML = ''
  document.getElementById('nextFeedingLoadingSpinner').classList.remove('hidden')

  await fetchSessions()

  sessions.forEach(function (session) {
    addSessionToList(session)
  })

  document.getElementById('loadingSpinner').classList.add('hidden')
  document.getElementById('nextFeedingLoadingSpinner').classList.add('hidden')

  if (sessions.length > 0) {
    let nextFeedingTime = new Date(new Date(sessions[sessions.length - 1].time).getTime() + 240 * 60000)
    document.getElementById('feedingTime').innerHTML = formatDateToLocalTime(nextFeedingTime)
  } else {
    document.getElementById('feedingTime').innerHTML = '---'
  }
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
      allowInput: true
    }
  )

  let minutesLeft = parseInt(document.getElementById(sessionId).getAttribute('minutesleft'))
  if (minutesLeft > 0) {
    document.getElementById('leftBoob').checked = true
    document.getElementById('minutesLeftInput').disabled = false
    document.getElementById('minutesLeftInput').value = minutesLeft
  }
  let minutesRight = parseInt(document.getElementById(sessionId).getAttribute('minutesright'))
  if (minutesRight > 0) {
    document.getElementById('rightBoob').checked = true
    document.getElementById('minutesRightInput').disabled = false
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
