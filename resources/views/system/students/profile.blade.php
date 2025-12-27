@extends('layout.mainLayout')
@section('title', 'Ficha de Estudiante')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/calendar-modal.css') }}">
@endpush
@section('content')

    @php
        use Carbon\Carbon;

        $fechaNacimiento = Carbon::createFromFormat('d/m/Y', $student->birthdate);
        $fechaActual = Carbon::now();
        $edad = $fechaActual->diffInYears($fechaNacimiento);
    @endphp

    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Ficha de Estudiante</h1>
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

    @if (session('success'))
        <div id="success" class="alert alert-success" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-left: 4px solid #10b981; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #ef4444; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('system.student.update') }}">
        @csrf
        <input type="hidden" name="operation" value="update" />
        <input type="hidden" name="student_id" value="{{ $student->id }}" />
        <input type="hidden" name="curp" value="{{ $student->curp }}" />
        <input type="hidden" name="birthdate" value="{{ $student->birthdate }}" />
        <input type="hidden" name="genre" value="{{ $student->genre }}" />
        <input type="hidden" name="course_id" value="{{ $student->course_id }}" />
        <input type="hidden" name="payment_periodicity_id" value="{{ $student->payment_periodicity_id }}" />
        <input type="hidden" name="start" value="{{ $student->start }}" />
        <input type="hidden" name="crew_id" value="{{ $student->crew_id }}" />
        <input type="hidden" name="name" value="{{ $student->name }}" />
        <input type="hidden" name="surnames" value="{{ $student->surnames }}" />

        <div class="row">
            <div class="col-lg-4">
                <div class="modern-card" style="text-align: center;">
                    <div style="padding: 24px;">
                        <div style="width: 180px; height: 180px; margin: 0 auto 20px; border-radius: 50%; overflow: hidden; border: 4px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                            <img src="{{ route('system.student.image', ['student_id' => $student->id]) }}" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <a href="{{ route('system.student.profile-image', ['student_id' => $student->id]) }}" class="btn-modern btn-primary" style="width: 100%;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M23 19C23 19.5304 22.7893 20.0391 22.4142 20.4142C22.0391 20.7893 21.5304 21 21 21H3C2.46957 21 1.96086 20.7893 1.58579 20.4142C1.21071 20.0391 1 19.5304 1 19V8C1 7.46957 1.21071 6.96086 1.58579 6.58579C1.96086 6.21071 2.46957 6 3 6H7L9 3H15L17 6H21C21.5304 6 22.0391 6.21071 22.4142 6.58579C22.7893 6.96086 23 7.46957 23 8V19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 17C14.2091 17 16 15.2091 16 13C16 10.7909 14.2091 9 12 9C9.79086 9 8 10.7909 8 13C8 15.2091 9.79086 17 12 17Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Cambiar Fotografía
                        </a>
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
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">TIPO PAGO</label>
                            <div class="text-uppercase" style="font-size: 14px; color: #374151;">{{ $student->paymentPeriodicity->name }}</div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">INICIO</label>
                            <div style="font-size: 14px; color: #374151;">{{ $student->start }}</div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label for="schedule_id" style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">HORARIO</label>
                            <select class="modern-input text-uppercase" name="schedule_id" id="schedule_id">
                                @foreach ($schedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('schedule_id', $student->schedule_id) == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">MODALIDAD</label>
                            <div class="btn-group" role="group" style="width: 100%;">
                                <input type="radio" value="false" class="btn-check" name="sabbatine" id="sabf" autocomplete="off" {{ old('sabbatine', $student->sabbatine == 0 ? 'false' : '') == 'false' ? 'checked' : '' }}>
                                <label class="btn btn-outline-orange text-uppercase" for="sabf" style="flex: 1;">Intersemanal</label>

                                <input type="radio" value="true" class="btn-check" name="sabbatine" id="sabt" autocomplete="off" {{ old('sabbatine', $student->sabbatine == 1 ? 'true' : '') == 'true' ? 'checked' : '' }}>
                                <label class="btn btn-outline-orange text-uppercase" for="sabt" style="flex: 1;">Sabatino</label>
                            </div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label for="modality_id" style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">TIPO</label>
                            <select class="modern-input text-uppercase" name="modality_id" id="modality_id">
                                @foreach ($modalities as $modality)
                                    <option value="{{ $modality->id }}" {{ old('modality_id', $student->modality_id) == $modality->id ? 'selected' : '' }}>
                                        {{ $modality->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="background: linear-gradient(135deg, #d1fae5 0%, #ecfdf5 100%); border: 2px solid #065f46; border-radius: 12px; padding: 16px; text-align: center;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #065f46; margin-bottom: 4px;">COLEGIATURA</label>
                            <div style="font-size: 28px; font-weight: 700; color: #065f46;">${{ number_format($student->tuition ?? 0, 2) }}</div>
                            <button type="button" class="btn-modern" style="margin-top: 12px; background: white; color: #065f46; border: 1px solid #065f46; padding: 8px 16px; font-size: 13px;" onclick="openModal('tuitionChangeModal')">
                                Solicitar cambio
                            </button>
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
                                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">FECHA DE NACIMIENTO</label>
                                <div style="font-size: 14px; color: #374151;">{{ $student->birthdate }}</div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">EDAD</label>
                                <div style="font-size: 14px; color: #374151;">{{ $age }} años</div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">GÉNERO</label>
                                <div class="text-uppercase" style="font-size: 14px; color: #374151;">
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
                                <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">CURP</label>
                                <div class="text-uppercase" style="font-size: 14px; color: #374151;">{{ $student->curp }}</div>
                            </div>

                            <div class="col-md-9 mb-3">
                                <div class="modern-field">
                                    <label for="address">DIRECCIÓN</label>
                                    <input class="modern-input text-uppercase" name="address" type="text" value="{{ old('address', $student->address) }}" />
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="modern-field">
                                    <label for="pc">C.P.</label>
                                    <input class="modern-input text-uppercase" name="pc" type="text" value="{{ old('pc', $student->PC) }}" />
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="modern-field">
                                    <label for="colony">COLONIA</label>
                                    <input class="modern-input text-uppercase" name="colony" type="text" value="{{ old('colony', $student->colony) }}" />
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="modern-field">
                                    <label for="municipality">MUNICIPIO</label>
                                    <input class="modern-input text-uppercase" name="municipality" type="text" value="{{ old('municipality', $student->municipality) }}" />
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="modern-field">
                                    <label for="phone">TELÉFONO</label>
                                    <input class="modern-input text-uppercase" name="phone" type="text" value="{{ old('phone', $student->phone) }}" />
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="modern-field">
                                    <label for="cel_phone">CELULAR</label>
                                    <input class="modern-input text-uppercase" name="cel_phone" type="text" value="{{ old('cel_phone', $student->cel_phone) }}" />
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="modern-field">
                                    <label for="email">EMAIL</label>
                                    <input class="modern-input" name="email" type="text" value="{{ old('email', $student->email) }}" />
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
                                    <input class="modern-input text-uppercase" name="tutor_name" type="text" value="{{ old('tutor_name', $student->tutor->tutor_name) }}" />
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="modern-field">
                                    <label for="tutor_surnames">APELLIDOS</label>
                                    <input class="modern-input text-uppercase" name="tutor_surnames" type="text" value="{{ old('tutor_surnames', $student->tutor->tutor_surnames) }}" />
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="modern-field">
                                    <label for="tutor_phone">TELÉFONO</label>
                                    <input class="modern-input text-uppercase" name="tutor_phone" type="text" value="{{ old('tutor_phone', $student->tutor->tutor_phone) }}" />
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="modern-field">
                                    <label for="tutor_cel_phone">CELULAR</label>
                                    <input class="modern-input text-uppercase" name="tutor_cel_phone" type="text" value="{{ old('tutor_cel_phone', $student->tutor->tutor_cel_phone) }}" />
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="modern-field">
                                    <label for="relationship">PARENTESCO</label>
                                    <input class="modern-input text-uppercase" name="relationship" type="text" value="{{ old('tutor_relationship', $student->tutor->relationship) }}" />
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
            <button class="btn-modern btn-primary" type="submit" style="min-width: 250px; padding: 14px;" onclick="showLoader(true)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16L21 8V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 21V13H7V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 3V8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Actualizar Datos
            </button>
        </div>
    </form>

    <div class="custom-modal" id="tuitionChangeModal" style="display:none;" onclick="closeOnOverlay(event, 'tuitionChangeModal')">
        <div class="custom-modal-content">
            <span class="custom-modal-close" onclick="closeModal('tuitionChangeModal')">&times;</span>
            <h5>Solicitar cambio de colegiatura</h5>
            <form method="POST" action="{{ route('system.student.request-tuition-change') }}">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <div class="mb-3">
                    <label class="form-label"><b>Colegiatura actual:</b></label>
                    <p>${{ number_format($student->tuition ?? 0, 2) }}</p>
                </div>
                <div class="mb-3">
                    <label for="new_tuition" class="form-label"><b>Nueva colegiatura:</b></label>
                    <input type="number" class="form-control" id="new_tuition" name="new_tuition" step="0.01" min="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="reason" class="form-label"><b>Motivo del cambio:</b></label>
                    <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                </div>
                <div class="mt-4 text-end">
                    <button type="button" class="btn btn-secondary me-2" onclick="closeModal('tuitionChangeModal')">Cancelar</button>
                    <button type="submit" class="btn bg-orange text-white">Enviar solicitud</button>
                </div>
            </form>
        </div>
    </div>

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
                    <button type="button" class="btn btn-secondary me-2" onclick="closeModal('documentModal')">Cancelar</button>
                    <button type="submit" class="btn bg-orange text-white">Subir documento</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/modal_utils.js') }}"></script>
@endpush
