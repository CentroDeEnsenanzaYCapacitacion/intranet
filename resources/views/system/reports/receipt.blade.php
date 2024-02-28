@extends('layout.mainLayout')
@section('title','Confirmación de preinscripción')
@section('content')
<div class="row d-flex text-center mt-content">
    <div class="col">
        <h1>Confirmación de preinscripción</h1>
    </div>
</div>
<form id="validatedRequestForm" action="{{ route('system.report.generatereceipt') }}" method="POST">
    @csrf
    <input type="hidden" value="{{ $request->report_id }}" name="report_id">
    @php
        $array = explode("-",$request->description);
        $discount = $array[0];
    @endphp
    <div class="row d-flex text-center mt-3">
        <div class="col">
            <div class="text-center" id="card">
                @if($request->approved)
                    <span class="h3">Se aplicará un descuento del <br><span style="color:#ff6900;font-size:50px;">{{ $discount }}</span></span><br><br>   
                @else
                    <span class="h3">No se aplicará descuento</span><br><br>
                @endif
                <input class="form-check-input" name="card_payment" type="checkbox" value="card" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Pago con tarjeta
                </label>
            </div>
        </div>
    </div>
    <div class="row d-flex text-center mt-5">
        <div class="col">
            <div class="text-center">
                <button onclick="showLoader(true)" id="validated_presign" class="btn bg-orange text-white w-25" type="submit">Preinscribir</button><br><br>
                <a onclick="showLoader(true)" class="btn btn-outline-orange text-white w-25" href="{{ route('system.reports.show') }}">Cancelar</button></a>
            </div>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/discount_redirection.js') }}"></script>
@endsection