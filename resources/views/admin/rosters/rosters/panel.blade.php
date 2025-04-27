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
                                @foreach ($crews as $crew)
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
                            <option value="8-22" {{ request('period', '8-22') == '8-22' ? 'selected' : '' }}>Del 8 al 22</option>
                            <option value="23-7" {{ request('period') == '23-7' ? 'selected' : '' }}>Del 23 al 7</option>
                        </select>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn bg-orange text-white">Calcular</button>
                </div>
            </form>

            @foreach ($staffGrouped as $crewId => $staffList)
                @php
                    $crewName = $crews->firstWhere('id', $crewId)?->name ?? 'Plantel desconocido';
                    $crewHours = $totalHoursByCrew[$crewId] ?? 0;
                @endphp

                <h4 class="mt-4">{{ $crewName }}</h4>
                <p class="text-muted mb-1">Horas trabajadas en este plantel:
                    <strong>{{ number_format($crewHours, 1) }}</strong></p>

                <ul>
                    @forelse ($staffList as $staff)
                        <li>
                            {{ $staff->name }} {{ $staff->surnames }}
                            @php
                                $hoursForThisCrew = $assignments
                                    ->where('crew_id', $crewId)
                                    ->where('staff_id', $staff->id)
                                    ->sum('hours');
                            @endphp

                            @if ($staff->isRoster)
                                <span class="text-muted">
                                    — {{ $periodDays }} días
                                </span>
                            @else
                                <span class="text-muted">
                                    — {{ number_format($hoursForThisCrew, 2) }} horas
                                </span>
                            @endif
                        </li>
                    @empty
                        <li class="text-muted">No hay trabajadores en este plantel.</li>
                    @endforelse
                </ul>
            @endforeach

            @if (!is_null($totalHours))
                <div class="alert alert-info mt-4">
                    Total de horas trabajadas en el periodo seleccionado:
                    <strong>{{ number_format($totalHours, 1) }}</strong>
                </div>
            @endif

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
