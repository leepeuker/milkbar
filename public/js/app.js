let sessions

document.addEventListener('DOMContentLoaded', async function () {
  await loadInitialData()

  document.getElementById('addNursingTime').addEventListener('click', function () {
    postData('/api/session').then(test => addSessionToTable(test.time))
  })
})

function addSessionToTable (sessionTime) {
  let tbodyRef = document.getElementById('nursingTimeTable')
  var newRow = tbodyRef.insertRow(1)
  var newCell = newRow.insertCell()
  newCell.appendChild(document.createTextNode(sessionTime))
}

async function loadInitialData () {
  await fetchSessions()

  sessions.forEach(function (session) {
    addSessionToTable(session.time)
  })
}

function fetchSessions () {
  return fetchData('/api/session')
    .then((author) => {
      sessions = author
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
