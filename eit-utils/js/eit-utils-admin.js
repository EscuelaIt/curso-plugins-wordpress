
jQuery(document).ready(function($) {
  
  const { __ } = wp.i18n;

  $('.nav-tab').on('click', function(e) {
      e.preventDefault();

      $('.nav-tab').removeClass('nav-tab-active');
      $(this).addClass('nav-tab-active');

      $('.tab-content').hide();
      $('#' + $(this).attr('id') + '-content').show();
  });




  $("#eitSelectAll").click(function() {
    $(".eit-row-selector").prop("checked", true);
  });
  $("#eitDeselectAll").click(function() {
      $(".eit-row-selector").prop("checked", false);
  });
  $("#eitDeleteSelected").click(function() {
      var ids = [];
      $(".eit-row-selector:checked").each(function() {
          ids.push($(this).val());
      });
      if (!ids.length) {
          alert("No se seleccionó ningún registro.");
          return;
      }
      // var confirmation = confirm("¿Está seguro de que desea borrar los registros seleccionados?");
      var confirmation = confirm(__("Are you sure you want to delete the selected records?", "eit-utils"));
      if (!confirmation) {
          return;
      }
      
      // $.post(utils_ajax_object.ajax_url, { 
      //     action: utils_ajax_object.action, 
      //     ids: ids, 
      //     nonce: utils_ajax_object.nonce,
      // }, function(response) {
      //     // location.reload();
      //     console.log(response)
      // });


      const data = {
        action: utils_ajax_object.action,
        nonce: utils_ajax_object.nonce,
        ids: ids
      };
      console.log(data);
    
      fetch(utils_ajax_object.ajax_url, {
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
      })
      .catch((error) => console.error(error) )

    });


});