@extends('layout.mainLayout')
@section('title', 'dashboard')
@section('content')
    <div class="dashboard-entrance">
        <div class="dashboard-welcome">
        <h1 class="dashboard-title">Panel de Control</h1>
        <p class="dashboard-subtitle">Selecciona una opción para comenzar</p>
    </div>

    <div class="dashboard-grid">
        @if (in_array(Auth::user()->role_id, [1, 2, 6, 7]))
            <a href="{{ route('adminFunctions') }}" class="dashboard-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 15C15.866 15 19 11.866 19 8C19 4.13401 15.866 1 12 1C8.13401 1 5 4.13401 5 8C5 11.866 8.13401 15 12 15Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8.21 13.89L7 23L12 20L17 23L15.79 13.88" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Funciones de Administrador</h3>
                    <p class="card-description">Gestión y configuración del sistema</p>
                </div>
                <div class="card-arrow">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </a>
        @endif

        <a href="{{ route('system.main') }}" class="dashboard-card">
            <div class="card-icon" style="background: linear-gradient(135deg, #F57F17 0%, #F9A825 100%);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 22V12H15V22" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="card-content">
                <h3 class="card-title">Sistema de Gestión Escolar</h3>
                <p class="card-description">Estudiantes, calificaciones y reportes</p>
            </div>
            <div class="card-arrow">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </a>

        @if (in_array(Auth::user()->role_id, [1, 6]))
            <a href="{{ route('web.menu') }}" class="dashboard-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="white" stroke-width="2"/>
                        <path d="M2 12H22" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 2C14.5 4.5 16 8 16 12C16 16 14.5 19.5 12 22C9.5 19.5 8 16 8 12C8 8 9.5 4.5 12 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Gestión de Datos Web</h3>
                    <p class="card-description">Contenido y configuración del sitio</p>
                </div>
                <div class="card-arrow">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </a>
        @endif

        <a href="{{ route('tickets.list') }}" class="dashboard-card">
            <div class="card-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14 2V8H20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 18V12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 15H15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="card-content">
                <h3 class="card-title">Tickets de Servicio</h3>
                <p class="card-description">Soporte y solicitudes de ayuda</p>
            </div>
            <div class="card-arrow">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </a>
    </div>
    </div>
@endsection
