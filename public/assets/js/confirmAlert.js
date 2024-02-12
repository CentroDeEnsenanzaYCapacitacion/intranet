
function confirmDelete(origin,Id) {
    var message;
    var formId;

    switch(origin){
        case 'user':
            message = "¿Estás seguro de que deseas bloquear este usuario?";
            formId = "delete-"+origin+"-"+Id;
            break;
        case 'course':
            message = "¿Estás seguro de que deseas eliminar este curso?";
            formId = "delete-"+origin+"-"+Id;
            break;
    }
    if (confirm(message)) {
        showLoader(true);
        document.getElementById(formId).submit();
    }
}