document.getElementById('validatedRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const newTab = window.open('', '_blank');
    if (newTab) {
        const formHtml = `
                <form action="${this.action}" method="post">
                    <input type="hidden" name="_token" value="${document.querySelector('input[name="_token"]').value}">
                    <input type="hidden" name="report_id" value="${document.querySelector('input[name="report_id"]').value}">
                    <input type="hidden" name="card_payment" value="${document.querySelector('input[name="card_payment"]').checked ? 'card' : ''}">
                    <input type="hidden" name="discount" value="${discount}">
                </form>
            `;
            newTab.document.body.innerHTML = formHtml;
            newTab.document.forms[0].submit();
        setTimeout(function() {
            window.location.href = "/system/reports";
        }, 3000);
    }else {
        alert('Por favor, permite las ventanas emergentes para este sitio.');
    }
});
