document.addEventListener("DOMContentLoaded", function () {
    var url = "";
    switch (appEnv) {
        case 'local':
            url = "/system/validate-amount";
            break;
        case 'development':
            url = "/intranet_dev/index.php/system/validate-amount";
            break;
        case 'production':
            url = "/system/reports";
            break;
    }
    document
        .getElementById("noDiscountForm")
        .addEventListener("submit", function (e) {
            e.preventDefault();
            const form = e.currentTarget;
            const reportId = document.querySelector(
                'input[name="report_id"]'
            ).value;

            fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'input[name="_token"]'
                    ).value,
                },
                body: JSON.stringify({ report_id: reportId }),
            })
                .then((response) => response.json())
                .then((data) => {
                    showLoader(false);
                    if (data.isValid) {
                        submitFormInNewTab(form);
                    } else {
                        displayError(
                            "No existe un costo registrado para el recibo que se intenta emitir, o el costo es 0, por favor, registre un costo para continuar."
                        );
                    }
                })
                .catch((error) => {
                    console.error("Error en la validación:", error);
                    displayError("Error en la validación");
                    showLoader(false);
                });
        });
});

function submitFormInNewTab(form) {
    var currentRedirectUrl = "";
    switch (appEnv) {
        case 'local':
            currentRedirectUrl = "/system/reports";
            break;
        case 'development':
            currentRedirectUrl = "/intranet_dev/index.php/system/reports";
            break;
        case 'production':
            currentRedirectUrl = "/system/reports";
            break;
    }
    const buttonText =
        document.getElementById("sign").innerText ||
        document.getElementById("sign").textContent;
    if (buttonText === "Inscribir") {
        const newTab = window.open("", "_blank");
        if (newTab) {
            const formHtml = `
                <form action="${form.action}" method="post">
                    <input type="hidden" name="_token" value="${
                        document.querySelector('input[name="_token"]').value
                    }">
                    <input type="hidden" name="report_id" value="${
                        document.querySelector('input[name="report_id"]').value
                    }">
                    <input type="hidden" name="discount" value="${
                        document.querySelector('input[name="discount"]:checked')
                            .value
                    }">
                    <input type="hidden" name="card_payment" value="${
                        document.querySelector('input[name="card_payment"]')
                            .checked
                            ? "card"
                            : ""
                    }">
                    <input type="hidden" name="reason" value="${
                        document.querySelector('textarea[name="reason"]').value
                    }">
                </form>
            `;
            newTab.document.body.innerHTML = formHtml;
            newTab.document.forms[0].submit();
            setTimeout(function () {
                window.location.href = currentRedirectUrl;
            }, 3000);
        } else {
            alert(
                "Por favor, permite las ventanas emergentes para este sitio."
            );
        }
    } else {
        form.submit();
    }
}

function displayError(message) {
    const errorContainer = document.getElementById("error-container");
    const errorList = document.getElementById("error-list");
    errorList.innerHTML = `<li>${message}</li>`;
    errorContainer.style.display = "block";
}
