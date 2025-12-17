document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("inscriptionForm");
    if (!form) return;

    const courseId = document.getElementById("courseId")?.value;
    const amountInput = document.querySelector('input[name="amount"]');
    const explanationContainer = document.getElementById("explanationContainer");
    const explanationTextarea = document.getElementById("price_explanation");
    let catalogAmount = null;

    if (courseId && amountInput) {
        fetch('/system/get-inscription-amount/' + courseId)
            .then(response => response.json())
            .then(data => {
                if (data.amount !== null) {
                    catalogAmount = parseFloat(data.amount);
                }
            })
            .catch(error => {
                console.error('Error al obtener el monto del catálogo:', error);
            });

        amountInput.addEventListener('input', function() {
            checkPriceDifference();
        });
    }

    function checkPriceDifference() {
        if (catalogAmount === null || !amountInput) return;

        const enteredAmount = parseFloat(amountInput.value);

        if (!isNaN(enteredAmount) && Math.abs(enteredAmount - catalogAmount) > 0.01) {
            if (explanationContainer) {
                explanationContainer.style.display = 'block';
                if (explanationTextarea) {
                    explanationTextarea.required = true;
                }
            }
        } else {
            if (explanationContainer) {
                explanationContainer.style.display = 'none';
                if (explanationTextarea) {
                    explanationTextarea.required = false;
                    explanationTextarea.value = '';
                }
            }
        }
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const courseName = document.getElementById("courseName")?.value || "";
        const isBachilleratoExamen = courseName.toUpperCase().includes("BACHILLERATO EN UN EXAMEN");

        if (!isBachilleratoExamen) {
            const amount = parseFloat(amountInput.value);

            if (isNaN(amount) || amount <= 0) {
                showLoader(false);
                displayError("El importe de inscripción debe ser mayor a 0");
                return;
            }

            if (explanationContainer && explanationContainer.style.display !== 'none') {
                if (!explanationTextarea || !explanationTextarea.value.trim()) {
                    showLoader(false);
                    displayError("Debes explicar la razón de la diferencia de precio");
                    return;
                }
            }
        }

        showLoader(true);
        submitFormInNewTab(form);
    });
});

function submitFormInNewTab(form) {
    const currentRedirectUrl = "/system/reports";
    const newTab = window.open("", "_blank");

    if (newTab) {
        const amountInput = document.querySelector('input[name="amount"]');
        const amountValue = amountInput ? amountInput.value : "0";

        const cardPaymentCheckbox = document.querySelector('input[name="card_payment"][type="checkbox"]');
        const cardPaymentValue = cardPaymentCheckbox && cardPaymentCheckbox.checked ? "card" : "";

        const priceExplanationTextarea = document.getElementById('price_explanation');
        const priceExplanationValue = priceExplanationTextarea ? priceExplanationTextarea.value : "";

        const formHtml = `
            <form action="${form.action}" method="post">
                <input type="hidden" name="_token" value="${
                    document.querySelector('input[name="_token"]').value
                }">
                <input type="hidden" name="report_id" value="${
                    document.querySelector('input[name="report_id"]').value
                }">
                <input type="hidden" name="amount" value="${amountValue}">
                <input type="hidden" name="card_payment" value="${cardPaymentValue}">
                <input type="hidden" name="price_explanation" value="${priceExplanationValue}">
            </form>
        `;
        newTab.document.body.innerHTML = formHtml;
        newTab.document.forms[0].submit();

        setTimeout(function () {
            window.location.href = currentRedirectUrl;
        }, 3000);
    } else {
        alert("Por favor, permite las ventanas emergentes para este sitio.");
        showLoader(false);
    }
}

function displayError(message) {
    const errorContainer = document.getElementById("error-container");
    const errorList = document.getElementById("error-list");
    errorList.innerHTML = `<li>${message}</li>`;
    errorContainer.style.display = "block";
}
