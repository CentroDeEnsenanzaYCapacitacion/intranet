@extends('layout.mainLayout')
@section('title','Descuento de inscripción')
@section('content')
<div class="row d-flex text-center mt-content">
    <div class="col">
        <h1>Selecciona un descuento para esta preinscripción</h1>
    </div>
</div>
<form action="{{ route('system.report.recipeorrequest') }}" method="POST">
    @csrf
    <input type="hidden" value="{{ $report_id }}" name="report_id">
    <div class="row d-flex text-center mt-5">
        <div class="col">
            <div class="text-center">
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" value="0" class="btn-check" name="discount" id="discount0" autocomplete="off" checked>
                    <label class="btn btn-outline-orange" for="discount0">No aplicar descuento</label>

                    <input type="radio" value="30" class="btn-check" name="discount" id="discount30"  autocomplete="off">
                    <label class="btn btn-outline-orange" for="discount30">30%</label>

                    <input type="radio" value="50" class="btn-check" name="discount" id="discount50"  autocomplete="off">
                    <label class="btn btn-outline-orange" for="discount50">50%</label>

                    <input type="radio" value="100" class="btn-check" name="discount" id="discount100"  autocomplete="off">
                    <label class="btn btn-outline-orange" for="discount100">100%</label>
                </div>
            </div>
            <span id="discountText">El descuento seleccionado será enviado para su aprobación.</span>
        </div>
    </div>
    <div class="row d-flex text-center mt-3">
        <div class="col">
            <div class="text-center" id="card">
                <input class="form-check-input" name="card_payment" type="checkbox" value="card" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Pago con tarjeta
                </label>
            </div>
        </div>
    </div>
    <div class="row d-flex text-center mt-3">
        <div class="col">
            <div class="text-center" id="txtReason" style="display: none;">
                <label for="exampleTextarea" class="form-label">Motivo del descuento</label>
                <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
            </div>
        </div>
    </div>
    <div class="row d-flex text-center mt-5">
        <div class="col">
            <div class="text-center">
                <button class="btn bg-orange text-white w-25" type="submit">Preinscribir</button><br><br>
                <a href="preinscription.php"><button type="button" class="btn btn-outline-orange text-white w-25">Cancelar</button></a>
            </div>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/presign.js') }}"></script>
@endsection