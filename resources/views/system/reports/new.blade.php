@extends('layout.mainLayout')
@section('title','Nuevo Informe')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Nuevo Informe</h1>
        <p class="dashboard-subtitle">Registro de prospecto interesado</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="background: #fee2e2; border: 1px solid #991b1b; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{!! nl2br(e($error)) !!}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('system.report.insert') }}" method="POST">
        @csrf
        <div class="modern-card">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>Información Personal</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Nombre(s)</label>
                        <input type="text" class="form-control text-uppercase" id="name" placeholder="Nombre(s)" name="name" value="{{ old('name') }}" required>
                        <span class="error-message" id="name-error"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="surnames" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Apellidos</label>
                        <input type="text" class="form-control text-uppercase" id="surnames" placeholder="Apellidos" name="surnames" value="{{ old('surnames') }}" required>
                        <span class="error-message" id="surname-error"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" placeholder="correo@ejemplo.com" name="email" value="{{ old('email') }}" required>
                        <span class="error-message" id="mail-error"></span>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="phone" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Teléfono</label>
                        <input type="text" class="form-control" id="phone" placeholder="Teléfono" name="phone" value="{{ old('phone') }}">
                        <span class="error-message" id="phone-error"></span>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cel_phone" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Celular</label>
                        <input type="text" class="form-control" id="cel_phone" placeholder="Celular" name="cel_phone" value="{{ old('cel_phone') }}" required>
                        <span class="error-message" id="cel-phone-error"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label style="display: block; margin-bottom: 12px; font-size: 14px; font-weight: 500; color: #374151;">Género</label>
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
                                <label class="form-check-label text-uppercase" for="nobinary" style="cursor: pointer; font-weight: 500; margin: 0;">No Binario</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modern-card" style="margin-top: 24px;">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 14L9 11M12 14V21M12 14C13.3968 14 14.7689 13.6698 16.0078 13.0351C17.2467 12.4004 18.3185 11.4781 19.1423 10.341C19.9661 9.20389 20.5197 7.88226 20.7598 6.48005C20.9999 5.07783 20.9201 3.63592 20.5263 2.27009M12 14C10.6032 14 9.23106 13.6698 7.99218 13.0351C6.75329 12.4004 5.68145 11.4781 4.85766 10.341C4.03388 9.20389 3.48025 7.88226 3.24018 6.48005C3.00011 5.07783 3.07989 3.63592 3.47368 2.27009M20.5263 2.27009C19.7687 2.10061 18.9932 2.00847 18.2143 2.00006M20.5263 2.27009L18.2143 2.00006M18.2143 2.00006V5.00006M3.47368 2.27009C4.23128 2.10061 5.00682 2.00847 5.78571 2.00006M3.47368 2.27009L5.78571 2.00006M5.78571 2.00006V5.00006" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>Información Académica</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="course_id" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Área de Interés</label>
                        <select class="form-control text-uppercase" name="course_id" id="course_id" required>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="crew_id" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Plantel de Interés</label>
                        <select class="form-control text-uppercase" name="crew_id" id="crew_id" required>
                            @foreach($crews as $crew)
                                @if($crew->id > 1)
                                    <option value="{{ $crew->id }}" {{ old('crew_id') == $crew->id ? 'selected' : '' }}>{{ $crew->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="marketing_id" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">¿Cómo Conoció CEC?</label>
                        <select class="form-control text-uppercase" name="marketing_id" id="marketing_id" required>
                            @foreach($marketings as $marketing)
                                <option value="{{ $marketing->id }}" {{ old('marketing_id') == $marketing->id ? 'selected' : '' }}>{{ $marketing->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: center; gap: 12px; margin-top: 32px; margin-bottom: 60px;">
            <button type="submit" class="btn-modern btn-primary" style="min-width: 200px;" onclick="showLoader(true)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16L21 8V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 21V13H7V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 3V8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Guardar Informe
            </button>
            <a href="{{ route('system.reports.show') }}" class="btn-modern" style="min-width: 200px; background: white; color: #6b7280; border: 1px solid #e5e7eb;" onclick="showLoader(true)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Cancelar
            </a>
        </div>
    </form>
@endsection
