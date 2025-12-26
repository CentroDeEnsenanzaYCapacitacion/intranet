@extends('layout.mainLayout')
@section('title', 'Calificaciones')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Calificaciones</h1>
        <p class="dashboard-subtitle">Gestión de calificaciones y evaluaciones</p>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card" style="opacity: 0.6; pointer-events: none;">
            <div class="card-icon" style="background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 20V10" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 20V4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6 20V14" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="card-content">
                <h3 class="card-title">Próximamente</h3>
                <p class="card-description">Funcionalidades en desarrollo</p>
            </div>
            <div class="card-arrow">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 8L8 12M8 8L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
    </div>
@endsection