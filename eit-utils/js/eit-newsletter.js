// Vue.createApp({
//   data() {
//       return {
//           name: '',
//           email: ''
//       }
//   },
//   methods: {
//       async submitForm() {
//           const response = await fetch(newsletter_ajax_object.ajax_url, {
//               method: 'POST',
//               headers: {
//                   'Content-Type': 'application/x-www-form-urlencoded'
//               },
//               body: new URLSearchParams({
//                   action: newsletter_ajax_object.action,
//                   nonce: newsletter_ajax_object.nonce,
//                   name: this.name,
//                   email: this.email
//               })
//           });

//           const result = await response.json();
//           if (result.success) {
//               this.name = '';
//               this.email = '';
//               alert('Formulario enviado con éxito');
//           } else {
//               alert('Error al enviar el formulario');
//           }
//       }
//   }
// }).mount('#eitForm');



// document.addEventListener('DOMContentLoaded', function() {
const newsForm = document.getElementById('eit_newsletter_form');
newsForm.addEventListener('submit', function(e) {

    e.preventDefault();

    const formData = new FormData(e.target);
    const formProps = Object.fromEntries(formData);
    
    const data = {
        action: newsletter_ajax_object.action,
        nonce: newsletter_ajax_object.nonce,
        name: formProps.eit_name,
        email: formProps.eit_email
    };
    console.log(data);
  
    fetch(newsletter_ajax_object.ajax_url, {
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
      location.reload();
    //   startCountdown(result.data.time) 
    })
    .catch((error) => console.error(error) )
  });