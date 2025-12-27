document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form');
    if (!form) {
        return;
    }

    form.addEventListener('submit', function(e) {
        let valid = true;
        let errorMessage = '';

        document.querySelectorAll('textarea[name^="description"]').forEach((textarea) => {
            const id = textarea.id.replace('description', '');
            const titleInput = document.getElementById('title' + id);

            if (textarea.value.trim() !== '' && titleInput.value.trim() === '') {
                valid = false;
                errorMessage = 'No se puede agregar una descripción sin un título';
            }
        });

        if (!valid) {
            e.preventDefault();
            alert(errorMessage);
        }
    });
});
