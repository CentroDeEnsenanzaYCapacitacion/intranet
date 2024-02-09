@extends('layout.mainLayout')
@section('title','Nuevo usuario')
@section('content')
<div class="row d-flex text-center mt-content">
    <div class="col">
        <h1>Nuevo Usuario</h1>
    </div>
</div>
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<form action="{{ route('admin.users.insert') }}" method="POST">
    @csrf
    <div class="row d-flex text-center mt-content">
        <div class="col">
            <div class="form-group">
                <label for="name"><b>Nombre(s):</b></label>
                <input type="text" class="form-control text-uppercase" id="name" placeholder="Nombre(s)" name="name">
                <span class="error-message" id="name-error"></span>
            </div>
            <div class="form-group">
                <label for="surnames"><b>Apellidos:</b></label>
                <input type="text" class="form-control text-uppercase" id="surnames" placeholder="Apellidos" name="surnames">
                <span class="error-message" id="surnames-error"></span>
            </div>
            <div class="form-group">
                <label for="email"><b>Correo Electrónico:</b></label>
                <input type="email" class="form-control text-uppercase" id="email" placeholder="Correo Electrónico" name="email">
                <span class="error-message" id="mail-error"></span>
            </div>
            <div class="form-group">
                <label for="phone"><b>Teléfono:</b></label>
                <input type="text" class="form-control text-uppercase" id="phone" placeholder="Teléfono" name="phone">
                <span class="error-message" id="phone-error"></span>
            </div>
            <div class="form-group">
                <label for="cel_phone"><b>Celular:</b></label>
                <input type="text" class="form-control text-uppercase" id="cel_phone" placeholder="Celular" name="cel_phone">
                <span class="error-message" id="cel-phone-error"></span>
            </div>
        </div>
        <div class="col">
            <b>Género:</b><br>
            <div class="text-center">
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" value="H" class="btn-check" name="genre" id="male" autocomplete="off" checked>
                    <label class="btn btn-outline-orange text-uppercase" for="male">Hombre</label>

                    <input type="radio" value="M" class="btn-check" name="genre" id="female" autocomplete="off">
                    <label class="btn btn-outline-orange text-uppercase" for="female">Mujer</label>

                    <input type="radio" value="NB" class="btn-check" name="genre" id="nobinary" autocomplete="off">
                    <label class="btn btn-outline-orange text-uppercase" for="nobinary">No binario</label>
                </div>
            </div>
            <div class="form-group">
                <label for="role_id"><b>Rol</b></label>
                <select class="form-control text-uppercase" name="role_id" id="role_id">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="crew_id"><b>Plantel</b></label>
                <select class="form-control text-uppercase" name="crew_id" id="crew_id">
                @foreach($crews as $crew)
                    @if($crew->id > 1) 
                        <option value="{{ $crew->id }}">{{ $crew->name }}</option>';
                    @endif
                @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row d-flex text-center mt-content">
        <div class="col">
            <button type="submit" onclick="showLoader(true)" class="btn bg-orange text-white w-25">Guardar Usuario</button>
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