
(function(){


const { __ } = wp.i18n;

function startCountdown( time ) {
  let remainingTime = time;
  let countdownInterval = setInterval(function() {
    remainingTime--
    if (remainingTime <= 0) {
      clearInterval(countdownInterval); // Detener la cuenta atrás cuando todos los valores llegan a cero
    }
    updateCountdownTimer(remainingTime); // Actualizar la cuenta atrás en el banner
  }, 1000); // Actualizar cada segundo (1000 milisegundos)
}

function updateCountdownTimer(remainingTime) {
  let days = Math.floor(remainingTime / (24 * 60 * 60));
  let hours = Math.floor((remainingTime % (24 * 60 * 60)) / (60 * 60));
  let minutes = Math.floor((remainingTime % (60 * 60)) / 60);
  let seconds = remainingTime % 60;
  let countdownTimer = document.querySelector(".countdown-timer");

  const tdays = __("days", "eit-countdown")
  const thours = __("hours", "eit-countdown")
  const tminutes = __("minutes", "eit-countdown")
  const tseconds = __("seconds", "eit-countdown")

  countdownTimer.textContent = `${days} ${tdays}, ${hours} ${thours}, ${minutes} ${tminutes}, ${seconds} ${tseconds}`;
}




document.addEventListener('DOMContentLoaded', function() {
    
  const data = {
      action: countdown_ajax_object.action,
      nonce: countdown_ajax_object.nonce
  };
  console.log(data);

  fetch(countdown_ajax_object.ajax_url, {
      method: 'POST',
      headers: {
          // 'Content-Type': 'application/json'
          'Content-Type': 'application/x-www-form-urlencoded',
      },
      // body: JSON.stringify(data)
      body: new URLSearchParams(data).toString()
  })
  .then( (response) => response.json() )
  .then( (result) => {
    console.log(result.data);
    startCountdown(result.data.time) 
  })
  .catch((error) => console.error(error) )
});



// jQuery(document).ready(function($) {
//       const data = {
//         action: countdown_ajax_object.action,
//         nonce: countdown_ajax_object.nonce
//     };

//       $.ajax({
//           type: "post",
//           url: countdown_ajax_object.ajax_url,
//           data: data,
//           success: function(result){
//               console.log(result);
//           }
//       });
// });

}())