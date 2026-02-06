@extends('layout.mainLayout')
@section('title', 'Inscripción')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Inscripción de Nuevo Alumno</h1>
        <p class="dashboard-subtitle">Proceso de inscripción del prospecto</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #ef4444; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
            <ul class="mb-0" style="padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #ef4444; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="alert alert-danger" style="display: none; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #ef4444; border-radius: 12px; padding: 16px; margin-bottom: 24px;" id="error-container">
        <ul id="error-list" class="mb-0" style="padding-left: 20px;"></ul>
    </div>

    <form id="inscriptionForm" action="{{ route('system.report.receiptorrequest') }}" method="POST" data-password-confirm>
        @csrf
        <input type="hidden" value="{{ $report_id }}" name="report_id">
        <input type="hidden" value="{{ $report->course->name ?? '' }}" id="courseName">
        <input type="hidden" value="{{ $report->course_id ?? '' }}" id="courseId">

        @php
            $isBachilleratoExamen = stripos($report->course->name ?? '', 'BACHILLERATO EN UN EXAMEN') !== false;
        @endphp

        <div class="modern-card">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 14L9 11M12 14V21M12 14C13.3968 14 14.7689 13.6698 16.0078 13.0351C17.2467 12.4004 18.3185 11.4781 19.1423 10.341C19.9661 9.20389 20.5197 7.88226 20.7598 6.48005C20.9999 5.07783 20.9201 3.63592 20.5263 2.27009M12 14C10.6032 14 9.23106 13.6698 7.99218 13.0351C6.75329 12.4004 5.68145 11.4781 4.85766 10.341C4.03388 9.20389 3.48025 7.88226 3.24018 6.48005C3.00011 5.07783 3.07989 3.63592 3.47368 2.27009M20.5263 2.27009C19.7687 2.10061 18.9932 2.00847 18.2143 2.00006M20.5263 2.27009L18.2143 2.00006M18.2143 2.00006V5.00006M3.47368 2.27009C4.23128 2.10061 5.00682 2.00847 5.78571 2.00006M3.47368 2.27009L5.78571 2.00006M5.78571 2.00006V5.00006" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>Detalles de Inscripción</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                @if (!$isBachilleratoExamen)
                    <div class="row">
                        <div class="col-md-12">
                            <div style="text-align: center; max-width: 400px; margin: 0 auto;">
                                <div class="modern-field">
                                    <label for="amount" style="display: block; margin-bottom: 16px; font-size: 16px; font-weight: 600; color: #374151; text-align: center;">Importe de Inscripción</label>
                                    <div class="input-group" style="margin-bottom: 24px;">
                                        <span class="input-group-text" style="background: linear-gradient(135deg, #F57F17 0%, #F9A825 100%); color: white; font-weight: 600; border: none; font-size: 18px;">$</span>
                                        <input type="number" step="0.01" min="0" class="form-control" id="amount" name="amount" placeholder="0.00" required style="font-size: 18px; font-weight: 600; text-align: center;">
                                    </div>
                                </div>

                                <div class="form-check" style="display: flex; align-items: center; justify-content: center; padding: 12px 20px; background: #f9fafb; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                                    <input class="form-check-input" name="card_payment" type="checkbox" value="card" id="flexCheckDefault" style="cursor: pointer; margin: 0; margin-right: 8px;">
                                    <label class="form-check-label" for="flexCheckDefault" style="cursor: pointer; font-weight: 500; margin: 0;">
                                        Pago con Tarjeta
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row d-none" id="explanationContainer" style="margin-top: 24px;">
                        <div class="col-md-12">
                            <div style="text-align: center; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #ef4444; border-radius: 12px;">
                                <div class="modern-field">
                                    <label for="price_explanation" style="display: block; margin-bottom: 12px; font-size: 14px; font-weight: 600; color: #991b1b; text-align: center;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                                            <path d="M12 9V13M12 17H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        El importe es diferente al registrado. Por favor, explica la razón:
                                    </label>
                                    <textarea class="modern-textarea" id="price_explanation" name="price_explanation" rows="3" placeholder="Escribe aquí la razón de la diferencia de precio..." style="text-align: center;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="amount" value="0">
                    <div class="row">
                        <div class="col-md-12">
                            <div style="text-align: center; padding: 32px; background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%); border: 2px solid #0369a1; border-radius: 12px;">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 16px; color: #0369a1;">
                                    <path d="M13 16H12V12H11M12 8H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div style="font-size: 18px; font-weight: 600; color: #0c4a6e; margin-bottom: 8px;">{{ $report->course->name }}</div>
                                <div style="font-size: 14px; color: #0369a1;">Este curso no requiere importe de inscripción</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div style="display: flex; justify-content: center; gap: 12px; margin-top: 32px; margin-bottom: 60px;">
            <button type="submit" class="btn-modern btn-primary" style="min-width: 200px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 21V5C16 4.46957 15.7893 3.96086 15.4142 3.58579C15.0391 3.21071 14.5304 3 14 3H10C9.46957 3 8.96086 3.21071 8.58579 3.58579C8.21071 3.96086 8 4.46957 8 5V21M4 7H20M4 7C3.46957 7 2.96086 7.21071 2.58579 7.58579C2.21071 7.96086 2 8.46957 2 9V19C2 19.5304 2.21071 20.0391 2.58579 20.4142C2.96086 20.7893 3.46957 21 4 21H20C20.5304 21 21.0391 20.7893 21.4142 20.4142C21.7893 20.0391 22 19.5304 22 19V9C22 8.46957 21.7893 7.96086 21.4142 7.58579C21.0391 7.21071 20.5304 7 20 7M4 7V5C4 4.46957 4.21071 3.96086 4.58579 3.58579C4.96086 3.21071 5.46957 3 6 3H8M20 7V5C20 4.46957 19.7893 3.96086 19.4142 3.58579C19.0391 3.21071 18.5304 3 18 3H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Inscribir
            </button>
            <a href="{{ route('system.reports.show') }}" class="btn-modern" style="min-width: 200px; background: white; color: #6b7280; border: 1px solid #e5e7eb;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Cancelar
            </a>
        </div>
    </form>

    @include('includes.password-confirm-modal')
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/xss-protection.js') }}"></script>
    <script src="{{ asset('assets/js/report_redirection.js') }}"></script>
@endpush
