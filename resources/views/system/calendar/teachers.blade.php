@php
    $userCrewId = auth()->user()->crew_id ?? null;
@endphp

@extends('layout.mainLayout')
@section('title', 'Calendario')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/calendar-modal.css') }}">
    @endpush

    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Asignación de Horas</h1>
        <p class="dashboard-subtitle">Calendario de asignación de horas para trabajadores</p>
    </div>

    @if(session('success'))
        <div id="success" class="alert alert-success" style="background: #d1fae5; border: 1px solid #065f46; color: #065f46; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="error" class="alert alert-danger" style="background: #fee2e2; border: 1px solid #991b1b; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            {{ session('error') }}
        </div>
    @endif

    @if ($userCrewId == 1)
        <div class="modern-card" style="margin-bottom: 24px;">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 21V5C19 3.89543 18.1046 3 17 3H7C5.89543 3 5 3.89543 5 5V21M19 21H21M19 21H14M5 21H3M5 21H10M10 21V17C10 16.4477 10.4477 16 11 16H13C13.5523 16 14 16.4477 14 17V21M10 21H14M9 8H10M9 12H10M14 8H15M14 12H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>Filtro de Plantel</h2>
                </div>
            </div>
            <div style="padding: 24px;">
                <div class="row">
                    <div class="col-md-6">
                        <label for="crewSelect" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">Seleccionar Plantel</label>
                        <select id="crewSelect" class="form-control" style="height: 48px; border: 2px solid #e5e7eb; border-radius: 12px;">
                            @foreach ($crews->where('id', '!=', 1) as $crew)
                                <option value="{{ $crew->id }}">{{ $crew->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 2V6M8 2V6M3 10H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Calendario</h2>
            </div>
        </div>
        <div style="padding: 24px;">
            <div id="calendar"></div>
        </div>
    </div>

    <div class="custom-modal" id="assignModal" onclick="closeOnOverlay(event, 'assignModal')">
        <div class="custom-modal-content">
            <span class="custom-modal-close" onclick="closeModal('assignModal')">&times;</span>

            <h5>Asignar horas</h5>
            <form id="assignForm" data-no-loader="true">
                @csrf

                <div class="mb-3">
                    <label for="staffSelect" class="form-label">Trabajador</label>
                    <select id="staffSelect" class="form-select" required>
                        <option value="" disabled selected>Selecciona un trabajador</option>
                        @foreach ($staff as $person)
                            <option value="{{ $person->id }}" data-departments="{{ $person->departmentCosts->pluck('department_id')->toJson() }}">{{ $person->name }} {{ $person->surnames }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="departmentSelect" class="form-label">Departamento</label>
                    <select id="departmentSelect" class="form-select" required disabled>
                        <option value="" disabled selected>Primero selecciona un trabajador</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subjectSelect" class="form-label">Materia</label>
                    <select id="subjectSelect" class="form-select" required disabled>
                        <option value="" disabled selected>Primero selecciona un departamento</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Horario</label>
                    <div class="d-flex gap-2">
                        <select id="startTime" class="form-select" required>
                            <option value="" disabled selected>Inicio</option>
                            @for ($hour = 7; $hour <= 20; $hour++)
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00
                                </option>
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30
                                </option>
                            @endfor
                        </select>

                        <select id="endTime" class="form-select" required>
                            <option value="" disabled selected>Fin</option>
                            @for ($hour = 7; $hour <= 21; $hour++)
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00
                                </option>
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <input type="hidden" id="selectedDate">

                <div class="mt-4 text-end">
                    <div id="assignError" class="alert alert-danger d-none mt-2"></div>

                    <button type="button" class="btn bg-orange text-white me-2" onclick="closeModal('assignModal')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Asignar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="custom-modal" id="editModal" onclick="closeOnOverlay(event, 'editModal')">
        <div class="custom-modal-content">
            <span class="custom-modal-close" onclick="closeModal('editModal')">&times;</span>

            <h5>Editar asignación</h5>
            <form id="editForm" data-no-loader="true">
                @csrf
                <input type="hidden" id="editAssignmentId">

                <div class="mb-3">
                    <label for="editStaffSelect" class="form-label">Trabajador</label>
                    <select id="editStaffSelect" class="form-select" required>
                        <option value="" disabled selected>Selecciona un trabajador</option>
                        @foreach ($staff as $person)
                            <option value="{{ $person->id }}" data-departments="{{ $person->departmentCosts->pluck('department_id')->toJson() }}">{{ $person->name }} {{ $person->surnames }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="editDepartmentSelect" class="form-label">Departamento</label>
                    <select id="editDepartmentSelect" class="form-select" required>
                        <option value="" disabled selected>Selecciona un departamento</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="editSubjectSelect" class="form-label">Materia</label>
                    <select id="editSubjectSelect" class="form-select" required>
                        <option value="" disabled selected>Selecciona una materia</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Horario</label>
                    <div class="d-flex gap-2">
                        <select id="editStartTime" class="form-select" required>
                            <option value="" disabled selected>Inicio</option>
                            @for ($hour = 7; $hour <= 20; $hour++)
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00
                                </option>
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30
                                </option>
                            @endfor
                        </select>

                        <select id="editEndTime" class="form-select" required>
                            <option value="" disabled selected>Fin</option>
                            @for ($hour = 7; $hour <= 21; $hour++)
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00
                                </option>
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="button" class="btn btn-danger me-2" id="deleteAssignmentBtn">Eliminar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const userCrewId = @json(auth()->user()->crew_id);
        const calendarConfig = {
            departments: @json($departments),
            subjectsByDepartment: @json($subjectsByDepartment)
        };
    </script>

    <script>
        const routes = {
            events: @json(route('hourAssignments.events')),
            store: @json(route('hourAssignments.store')),
            update: id => `/admin/hour-assignments/${id}`,
            delete: id => `/admin/hour-assignments/${id}`,
        };
        const csrfToken = "{{ csrf_token() }}";

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove("show");
            setTimeout(() => {
                modal.style.display = 'none';

                if (modalId === 'assignModal') {
                    const errorBox = document.getElementById("assignError");
                    if (errorBox) {
                        errorBox.textContent = "";
                        errorBox.classList.add("d-none");
                    }
                }
            }, 300);
        }

    </script>

    <script src="{{ asset('assets/js/calendar.js') }}"></script>
@endpush
