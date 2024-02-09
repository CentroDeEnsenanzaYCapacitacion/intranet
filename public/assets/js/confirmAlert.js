function confirmDelete(origin,userId) {
    var message;
    var formId;

    switch(origin){
        case 'user':
            message = "¿Estás seguro de que deseas bloquear este usuario?";
            formId = "delete-"+origin+"-"+userId;
            break;
    }
    if (confirm(message)) {
        showLoader(true);
        document.getElementById(formId).submit();
    }
}