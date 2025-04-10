@extends('layout.mainLayout')
@section('title', 'Calendario')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/calendar-modal.css') }}">
    @endpush

    <div class="card shadow ccont">
        <div class="card-body">
            <div class="row d-flex text-center mt-3">
                <div class="col mb-3">
                    <h1>Asignación de horas</h1>
                </div>
            </div>

            <div class="container">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <div class="custom-modal" id="assignModal" onclick="closeOnOverlay(event, 'assignModal')">
        <div class="custom-modal-content">

            <span class="custom-modal-close" onclick="closeModal('assignModal')">&times;</span>

            <h5>Asignar horas</h5>
            <form id="assignForm">
                @csrf
                <div class="mb-3">
                    <label for="staffSelect" class="form-label">Trabajador</label>
                    <select id="staffSelect" class="form-select" required>
                        <option value="" disabled selected>Selecciona un trabajador</option>
                        @foreach ($staff as $person)
                            <option value="{{ $person->id }}">{{ $person->name }} {{ $person->surnames }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="subjectSelect" class="form-label">Materia</label>
                    <select id="subjectSelect" class="form-select" required>
                        <option value="" disabled selected>Selecciona una materia</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Horario</label>
                    <div class="d-flex gap-2">
                        <select id="startTime" class="form-select" required>
                            <option value="" disabled selected>Inicio</option>
                            @for ($hour = 7; $hour <= 20; $hour++)
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00</option>
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30</option>
                            @endfor
                        </select>
                        <select id="endTime" class="form-select" required>
                            <option value="" disabled selected>Fin</option>
                            @for ($hour = 7; $hour <= 21; $hour++)
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00</option>
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <input type="hidden" id="selectedDate">

                <div class="text-end mt-4">
                    <button type="button" class="btn btn-secondary me-2" onclick="closeModal('assignModal')">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">Asignar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="custom-modal" id="editModal" onclick="closeOnOverlay(event, 'editModal')">
        <div class="custom-modal-content">
            <span class="custom-modal-close" onclick="closeModal('editModal')">&times;</span>

            <h5>Editar asignación</h5>
            <form id="editForm">
                @csrf
                <input type="hidden" id="editAssignmentId">
                <div class="mb-3">
                    <label for="editStaffSelect" class="form-label">Trabajador</label>
                    <select id="editStaffSelect" class="form-select" required>
                        <option value="" disabled selected>Selecciona un trabajador</option>
                        @foreach ($staff as $person)
                            <option value="{{ $person->id }}">{{ $person->name }} {{ $person->surnames }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="editSubjectSelect" class="form-label">Materia</label>
                    <select id="editSubjectSelect" class="form-select" required>
                        <option value="" disabled selected>Selecciona una materia</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Horario</label>
                    <div class="d-flex gap-2">
                        <select id="editStartTime" class="form-select" required>
                            <option value="" disabled selected>Inicio</option>
                            @for ($hour = 7; $hour <= 20; $hour++)
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00</option>
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30</option>
                            @endfor
                        </select>
                        <select id="editEndTime" class="form-select" required>
                            <option value="" disabled selected>Fin</option>
                            @for ($hour = 7; $hour <= 21; $hour++)
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00</option>
                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:30</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="button" class="btn btn-danger me-2" id="deleteAssignmentBtn">
                        Eliminar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Guardar cambios
                    </button>
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
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    </script>

    <script src="{{ asset('assets/js/calendar.js') }}"></script>
@endpush
