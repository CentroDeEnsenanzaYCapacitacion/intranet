@extends('layout.mainLayout')
@section('title', 'Completar Ficha de Estudiante')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/calendar-modal.css') }}">
@endpush
@section('content')

    @php
        $yearNow = date('Y');
        $years = [];
        for ($i = $yearNow - 5; $i <= $yearNow + 2; $i++) {
            $years[] = substr($i, -2);
        }
    @endphp

    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Completar Ficha de Estudiante</h1>
        <p class="dashboard-subtitle">{{ $student->surnames . ', ' . $student->name }} - {{ $student->crew->name[0] . '/' . $student->id }}</p>
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

    <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #ef4444; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #991b1b; flex-shrink: 0;">
                <path d="M12 9V13M12 17H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div>
                <div style="font-size: 16px; font-weight: 600; color: #991b1b; margin-bottom: 4px;">Estudiante Nuevo</div>
                <div style="font-size: 14px; color: #991b1b;">Por favor completa todos los campos requeridos para activar el perfil</div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('system.student.update') }}">
        @csrf
        <input type="hidden" name="operation" value="new" />
        <input type="hidden" name="crew_id" value="{{ $student->crew_id }}" />
        <input type="hidden" name="student_id" value="{{ $student->id }}" />
        <input type="hidden" name="name" value="{{ $student->name }}" />
        <input type="hidden" name="surnames" value="{{ $student->surnames }}" />
        <input type="hidden" name="course_id" value="{{ $student->course_id }}" />
        <input type="hidden" name="genre" value="{{ $student->genre }}" />

        <div class="row">
            <div class="col-lg-4">
                <div class="modern-card" style="text-align: center;">
                    <div style="padding: 24px;">
                        <div style="width: 180px; height: 180px; margin: 0 auto 20px; border-radius: 50%; overflow: hidden; border: 4px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                            <img src="{{ route('system.student.image', ['student_id' => $student->id]) }}" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <button type="button" class="btn-modern btn-primary" style="width: 100%;" onclick="saveFormDataAndRedirect()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M23 19C23 19.5304 22.7893 20.0391 22.4142 20.4142C22.0391 20.7893 21.5304 21 21 21H3C2.46957 21 1.96086 20.7893 1.58579 20.4142C1.21071 20.0391 1 19.5304 1 19V8C1 7.46957 1.21071 6.96086 1.58579 6.58579C1.96086 6.21071 2.46957 6 3 6H7L9 3H15L17 6H21C21.5304 6 22.0391 6.21071 22.4142 6.58579C22.7893 6.96086 23 7.46957 23 8V19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 17C14.2091 17 16 15.2091 16 13C16 10.7909 14.2091 9 12 9C9.79086 9 8 10.7909 8 13C8 15.2091 9.79086 17 12 17Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Cambiar Fotografía
                        </button>
                    </div>
                </div>

                <div class="modern-card" style="margin-top: 24px;">
                    <div class="card-header-modern">
                        <div class="header-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 14L9 11M12 14V21M12 14C13.3968 14 14.7689 13.6698 16.0078 13.0351C17.2467 12.4004 18.3185 11.4781 19.1423 10.341C19.9661 9.20389 20.5197 7.88226 20.7598 6.48005C20.9999 5.07783 20.9201 3.63592 20.5263 2.27009M12 14C10.6032 14 9.23106 13.6698 7.99218 13.0351C6.75329 12.4004 5.68145 11.4781 4.85766 10.341C4.03388 9.20389 3.48025 7.88226 3.24018 6.48005C3.00011 5.07783 3.07989 3.63592 3.47368 2.27009M20.5263 2.27009C19.7687 2.10061 18.9932 2.00847 18.2143 2.00006M20.5263 2.27009L18.2143 2.00006M18.2143 2.00006V5.00006M3.47368 2.27009C4.23128 2.10061 5.00682 2.00847 5.78571 2.00006M3.47368 2.27009L5.78571 2.00006M5.78571 2.00006V5.00006" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <h2>Info Académica</h2>
                        </div>
                    </div>

                    <div style="padding: 24px;">
                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">MATRÍCULA</label>
                            <div style="font-size: 16px; font-weight: 600; color: #111827;">{{ $student->crew->name[0] . '/' . $student->id }}</div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">CURSO</label>
                            <div class="text-uppercase" style="font-size: 14px; color: #374151;">{{ $student->course->name }}</div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">GENERACIÓN</label>
                            <div style="display: flex; gap: 8px;">
                                <select name="gen_month" class="form-control" style="flex: 1;">
                                    <option value="F" {{ old('gen_month') == 'F' ? 'selected' : '' }}>Febrero</option>
                                    <option value="M" {{ old('gen_month') == 'M' ? 'selected' : '' }}>Mayo</option>
                                    <option value="A" {{ old('gen_month') == 'A' ? 'selected' : '' }}>Agosto</option>
                                    <option value="N" {{ old('gen_month') == 'N' ? 'selected' : '' }}>Noviembre</option>
                                </select>
                                <select name="gen_year" class="form-control" style="flex: 1;">
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}" {{ old('gen_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label for="payment_periodicity_id" style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">PERIODICIDAD DE PAGO</label>
                            <select class="modern-input text-uppercase" name="payment_periodicity_id" id="payment_periodicity_id">
                                @foreach ($payment_periodicities as $payment_periodicity)
                                    <option value="{{ $payment_periodicity->id }}" {{ old('payment_periodicity_id') == $payment_periodicity->id ? 'selected' : '' }}>
                                        {{ $payment_periodicity->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label for="schedule_id" style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">HORARIO</label>
                            <select class="modern-input text-uppercase" name="schedule_id" id="schedule_id">
                                @foreach ($schedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">MODALIDAD</label>
                            <div class="btn-group" role="group" style="width: 100%;">
                                <input type="radio" value="false" class="btn-check" name="sabbatine" id="sabf" autocomplete="off" {{ old('sabbatine', 'false') == 'false' ? 'checked' : '' }}>
                                <label class="btn btn-outline-orange text-uppercase" for="sabf" style="flex: 1;">Intersemanal</label>

                                <input type="radio" value="true" class="btn-check" name="sabbatine" id="sabt" autocomplete="off" {{ old('sabbatine') == 'true' ? 'checked' : '' }}>
                                <label class="btn btn-outline-orange text-uppercase" for="sabt" style="flex: 1;">Sabatino</label>
                            </div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label for="modality_id" style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">TIPO</label>
                            <select class="modern-input text-uppercase" name="modality_id" id="modality_id">
                                @foreach ($modalities as $modality)
                                    <option value="{{ $modality->id }}" {{ old('modality_id') == $modality->id ? 'selected' : '' }}>
                                        {{ $modality->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label for="start" style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">INICIO</label>
                            <input placeholder="Selecciona fecha..." type="text" id="datePicker" name="start" class="modern-input" value="{{ old('start', $savedData['start'] ?? '') }}">
                        </div>

                        <div style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border: 2px solid #F57F17; border-radius: 12px; padding: 16px;">
                            <label for="tuition" style="display: block; font-size: 12px; font-weight: 600; color: #F57F17; margin-bottom: 4px; text-align: center;">COLEGIATURA *</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: linear-gradient(135deg, #F57F17 0%, #F9A825 100%); color: white; font-weight: 600; border: none; font-size: 18px;">$</span>
                                <input class="form-control" type="number" step="0.01" min="0.01" name="tuition" value="{{ old('tuition', $savedData['tuition'] ?? '') }}" placeholder="0.00" required style="font-size: 16px; font-weight: 600; text-align: center;">
                            </div>
                            @if ($errors->has('tuition'))
                                <div style="color: #991b1b; font-size: 12px; margin-top: 4px; text-align: center;">{{ $errors->first('tuition') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="modern-card">
                    <div class="card-header-modern">
                        <div class="header-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <h2>Información General</h2>
                        </div>
                    </div>

                    <div style="padding: 24px;">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">NOMBRE COMPLETO</label>
                                <div class="text-uppercase" style="font-size: 16px; font-weight: 600; color: #111827;">{{ $student->surnames . ', ' . $student->name }}</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="modern-field">
                                    <label for="birthdate">FECHA DE NACIMIENTO *</label>
                                    <input placeholder="Selecciona fecha..." type="text" id="datePicker" name="birthdate" class="modern-input" value="{{ old('birthdate', $savedData['birthdate'] ?? '') }}">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">GÉNERO</label>
                                <div class="text-uppercase" style="font-size: 14px; color: #374151; padding: 8px 0;">
                                    @if ($student->genre == 'M')
                                        Mujer
                                    @elseif($student->genre == 'H')
                                        Hombre
                                    @else
                                        No Binario
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="modern-field">
                                    <label for="curp">CURP</label>
                                    <input class="modern-input text-uppercase" name="curp" type="text" value="{{ old('curp', $savedData['curp'] ?? '') }}" />
                                </div>
                            </div>

                            <div class="col-md-9 mb-3">
                                <div class="modern-field">
                                    <label for="address">DIRECCIÓN</label>
                                    <input class="modern-input text-uppercase" type="text" name="address" value="{{ old('address', $savedData['address'] ?? '') }}" />
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="modern-field">
                                    <label for="pc">C.P.</label>
                                    <input class="modern-input text-uppercase" type="text" name="pc" value="{{ old('pc', $savedData['pc'] ?? '') }}" />
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="modern-field">
                                    <label for="colony">COLONIA</label>
                                    <input class="modern-input text-uppercase" type="text" name="colony" value="{{ old('colony', $savedData['colony'] ?? '') }}" />
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="modern-field">
                                    <label for="municipality">MUNICIPIO</label>
                                    <input class="modern-input text-uppercase" type="text" name="municipality" value="{{ old('municipality', $savedData['municipality'] ?? '') }}" />
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="modern-field">
                                    <label for="phone">TELÉFONO</label>
                                    <input class="modern-input text-uppercase" type="text" name="phone" value="{{ old('phone', $savedData['phone'] ?? ($student->phone ?? '')) }}" />
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="modern-field">
                                    <label for="cel_phone">CELULAR</label>
                                    <input class="modern-input text-uppercase" type="text" name="cel_phone" value="{{ old('cel_phone', $savedData['cel_phone'] ?? ($student->cel_phone ?? '')) }}" />
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="modern-field">
                                    <label for="email">EMAIL</label>
                                    <input class="modern-input" type="text" name="email" value="{{ old('email', $savedData['email'] ?? $student->email) }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modern-card" style="margin-top: 24px;">
                    <div class="card-header-modern">
                        <div class="header-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21M23 21V19C22.9993 18.1137 22.7044 17.2528 22.1614 16.5523C21.6184 15.8519 20.8581 15.3516 20 15.13M16 3.13C16.8604 3.35031 17.623 3.85071 18.1676 4.55232C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89318 18.7122 8.75608 18.1676 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88M13 7C13 9.20914 11.2091 11 9 11C6.79086 11 5 9.20914 5 7C5 4.79086 6.79086 3 9 3C11.2091 3 13 4.79086 13 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <h2>Información de Tutor</h2>
                        </div>
                    </div>

                    <div style="padding: 24px;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="modern-field">
                                    <label for="tutor_name">NOMBRE</label>
                                    <input class="modern-input text-uppercase" type="text" name="tutor_name" value="{{ old('tutor_name', $savedData['tutor_name'] ?? '') }}" />
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="modern-field">
                                    <label for="tutor_surnames">APELLIDOS</label>
                                    <input class="modern-input text-uppercase" type="text" name="tutor_surnames" value="{{ old('tutor_surnames', $savedData['tutor_surnames'] ?? '') }}" />
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="modern-field">
                                    <label for="tutor_phone">TELÉFONO</label>
                                    <input class="modern-input text-uppercase" type="text" name="tutor_phone" value="{{ old('tutor_phone', $savedData['tutor_phone'] ?? '') }}" />
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="modern-field">
                                    <label for="tutor_cel_phone">CELULAR</label>
                                    <input class="modern-input text-uppercase" type="text" name="tutor_cel_phone" value="{{ old('tutor_cel_phone', $savedData['tutor_cel_phone'] ?? '') }}" />
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="modern-field">
                                    <label for="relationship">PARENTESCO</label>
                                    <input class="modern-input text-uppercase" type="text" name="relationship" value="{{ old('relationship', $savedData['relationship'] ?? '') }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modern-card" style="margin-top: 24px;">
                    <div class="card-header-modern">
                        <div class="header-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <h2>Documentación</h2>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Documento</th>
                                    <th style="width: 80px; text-align: center;">Estado</th>
                                    <th style="width: 120px; text-align: center;">Vista Previa</th>
                                    <th style="width: 140px; text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($student->documents as $document)
                                    <tr>
                                        <td class="text-uppercase">{{ $document->name }}</td>
                                        <td style="text-align: center;">
                                            @if ($document->pivot->uploaded)
                                                <span class="badge badge-success">Subido</span>
                                            @else
                                                <span class="badge badge-danger">Pendiente</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @if ($document->pivot->uploaded)
                                                @php
                                                    $directory = 'profiles/' . $student->id;
                                                    $baseFileName = str_replace(' ', '_', strtolower($document->name));
                                                    $extensions = ['jpeg', 'png', 'jpg', 'gif', 'svg', 'webp', 'pdf'];
                                                    $isPdf = false;
                                                    foreach ($extensions as $ext) {
                                                        $possiblePath = $directory . '/' . $baseFileName . '.' . $ext;
                                                        if (Storage::disk('local')->exists($possiblePath)) {
                                                            if ($ext === 'pdf') {
                                                                $isPdf = true;
                                                            }
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                <a href="{{ route('system.student.document', ['student_id' => $student->id, 'document_id' => $document->id]) }}" target="_blank">
                                                    @if ($isPdf)
                                                        <i class="fas fa-file-pdf" style="font-size: 32px; color: #dc3545;"></i>
                                                    @else
                                                        <img src="{{ route('system.student.document', ['student_id' => $student->id, 'document_id' => $document->id]) }}" alt="{{ $document->name }}" style="max-width: 60px; max-height: 60px; border: 1px solid #ddd; border-radius: 4px;">
                                                    @endif
                                                </a>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @if (!$document->pivot->uploaded)
                                                <button type="button" class="btn-modern" style="background: white; color: #F57F17; border: 1px solid #F57F17; padding: 8px 16px; font-size: 13px;" onclick="openDocumentModal({{ $document->id }}, '{{ $document->name }}')">
                                                    Añadir
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modern-card" style="margin-top: 24px;">
                    <div class="card-header-modern">
                        <div class="header-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 5H6C5.46957 5 4.96086 5.21071 4.58579 5.58579C4.21071 5.96086 4 6.46957 4 7V18C4 18.5304 4.21071 19.0391 4.58579 19.4142C4.96086 19.7893 5.46957 20 6 20H17C17.5304 20 18.0391 19.7893 18.4142 19.4142C18.7893 19.0391 19 18.5304 19 18V13M17.5858 3.58579C17.851 3.32057 18.1054 3.10536 18.2929 3.29289L20.7071 5.70711C20.8946 5.89464 20.6794 6.149 20.4142 6.41421L11 15.8284L8 16L8.17157 13L17.5858 3.58579Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <h2>Observaciones</h2>
                        </div>
                    </div>

                    <div style="padding: 24px;">
                        @if(count($student->observations) > 0)
                            <div style="margin-bottom: 24px;">
                                @foreach ($student->observations as $observation)
                                    <div style="display: flex; margin-bottom: 16px; padding: 16px; background: #f9fafb; border-left: 4px solid #F57F17; border-radius: 8px;">
                                        <div style="min-width: 90px; font-weight: 600; color: #F57F17; font-size: 13px;">
                                            {{ $observation->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-uppercase" style="flex: 1; margin-left: 16px; color: #374151; font-size: 14px; text-align: justify;">
                                            {{ $observation->description }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="text-align: center; padding: 40px 20px; color: #6b7280;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 12px; opacity: 0.3;">
                                    <path d="M11 5H6C5.46957 5 4.96086 5.21071 4.58579 5.58579C4.21071 5.96086 4 6.46957 4 7V18C4 18.5304 4.21071 19.0391 4.58579 19.4142C4.96086 19.7893 5.46957 20 6 20H17C17.5304 20 18.0391 19.7893 18.4142 19.4142C18.7893 19.0391 19 18.5304 19 18V13M17.5858 3.58579C17.851 3.32057 18.1054 3.10536 18.2929 3.29289L20.7071 5.70711C20.8946 5.89464 20.6794 6.149 20.4142 6.41421L11 15.8284L8 16L8.17157 13L17.5858 3.58579Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div style="font-size: 14px; font-weight: 500;">No hay observaciones registradas</div>
                            </div>
                        @endif

                        <div class="modern-field">
                            <label for="observation">NUEVA OBSERVACIÓN</label>
                            <textarea class="modern-textarea text-uppercase" name="observation" id="observation" rows="3" placeholder="Escribe una nueva observación..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: center; margin-top: 32px; margin-bottom: 60px;">
            <button class="btn-modern btn-primary" type="submit" style="min-width: 250px; padding: 14px;" onclick="if(this.form.checkValidity()) showLoader(true)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16L21 8V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 21V13H7V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 3V8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Guardar Información
            </button>
        </div>
    </form>

    <div class="custom-modal" id="documentModal" style="display:none;" onclick="closeOnOverlay(event, 'documentModal')">
        <div class="custom-modal-content">
            <span class="custom-modal-close" onclick="closeModal('documentModal')">&times;</span>
            <h5 id="documentModalTitle">Añadir documento</h5>
            <form method="POST" action="{{ route('system.student.upload-document') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <input type="hidden" name="document_id" id="document_id">

                <div class="mb-3">
                    <label for="document_file" class="form-label"><b>Selecciona el archivo:</b></label>
                    <input type="file" class="form-control" id="document_file" name="document_file" accept="image/*,.pdf" required>
                    <small class="form-text text-muted">
                        Formatos permitidos: Imágenes (JPG, PNG, etc.) y PDF
                    </small>
                </div>

                <div class="mt-4 text-end">
                    <button type="button" class="btn bg-orange text-white me-2" onclick="closeModal('documentModal')">Cancelar</button>
                    <button type="submit" class="btn bg-orange text-white">Subir documento</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    window.saveFormDataUrl = '{{ route('system.student.save-form-data', ['student_id' => $student->id]) }}';
    window.profileImageUrl = '{{ route('system.student.profile-image', ['student_id' => $student->id]) }}';
</script>
<script src="{{ asset('assets/js/student_form_save.js') }}"></script>
<script src="{{ asset('assets/js/modal_utils.js') }}"></script>
@endpush
