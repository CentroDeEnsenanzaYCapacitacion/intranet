@extends('layout.mainLayout')
@section('title', 'Nuevo usuario')
@section('content')
    <div class="card shadow ccont pb-3">
        <div class="card-body">
            <div class="row d-flex text-center mt-3">
                <div class="col">
                    <h1>Nuevo Usuario</h1>
                </div>
            </div>
            @if (session('error'))
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
                            <input type="text" class="form-control text-uppercase" id="name" placeholder="Nombre(s)"
                                name="name" value="{{ old('name') }}">
                            <span class="error-message" id="name-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="surnames"><b>Apellidos:</b></label>
                            <input type="text" class="form-control text-uppercase" id="surnames" placeholder="Apellidos"
                                name="surnames" value="{{ old('surnames') }}">
                            <span class="error-message" id="surnames-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="email"><b>Correo Electrónico:</b></label>
                            <input type="email" class="form-control text-uppercase" id="email"
                                placeholder="Correo Electrónico" name="email" value="{{ old('email') }}">
                            <span class="error-message" id="mail-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="phone"><b>Teléfono:</b></label>
                            <input type="text" class="form-control text-uppercase" id="phone" placeholder="Teléfono"
                                name="phone" value="{{ old('phone') }}">
                            <span class="error-message" id="phone-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="cel_phone"><b>Celular:</b></label>
                            <input type="text" class="form-control text-uppercase" id="cel_phone" placeholder="Celular"
                                name="cel_phone" value="{{ old('cel_phone') }}">
                            <span class="error-message" id="cel-phone-error"></span>
                        </div>
                    </div>
                    <div class="col">
                        <b>Género:</b><br>
                        <div class="text-center">
                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" value="H" {{ old('genre') == 'H' ? 'checked' : '' }}
                                    class="btn-check" name="genre" id="male" autocomplete="off" checked>
                                <label class="btn btn-outline-orange text-uppercase" for="male">Hombre</label>

                                <input type="radio" value="M" {{ old('genre') == 'M' ? 'checked' : '' }}
                                    class="btn-check" name="genre" id="female" autocomplete="off">
                                <label class="btn btn-outline-orange text-uppercase" for="female">Mujer</label>

                                <input type="radio" value="NB" {{ old('genre') == 'NB' ? 'checked' : '' }}
                                    class="btn-check" name="genre" id="nobinary" autocomplete="off">
                                <label class="btn btn-outline-orange text-uppercase" for="nobinary">No binario</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role_id"><b>Rol</b></label>
                            <select class="form-control text-uppercase" name="role_id" id="role_id">
                                @if (Auth::user()->role_id == 2)
                                    @if (isset($roles[1]))
                                        <option value="{{ $roles[1]->id }}"
                                            {{ old('role_id') == $roles[1]->id ? 'selected' : '' }}>
                                            {{ $roles[1]->name }}
                                        </option>
                                    @endif
                                    @if (isset($roles[2]))
                                        <option value="{{ $roles[2]->id }}"
                                            {{ old('role_id') == $roles[2]->id ? 'selected' : '' }}>
                                            {{ $roles[2]->name }}
                                        </option>
                                    @endif
                                @else
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group" id="plantel-section">
                            <label for="crew_id"><b>Plantel</b></label>
                            <select class="form-control text-uppercase" name="crew_id" id="crew_id">
                                @foreach ($crews as $crew)
                                    @if ($crew->id > 1)
                                        <option value="{{ $crew->id }}"
                                            {{ old('crew_id') == $crew->id ? 'selected' : '' }}>
                                            {{ $crew->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="crew_id" id="crew_id_hidden" value="1" disabled>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const roleSelect = document.getElementById("role_id");
                                const plantelSection = document.getElementById("plantel-section");
                                const crewSelect = document.getElementById("crew_id");
                                const crewHidden = document.getElementById("crew_id_hidden");

                                function togglePlantel() {
                                    const v = roleSelect.value;
                                    const mustForceCrew1 = (v === "1" || v === "5");

                                    if (mustForceCrew1) {

                                        plantelSection.style.display = "none";
                                        crewSelect.disabled = true;

                                        crewHidden.disabled = false;
                                        crewHidden.value = "1";
                                    } else {

                                        plantelSection.style.display = "block";
                                        crewSelect.disabled = false;

                                        crewHidden.disabled = true;
                                    }
                                }

                                togglePlantel();
                                roleSelect.addEventListener("change", togglePlantel);
                            });
                        </script>
                    </div>
                </div>
                <div class="row d-flex text-center mt-content">
                    <div class="col">
                        <button type="submit" onclick="showLoader(true)" class="btn bg-orange text-white w-25">Guardar
                            Usuario</button>
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
