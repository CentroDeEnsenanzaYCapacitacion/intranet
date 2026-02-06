document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('passwordConfirmModal');
    if (!modal) return;

    var input = document.getElementById('passwordConfirmInput');
    var errorEl = document.getElementById('passwordConfirmError');
    var confirmBtn = document.getElementById('passwordConfirmBtn');
    var closeBtn = document.getElementById('passwordConfirmClose');
    var pendingForm = null;
    var pendingSubmitter = null;
    var pendingLink = null;

    var forms = document.querySelectorAll('form[data-password-confirm]');
    forms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (form.dataset.passwordConfirmed === 'true') {
                return;
            }
            e.preventDefault();
            pendingForm = form;
            pendingSubmitter = e.submitter || null;
            pendingLink = null;
            input.value = '';
            errorEl.style.display = 'none';
            openModal('passwordConfirmModal');
            setTimeout(function () { input.focus(); }, 350);
        });
    });

    var links = document.querySelectorAll('a[data-password-confirm]');
    links.forEach(function (link) {
        link.addEventListener('click', function (e) {
            if (link.dataset.passwordConfirmed === 'true') {
                return;
            }
            e.preventDefault();
            pendingLink = link;
            pendingForm = null;
            pendingSubmitter = null;
            input.value = '';
            errorEl.style.display = 'none';
            openModal('passwordConfirmModal');
            setTimeout(function () { input.focus(); }, 350);
        });
    });

    confirmBtn.addEventListener('click', submitPassword);
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitPassword();
        }
    });

    closeBtn.addEventListener('click', function () {
        closeModal('passwordConfirmModal');
        pendingForm = null;
        pendingSubmitter = null;
        pendingLink = null;
    });

    function submitPassword() {
        var password = input.value;
        if (!password) {
            errorEl.textContent = 'La contraseña es requerida.';
            errorEl.style.display = 'block';
            return;
        }

        confirmBtn.disabled = true;

        fetch('/confirm-password/ajax', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ password: password })
        })
        .then(function (response) {
            return response.json().then(function (data) {
                return { ok: response.ok, data: data };
            });
        })
        .then(function (result) {
            confirmBtn.disabled = false;
            if (result.ok && result.data.confirmed) {
                closeModal('passwordConfirmModal');
                if (pendingForm) {
                    pendingForm.dataset.passwordConfirmed = 'true';
                    if (pendingSubmitter && pendingSubmitter.hasAttribute('formaction')) {
                        pendingSubmitter.click();
                    } else {
                        pendingForm.submit();
                    }
                } else if (pendingLink) {
                    pendingLink.dataset.passwordConfirmed = 'true';
                    pendingLink.click();
                }
            } else {
                var msg = (result.data.errors && result.data.errors.password)
                    ? result.data.errors.password[0]
                    : 'La contraseña es incorrecta.';
                errorEl.textContent = msg;
                errorEl.style.display = 'block';
                input.select();
            }
        })
        .catch(function () {
            confirmBtn.disabled = false;
            errorEl.textContent = 'Error de conexión. Inténtalo de nuevo.';
            errorEl.style.display = 'block';
        });
    }
});
