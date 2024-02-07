document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.table').addEventListener('click', function(event) {
        var form = document.getElementById('formPost');
        var userIdElement = document.getElementById('userId');

        if (event.target.classList.contains('editButton')) {
            var amountId = event.target.getAttribute('data-amount-id');
            userIdElement.value = amountId;
            form.action = '../../msrvs/users/saveAmount.php'; 
            form.submit();
        }
    });
});

function esNumerico(valor) {
    return /^\d+$/.test(valor);
}

document.getElementById('saveAmount').addEventListener('click', function(event) {
    const formData = {
        name: document.getElementById('name').value.trim(),
        amount: document.getElementById('amount').value.trim(),
        recipe_type: document.getElementById('recipe_type_id').value.trim(),
        crew: document.getElementById('crew_id').value.trim()
    };

    const errorMessages = {
        name: 'Por favor, introduce el nombre.',
        amount: 'Por favor, introduce el importe.',
        amount_format: 'El importe introducido no es válido.',
        recipe_type: 'El tipo de recibo seleccionado no es válido.',
        crew: 'El plantel seleccionado no es válido.'
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

    resetErrorDisplay();

    if (!formData.name) {
        showError('name-error', errorMessages.name);
        hasError = true;
    }

    if (!formData.amount) {
        showError('amount-error', errorMessages.amount);
        hasError = true;
    }else{
        if (!esNumerico(formData.amount)){
            showError('amount-error', errorMessages.amount_format);
            hasError = true;
        }
    }

    if (!formData.recipe_type) {
        showError('recipe-type-error', errorMessages.recipe_type);
        hasError = true;
    }

    if (!formData.crew) {
        showError('crew-error', errorMessages.crew);
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
