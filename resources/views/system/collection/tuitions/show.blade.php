@extends('layout.mainLayout')
@section('title','Colegiaturas del Estudiante')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Cobro de Colegiaturas</h1>
        <p class="dashboard-subtitle">{{ $student->surnames }}, {{ $student->name }} - {{ $student->crew->name[0].'/'.$student->id }}</p>
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

    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Información del Estudiante</h2>
            </div>
            <a href="{{ route('system.collection.student.newtuition',['student_id'=>$student->id]) }}" class="btn-modern btn-primary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Nuevo Cobro
            </a>
        </div>

        <div style="padding: 24px;">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">MATRÍCULA</label>
                    <div class="text-uppercase" style="font-size: 16px; font-weight: 600; color: #111827;">{{ $student->crew->name[0].'/'.$student->id }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">NOMBRE COMPLETO</label>
                    <div class="text-uppercase" style="font-size: 16px; font-weight: 600; color: #111827;">{{ $student->surnames }}, {{ $student->name }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">CURSO</label>
                    <div class="text-uppercase" style="font-size: 14px; color: #374151;">{{ $student->course->name }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">GENERACIÓN</label>
                    <div class="text-uppercase" style="font-size: 14px; color: #374151;">{{ $student->generation }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">MODALIDAD</label>
                    <div class="text-uppercase" style="font-size: 14px; color: #374151;">{{ $student->modality->name }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">HORARIO</label>
                    <div class="text-uppercase" style="font-size: 14px; color: #374151;">{{ $student->schedule->name }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">PERIODICIDAD</label>
                    <div class="text-uppercase" style="font-size: 14px; color: #374151;">{{ $student->paymentPeriodicity->name }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">COLEGIATURA</label>
                    <div style="font-size: 18px; font-weight: 700; color: #065f46;">${{ number_format($student->tuition, 2, '.', ',') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="modern-card" style="margin-top: 24px;">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Historial de Cobros</h2>
            </div>
            <span class="badge badge-primary">{{ count($payments) }}</span>
        </div>

        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th>Importe</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $pay)
                        <tr>
                            <td class="font-medium">#{{ $pay->id }}</td>
                            <td>{{ $pay->created_at->format('d/m/Y') }}</td>
                            <td class="text-uppercase">{{ $pay->concept }}</td>
                            <td style="font-weight: 600; color: #065f46;">${{ number_format($pay->amount, 2, '.', ',') }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('system.collection.receipt.reprint', ['receipt_id' => $pay->id]) }}" target="_blank" class="action-btn action-edit" title="Reimprimir Recibo">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6 9V2H18V9M6 18H4C3.46957 18 2.96086 17.7893 2.58579 17.4142C2.21071 17.0391 2 16.5304 2 16V11C2 10.4696 2.21071 9.96086 2.58579 9.58579C2.96086 9.21071 3.46957 9 4 9H20C20.5304 9 21.0391 9.21071 21.4142 9.58579C21.7893 9.96086 22 10.4696 22 11V16C22 16.5304 21.7893 17.0391 21.4142 17.4142C21.0391 17.7893 20.5304 18 20 18H18M6 14H18V22H6V14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #6b7280;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 16px; opacity: 0.3;">
                                    <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div style="font-size: 16px; font-weight: 500;">No hay cobros registrados</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
