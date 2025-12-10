@extends('layout.mainLayout')
@section('title', 'Estadísticas de informes')
@section('content')

    <script src="https://www.gstatic.com/charts/loader.js"></script>

    <style>
        .table-container {
            max-height: 400px;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid #ddd;
            margin-bottom: 40px;
        }

        .table thead {
            position: sticky;
            top: 0;
            background: white;
            z-index: 100;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            white-space: nowrap;
            padding: 10px;
        }
    </style>

    <div class="card shadow ccont">
        <div class="card-body">

            <div class="text-center mb-4">
                <h1>Datos de cobranza</h1>
            </div>

            <form method="GET" action="{{ route('admin.stats.billing') }}" id="filtersForm">
                <div class="row align-items-end">
                    <div class="col-md-2">
                        <label>Plantel:</label>
                        @if (Auth::user()->role_id == 1)
                            <select name="plantel" class="form-control">
                                @foreach ($crews as $crew)
                                    <option value="{{ $crew->id }}" {{ request('plantel') == $crew->id ? 'selected' : '' }}>
                                        {{ $crew->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="form-control bg-light">
                                {{ Auth::user()->crew->name }}
                            </div>
                        @endif
                    </div>

                    <div class="col-md-2">
                        <label>Fecha:</label>
                        <select name="fecha" id="fecha" class="form-control" onchange="mostrarInputFecha()">
                            <option value="historico" {{ request('fecha') == 'historico' ? 'selected' : '' }}>Histórico
                            </option>
                            <option value="hoy" {{ request('fecha') == 'hoy' ? 'selected' : '' }}>Hoy</option>
                            <option value="semana" {{ request('fecha') == 'semana' ? 'selected' : '' }}>Última semana
                            </option>
                            <option value="mes" {{ request('fecha') == 'mes' ? 'selected' : '' }}>Último mes</option>
                            <option value="personalizado" {{ request('fecha') == 'personalizado' ? 'selected' : '' }}>
                                Personalizado</option>
                        </select>
                    </div>

                    <div class="col-md-3" id="filtroFechaPersonalizado" style="display: none;">
                        <label>Rango de fechas:</label>
                        <div class="row">
                            <div class="col-6 pe-1">
                                <input type="date" name="fecha_inicio" class="form-control"
                                    value="{{ request('fecha_inicio') }}">
                            </div>
                            <div class="col-6 ps-1">
                                <div class="input-group">
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                                        value="{{ request('fecha_fin') }}">
                                    <button type="button" class="btn btn-outline-secondary px-2 py-1"
                                        onclick="document.getElementById('fecha_fin').value = ''" title="Limpiar fecha">
                                        &times;
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label>Tipo Recibo:</label>
                        <select name="tipo_recibo" class="form-control">
                            <option value="">Todos</option>
                            @foreach ($receiptTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ request('tipo_recibo') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Tipo Pago:</label>
                        <select name="tipo_pago" class="form-control">
                            <option value="">Todos</option>
                            @foreach ($paymentTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ request('tipo_pago') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn bg-orange text-white w-100">Filtrar</button>
                    </div>
                </div>
            </form>

            <div class="mt-5 d-flex align-items-center flex-wrap gap-3 mb-3">
                <h4 class="mb-0 d-flex align-items-center gap-3">
                    {{ $receipts->count() }} recibos
                    <span class="fs-2 text-success">
                        ${{ number_format($receiptsTotal, 2) }}
                    </span>
                </h4>

                @if ($tiposPagoConTotales->count() > 1)
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @foreach ($tiposPagoConTotales as $item)
                            <div class="bg-light border rounded px-3 py-2 text-muted">
                                <strong>{{ $item['nombre'] }}:</strong> ${{ number_format($item['total'], 2) }}
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

            <div class="table-container">
                @if ($receipts->isNotEmpty())
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Folio</th>
                                <th>Fecha</th>
                                <th>Estudiante</th>
                                <th>Monto</th>
                                <th>Tipo de pago</th>
                                <th>Tipo de recibo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($receipts as $receipt)
                                <tr>
                                    <td>{{ $receipt->id }}</td>
                                    <td>{{ $receipt->folio }}</td>
                                    <td>{{ $receipt->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $receipt->student->name ?? 'No asignado' }}</td>
                                    <td>${{ number_format($receipt->amount, 2) }}</td>
                                    <td>{{ $receipt->payment->name ?? 'No definido' }}</td>
                                    <td>{{ $receipt->receiptType->name ?? 'No definido' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center">No hay recibos disponibles.</p>
                @endif
            </div>

            <h4 class="mt-5">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0">
                        {{ $paybills->count() }} vales
                    </h4>
                    <span class="text-danger fs-2 ms-3">
                        ${{ number_format($paybillsTotal, 2) }}
                    </span>
                </div>
            </h4>

            <div class="table-container">
                @if ($paybills->isNotEmpty())
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Folio</th>
                                <th>Fecha</th>
                                <th>Plantel</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paybills as $pb)
                                <tr>
                                    <td>{{ $pb->id }}</td>
                                    <td>{{ $pb->folio }}</td>
                                    <td>{{ $pb->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $pb->crew->name ?? 'No asignado' }}</td>
                                    <td>${{ number_format($pb->amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center">No hay gastos disponibles.</p>
                @endif
            </div>

            <div class="mt-4 text-start">
                <h2>
                    En caja:
                    <span class="{{ $diferenciaTotal >= 0 ? 'text-success' : 'text-danger' }}">
                        ${{ number_format($diferenciaTotal, 2) }}
                    </span>
                </h2>
            </div>

        </div>
    </div>

    <script>
        function mostrarInputFecha() {
            const seleccion = document.getElementById("fecha").value;
            document.getElementById("filtroFechaPersonalizado").style.display = (seleccion === "personalizado") ? "block" :
                "none";
        }
        mostrarInputFecha();
    </script>

@endsection
