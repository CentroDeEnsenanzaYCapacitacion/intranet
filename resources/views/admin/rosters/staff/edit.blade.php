@extends('layout.mainLayout')
@section('title','Editar Empleado')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Editar Empleado</h1>
        <p class="dashboard-subtitle">Actualización de datos del personal</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #ef4444; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18.5 2.50001C18.8978 2.10219 19.4374 1.87869 20 1.87869C20.5626 1.87869 21.1022 2.10219 21.5 2.50001C21.8978 2.89784 22.1213 3.4374 22.1213 4.00001C22.1213 4.56262 21.8978 5.10219 21.5 5.50001L12 15L8 16L9 12L18.5 2.50001Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Datos del Empleado</h2>
            </div>
        </div>

        <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST" class="staff-form" data-password-confirm>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="modern-field">
                        <label>Nombre *</label>
                        <input type="text" name="name" class="modern-input" value="{{ old('name', $staff->name) }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="modern-field">
                        <label>Apellidos</label>
                        <input type="text" name="surnames" class="modern-input" value="{{ old('surnames', $staff->surnames) }}" readonly>
                    </div>
                </div>
            </div>

            <div class="modern-field">
                <label>Dirección</label>
                <input type="text" name="Address" class="modern-input" value="{{ old('Address', $staff->Address) }}">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="modern-field">
                        <label>Colonia</label>
                        <input type="text" name="colony" class="modern-input" value="{{ old('colony', $staff->colony) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="modern-field">
                        <label>Municipio</label>
                        <input type="text" name="municipalty" class="modern-input" value="{{ old('municipalty', $staff->municipalty) }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="modern-field">
                        <label>Teléfono</label>
                        <input type="text" name="phone" class="modern-input" value="{{ old('phone', $staff->phone) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="modern-field">
                        <label>Celular</label>
                        <input type="text" name="cel" class="modern-input" value="{{ old('cel', $staff->cel) }}">
                    </div>
                </div>
            </div>

            <div class="modern-field">
                <label>RFC</label>
                <input type="text" name="rfc" class="modern-input" value="{{ old('rfc', $staff->rfc) }}">
            </div>

            <div class="modern-field">
                <label>Puesto</label>
                <input type="text" name="position" class="modern-input" value="{{ old('position', $staff->position) }}">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="modern-field">
                        <label>Correo personal</label>
                        <input type="email" name="personal_mail" class="modern-input" value="{{ old('personal_mail', $staff->personal_mail) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="modern-field">
                        <label>Correo CEC</label>
                        <input type="email" name="cec_mail" class="modern-input" value="{{ old('cec_mail', $staff->cec_mail) }}">
                    </div>
                </div>
            </div>

            <div class="departments-section" style="margin-top: 24px; padding: 20px; background: var(--bg-secondary, #f8f9fa); border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h3 style="margin: 0; font-size: 1.1rem;">Departamentos y Costos</h3>
                    <button type="button" id="add-department-btn" class="btn-modern btn-primary" style="padding: 8px 16px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Agregar
                    </button>
                </div>
                <div id="departments-container"></div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn-modern btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 2.58579C3.96086 2.21071 4.46957 2 5 2H16L21 7V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17 21V13H7V21M7 3V7H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Actualizar empleado
                </button>
                <a href="{{ route('admin.staff.show') }}" class="btn-modern btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        window.staffDepartmentsConfig = {
            departments: @json($departments),
            existingDepartments: @json($staff->departmentCosts)
        };
    </script>
    <script src="{{ asset('assets/js/staff-departments.js') }}"></script>

    @include('includes.password-confirm-modal')
@endsection
