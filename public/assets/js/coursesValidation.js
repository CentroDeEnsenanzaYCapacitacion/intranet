document.addEventListener("DOMContentLoaded", function() {
    $(document).on('click', '.editButton', function() {
        var courseId = $(this).data('course-id');
        $('#hiddenCourseId').val(courseId);
        $('#editCourseForm').submit();
    });    
});


document.getElementById('saveCourse').addEventListener('click', function(event) {
    const formData = {
        name: document.getElementById('name').value.trim(),
    };

    const errorMessages = {
        name: 'Por favor, introduce el nombre.',
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
