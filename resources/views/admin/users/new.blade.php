@extends('layout.mainLayout')
@section('title', 'Nuevo usuario')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Nuevo usuario</h1>
        <p class="dashboard-subtitle">Crea una cuenta y asigna rol y plantel.</p>
    </div>

    @if (session('error'))
        <div class="alert alert-danger" style="background: #fee2e2; border: 1px solid #991b1b; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" style="background: #fee2e2; border: 1px solid #991b1b; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{!! nl2br(e($error)) !!}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.insert') }}" method="POST" onsubmit="showLoader(true)">
        @csrf

        <div class="modern-card">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <h2>Informaci&oacute;n del usuario</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="modern-field">
                            <label for="name">Nombre(s)</label>
                            <input type="text" class="form-control modern-input text-uppercase" id="name" placeholder="Nombre(s)" name="name" value="{{ old('name') }}">
                            <span class="error-message" id="name-error"></span>
                        </div>
                        @error('name')
                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="modern-field">
                            <label for="surnames">Apellidos</label>
                            <input type="text" class="form-control modern-input text-uppercase" id="surnames" placeholder="Apellidos" name="surnames" value="{{ old('surnames') }}">
                            <span class="error-message" id="surnames-error"></span>
                        </div>
                        @error('surnames')
                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="modern-field">
                            <label for="email">Correo electr&oacute;nico</label>
                            <input type="email" class="form-control modern-input text-uppercase" id="email" placeholder="Correo electr&oacute;nico" name="email" value="{{ old('email') }}">
                            <span class="error-message" id="mail-error"></span>
                        </div>
                        @error('email')
                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="modern-field">
                            <label for="phone">Tel&eacute;fono</label>
                            <input type="text" class="form-control modern-input text-uppercase" id="phone" placeholder="Tel&eacute;fono" name="phone" value="{{ old('phone') }}">
                            <span class="error-message" id="phone-error"></span>
                        </div>
                        @error('phone')
                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="modern-field">
                            <label for="cel_phone">Celular</label>
                            <input type="text" class="form-control modern-input text-uppercase" id="cel_phone" placeholder="Celular" name="cel_phone" value="{{ old('cel_phone') }}">
                            <span class="error-message" id="cel-phone-error"></span>
                        </div>
                        @error('cel_phone')
                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label style="display: block; margin-bottom: 12px; font-size: 14px; font-weight: 500; color: #374151;">G&eacute;nero</label>
                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                            <div class="form-check" style="display: flex; align-items: center; padding: 12px 20px; background: #f9fafb; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; flex: 1; min-width: 150px;">
                                <input type="radio" value="H" {{ old('genre') == 'H' || !old('genre') ? 'checked' : '' }} class="form-check-input" name="genre" id="male" style="cursor: pointer; margin: 0; margin-right: 8px;">
                                <label class="form-check-label text-uppercase" for="male" style="cursor: pointer; font-weight: 500; margin: 0;">Hombre</label>
                            </div>
                            <div class="form-check" style="display: flex; align-items: center; padding: 12px 20px; background: #f9fafb; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; flex: 1; min-width: 150px;">
                                <input type="radio" value="M" {{ old('genre') == 'M' ? 'checked' : '' }} class="form-check-input" name="genre" id="female" style="cursor: pointer; margin: 0; margin-right: 8px;">
                                <label class="form-check-label text-uppercase" for="female" style="cursor: pointer; font-weight: 500; margin: 0;">Mujer</label>
                            </div>
                            <div class="form-check" style="display: flex; align-items: center; padding: 12px 20px; background: #f9fafb; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; flex: 1; min-width: 150px;">
                                <input type="radio" value="NB" {{ old('genre') == 'NB' ? 'checked' : '' }} class="form-check-input" name="genre" id="nobinary" style="cursor: pointer; margin: 0; margin-right: 8px;">
                                <label class="form-check-label text-uppercase" for="nobinary" style="cursor: pointer; font-weight: 500; margin: 0;">No binario</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modern-card">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 3V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M6 7H18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M6 17H18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <h2 id="role-title" data-full="Rol y plantel" data-short="Rol">Rol y plantel</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="modern-field">
                            <label for="role_id">Rol</label>
                            <select class="form-select modern-input text-uppercase" name="role_id" id="role_id">
                                @if (Auth::user()->role_id == 2)
                                    @if (isset($roles[1]))
                                        <option value="{{ $roles[1]->id }}" {{ old('role_id') == $roles[1]->id ? 'selected' : '' }}>
                                            {{ $roles[1]->name }}
                                        </option>
                                    @endif
                                    @if (isset($roles[2]))
                                        <option value="{{ $roles[2]->id }}" {{ old('role_id') == $roles[2]->id ? 'selected' : '' }}>
                                            {{ $roles[2]->name }}
                                        </option>
                                    @endif
                                @elseif (Auth::user()->role_id == 6)
                                    @foreach ($roles as $role)
                                        @if ($role->id == 4)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                @else
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3" id="plantel-section">
                        <div class="modern-field">
                            <label for="crew_id">Plantel</label>
                            <select class="form-select modern-input text-uppercase" name="crew_id" id="crew_id">
                                @foreach ($crews as $crew)
                                    @if ($crew->id > 1)
                                        <option value="{{ $crew->id }}" {{ old('crew_id') == $crew->id ? 'selected' : '' }}>
                                            {{ $crew->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="crew_id" id="crew_id_hidden" value="1" disabled>
            </div>
        </div>

        <div style="display: flex; justify-content: center; gap: 12px; margin-top: 32px; margin-bottom: 60px;">
            <button type="submit" class="btn-modern btn-primary" style="min-width: 200px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 12L9 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M21 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M21 12L15 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M9 6H6C4.34315 6 3 7.34315 3 9V15C3 16.6569 4.34315 18 6 18H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Guardar usuario
            </button>
            <a href="{{ route('admin.users.show') }}" class="btn-modern" style="min-width: 200px; background: white; color: #6b7280; border: 1px solid #e5e7eb;" onclick="showLoader(true)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Cancelar
            </a>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const roleSelect = document.getElementById("role_id");
        const plantelSection = document.getElementById("plantel-section");
        const crewSelect = document.getElementById("crew_id");
        const crewHidden = document.getElementById("crew_id_hidden");

        const roleTitle = document.getElementById("role-title");

        const togglePlantel = () => {
            const v = roleSelect.value;
            const mustForceCrew1 = (v === "1" || v === "5" || v === "6");

            if (mustForceCrew1) {
                plantelSection.style.display = "none";
                crewSelect.disabled = true;

                crewHidden.disabled = false;
                crewHidden.value = "1";
                if (roleTitle) {
                    roleTitle.textContent = roleTitle.dataset.short || "Rol";
                }
            } else {
                plantelSection.style.display = "block";
                crewSelect.disabled = false;

                crewHidden.disabled = true;
                if (roleTitle) {
                    roleTitle.textContent = roleTitle.dataset.full || "Rol y plantel";
                }
            }
        };

        togglePlantel();
        roleSelect.addEventListener("change", togglePlantel);
    });
</script>
@endpush
