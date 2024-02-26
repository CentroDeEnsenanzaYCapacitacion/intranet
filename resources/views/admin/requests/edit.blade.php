@extends('layout.mainLayout')
@section('title','Solicitudes')
@section('content')
<div class="row d-flex text-center mt-content">
    <div class="col">
        <h1>Cambio de pordentaje de preinscripción</h1>
    </div>
</div>
<form id="myForm" action="{{ route('admin.requests.changePercentage',['request_id'=>$request->id]) }}" method="POST">
    @csrf
    @php
        $array = explode("-",$request->description);
        $discount = trim($array[0]);
    @endphp
    <div class="row d-flex text-center mt-3">
        <div class="col">
            <div class="text-center">
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" value="30%" {{ $discount == '30%' ? 'checked' : '' }} class="btn-check" name="discount" id="discount30"  autocomplete="off">
                    <label class="btn btn-outline-orange" for="discount30">30%</label>

                    <input type="radio" value="50%" {{ $discount == '50%' ? 'checked' : '' }} class="btn-check" name="discount" id="discount50"  autocomplete="off">
                    <label class="btn btn-outline-orange" for="discount50">50%</label>

                    <input type="radio" value="100%" {{ $discount == '100%' ? 'checked' : '' }} class="btn-check" name="discount" id="discount100"  autocomplete="off">
                    <label class="btn btn-outline-orange" for="discount100">100%</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row d-flex text-center mt-5">
        <div class="col">
            <div class="text-center">
                <button onclick="showLoader(true)" id=presign class="btn bg-orange text-white w-25" type="submit">Cambiar porcentaje</button><br><br>
                <a href="{{ route('admin.requests.show') }}"><button type="button" class="btn btn-outline-orange text-white w-25">Cancelar</button></a>
            </div>
        </div>
    </div>
</form>
@endsection