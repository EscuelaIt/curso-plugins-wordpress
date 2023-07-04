// No se requiere jQuery para este script

document.addEventListener('DOMContentLoaded', function() {
  var btnScrollUp = document.getElementById('eit-scroll-top');

  window.addEventListener('scroll', function() {
      if (window.scrollY > 100) {
          btnScrollUp.classList.add('show')
      } else {
          btnScrollUp.classList.remove('show')
      }
  });

  btnScrollUp.addEventListener('click', function(e) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
  });
});
