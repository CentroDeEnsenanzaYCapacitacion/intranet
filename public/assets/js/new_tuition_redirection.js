document.addEventListener("DOMContentLoaded", function () {
    document
        .getElementById("newTuitionForm")
        .addEventListener("submit", function (e) {
            e.preventDefault();
            submitFormInNewTab(e.currentTarget);
        });
});

function submitFormInNewTab(form) {
    const currentRedirectUrl =
        "/system/collection/" +
        document.querySelector('input[name="student_id"]').value +
        "/tuitions";
    const newTab = window.open("", "_blank");
    if (newTab) {
        const formHtml = `
            <form action="${form.action}" method="post">
                <input type="hidden" name="_token" value="${
                    document.querySelector('input[name="_token"]').value
                }">
                <input type="hidden" name="crew_id" value="${
                    document.querySelector('input[name="crew_id"]').value
                }">
                <input type="hidden" name="student_id" value="${
                    document.querySelector('input[name="student_id"]').value
                }">
                <input type="hidden" name="concept" value="${
                    document.querySelector('input[name="concept"]').value
                }">
                <input type="hidden" name="amount" value="${
                    document.querySelector('input[name="amount"]').value
                }">
                <input type="hidden" name="receipt_type_id" value="${
                    document.querySelector('select[name="receipt_type_id"]').value
                }">
                <input type="hidden" name="attr_id" value="${
                    document.querySelector('select[name="attr_id"]').value
                }">
                <input type="hidden" name="receipt_amount" value="${
                    document.querySelector('input[name="receipt_amount"]').value
                }">
                <input type="hidden" name="card_payment" value="${
                    document.querySelector('input[name="card_payment"]')
                        .checked
                        ? "card"
                        : ""
                }">
                <input type="hidden" name="voucher" value="${
                    document.querySelector('input[name="voucher"]').value
                }">
                <input type="hidden" name="bill" value="${
                    document.querySelector('input[name="bill"]')
                        .checked
                        ? "bill"
                        : ""
                }">
            </form>
        `;
        newTab.document.body.innerHTML = formHtml;
        newTab.document.forms[0].submit();
        setTimeout(function () {
            window.location.href = currentRedirectUrl;
        }, 3000);
    } else {
        alert("Por favor, permite las ventanas emergentes para este sitio.");
    }
}

function displayError(message) {
    const errorContainer = document.getElementById("error-container");
    const errorList = document.getElementById("error-list");
    errorList.innerHTML = `<li>${message}</li>`;
    errorContainer.style.display = "block";
}
