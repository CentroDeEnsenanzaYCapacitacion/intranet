@extends('layout.mainLayout')
@section('title', 'dashboard')
@section('content')
    <div class="card shadow ccont">
        <div class="card-body">
            @if (session('success'))
                <div id="success" class="alert alert-success" style="margin-top: 100px;">
                    {{ session('success') }}
                </div>
            @endif
            <div class="row d-flex text-center mt-3">
                <div class="col">
                    <h1>Emisión de recibo</h1>
                </div>
            </div>
            <form id="form" action="{{ route('system.collection.tuitions.receipt-post') }}" method="POST">
                @csrf
                <div class="row d-flex text-center mt-3">
                    <div class="col">
                        <input type="hidden" name="concept" id="conceptHidden">
                        <input type="hidden" name="amount" id="amountHidden">
                        <div class="form-group">
                            <label for="receipt_type_id"><b>Tipo de recibo</b></label>
                            <select class="form-control text-uppercase"
                                style="margin: 0 auto; width: 25%; text-align: center;" name="receipt_type_id"
                                id="receipt_type_id">
                                <?php
                                foreach ($receipt_types as $type) {
                                    echo '<option value="' . $type->id . '">' . $type->name . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <b>Concepto:</b><br>
                        <div id="conceptDiv"></div><br>

                        <b>Importe:</b><br>
                        <div id="amountDiv"></div><br>

                        <div class="form-group">
                            <label for="attr_id"><b>Atributos</b></label>
                            <select class="form-control text-uppercase"
                                style="margin: 0 auto; width: 25%; text-align: center;" name="attr_id" id="attr_id">
                                <?php
                                foreach ($receipt_attributes as $attribute) {
                                    echo '<option value="' . $attribute->id . '">' . $attribute->name . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="text-center" id="card">
                            <input class="form-check-input" name="card_payment" type="checkbox" value="card"
                                id="cardCheck">
                            <label class="form-check-label" for="cardCheck">
                                Tarjeta/Depósito
                            </label>
                            Boucher
                            <input type="text">
                        </div>

                        <div class="text-center" id="card">
                            <input class="form-check-input" name="bill" type="checkbox" value="bill" id="billCheck">
                            <label class="form-check-label" for="billCheck">
                                Factura
                            </label>
                        </div>
                        <br>
                        <button class="btn bg-orange text-white" type="submit" onclick="showLoader(true)">
                            Emitir recibo
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if (session('error'))
    <div id="error" class="alert alert-danger" style="margin-top: 20px;">
        {{ session('error') }}
    </div>
@endif
    <script>
        document.getElementById('form').addEventListener('submit', function(event) {
            var conceptDiv = document.getElementById('conceptDiv').textContent;
            var amountDiv = document.getElementById('amountDiv').textContent;
            document.getElementById('conceptHidden').value = conceptDiv;
            document.getElementById('amountHidden').value = amountDiv;
        });

        document.getElementById('receipt_type_id').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];


            var receiptType = selectedOption.textContent;
            // var billChecked = document.getElementById('flexCheckDefault').checked;
            // var billValue = billChecked ? 'Factura' : '';

            // var cardChecked = document.getElementById('flexCheckDefault').checked;
            // var cardValue = cardChecked ? 'Tarjeta/Depósito' : '';

            var conceptText = receiptType;
            //conceptText += 'Boucher: ' + (billValue !== '' ? billValue + '\n' : '') + (cardValue !== '' ? cardValue + '\n' : '');

            document.getElementById('conceptDiv').textContent = conceptText;
        });
    </script>


@endsection
