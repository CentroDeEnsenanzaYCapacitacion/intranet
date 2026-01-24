document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('changePasswordForm');
    if (!form) {
        return;
    }

    form.addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password_confirmation').value;

        if (password !== confirmation) {
            e.preventDefault();
            alert('Las contraseñas no coinciden.');
            return false;
        }

        if (password.length < 12) {
            e.preventDefault();
            alert('La contraseña debe tener al menos 12 caracteres.');
            return false;
        }

        const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/;

        if (!pattern.test(password)) {
            e.preventDefault();
            alert('La contraseña debe incluir mayúsculas, minúsculas, números y símbolos.');
            return false;
        }
    });
});
