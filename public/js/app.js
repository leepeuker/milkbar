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

  document.getElementById('updateButton').addEventListener('click', function (event) {

    var matches = document.getElementById('sessionTimeModalInput').value.match(/(\d{1,2})\.(\d{1,2})\.(\d{4}), (\d{2}):(\d{2})/)
    let date = new Date(`${matches[3]}-${matches[2].padStart(2, '0')}-${matches[1].padStart(2, '0')} ${matches[4]}:${matches[5]}`)

    putData(
      '/api/session/' + document.getElementById('sessionIdModalInput').value,
      {
        'time': date.toISOString()
      }
    ).then(session => refreshSessionData())
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
  document.getElementById('sessionTimeModalInput').value = document.getElementById(sessionId).innerText

  var myModal = new bootstrap.Modal(document.getElementById('sessionModal'), {
    keyboard: false
  })
  myModal.show()
}

function formatDateToLocalTime (dateString) {
  console.log(dateString)
  let date = new Date(Date.parse(dateString))

  return `${date.getDate().toString().padStart(2, '0')}.${(date.getMonth() + 1).toString().padStart(2, '0')}.${date.getFullYear()}, ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`
}
