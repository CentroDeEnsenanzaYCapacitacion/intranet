@extends('layout.mainLayout')
@section('title','Gestión Web')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Gestión de Datos Web</h1>
        <p class="dashboard-subtitle">Administra el contenido del sitio web</p>
    </div>

    <div class="dashboard-grid">
        @if (in_array(Auth::user()->role_id, [1, 6]))
            <a id='reports' href="{{route('web.carousel')}}" class="dashboard-card" onclick="showLoader(true)">
                <div class="card-icon" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" y="7" width="20" height="15" rx="2" stroke="white" stroke-width="2"/>
                        <path d="M16 2V6" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 2V6" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="8" cy="14" r="2" fill="white"/>
                        <path d="M15 17L12 14L8 18" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Carrusel Inicial</h3>
                    <p class="card-description">Gestiona las imágenes del slider principal</p>
                </div>
                <div class="card-arrow">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </a>

            <a id='profiles' href="{{route('web.mvv')}}" class="dashboard-card" onclick="showLoader(true)">
                <div class="card-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="white" stroke-width="2"/>
                        <path d="M12 16V12" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 8H12.01" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Misión, Visión y Valores</h3>
                    <p class="card-description">Edita la información institucional</p>
                </div>
                <div class="card-arrow">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </a>

            <a id='opinions' href="{{route('web.opinions')}}" class="dashboard-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 15C21 16.6569 19.6569 18 18 18H8L3 21V6C3 4.34315 4.34315 3 6 3H18C19.6569 3 21 4.34315 21 6V15Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 9H16" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 13H14" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Opiniones</h3>
                    <p class="card-description">Publica opiniones de alumnos egresados</p>
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
