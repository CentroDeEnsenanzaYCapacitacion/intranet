@extends('layout.mainLayout')
@section('title', 'Estadísticas de cobranza')
@section('content')

    <script src="https://www.gstatic.com/charts/loader.js"></script>

    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Datos de Cobranza</h1>
        <p class="dashboard-subtitle">Estadísticas de ingresos y gastos</p>
    </div>

    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 3H21C21.5304 3 22.0391 3.21071 22.4142 3.58579C22.7893 3.96086 23 4.46957 23 5V8C23 8.53043 22.7893 9.03914 22.4142 9.41421C22.0391 9.78929 21.5304 10 21 10H3C2.46957 10 1.96086 9.78929 1.58579 9.41421C1.21071 9.03914 1 8.53043 1 8V5C1 4.46957 1.21071 3.96086 1.58579 3.58579C1.96086 3.21071 2.46957 3 3 3ZM3 12H21C21.5304 12 22.0391 12.2107 22.4142 12.5858C22.7893 12.9609 23 13.4696 23 14V19C23 19.5304 22.7893 20.0391 22.4142 20.4142C22.0391 20.7893 21.5304 21 21 21H3C2.46957 21 1.96086 20.7893 1.58579 20.4142C1.21071 20.0391 1 19.5304 1 19V14C1 13.4696 1.21071 12.9609 1.58579 12.5858C1.96086 12.2107 2.46957 12 3 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Filtros de Búsqueda</h2>
            </div>
        </div>

        <div style="padding: 24px;">
            <form method="GET" action="{{ route('admin.stats.billing') }}" id="filtersForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="modern-field">
                            <label>Plantel</label>
                            @if (Auth::user()->role_id == 1)
                                <select name="plantel" class="modern-input">
                                    @foreach ($crews as $crew)
                                        <option value="{{ $crew->id }}" {{ request('plantel') == $crew->id ? 'selected' : '' }}>
                                            {{ $crew->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <div class="modern-input" style="background: #f3f4f6; cursor: not-allowed;">
                                    {{ Auth::user()->crew->name }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="modern-field">
                            <label>Fecha</label>
                            <select name="fecha" id="fecha" class="modern-input" onchange="mostrarInputFecha()">
                                <option value="historico" {{ request('fecha') == 'historico' ? 'selected' : '' }}>Histórico</option>
                                <option value="hoy" {{ request('fecha') == 'hoy' ? 'selected' : '' }}>Hoy</option>
                                <option value="semana" {{ request('fecha') == 'semana' ? 'selected' : '' }}>Última semana</option>
                                <option value="mes" {{ request('fecha') == 'mes' ? 'selected' : '' }}>Último mes</option>
                                <option value="personalizado" {{ request('fecha') == 'personalizado' ? 'selected' : '' }}>Personalizado</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="modern-field">
                            <label>Tipo Recibo</label>
                            <select name="tipo_recibo" class="modern-input">
                                <option value="">Todos</option>
                                @foreach ($receiptTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('tipo_recibo') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="modern-field">
                            <label>Tipo Pago</label>
                            <select name="tipo_pago" class="modern-input">
                                <option value="">Todos</option>
                                @foreach ($paymentTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('tipo_pago') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3" id="filtroFechaPersonalizado" style="display: none;">
                        <div class="modern-field">
                            <label>Rango de Fechas</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="date" name="fecha_inicio" class="modern-input" value="{{ request('fecha_inicio') }}" placeholder="Fecha inicio">
                                </div>
                                <div class="col-md-6">
                                    <div style="display: flex; gap: 8px;">
                                        <input type="date" name="fecha_fin" id="fecha_fin" class="modern-input" value="{{ request('fecha_fin') }}" placeholder="Fecha fin" style="flex: 1;">
                                        <button type="button" class="btn-modern btn-primary" onclick="document.getElementById('fecha_fin').value = ''" title="Limpiar fecha" style="padding: 0 16px;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: center; margin-top: 8px;">
                    <button type="submit" class="btn-modern btn-primary" style="min-width: 200px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modern-card" style="margin-bottom: 24px;">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 14L15 14M9 10L19 10M7 18V6C7 5.46957 7.21071 4.96086 7.58579 4.58579C7.96086 4.21071 8.46957 4 9 4H18C18.5304 4 19.0391 4.21071 19.4142 4.58579C19.7893 4.96086 20 5.46957 20 6V18C20 18.5304 19.7893 19.0391 19.4142 19.4142C19.0391 19.7893 18.5304 20 18 20H9C8.46957 20 7.96086 19.7893 7.58579 19.4142C7.21071 19.0391 7 18.5304 7 18ZM5 18C5 18.5304 4.78929 19.0391 4.41421 19.4142C4.03914 19.7893 3.53043 20 3 20C2.46957 20 1.96086 19.7893 1.58579 19.4142C1.21071 19.0391 1 18.5304 1 18C1 17.4696 1.21071 16.9609 1.58579 16.5858C1.96086 16.2107 2.46957 16 3 16C3.53043 16 4.03914 16.2107 4.41421 16.5858C4.78929 16.9609 5 17.4696 5 18ZM5 6C5 6.53043 4.78929 7.03914 4.41421 7.41421C4.03914 7.78929 3.53043 8 3 8C2.46957 8 1.96086 7.78929 1.58579 7.41421C1.21071 7.03914 1 6.53043 1 6C1 5.46957 1.21071 4.96086 1.58579 4.58579C1.96086 4.21071 2.46957 4 3 4C3.53043 4 4.03914 4.21071 4.41421 4.58579C4.78929 4.96086 5 5.46957 5 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Recibos</h2>
            </div>
            <div style="display: flex; gap: 16px; align-items: center;">
                <span class="badge badge-gray">{{ $receipts->count() }}</span>
                <div style="text-align: right;">
                    <div style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Total</div>
                    <div style="font-size: 20px; font-weight: 700; color: #065f46;">${{ number_format($receiptsTotal, 2) }}</div>
                </div>
            </div>
        </div>

        @if ($tiposPagoConTotales->count() > 1)
            <div style="padding: 16px 24px; background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                    @foreach ($tiposPagoConTotales as $item)
                        <div style="padding: 8px 16px; background: white; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 13px;">
                            <span style="color: #6b7280;">{{ $item['nombre'] }}:</span>
                            <span style="font-weight: 600; color: #1a1a1a; margin-left: 8px;">${{ number_format($item['total'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div style="max-height: 400px; overflow-y: auto;">
            <table class="modern-table">
                <thead style="position: sticky; top: 0; z-index: 10; background: #f9fafb;">
                    <tr>
                        <th>#</th>
                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Estudiante</th>
                        <th>Monto</th>
                        <th>Tipo de Pago</th>
                        <th>Tipo de Recibo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receipts as $receipt)
                        <tr>
                            <td>{{ $receipt->id }}</td>
                            <td class="font-medium">{{ $receipt->folio }}</td>
                            <td>{{ $receipt->created_at->format('d/m/Y') }}</td>
                            <td>{{ $receipt->student->name ?? 'No asignado' }}</td>
                            <td style="font-weight: 600; color: #065f46;">${{ number_format($receipt->amount, 2) }}</td>
                            <td class="text-uppercase">{{ $receipt->payment->name ?? 'No definido' }}</td>
                            <td class="text-uppercase">{{ $receipt->receiptType->name ?? 'No definido' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 16px; opacity: 0.3;">
                                    <path d="M9 14L15 14M9 10L19 10M7 18V6C7 5.46957 7.21071 4.96086 7.58579 4.58579C7.96086 4.21071 8.46957 4 9 4H18C18.5304 4 19.0391 4.21071 19.4142 4.58579C19.7893 4.96086 20 5.46957 20 6V18C20 18.5304 19.7893 19.0391 19.4142 19.4142C19.0391 19.7893 18.5304 20 18 20H9C8.46957 20 7.96086 19.7893 7.58579 19.4142C7.21071 19.0391 7 18.5304 7 18ZM5 18C5 18.5304 4.78929 19.0391 4.41421 19.4142C4.03914 19.7893 3.53043 20 3 20C2.46957 20 1.96086 19.7893 1.58579 19.4142C1.21071 19.0391 1 18.5304 1 18C1 17.4696 1.21071 16.9609 1.58579 16.5858C1.96086 16.2107 2.46957 16 3 16C3.53043 16 4.03914 16.2107 4.41421 16.5858C4.78929 16.9609 5 17.4696 5 18ZM5 6C5 6.53043 4.78929 7.03914 4.41421 7.41421C4.03914 7.78929 3.53043 8 3 8C2.46957 8 1.96086 7.78929 1.58579 7.41421C1.21071 7.03914 1 6.53043 1 6C1 5.46957 1.21071 4.96086 1.58579 4.58579C1.96086 4.21071 2.46957 4 3 4C3.53043 4 4.03914 4.21071 4.41421 4.58579C4.78929 4.96086 5 5.46957 5 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div style="font-size: 16px; font-weight: 500;">No hay recibos disponibles</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modern-card" style="margin-bottom: 24px;">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 9V7C17 6.46957 16.7893 5.96086 16.4142 5.58579C16.0391 5.21071 15.5304 5 15 5H5C4.46957 5 3.96086 5.21071 3.58579 5.58579C3.21071 5.96086 3 6.46957 3 7V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19H7M9 15H19C19.5304 15 20.0391 14.7893 20.4142 14.4142C20.7893 14.0391 21 13.5304 21 13V11C21 10.4696 20.7893 9.96086 20.4142 9.58579C20.0391 9.21071 19.5304 9 19 9H9C8.46957 9 7.96086 9.21071 7.58579 9.58579C7.21071 9.96086 7 10.4696 7 11V13C7 13.5304 7.21071 14.0391 7.58579 14.4142C7.96086 14.7893 8.46957 15 9 15ZM14 12C14 12.2652 13.8946 12.5196 13.7071 12.7071C13.5196 12.8946 13.2652 13 13 13C12.7348 13 12.4804 12.8946 12.2929 12.7071C12.1054 12.5196 12 12.2652 12 12C12 11.7348 12.1054 11.4804 12.2929 11.2929C12.4804 11.1054 12.7348 11 13 11C13.2652 11 13.5196 11.1054 13.7071 11.2929C13.8946 11.4804 14 11.7348 14 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Vales / Gastos</h2>
            </div>
            <div style="display: flex; gap: 16px; align-items: center;">
                <span class="badge badge-gray">{{ $paybills->count() }}</span>
                <div style="text-align: right;">
                    <div style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Total</div>
                    <div style="font-size: 20px; font-weight: 700; color: #991b1b;">${{ number_format($paybillsTotal, 2) }}</div>
                </div>
            </div>
        </div>

        <div style="max-height: 400px; overflow-y: auto;">
            <table class="modern-table">
                <thead style="position: sticky; top: 0; z-index: 10; background: #f9fafb;">
                    <tr>
                        <th>#</th>
                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Plantel</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paybills as $pb)
                        <tr>
                            <td>{{ $pb->id }}</td>
                            <td class="font-medium">{{ $pb->folio }}</td>
                            <td>{{ $pb->created_at->format('d/m/Y') }}</td>
                            <td class="text-uppercase">{{ $pb->crew->name ?? 'No asignado' }}</td>
                            <td style="font-weight: 600; color: #991b1b;">${{ number_format($pb->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #6b7280;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 16px; opacity: 0.3;">
                                    <path d="M17 9V7C17 6.46957 16.7893 5.96086 16.4142 5.58579C16.0391 5.21071 15.5304 5 15 5H5C4.46957 5 3.96086 5.21071 3.58579 5.58579C3.21071 5.96086 3 6.46957 3 7V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19H7M9 15H19C19.5304 15 20.0391 14.7893 20.4142 14.4142C20.7893 14.0391 21 13.5304 21 13V11C21 10.4696 20.7893 9.96086 20.4142 9.58579C20.0391 9.21071 19.5304 9 19 9H9C8.46957 9 7.96086 9.21071 7.58579 9.58579C7.21071 9.96086 7 10.4696 7 11V13C7 13.5304 7.21071 14.0391 7.58579 14.4142C7.96086 14.7893 8.46957 15 9 15ZM14 12C14 12.2652 13.8946 12.5196 13.7071 12.7071C13.5196 12.8946 13.2652 13 13 13C12.7348 13 12.4804 12.8946 12.2929 12.7071C12.1054 12.5196 12 12.2652 12 12C12 11.7348 12.1054 11.4804 12.2929 11.2929C12.4804 11.1054 12.7348 11 13 11C13.2652 11 13.5196 11.1054 13.7071 11.2929C13.8946 11.4804 14 11.7348 14 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div style="font-size: 16px; font-weight: 500;">No hay gastos disponibles</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modern-card" style="background: linear-gradient(135deg, {{ $diferenciaTotal >= 0 ? '#d1fae5' : '#fee2e2' }} 0%, {{ $diferenciaTotal >= 0 ? '#ecfdf5' : '#fef2f2' }} 100%); border: 2px solid {{ $diferenciaTotal >= 0 ? '#065f46' : '#991b1b' }};">
        <div style="padding: 24px; text-align: center;">
            <div style="font-size: 16px; color: {{ $diferenciaTotal >= 0 ? '#065f46' : '#991b1b' }}; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">En Caja</div>
            <div style="font-size: 48px; font-weight: 700; color: {{ $diferenciaTotal >= 0 ? '#065f46' : '#991b1b' }};">${{ number_format($diferenciaTotal, 2) }}</div>
        </div>
    </div>

    <script>
        function mostrarInputFecha() {
            const seleccion = document.getElementById("fecha").value;
            document.getElementById("filtroFechaPersonalizado").style.display = (seleccion === "personalizado") ? "block" : "none";
        }
        mostrarInputFecha();
    </script>

@endsection
