// Disable form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Get the forms we want to add validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

function validateInput(event,type){
    var pattern;
    switch (type) {
        case "text":
            pattern = /^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/;
            break;
           
        case "test&number":
            pattern = /^[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,50}$/;
            break;

        case "numbers":
            pattern = /^[.\\,\\0-9]{1,}$/;
            break;
        
        case "t&n":
            pattern = /^[A-Za-z0-9]{1,}$/;
            break;
        
        case "email":
            pattern = /^[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/;
            break;
        
        case "pass":
            pattern = /^[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}$/;
            break;
        
        case "regex":
            pattern = /^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/;
            break;
        
        case "icon":{
            pattern = /^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/;
            $(".viewIcon").html('<i class="'+event.target.value+'"></i>')
            break;
        }
        case "phone":
            pattern = /^[-\\(\\)\\0-9 ]{1,}$/;
            break;

    }

    if(!pattern.test(event.target.value)){

        $(event.target).parent().parent().addClass("was-validated");
        $(event.target).parent().children(".invalid-feedback").html("Error de sintaxis");
    
      }
}