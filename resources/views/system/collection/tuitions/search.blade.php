@extends('layout.mainLayout')
@section('title', 'Cobranza de Colegiaturas')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Cobranza de Colegiaturas</h1>
        <p class="dashboard-subtitle">Busca un estudiante para registrar el pago de colegiatura</p>
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
                    <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Búsqueda de Estudiante</h2>
            </div>
        </div>

        <div style="padding: 24px;">
            <form action="{{ route('system.collection.tuitions.search-post') }}" method="POST">
                @csrf
                <div style="display: flex; gap: 12px; max-width: 600px; margin: 0 auto;">
                    <input
                        name="data"
                        type="text"
                        class="form-control"
                        placeholder="Buscar por nombre, apellidos o matrícula"
                        value="{{ old('data') }}"
                        style="flex: 1; height: 48px; font-size: 16px; border: 2px solid #e5e7eb; border-radius: 12px; padding: 0 16px;"
                    >
                    <button
                        class="btn-modern btn-primary"
                        type="submit"
                        style="height: 48px; min-width: 120px; display: flex; align-items: center; justify-content: center; gap: 8px;"
                        onclick="showLoader(true)"
                    >
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if (count($students) > 0)
        <div class="modern-card" style="margin-top: 24px;">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>Resultados</h2>
                </div>
                <span class="badge badge-primary">{{ count($students) }}</span>
            </div>

            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td class="text-uppercase font-medium">{{ $student->crew->name[0].'/'.$student->id }}</td>
                                <td class="text-uppercase">{{ $student->surnames.', '.$student->name }}</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('system.collection.student.tuitions', $student->id) }}" class="action-btn action-edit" title="Ver Colegiaturas" onclick="showLoader(true)">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 2V6M12 18V22M4.93 4.93L7.76 7.76M16.24 16.24L19.07 19.07M2 12H6M18 12H22M4.93 19.07L7.76 16.24M16.24 7.76L19.07 4.93" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif (old('data'))
        <div class="modern-card" style="margin-top: 24px;">
            <div style="text-align: center; padding: 60px 20px; color: #6b7280;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 16px; opacity: 0.3;">
                    <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">No se encontraron estudiantes</div>
                <div style="font-size: 14px;">Intenta con otros términos de búsqueda</div>
            </div>
        </div>
    @endif

    <script>
        function redirectToStudent(url) {
            window.location.href = url;
        }
    </script>
@endsection
