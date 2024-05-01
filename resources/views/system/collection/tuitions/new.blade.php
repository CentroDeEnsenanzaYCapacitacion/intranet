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
                                style="margin: 0 auto; width: 40%; text-align: center;" name="receipt_type_id"
                                id="receipt_type_id">
                                <?php
                                foreach ($receipt_types as $type) {
                                    echo '<option value="' . $type->id . '">' . $type->name . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div id="attr_group" class="form-group">
                            <label for="attr_id"><b>Atributos</b></label>
                            <select class="form-control text-uppercase"
                                style="margin: 0 auto; width: 40%; text-align: center;" name="attr_id" id="attr_id">
                                <?php
                                foreach ($receipt_attributes as $attribute) {
                                    echo '<option value="' . $attribute->id . '">' . $attribute->name . '</option>';
                                }
                                ?>
                            </select>
                        </div>


                        <b>Concepto:</b><br>
                        <div id="conceptDiv" class="text-uppercase"></div><br>

                        <b>Importe:</b><br>
                        <div id="amountDiv"></div>
                        <div style="display: flex; justify-content: center;">
                            <input class="form-control text-uppercase" style="text-align: center;" type="text"
                                name="receipt_amount" id="receipt_amount" placeholder="Ingrese el monto"
                                style="display:none;">
                        </div><br>

                        <div class="text-center" id="card">
                            <input class="form-check-input" name="card_payment" type="checkbox" value="card"
                                id="cardCheck">
                            <label class="form-check-label" for="cardCheck">
                                <b>Tarjeta/Depósito</b>
                            </label>
                            <div id="boucher" class="mt-3">
                                <b>Boucher</b>
                                <div style="display: flex; justify-content: center;">
                                    <input id="boucher_input" class="form-control text-uppercase"
                                        style="text-align: center;" name="boucher" type="text">
                                </div>
                            </div>

                        </div>

                        <div class="text-center mt-3" id="card">
                            <input class="form-check-input" name="bill" type="checkbox" value="bill" id="billCheck">
                            <label class="form-check-label" for="billCheck">
                                <b>Factura</b>
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
        var student = @json($student);
        var course = @json($course);
        var crew_course_amounts = @json($crew_course_amounts);
        var general_amounts = @json($general_amounts);
        var receipt_attributes = @json($receipt_attributes);
    </script>
    <script src="{{ asset('assets/js/new_tuition.js') }}"></script>
@endsection
