@extends('layout.mainLayout')
@section('title','Cobranza')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Cobranza</h1>
        <p class="dashboard-subtitle">Gestión de cobros y pagos del sistema</p>
    </div>

    <div class="dashboard-grid">
        @if (in_array(Auth::user()->role_id, [1, 2, 3]))
            <a id='reports' href="{{route('system.collection.tuition')}}" class="dashboard-card" onclick="showLoader(true)">
                <div class="card-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 1V23" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17 5H9.5C8.57174 5 7.6815 5.36875 7.02513 6.02513C6.36875 6.6815 6 7.57174 6 8.5C6 9.42826 6.36875 10.3185 7.02513 10.9749C7.6815 11.6313 8.57174 12 9.5 12H14.5C15.4283 12 16.3185 12.3687 16.9749 13.0251C17.6313 13.6815 18 14.5717 18 15.5C18 16.4283 17.6313 17.3185 16.9749 17.9749C16.3185 18.6313 15.4283 19 14.5 19H6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Cobros</h3>
                    <p class="card-description">Registro de colegiaturas y pagos de estudiantes</p>
                </div>
                <div class="card-arrow">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </a>
        @endif

        @if (in_array(Auth::user()->role_id, [1, 2]))
            <a id='profiles' href="{{route('system.collection.paybills')}}" class="dashboard-card" onclick="showLoader(true)">
                <div class="card-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="1" y="4" width="22" height="16" rx="2" stroke="white" stroke-width="2"/>
                        <path d="M1 10H23" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M7 15H7.01" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M11 15H13" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Pagos</h3>
                    <p class="card-description">Control de egresos y gastos del sistema</p>
                </div>
                <div class="card-arrow">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </a>
        @endif
    </div>
@endsection
