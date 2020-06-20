window.onload = call()

function call() {
  fetch('./timeline.php')
  .then(function(response) {
      return response.text()
  })
  .then(function(body) {
      document.getElementById('timeline').innerHTML = body
  })
}

setInterval(call, 100000)
