(function() {

  const { __, _x, _n, _nx, sprintf, count } = wp.i18n;


  // actualiza opción seleccionada del radio button
  function eit_update_radio() {

    const body = document.querySelector('body');

    const theme = localStorage.getItem('eitTheme')
    if ( theme ) {
      body.classList.add( theme );
      const eitRadios = document.querySelectorAll('input[name="eit_dark_mode"]');
      eitRadios.forEach( x => {
        x.checked = false;
        if (x.value == theme) x.checked = true;
      })

    }
  }


  document.addEventListener('DOMContentLoaded', function() {


    eit_update_radio();

    const eitRadios = document.querySelectorAll('input[name="eit_dark_mode"]');
    eitRadios.forEach ( radio => radio.addEventListener('change', eit_save_change))

    function eit_save_change(ev) {
      let selectedTheme = ev.target.value;

      
      localStorage.setItem('eitTheme',selectedTheme)
      console.log(__("Save theme to local storage", "eit-dark-mode"))
      show_msg(1);
      show_msg(4);

      
      const body = document.querySelector('body');
      body.classList.remove('eit-dark', 'eit-light', 'eit-system');
      body.classList.add(selectedTheme);

    }
  })

  function show_msg( num ) {
    const message = sprintf(
      _n(
        'An item has been saved.',
        '%d items have been saved.',
        num,
        'eit-dark-mode'
      ),
      num
    );
    console.log(message);
  }

})();