@extends('layout.mainLayout')
@section('title','dashboard')
@section('content')
<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>Nuevo vale</h1>
            </div>
        </div>
        <div class="row d-flex text-center mt-3">
            <form id="paybillForm" action="{{ route('system.collection.paybill-post') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ Auth::user()->crew_id }}" name="crew_id">
                <div class="row d-flex text-center mt-3">
                    <div class="col">
                        <div class="form-group">
                            <label for="receipt_type_id"><b>Autoriza:</b></label>
                            <select class="form-control text-uppercase"
                                style="margin: 0 auto; width: 25%; text-align: center;" name="user_id"
                                id="user_id">
                                <?php
                                foreach ($users as $user) {
                                    echo '<option value="' . $user->id . '">' . $user->name . ' '.$user->surnames.'</option>';
                                }
                                ?>
                            </select>
                        </div><br>

                        <b>Recibe:</b><br>
                        <div style="display: flex; justify-content: center;">
                            <input id="boucher_input" class="form-control text-uppercase"
                                style="text-align: center;" name="receives" type="text">
                        </div><br>

                        <b>Concepto:</b><br>
                        <div style="display: flex; justify-content: center;">
                            <input id="boucher_input" class="form-control text-uppercase"
                                style="text-align: center;" name="concept" type="text">
                        </div><br>

                        <b>Importe:</b><br>
                        <div style="display: flex; justify-content: center;">
                            <input class="form-control text-uppercase" style="text-align: center;" type="text"
                                name="amount" id="receipt_amount" placeholder="Ingrese el monto"
                                style="display:none;">
                        </div><br>

                        <button class="btn bg-orange text-white" type="submit" onclick="showLoader(true)">
                            Emitir vale
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/paybill_redirection.js') }}"></script>
@endsection
