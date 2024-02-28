@extends('layout.mainLayout')
@section('title','dashboard')
@section('content')
<div class="row d-flex text-center mt-content">
    <div class="col">
        <h1>Nuevo Informe</h1>
    </div>
</div>
<form action="{{ route('system.report.insert') }}" method="POST">
    @csrf
    <div class="row d-flex text-center mt-5">
        <div class="col">
            <div class="form-group">
                <label for="name"><b>Nombre(s):</b></label>
                <input type="text" class="form-control text-uppercase" id="name" placeholder="Nombre(s)" name="name" value="{{ old('name') }}">
                <span class="error-message" id="name-error"></span>
            </div>
            <div class="form-group">
                <label for="surnames"><b>Apellidos:</b></label>
                <input type="text" class="form-control text-uppercase" id="surnames" placeholder="Apellidos" name="surnames" value="{{ old('surnames') }}">
                <span class="error-message" id="surname-error"></span>
            </div>
            <div class="form-group">
                <label for="email"><b>Correo Electrónico:</b></label>
                <input type="email" class="form-control text-uppercase" id="email" placeholder="Correo Electrónico" name="email" value="{{ old('email') }}">
                <span class="error-message" id="mail-error"></span>
            </div>
            <div class="form-group">
                <label for="phone"><b>Teléfono:</b></label>
                <input type="text" class="form-control text-uppercase" id="phone" placeholder="Teléfono" name="phone" value="{{ old('phone') }}">
                <span class="error-message" id="phone-error"></span>
            </div>
            <div class="form-group">
                <label for="cel_phone"><b>Celular:</b></label>
                <input type="text" class="form-control text-uppercase" id="cel_phone" placeholder="Celular" name="cel_phone" value="{{ old('cel_phone') }}">
                <span class="error-message" id="cel-phone-error"></span>
            </div>
        </div>
        <div class="col">
            <b>Género:</b><br>
            <div class="text-center">
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" value="H" {{ old('genre') == 'H' ? 'selected' : '' }} class="btn-check text-uppercase" name="genre" id="male" autocomplete="off" checked>
                    <label class="btn btn-outline-orange text-uppercase" for="male">Hombre</label>

                    <input type="radio" value="M" {{ old('genre') == 'M' ? 'selected' : '' }} class="btn-check uppercase" name="genre" id="female" autocomplete="off">
                    <label class="btn btn-outline-orange text-uppercase" for="female">Mujer</label>

                    <input type="radio" value="NB" {{ old('genre') == 'NB' ? 'selected' : '' }} class="btn-check uppercase" name="genre" id="nobinary" autocomplete="off">
                    <label class="btn btn-outline-orange text-uppercase" for="nobinary">No binario</label>
                </div>
            </div>
            <div class="form-group">
                <label for="exampleSelect"><b>Área de interés</b></label>
                <select class="form-control text-uppercase" name="course_id" id="course_id">
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="exampleSelect"><b>Conoce CEC por</b></label>
                <select class="form-control text-uppercase" name="marketing_id" id="marketing_id">
                    @foreach($marketings as $marketing) 
                        <option value="{{ $marketing->id }}" {{ old('marketing_id') == $marketing->id ? 'selected' : '' }}>{{ $marketing->name }}</option>
                    @endforeach    
                </select>
            </div>
            <div class="form-group">
                <label for="exampleSelect"><b>Plantel de interés</b></label>
                <select class="form-control text-uppercase" name="crew_id" id="crew_id">
                    @foreach($crews as $crew) 
                        @if($crew->id > 1)
                            <option value="{{ $crew->id }}" {{ old('crew_id') == $crew->id ? 'selected' : '' }}>{{ $crew->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row d-flex text-center mt-5">
        <div class="col">
            <button onclick="showLoader(true)" type="submit" class="btn bg-orange text-white w-25">Guardar informe</button>
        </div>
    </div>
</form><br>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection