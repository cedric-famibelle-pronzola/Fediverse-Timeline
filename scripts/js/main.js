const request = window.location.search
const index = new RegExp('.*index.php$')
const home = new RegExp('.*\/$')
const search = new RegExp('.*\?id\=')
let lastId

if (index.test(window.location.pathname) || home.test(window.location.pathname)) {
  document.getElementById('iframe').style.display = 'none'
  document.getElementById('iframe-link').style.display = 'none'
}

const lastIdInterval = () => {
  fetch('./last-id.php', {
    method: 'POST',
    body: 'request',
    headers: {
      'Content-type': 'application/text-plain; chartset=UTF-8'
    }
  })
  .then(response => response.text())
  .then(body => {
    document.getElementById('last-id').innerText = body
    lastId = body
    document.getElementById('iframe-link').setAttribute('src', `src="${window.location}timeline.php?id=${parseInt(body, 10)}"` )
  })
}

if (document.getElementById('last-id')) {
  lastIdInterval()
}

const fetchInterval = () => {
  const data = {
    id: request ? request.replace(search, '') : null,
    instanceName: 'mamot.fr',
    width: 400,
    height: 800,
    timelineChoice: false,
    default: true
  }

  fetch('./timeline.php', {
    method: 'POST',
    body: JSON.stringify(data),
    headers: {
      'Content-type': 'application/json; chartset=UTF-8'
    }
  })
    .then(response => response.text())
    .then(body => {
      const iframe = document.getElementById('iframe')
      const iframeLink = document.getElementById('iframe-link')
      if (!iframe && !iframeLink) {
        document.getElementById('integrate').innerHTML = body
      }
    })
}

const formTextSubmit = textForm => {
  fetch('./timeline.php', {
    method: 'POST',
    body: JSON.stringify(textForm),
    headers: {
      'Content-type': 'application/json; chartset=UTF-8'
    }
  })
    .then(response => response.text())
    .then(body => {
      const iframe = document.getElementById('iframe')
      const iframeLink = document.getElementById('iframe-link')
      const intervalIframeLink = () => {
        iframeLink.innerText = `<iframe width="${textForm.width}" height="${textForm.height}" allowfullscreen referrerpolicy="no-referrer" frameborder="0" src="${window.location}timeline.php?id=${parseInt(lastId, 10)}"></iframe>`
      }
      setTimeout(intervalIframeLink, 1000)
      iframeLink.style.display = 'block';
      iframe.style.display = 'block';
      iframe.setAttribute('width', textForm.width)
      iframe.setAttribute('height', textForm.height)
      return iframe.contentWindow.document.body.innerHTML = body
    })
}

const formSelectSubmit = selectForm => {
  fetch('./timeline.php', {
    method: 'POST',
    body: JSON.stringify(selectForm),
    headers: {
      'Content-type': 'application/json; chartset=UTF-8'
    }
  })
    .then(response => response.text())
    .then(body => {
      const iframe = document.getElementById('iframe')
      const iframeLink = document.getElementById('iframe-link')
      const intervalIframeLink = () => {
        iframeLink.innerText = `<iframe width="400" height="800" allowfullscreen referrerpolicy="no-referrer" frameborder="0" src="${window.location}timeline.php?id=${parseInt(selectForm.id, 10)}"></iframe>`
      }
      setTimeout(intervalIframeLink, 1000)
      iframeLink.style.display = 'block';
      iframe.style.display = 'block';
      iframe.setAttribute('width', 400)
      iframe.setAttribute('height', 800)
      return iframe.contentWindow.document.body.innerHTML = body
    })
}

if (document.getElementById('instance-text') && document.getElementById('instance-select')) {
  const selectedInstance = document.getElementById('selected-instance')
  let timelineChoice = false
  let textFormValidation = false

  document.getElementById('yes-no').checked = false
  document.getElementById('yes-no').addEventListener('change', () => {
    document.getElementById('yes-no').checked ? timelineChoice = true : timelineChoice = false
  })

  document.getElementById('form-text').addEventListener('click', () => {
    document.getElementById('errors').innerText=''
    document.getElementById('validation-instance-name').innerText=''
    document.getElementById('validation-width').innerText=''
    document.getElementById('validation-height').innerText=''

    const textForm = {
      instanceName: document.getElementById('instance-name').value,
      width: parseInt(document.getElementById('width').value, 10),
      height: parseInt(document.getElementById('height').value, 10),
      timelineChoice
    }

    if (textForm.instanceName === '') {
      document.getElementById('validation-instance-name').innerText='Required'
    }

    if (isNaN(textForm.width)) {
      document.getElementById('validation-width').innerText='Requires a number'
    } else if (textForm.width < 0) {
      document.getElementById('validation-width').innerText='Requires a positive number'
    }
    
    if (isNaN(textForm.height)) {
      document.getElementById('validation-height').innerText='Requires a number'
    } else if (textForm.height < 0) {
      document.getElementById('validation-height').innerText='Requires a positive number'
    }

    if (textForm.instanceName !== '' && !isNaN(textForm.width) && !isNaN(textForm.height)) {
      textFormValidation = true
    } else {
      textFormValidation = false
    }

    if (textFormValidation) {
      formTextSubmit(textForm)
      setTimeout(lastIdInterval, 500)
    } else {
      document.getElementById('errors').innerText='There are some errors in your form.'
    }
  })

  document.getElementById('form-select').addEventListener('click', () => {
    const selectedInstanceValue = selectedInstance.options[selectedInstance.selectedIndex].value
    console.log(selectedInstanceValue)
    if (selectedInstanceValue !== '0') {
      const selectForm = {
        id: selectedInstanceValue,
        timelineChoice
      }
      formSelectSubmit(selectForm)
    } else {
      alert('There is no instance')
    }
  })

}

fetchInterval()
setInterval(fetchInterval, 30000)
