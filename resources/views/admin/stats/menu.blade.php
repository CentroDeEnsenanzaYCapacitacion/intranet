@extends('layout.mainLayout')
@section('title','Menú estadísticas')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Estadísticas</h1>
        <p class="dashboard-subtitle">Análisis y reportes del sistema</p>
    </div>

    <div class="dashboard-grid">
        @if (in_array(Auth::user()->role_id, [1, 2]))
            <a href="{{route('admin.stats.reports',['period'=>'mensual'])}}" class="dashboard-card" onclick="showLoader(true)">
                <div class="card-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 20V10" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 20V4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6 20V14" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Informes</h3>
                    <p class="card-description">Reportes estadísticos generales</p>
                </div>
                <div class="card-arrow">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </a>

            <a href="{{route('admin.stats.billing')}}" class="dashboard-card" onclick="showLoader(true)">
                <div class="card-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 1V23" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17 5H9.5C8.57174 5 7.6815 5.36875 7.02513 6.02513C6.36875 6.6815 6 7.57174 6 8.5C6 9.42826 6.36875 10.3185 7.02513 10.9749C7.6815 11.6313 8.57174 12 9.5 12H14.5C15.4283 12 16.3185 12.3687 16.9749 13.0251C17.6313 13.6815 18 14.5717 18 15.5C18 16.4283 17.6313 17.3185 16.9749 17.9749C16.3185 18.6313 15.4283 19 14.5 19H6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Cobranza</h3>
                    <p class="card-description">Estadísticas de pagos e ingresos</p>
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
