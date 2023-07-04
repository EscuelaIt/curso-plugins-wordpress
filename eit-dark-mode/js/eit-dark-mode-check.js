document.addEventListener('DOMContentLoaded', function() {

  const body = document.querySelector('body');
  const theme = localStorage.getItem('eitTheme')
  if ( theme ) {
    body.classList.add( theme );
  }

})