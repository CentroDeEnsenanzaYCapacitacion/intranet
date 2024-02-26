@extends('layout.mainLayout')
@section('title','Confirmación de preinscripción')
@section('content')
<div class="row d-flex text-center mt-content">
    <div class="col">
        <h1>Confirmación de preinscripción</h1>
    </div>
</div>
<form id="myForm" action="" method="POST">
    @csrf
    <input type="hidden" value="{{ $request->report_id }}" name="report_id">
    @php
        $array = explode("-",$request->description);
        $discount = $array[0];
        $reason = $array[1];
    @endphp
    <input type="hidden" value="{{ $discount }}" name="discount">
    <div class="row d-flex text-center mt-3">
        <div class="col">
            <div class="text-center" id="card">
                <span class="h3">Se aplicará un descuento del <br><span style="color:#ff6900;font-size:50px;">{{ $discount }}</span></span><br><br>
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
                <button id=presign class="btn bg-orange text-white w-25" type="submit">Preinscribir</button><br><br>
                <a href="{{ route('system.reports.show') }}"><button type="button" class="btn btn-outline-orange text-white w-25">Cancelar</button></a>
            </div>
        </div>
    </div>
</form>
<br>
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<script src="{{ asset('assets/js/redirection.js') }}"></script>
@endsection