@extends('layout.mainLayout')
@section('title', 'Costos Registrados')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Gestión de Costos</h1>
        <p class="dashboard-subtitle">Administra los montos y colegiaturas del sistema</p>
    </div>

    @if (session('success'))
        <div id="success" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2V22M17 5H9.5C8.57174 5 7.6815 5.36875 7.02513 6.02513C6.36875 6.6815 6 7.57174 6 8.5C6 9.42826 6.36875 10.3185 7.02513 10.9749C7.6815 11.6313 8.57174 12 9.5 12H14.5C15.4283 12 16.3185 12.3687 16.9749 13.0251C17.6313 13.6815 18 14.5717 18 15.5C18 16.4283 17.6313 17.3185 16.9749 17.9749C16.3185 18.6313 15.4283 19 14.5 19H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Costos Registrados</h2>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.catalogues.amounts.generate') }}" class="btn-modern btn-primary" onclick="showLoader(true)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Generar Inscripciones
                </a>
                @if($amounts->where('receipt_type_id', '!=', 1)->where('crew_id', '!=', 1)->count() > 0)
                    <a href="{{ route('admin.catalogues.amounts.clean') }}"
                       class="btn-modern btn-danger"
                       onclick="return confirm('¿Estás seguro de eliminar todos los costos que no sean inscripciones?') && showLoader(true)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Limpiar Colegiaturas
                    </a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Plantel</th>
                        <th>Tipo</th>
                        <th>Curso</th>
                        <th>Monto</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($amounts as $amount)
                        <tr>
                            <td>
                                @if($amount->crew_id == 1)
                                    <span class="badge badge-success">TODOS LOS PLANTELES</span>
                                @else
                                    <span class="badge badge-primary">{{ $amount->crew->name }}</span>
                                @endif
                            </td>
                            <td class="text-uppercase font-medium">{{ $amount->receiptType->name }}</td>
                            <td class="text-uppercase">
                                @isset($amount->course)
                                    {{ $amount->course->name }}
                                @endisset
                            </td>
                            <td class="font-medium">${{ number_format($amount->amount, 2, '.', ',') }}</td>
                            <td>
                                <div class="action-buttons">
                                    @if (Auth::user()->role_id !== 1)
                                        @if ($amount->id > 133)
                                            <a href="{{ route('admin.catalogues.amount.edit', ['id' => $amount->id]) }}"
                                               class="action-btn action-edit"
                                               onclick="showLoader(true)"
                                               title="Editar costo">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M18.5 2.50001C18.8978 2.10219 19.4374 1.87869 20 1.87869C20.5626 1.87869 21.1022 2.10219 21.5 2.50001C21.8978 2.89784 22.1213 3.4374 22.1213 4.00001C22.1213 4.56262 21.8978 5.10219 21.5 5.50001L12 15L8 16L9 12L18.5 2.50001Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </a>
                                        @else
                                            <button class="action-btn" disabled title="Costo protegido" style="opacity: 0.4; cursor: not-allowed;">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11" stroke="currentColor" stroke-width="2"/>
                                                </svg>
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('admin.catalogues.amount.edit', ['id' => $amount->id]) }}"
                                           class="action-btn action-edit"
                                           onclick="showLoader(true)"
                                           title="Editar costo">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M18.5 2.50001C18.8978 2.10219 19.4374 1.87869 20 1.87869C20.5626 1.87869 21.1022 2.10219 21.5 2.50001C21.8978 2.89784 22.1213 3.4374 22.1213 4.00001C22.1213 4.56262 21.8978 5.10219 21.5 5.50001L12 15L8 16L9 12L18.5 2.50001Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
