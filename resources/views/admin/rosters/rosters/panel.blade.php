@php
    $userCrewId = auth()->user()->crew_id ?? null;
@endphp

@extends('layout.mainLayout')
@section('title', 'Cálculo de nómina')
@section('content')

    <div class="card shadow ccont">
        <div class="card-body">
            <div class="row d-flex text-center mt-3">
                <div class="col mb-3">
                    <h1>Cálculo de nómina</h1>
                </div>
            </div>

            <form method="GET" class="mb-4">
                <div class="row">
                    @if ($userCrewId == 1)
                        <div class="col-md-6 mb-3">
                            <label for="crew" class="form-label">Seleccionar plantel</label>
                            <select class="form-control" name="crew" id="crew">
                                <option value="all" {{ request('crew') == 'all' ? 'selected' : '' }}>Todos</option>
                                @foreach ($allCrews as $crew)
                                    <option value="{{ $crew->id }}"
                                        {{ request('crew') == $crew->id ? 'selected' : '' }}>
                                        {{ $crew->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="year" class="form-label">Año</label>
                        <select class="form-control" name="year" id="year">
                            @for ($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}"
                                    {{ request('year', now()->year) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="month" class="form-label">Mes</label>
                        <select class="form-control" name="month" id="month">
                            @foreach ([1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'] as $num => $name)
                                <option value="{{ $num }}"
                                    {{ request('month', now()->month) == $num ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="period" class="form-label">Periodo</label>
                        <select class="form-control" name="period" id="period">
                            <option value="8-22" {{ request('period', '8-22') == '8-22' ? 'selected' : '' }}>Del 8 al 22
                            </option>
                            <option value="23-7" {{ request('period') == '23-7' ? 'selected' : '' }}>Del 23 al 7</option>
                        </select>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary">Calcular</button>
                </div>
            </form>

            @foreach ($staffGrouped as $crewId => $staffList)
                @php
                    $crewName = $allCrews->firstWhere('id', $crewId)?->name ?? 'Plantel desconocido';
                    $crewHours = $totalHoursByCrew[$crewId] ?? 0;
                    $crewCost = $totalCostByCrew[$crewId] ?? 0;
                @endphp

                <h4 class="mt-4">{{ $crewName }}</h4>
                <p class="text-muted mb-1">
                    Horas trabajadas en este plantel: <strong>{{ number_format($crewHours, 1) }}</strong>
                </p>
                <p class="text-muted mb-1">
                    Costo total en este plantel: <strong>${{ number_format($crewCost, 2) }}</strong>
                </p>

                <ul>
                    @foreach ($staffList->sortByDesc('isRoster') as $staff)
                        <li>
                            {{ $staff->name }} {{ $staff->surnames }}

                            @php
                                $collapseId = 'adjustments-' . $crewId . '-' . $staff->id;

                                $hoursForThisCrew = $assignments
                                    ->where('crew_id', $crewId)
                                    ->where('staff_id', $staff->id)
                                    ->sum('hours');

                                $baseCost = $staff->isRoster
                                    ? $staff->cost * $periodDays
                                    : $staff->cost * $hoursForThisCrew;

                                // Recalcular los ajustes para este trabajador en el plantel ($crewId) actual,
                                // en vez de usar $staff->filtered_adjustments que pudo haberse guardado de otro plantel.
                                $adjustmentsForCrew = $staff
                                    ->adjustments()
                                    ->where('year', request('year', now()->year))
                                    ->where('month', request('month', now()->month))
                                    ->where('period', request('period', '8-22'))
                                    ->where('crew_id', $crewId)
                                    ->with('adjustmentDefinition')
                                    ->get();

                                $adjSum = 0;
                                foreach ($adjustmentsForCrew as $adj) {
                                    $adjSum +=
                                        $adj->adjustmentDefinition->type === 'perception'
                                            ? $adj->amount
                                            : -$adj->amount;
                                }

                                $totalWithAdjustments = $baseCost + $adjSum;
                            @endphp

                            @if ($staff->isRoster)
                                <span class="text-muted">— {{ $periodDays }} días</span>
                            @else
                                <span class="text-muted">— {{ number_format($hoursForThisCrew, 2) }} horas</span>
                            @endif

                            <span class="text-muted">| ${{ number_format($baseCost, 2) }}</span>

                            @if ($adjustmentsForCrew->count())
                                <span class="text-muted"> → Total:
                                    <strong>${{ number_format($totalWithAdjustments, 2) }}</strong></span>
                            @endif

                            <button type="button" class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="collapse"
                                data-bs-target="#{{ $collapseId }}">
                                Ajustes
                            </button>

                            <div class="collapse mt-2" id="{{ $collapseId }}">
                                <form action="{{ route('admin.staff.adjustments.store') }}" method="POST"
                                    class="d-flex mb-2 mt-2">
                                    @csrf
                                    <input type="hidden" name="staff_id" value="{{ $staff->id }}">
                                    <input type="hidden" name="year" value="{{ request('year', now()->year) }}">
                                    <input type="hidden" name="month" value="{{ request('month', now()->month) }}">
                                    <input type="hidden" name="period" value="{{ request('period', '8-22') }}">
                                    <input type="hidden" name="crew_id" value="{{ $crewId }}">
                                    <select name="definition_id" class="form-control me-2">
                                        @foreach ($adjustmentDefinitions as $definition)
                                            <option value="{{ $definition->id }}">
                                                {{ $definition->type === 'perception' ? '🟢' : '🔴' }}
                                                {{ $definition->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="amount" class="form-control me-2" step="0.01"
                                        placeholder="Importe" required>
                                    <button type="submit" class="btn btn-success">Agregar</button>
                                </form>
                                <hr>
                                <div class="mx-auto" style="max-width: 600px;">
                                    <ul class="list-group">
                                        @foreach ($adjustmentsForCrew as $adj)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div class="d-flex w-100 justify-content-between align-items-center">
                                                    <span>
                                                        {{ $adj->adjustmentDefinition->type === 'perception' ? '🟢' : '🔴' }}
                                                        {{ $adj->adjustmentDefinition->name }}
                                                    </span>
                                                    <span class="text-end ms-auto">
                                                        {{ $adj->adjustmentDefinition->type === 'deduction' ? '-' : '' }}${{ number_format($adj->amount, 2) }}
                                                    </span>
                                                </div>
                                                <form action="{{ route('admin.staff.adjustments.destroy', $adj->id) }}"
                                                    method="POST" class="ms-2 d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger">Eliminar</button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endforeach

            <div class="alert alert-info mt-4">
                Total de horas trabajadas en el periodo seleccionado:
                <strong>{{ number_format($totalHours, 1) }}</strong><br>
                Costo total de nómina en el periodo seleccionado:
                <strong>${{ number_format($adjustedTotalCost, 2) }}</strong>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection
