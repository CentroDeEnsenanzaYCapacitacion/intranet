@extends('layout.mainLayout')
@section('title','Emisión de Vales')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Emisión de Vales</h1>
        <p class="dashboard-subtitle">Registro de vales emitidos para otros conceptos</p>
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
                    <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Historial de Vales</h2>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span class="badge badge-primary">{{ count($paybills) }}</span>
                <a href="{{ route('system.collection.newpaybill') }}" class="btn-modern btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Nuevo Vale
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Folio</th>
                        <th>Concepto</th>
                        <th>Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paybills as $paybill)
                        <tr>
                            <td>{{ $paybill->created_at->format('d/m/Y') }}</td>
                            <td class="font-medium">#{{ $paybill->id }}</td>
                            <td class="text-uppercase">{{ $paybill->concept }}</td>
                            <td style="font-weight: 600; color: #065f46;">${{ number_format($paybill->amount, 2, '.', ',') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: #6b7280;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 16px; opacity: 0.3;">
                                    <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div style="font-size: 16px; font-weight: 500;">No hay vales registrados</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
