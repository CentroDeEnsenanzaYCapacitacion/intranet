@extends('layout.mainLayout')
@section('title','Modificar usuario')
@section('content')
<div class="card shadow ccont pb-3">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>Modificar Usuario</h1>
            </div>
        </div>
        <form action="{{ route('admin.users.update',['id'=>$user->id]) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row d-flex text-center mt-5">
                <div class="col">
                    <div class="form-group">
                        <label for="name"><b>Nombre(s):</b></label>
                        <input type="text" class="form-control text-uppercase" id="name" placeholder="Nombre(s)" name="name" value='{{ $user->name }}'>
                        <span class="error-message" id="name-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="surnames"><b>Apellidos:</b></label>
                        <input type="text" class="form-control text-uppercase" id="surnames" placeholder="Apellidos" name="surnames" value='{{ $user->surnames }}'>
                        <span class="error-message" id="surnames-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="email"><b>Correo Electrónico:</b></label>
                        <input type="email" class="form-control text-uppercase" id="email" placeholder="Correo Electrónico" name="email" value='{{ $user->email }}'>
                        <span class="error-message" id="mail-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="phone"><b>Teléfono:</b></label>
                        <input type="text" class="form-control text-uppercase" id="phone" placeholder="Teléfono" name="phone" value='{{ $user->phone }}'>
                        <span class="error-message" id="phone-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="cel_phone"><b>Celular:</b></label>
                        <input type="text" class="form-control text-uppercase" id="cel_phone" placeholder="Celular" name="cel_phone" value='{{ $user->cel_phone }}'>
                        <span class="error-message" id="cel-phone-error"></span>
                    </div>
                </div>
                <div class="col">
                    <b>Género:</b><br>
                    <div class="text-center">
                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" value="H" class="btn-check" name="genre" id="male" autocomplete="off" @if($user->genre=="H") checked @endif>
                            <label class="btn btn-outline-orange text-uppercase" for="male">Hombre</label>

                            <input type="radio" value="M" class="btn-check" name="genre" id="female" autocomplete="off" @if($user->genre=="M") checked @endif>
                            <label class="btn btn-outline-orange text-uppercase" for="female">Mujer</label>

                            <input type="radio" value="NB" class="btn-check" name="genre" id="nobinary" autocomplete="off" @if($user->genre=="NB") checked @endif>
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
                    <button type="submit" onclick="showLoader(true)" class="btn bg-orange text-white w-25">Modificar Usuario</button><br><br>
                    <a href="{{ route('admin.users.show') }}"><button type="button" class="btn btn-outline-orange text-white w-25">Volver</button></a>
                </div>
            </div>
        </form>
    </div>
</div>
<br>
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
