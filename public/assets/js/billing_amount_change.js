document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-amount-change').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var type = this.dataset.type;
            var itemId = this.dataset.id;
            var folio = this.dataset.folio;
            var amount = parseFloat(this.dataset.amount);

            document.getElementById('amountChangeType').value = type;
            document.getElementById('amountChangeItemId').value = itemId;
            document.getElementById('amountChangeLabel').textContent = (type === 'receipt' ? 'Recibo' : 'Vale') + ' #' + folio;
            document.getElementById('amountChangeCurrent').textContent = '$' + amount.toFixed(2);
            document.getElementById('new_amount').value = '';
            document.getElementById('amount_change_reason').value = '';
            openModal('amountChangeModal');
        });
    });
});
