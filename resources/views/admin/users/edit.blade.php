@extends('layout.mainLayout')
@section('title', 'Modificar usuario')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Modificar Usuario</h1>
        <p class="dashboard-subtitle">Actualiza información del usuario, rol y plantel</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #ef4444; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', ['id' => $user->id]) }}" method="POST" onsubmit="showLoader(true)">
        @csrf
        @method('PUT')

        <div class="modern-card" style="margin-bottom: 24px;">
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
                            <input type="text" class="form-control modern-input text-uppercase" id="name" placeholder="Nombre(s)" name="name" value="{{ old('name', $user->name) }}">
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
                            <input type="text" class="form-control modern-input text-uppercase" id="surnames" placeholder="Apellidos" name="surnames" value="{{ old('surnames', $user->surnames) }}">
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
                            <input type="email" class="form-control modern-input text-uppercase" id="email" placeholder="Correo electr&oacute;nico" name="email" value="{{ old('email', $user->email) }}">
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
                            <input type="text" class="form-control modern-input text-uppercase" id="phone" placeholder="Tel&eacute;fono" name="phone" value="{{ old('phone', $user->phone) }}">
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
                            <input type="text" class="form-control modern-input text-uppercase" id="cel_phone" placeholder="Celular" name="cel_phone" value="{{ old('cel_phone', $user->cel_phone) }}">
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
                                <input type="radio" value="H" @if (old('genre', $user->genre) == 'H') checked @endif class="form-check-input" name="genre" id="male" style="cursor: pointer; margin: 0; margin-right: 8px;">
                                <label class="form-check-label text-uppercase" for="male" style="cursor: pointer; font-weight: 500; margin: 0;">Hombre</label>
                            </div>
                            <div class="form-check" style="display: flex; align-items: center; padding: 12px 20px; background: #f9fafb; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; flex: 1; min-width: 150px;">
                                <input type="radio" value="M" @if (old('genre', $user->genre) == 'M') checked @endif class="form-check-input" name="genre" id="female" style="cursor: pointer; margin: 0; margin-right: 8px;">
                                <label class="form-check-label text-uppercase" for="female" style="cursor: pointer; font-weight: 500; margin: 0;">Mujer</label>
                            </div>
                            <div class="form-check" style="display: flex; align-items: center; padding: 12px 20px; background: #f9fafb; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; flex: 1; min-width: 150px;">
                                <input type="radio" value="NB" @if (old('genre', $user->genre) == 'NB') checked @endif class="form-check-input" name="genre" id="nobinary" style="cursor: pointer; margin: 0; margin-right: 8px;">
                                <label class="form-check-label text-uppercase" for="nobinary" style="cursor: pointer; font-weight: 500; margin: 0;">No binario</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modern-card" style="margin-bottom: 24px;">
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
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3" id="plantel-section">
                        <div class="modern-field">
                            <label for="crew_id">Plantel</label>
                            <select class="form-select modern-input text-uppercase" name="crew_id" id="crew_id">
                                @foreach ($crews as $crew)
                                    @if ($crew->id > 1)
                                        <option value="{{ $crew->id }}" {{ old('crew_id', $user->crew_id) == $crew->id ? 'selected' : '' }}>
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
                    <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16L21 8V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 21V13H7V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 3V8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Guardar cambios
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
<script src="{{ asset('assets/js/user_role_toggle.js') }}"></script>
@endpush
