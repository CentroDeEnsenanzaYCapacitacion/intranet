@extends('layout.mainLayout')
@section('title', 'Calendarios')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Calendarios</h1>
        <p class="dashboard-subtitle">Horarios y planificación académica</p>
    </div>

    <div class="dashboard-grid">
        <a href="{{ route('admin.schedule.calendar') }}" class="dashboard-card" onclick="showLoader(true)">
            <div class="card-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="4" width="18" height="18" rx="2" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 2V6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 2V6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3 10H21" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="card-content">
                <h3 class="card-title">Asignación de horas</h3>
                <p class="card-description">Calendario de asignación de horas para trabajadores</p>
            </div>
            <div class="card-arrow">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('system.calendars.eub') }}" class="dashboard-card" onclick="showLoader(true)">
            <div class="card-icon" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="4" width="18" height="18" rx="2" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 2V6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 2V6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3 10H21" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="card-content">
                <h3 class="card-title">Apertura EUB</h3>
                <p class="card-description">Establece cuando se abre y cierra el examen único de bachillerato</p>
            </div>
            <div class="card-arrow">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </a>
    </div>
@endsection
