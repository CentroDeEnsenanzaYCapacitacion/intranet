@php
    $userCrewId = auth()->user()->crew_id ?? null;
@endphp

@extends('layout.mainLayout')
@section('title','Nuevo Empleado')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Nuevo Empleado</h1>
        <p class="dashboard-subtitle">Registro de nuevo personal al sistema</p>
    </div>

    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 21V19C16 17.9391 15.5786 16.9217 14.8284 16.1716C14.0783 15.4214 13.0609 15 12 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21M20 8V14M23 11H17M12.5 7C12.5 8.933 10.933 10.5 9 10.5C7.067 10.5 5.5 8.933 5.5 7C5.5 5.067 7.067 3.5 9 3.5C10.933 3.5 12.5 5.067 12.5 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Datos del Empleado</h2>
            </div>
        </div>

        <form action="{{ route('admin.staff.store') }}" method="POST">
            @csrf

            @if ($userCrewId == 1)
                <div class="modern-field">
                    <label for="crew_id">Plantel</label>
                    <select class="modern-input" name="crew_id" required>
                        <option value="" disabled selected>Selecciona un plantel</option>
                        @foreach ($crews as $crew)
                            <option value="{{ $crew->id }}">{{ $crew->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="modern-field">
                        <label for="name">Nombre *</label>
                        <input type="text" name="name" class="modern-input" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="modern-field">
                        <label for="surnames">Apellidos</label>
                        <input type="text" name="surnames" class="modern-input">
                    </div>
                </div>
            </div>

            <div class="modern-field">
                <label for="Address">Dirección</label>
                <input type="text" name="Address" class="modern-input">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="modern-field">
                        <label for="colony">Colonia</label>
                        <input type="text" name="colony" class="modern-input">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="modern-field">
                        <label for="municipalty">Municipio</label>
                        <input type="text" name="municipalty" class="modern-input">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="modern-field">
                        <label for="phone">Teléfono</label>
                        <input type="text" name="phone" class="modern-input">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="modern-field">
                        <label for="cel">Celular</label>
                        <input type="text" name="cel" class="modern-input">
                    </div>
                </div>
            </div>

            <div class="modern-field">
                <label for="rfc">RFC</label>
                <input type="text" name="rfc" class="modern-input">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="modern-field">
                        <label for="department">Departamento</label>
                        <input type="text" name="department" class="modern-input">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="modern-field">
                        <label for="position">Puesto</label>
                        <input type="text" name="position" class="modern-input">
                    </div>
                </div>
            </div>

            <div class="modern-field">
                <label for="personal_mail">Correo personal</label>
                <input type="email" name="personal_mail" class="modern-input">
            </div>

            <div class="modern-field">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="requiresMail" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                    <span>Requiere correo</span>
                </label>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="modern-field">
                        <label for="cost">Costo *</label>
                        <input type="number" name="cost" class="modern-input" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="modern-field">
                        <label for="cost_type">Tipo de costo *</label>
                        <select name="cost_type" class="modern-input" required>
                            <option value="day">Por día</option>
                            <option value="hour">Por hora</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn-modern btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 2.58579C3.96086 2.21071 4.46957 2 5 2H16L21 7V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17 21V13H7V21M7 3V7H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Guardar empleado
                </button>
                <a href="{{ route('admin.staff.show') }}" class="btn-modern btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
