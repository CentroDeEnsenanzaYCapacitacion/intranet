document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("inscriptionForm");
    if (!form) return;

    const courseId = document.getElementById("courseId")?.value;
    const amountInput = document.querySelector('input[name="amount"]');
    const explanationContainer = document.getElementById("explanationContainer");
    const explanationTextarea = document.getElementById("price_explanation");
    let catalogAmount = null;

    if (explanationContainer) {
        explanationContainer.classList.add('d-none');
        explanationContainer.style.display = 'none';
    }
    if (explanationTextarea) {
        explanationTextarea.required = false;
    }

    if (courseId && amountInput) {
        fetch('/system/get-inscription-amount/' + courseId)
            .then(response => response.json())
            .then(data => {
                if (data.amount !== null) {
                    catalogAmount = parseFloat(data.amount);
                }
                checkPriceDifference();
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
                explanationContainer.classList.remove('d-none');
                explanationContainer.style.display = 'block';
                if (explanationTextarea) {
                    explanationTextarea.required = true;
                }
            }
        } else {
            if (explanationContainer) {
                explanationContainer.classList.add('d-none');
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
        form.target = "_blank";
        form.submit();

        setTimeout(function () {
            window.location.href = "/system/reports";
        }, 2000);
    });
});

function displayError(message) {
    const errorContainer = document.getElementById("error-container");
    const errorList = document.getElementById("error-list");
    if (!errorContainer || !errorList) return;

    const li = document.createElement('li');
    li.textContent = message;
    errorList.innerHTML = '';
    errorList.appendChild(li);
    errorContainer.style.display = "block";

    const header = document.querySelector(".header-fixed");
    const offset = header ? header.offsetHeight + 10 : 0;
    const top = errorContainer.getBoundingClientRect().top + window.pageYOffset - offset;
    window.scrollTo({ top, behavior: "smooth" });
}
