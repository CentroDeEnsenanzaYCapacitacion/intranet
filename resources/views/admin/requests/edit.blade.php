@extends('layout.mainLayout')
@section('title','Solicitudes')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Editar Solicitud</h1>
        <p class="dashboard-subtitle">Gestión y modificación de solicitudes</p>
    </div>

    @if($request->request_type_id == 3)
        <div class="modern-card" style="margin-bottom: 24px;">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 2V6M15 2V6M3 10H21M5 4H19C20.1046 4 21 4.89543 21 6V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V6C3 4.89543 3.89543 4 5 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>{{ $request->requestType->name }}</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                <div class="modern-field">
                    <label><b>Estudiante:</b></label>
                    <div style="margin-top: 8px;">
                        <a href="{{ route('system.student.profile', ['student_id' => $request->student_id]) }}" style="color: #0369a1; text-decoration: none; font-weight: 500;">
                            {{ $request->student->surnames }}, {{ $request->student->name }}
                        </a>
                        <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Matrícula: {{ $request->student->crew->name[0] }}/{{ $request->student->id }}</div>
                    </div>
                </div>

                <div class="modern-field">
                    <label><b>Colegiatura actual:</b></label>
                    <div style="margin-top: 8px; font-weight: 500; color: #111827;">${{ number_format($request->student->tuition ?? 0, 2) }}</div>
                </div>

                @php
                    preg_match('/Nueva colegiatura: \$([\d,\.]+)/', $request->description, $matches);
                    $newTuition = isset($matches[1]) ? str_replace(',', '', $matches[1]) : '';
                    $reason = preg_replace('/Nueva colegiatura: \$[\d,\.]+ - /', '', $request->description);
                @endphp

                <form id="myForm" action="{{ route('admin.requests.changeTuition', ['request_id' => $request->id]) }}" method="POST">
                    @csrf
                    <div class="modern-field">
                        <label for="new_tuition"><b>Nueva colegiatura:</b></label>
                        <input type="number" class="modern-input" id="new_tuition" name="new_tuition" step="0.01" min="0.01" value="{{ $newTuition }}" required>
                    </div>

                    <div class="modern-field">
                        <label><b>Motivo:</b></label>
                        <div style="margin-top: 8px; color: #374151;">{{ $reason }}</div>
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: center; margin-top: 32px;">
                        <button onclick="showLoader(true)" class="btn-modern btn-primary" type="submit">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 13L9 17L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Guardar y aprobar</span>
                        </button>
                        <a href="{{ route('admin.requests.show') }}" class="btn-modern btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Cancelar</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="modern-card" style="margin-bottom: 24px;">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 16H12V12H11M12 8H12.01M21 12C21 13.1819 20.7672 14.3522 20.3149 15.4442C19.8626 16.5361 19.1997 17.5282 18.364 18.364C17.5282 19.1997 16.5361 19.8626 15.4442 20.3149C14.3522 20.7672 13.1819 21 12 21C10.8181 21 9.64778 20.7672 8.55585 20.3149C7.46392 19.8626 6.47177 19.1997 5.63604 18.364C4.80031 17.5282 4.13738 16.5361 3.68508 15.4442C3.23279 14.3522 3 13.1819 3 12C3 9.61305 3.94821 7.32387 5.63604 5.63604C7.32387 3.94821 9.61305 3 12 3C14.3869 3 16.6761 3.94821 18.364 5.63604C20.0518 7.32387 21 9.61305 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>{{ $request->requestType->name }}</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                <div class="modern-field">
                    <label><b>Descripción:</b></label>
                    <div style="margin-top: 8px; color: #374151;">{{ $request->description }}</div>
                </div>

                <div style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-left: 4px solid #3b82f6; border-radius: 12px; padding: 16px; margin-top: 24px;">
                    <p style="margin: 0; color: #1e3a8a; font-size: 14px;">Esta solicitud no requiere edición.</p>
                </div>

                <div style="display: flex; justify-content: center; margin-top: 32px;">
                    <a href="{{ route('admin.requests.show') }}" class="btn-modern btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 19L3 12M3 12L10 5M3 12H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Volver</span>
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection
