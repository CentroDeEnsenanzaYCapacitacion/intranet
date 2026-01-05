document.addEventListener("DOMContentLoaded", function () {
    document
        .getElementById("paybillForm")
        .addEventListener("submit", function (e) {
            e.preventDefault();
            submitFormInNewTab(e.currentTarget);
        });
});

function submitFormInNewTab(form) {
    const currentRedirectUrl = "/system/collection/paybills";
    const newTab = window.open("", "_blank");
    if (newTab) {
        const formHtml = `
            <form action="${escapeAttributes(form.action)}" method="post">
                <input type="hidden" name="_token" value="${escapeAttributes(
                    document.querySelector('input[name="_token"]').value
                )}">
                <input type="hidden" name="crew_id" value="${escapeAttributes(
                    document.querySelector('input[name="crew_id"]').value
                )}">
                <input type="hidden" name="user_id" value="${escapeAttributes(
                    document.querySelector('select[name="user_id"]').value
                )}">
                <input type="hidden" name="receives" value="${escapeAttributes(
                    document.querySelector('input[name="receives"]').value
                )}">
                <input type="hidden" name="concept" value="${escapeAttributes(
                    document.querySelector('input[name="concept"]').value
                )}">
                <input type="hidden" name="amount" value="${escapeAttributes(
                    document.querySelector('input[name="amount"]').value
                )}">
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
}

function displayError(message) {
    const errorContainer = document.getElementById("error-container");
    const errorList = document.getElementById("error-list");
    const li = document.createElement('li');
    li.textContent = message;
    errorList.innerHTML = '';
    errorList.appendChild(li);
    errorContainer.style.display = "block";
}
