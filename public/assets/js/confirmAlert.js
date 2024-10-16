
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
        case 'request':
            message = "¿Estás seguro de que deseas rechazar esta solicitud? (Esta cción no se puede deshacer y no se podrá enviar una nueva solicitud)";
            break;
    }
    if (confirm(message)) {
        var url = "";
        switch (appEnv) {
            case 'local':
                url = "/admin/request/" + Id + "/decline";
                break;
            case 'development':
                url = "/intranet_dev/index.php/admin/request/" + Id + "/decline";
                break;
            case 'production':
                url = "/system/reports";
                break;
        }
        showLoader(true);
        if(origin === 'request') {
            window.location.href = url;
        } else {
            document.getElementById(formId).submit();
        }
    }
}
