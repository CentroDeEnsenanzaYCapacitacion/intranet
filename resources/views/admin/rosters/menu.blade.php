@extends('layout.mainLayout')
@section('title','Gestión de nóminas')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Gestión de Nóminas</h1>
        <p class="dashboard-subtitle">Administra personal y cálculos de nómina</p>
    </div>

    <div class="dashboard-grid">
        @if (in_array(Auth::user()->role_id, [1, 2]))
            <a id="adminStaff" href="{{route('admin.staff.show')}}" class="dashboard-card" onclick="showLoader(true)">
                <div class="card-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="9" cy="7" r="4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M23 21V19C22.9993 18.1137 22.7044 17.2528 22.1614 16.5523C21.6184 15.8519 20.8581 15.3516 20 15.13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 3.13C16.8604 3.35031 17.623 3.85071 18.1676 4.55232C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89318 18.7122 8.75608 18.1676 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Gestión de Personal</h3>
                    <p class="card-description">Administra empleados y plantilla</p>
                </div>
                <div class="card-arrow">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </a>

            <a id="adminRosters" href="{{route('admin.rosters.panel')}}" class="dashboard-card" onclick="showLoader(true)">
                <div class="card-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" y="3" width="20" height="18" rx="2" stroke="white" stroke-width="2"/>
                        <path d="M8 7H16" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 11H16" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 15H12" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Cálculo de Nómina</h3>
                    <p class="card-description">Procesa pagos y deducciones</p>
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
