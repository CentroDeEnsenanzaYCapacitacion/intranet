@extends('layout.mainLayout')
@section('title', 'Apertura EUB')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Apertura EUB</h1>
        <p class="dashboard-subtitle">Establece cuando se abre y cierra el examen unico de bachillerato</p>
    </div>

    @if (session('success'))
        <div id="success" class="alert alert-success" style="background: #d1fae5; border: 1px solid #065f46; color: #065f46; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" style="background: #fee2e2; border: 1px solid #991b1b; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
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
                    <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Alumnos inscritos</h2>
            </div>
            <span class="badge badge-primary">{{ count($students) }}</span>
        </div>

        @if (count($students) > 0)
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Matricula</th>
                            <th>Nombre</th>
                            <th>Curso</th>
                            <th>Inicio examen</th>
                            <th>Fin examen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td class="text-uppercase font-medium">
                                    {{ $student->crew->name[0].'/'.$student->id }}
                                    <form id="eub-form-{{ $student->id }}" action="{{ route('system.calendars.eub.update', $student->id) }}" method="POST" data-password-confirm>
                                        @csrf
                                    </form>
                                </td>
                                <td class="text-uppercase">{{ $student->surnames.', '.$student->name }}</td>
                                <td class="text-uppercase">{{ $student->course ? $student->course->name : 'Sin curso' }}</td>
                                <td>
                                    <input
                                        form="eub-form-{{ $student->id }}"
                                        type="datetime-local"
                                        name="start_at"
                                        value="{{ $student->eubExamWindow && $student->eubExamWindow->start_at ? $student->eubExamWindow->start_at->format('Y-m-d\\TH:i') : '' }}"
                                        class="form-control"
                                        style="min-width: 180px; height: 40px; border: 2px solid #e5e7eb; border-radius: 10px; padding: 0 12px;"
                                    >
                                </td>
                                <td>
                                    <input
                                        form="eub-form-{{ $student->id }}"
                                        type="datetime-local"
                                        name="end_at"
                                        value="{{ $student->eubExamWindow && $student->eubExamWindow->end_at ? $student->eubExamWindow->end_at->format('Y-m-d\\TH:i') : '' }}"
                                        class="form-control"
                                        style="min-width: 180px; height: 40px; border: 2px solid #e5e7eb; border-radius: 10px; padding: 0 12px;"
                                    >
                                </td>
                                <td>
                                    <button
                                        form="eub-form-{{ $student->id }}"
                                        type="submit"
                                        class="btn-modern btn-primary"
                                        style="height: 40px; padding: 0 16px; font-size: 14px;"
                                        onclick="showLoader(true)"
                                    >
                                        Guardar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; color: #6b7280;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 16px; opacity: 0.3;">
                    <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">No hay alumnos inscritos</div>
                <div style="font-size: 14px;">No se encontraron estudiantes con ese curso</div>
            </div>
        @endif
    </div>

    @include('includes.password-confirm-modal')
@endsection
