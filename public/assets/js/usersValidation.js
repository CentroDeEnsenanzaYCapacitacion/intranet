document.addEventListener("DOMContentLoaded", function() {
    $(document).on('click', '.editButton', function() {
        var userId = $(this).data('user-id');
        $('#hiddenUserId').val(userId);
        $('#editUserForm').submit();
    });    
});

document.getElementById('saveUser').addEventListener('click', function(event) {
    const formData = {
        name: document.getElementById('name').value.trim(),
        surname: document.getElementById('surnames').value.trim(),
        email: document.getElementById('email').value.trim(),
        phone: document.getElementById('phone').value.trim(),
        celPhone: document.getElementById('cel_phone').value.trim()
    };

    const errorMessages = {
        name: 'Por favor, introduce el nombre.',
        surname: 'Por favor, introduce los apellidos.',
        email: 'Por favor, introduce un correo electrónico.',
        emailInvalid: 'Por favor, introduce una dirección de correo válida.',
        phone: 'Por favor, introduzca al menos un número de teléfono o celular.'
    };

    const hasError = validateForm(formData, errorMessages);

    if (hasError) {
        event.preventDefault();
    } else {
        showLoader(true);
    }
});

function validateForm(formData, errorMessages) {
    let hasError = false;
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    resetErrorDisplay();

    if (!formData.name) {
        showError('name-error', errorMessages.name);
        hasError = true;
    }
    if (!formData.surname) {
        showError('surnames-error', errorMessages.surname);
        hasError = true;
    }
    if (!formData.email) {
        showError('mail-error', errorMessages.email);
        hasError = true;
    } else if (!emailRegex.test(formData.email)) {
        showError('mail-error', errorMessages.emailInvalid);
        hasError = true;
    }
    if (!formData.phone && !formData.celPhone) {
        showError('phone-error', errorMessages.phone);
        showError('cel-phone-error', errorMessages.phone);
        hasError = true;
    }

    return hasError;
}

function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

function resetErrorDisplay() {
    document.querySelectorAll('.error-message').forEach(element => element.style.display = 'none');
}
