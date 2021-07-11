let sessions

document.addEventListener('DOMContentLoaded', async function () {
  await refreshSessionData()

  document.getElementById('addNursingTime').addEventListener('click', function () {
    postData('/api/session').then(session => addSessionToList(session))
  })

  document.getElementById('sessionModal').addEventListener('hidden.bs.modal', function () {
    resetSessionModal()
  })

  document.getElementById('deleteButton').addEventListener('click', function (event) {
    deleteData('/api/session/' + document.getElementById('sessionIdModalInput').value).then(session => refreshSessionData())
  })
})

function resetSessionModal () {
  document.getElementById('sessionTimeModalInput').value = null
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
  sessionList.prepend(li)
}

async function refreshSessionData () {
  document.getElementById('sessionList').innerHTML = ''
  document.getElementById('loadingSpinner').classList.remove('hidden')

  await fetchSessions()

  sessions.forEach(function (session) {
    addSessionToList(session)
  })

  document.getElementById('loadingSpinner').classList.add('hidden')
}

function fetchSessions () {
  return fetchData('/api/session')
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

async function deleteData (url) {
  await fetch(url, {
    method: 'DELETE',
    cache: 'no-cache'
  })
}

function showSessionModal (sessionId) {
  document.getElementById('sessionIdModalInput').value = sessionId
  document.getElementById('sessionTimeModalInput').value = document.getElementById(sessionId).innerText

  var myModal = new bootstrap.Modal(document.getElementById('sessionModal'), {
    keyboard: false
  })
  myModal.show()
}

function formatDateToLocalTime (dateString) {
  let date = new Date(Date.parse(dateString))

  let formattedDateTimeString = date.toLocaleString('de-DE')

  return formattedDateTimeString.substring(0, formattedDateTimeString.length - 3)
}
