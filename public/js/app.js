let sessions

document.addEventListener('DOMContentLoaded', async function () {
  await loadInitialData()

  document.getElementById('addNursingTime').addEventListener('click', function () {
    postData('/api/session').then(test => addSessionToList(test.time))
  })
})

function addSessionToList (sessionTime) {
  let sessionList = document.getElementById('sessionList')
  let li = document.createElement('li')

  li.classList.add('list-group-item', 'list-group-item-main', 'd-flex', 'justify-content-between', 'align-items-center')
  li.appendChild(document.createTextNode(sessionTime))
  sessionList.prepend(li)
}

async function loadInitialData () {
  await fetchSessions()

  sessions.forEach(function (session) {
    addSessionToList(session.time)
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
