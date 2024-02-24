function showLoader(visible){
    if(visible){
        document.getElementById('loader-container').style.display = 'block';
    }else{
        document.getElementById('loader-container').style.display = 'none';
    }
}

window.addEventListener('pageshow', (event) => {
    showLoader(false);
});

window.onload = function() {
    setTimeout(function() {
        const alerta = document.getElementById('success');
        if (alerta) {
          alerta.style.opacity = '0';
          setTimeout(function() {
            alerta.style.display = 'none';
          }, 500);
        }
      }, 5000);
  };

