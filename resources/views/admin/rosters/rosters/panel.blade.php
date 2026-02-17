@php
    $userCrewId = auth()->user()->crew_id ?? null;
@endphp

@extends('layout.mainLayout')
@section('title', 'Cálculo de nómina')
@section('content')

    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Cálculo de Nómina</h1>
        <p class="dashboard-subtitle">Gestión y cálculo de nómina por periodo</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="background: #fee2e2; border: 1px solid #991b1b; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <ul class="mb-0">
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
                    <path d="M9 5H7C6.46957 5 5.96086 5.21071 5.58579 5.58579C5.21071 5.96086 5 6.46957 5 7V19C5 19.5304 5.21071 20.0391 5.58579 20.4142C5.96086 20.7893 6.46957 21 7 21H17C17.5304 21 18.0391 20.7893 18.4142 20.4142C18.7893 20.0391 19 19.5304 19 19V7C19 6.46957 18.7893 5.96086 18.4142 5.58579C18.0391 5.21071 17.5304 5 17 5H15M9 5C9 5.53043 9.21071 6.03914 9.58579 6.41421C9.96086 6.78929 10.4696 7 11 7H13C13.5304 7 14.0391 6.78929 14.4142 6.41421C14.7893 6.03914 15 5.53043 15 5M9 5C9 4.46957 9.21071 3.96086 9.58579 3.58579C9.96086 3.21071 10.4696 3 11 3H13C13.5304 3 14.0391 3.21071 14.4142 3.58579C14.7893 3.96086 15 4.46957 15 5M12 12H15M12 16H15M9 12H9.01M9 16H9.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Filtros de Búsqueda</h2>
            </div>
        </div>

        <div style="padding: 24px;">
            <form method="GET">
                <div class="row">
                    @if ($userCrewId == 1)
                        <div class="col-md-12 mb-3">
                            <label for="crew" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Plantel</label>
                            <select class="form-control" name="crew" id="crew">
                                <option value="all" {{ request('crew') == 'all' ? 'selected' : '' }}>Todos los planteles</option>
                                @foreach ($allCrews as $crew)
                                    <option value="{{ $crew->id }}" {{ request('crew') == $crew->id ? 'selected' : '' }}>
                                        {{ $crew->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-md-4 mb-3">
                        <label for="year" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Año</label>
                        <select class="form-control" name="year" id="year">
                            @for ($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ request('year', now()->year) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="month" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Mes</label>
                        <select class="form-control" name="month" id="month">
                            @foreach ([1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'] as $num => $name)
                                <option value="{{ $num }}" {{ request('month', now()->month) == $num ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="period" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Periodo</label>
                        <select class="form-control" name="period" id="period">
                            <option value="8-22" {{ request('period', '8-22') == '8-22' ? 'selected' : '' }}>Del 8 al 22</option>
                            <option value="23-7" {{ request('period') == '23-7' ? 'selected' : '' }}>Del 23 al 7</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; justify-content: center; margin-top: 16px;">
                    <button type="submit" class="btn-modern btn-primary" style="min-width: 200px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Calcular
                    </button>
                </div>
            </form>
        </div>
    </div>

    @foreach ($staffGrouped as $crewId => $staffList)
        @php
            $crewName = $allCrews->firstWhere('id', $crewId)?->name ?? 'Plantel desconocido';
            $crewHours = $totalHoursByCrew[$crewId] ?? 0;
            $crewCost = $totalCostByCrew[$crewId] ?? 0;
        @endphp

        <div class="modern-card" style="margin-top: 24px;">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 21V5C19 4.46957 18.7893 3.96086 18.4142 3.58579C18.0391 3.21071 17.5304 3 17 3H7C6.46957 3 5.96086 3.21071 5.58579 3.58579C5.21071 3.96086 5 4.46957 5 5V21M19 21H21M19 21H14M5 21H3M5 21H10M10 21V17C10 16.4696 10.2107 15.9609 10.5858 15.5858C10.9609 15.2107 11.4696 15 12 15C12.5304 15 13.0391 15.2107 13.4142 15.5858C13.7893 15.9609 14 16.4696 14 17V21M10 21H14M9 9H10M14 9H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>{{ $crewName }}</h2>
                </div>
                <div style="display: flex; gap: 16px; align-items: center;">
                    <div style="text-align: right;">
                        <div style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Horas</div>
                        <div style="font-size: 18px; font-weight: 700; color: #1a1a1a;">{{ number_format($crewHours, 1) }}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Costo Total</div>
                        <div style="font-size: 18px; font-weight: 700; color: #F57F17;">${{ number_format($crewCost, 2) }}</div>
                    </div>
                </div>
            </div>

            <div style="padding: 24px;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach ($staffList->sortByDesc(fn($s) => $allDepartmentCosts->get($s->id, collect())->contains('is_roster', true)) as $staff)
                        @php
                            $collapseId = 'adjustments-' . $crewId . '-' . $staff->id;
                            $staffDeptCosts = $allDepartmentCosts->get($staff->id, collect());
                            $hasRoster = $staffDeptCosts->contains('is_roster', true);

                            $hoursForThisCrew = $assignments
                                ->where('crew_id', $crewId)
                                ->where('staff_id', $staff->id)
                                ->sum('hours');

                            $baseCost = $totalCostByStaff[$staff->id] ?? 0;

                            $adjustmentsForCrew = $staff->filtered_adjustments ?? collect();

                            $adjSum = 0;
                            foreach ($adjustmentsForCrew as $adj) {
                                $adjSum += $adj->adjustmentDefinition->type === 'perception' ? $adj->amount : -$adj->amount;
                            }

                            $totalWithAdjustments = $baseCost + $adjSum;

                            $staffAssignmentsByDept = $assignments
                                ->where('crew_id', $crewId)
                                ->where('staff_id', $staff->id)
                                ->groupBy('department_id');
                        @endphp

                        <li style="padding: 16px; margin-bottom: 8px; background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb;">
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                                <div style="flex: 1; min-width: 200px;">
                                    <span style="font-weight: 600; color: #1a1a1a; font-size: 14px;">{{ $staff->name }} {{ $staff->surnames }}</span>
                                    <div style="margin-top: 4px; font-size: 13px; color: #6b7280;">
                                        @if ($hasRoster)
                                            <span class="badge badge-primary" style="font-size: 10px; padding: 4px 10px;">PLANILLA</span>
                                            <span>{{ $periodDays }} días</span>
                                        @endif
                                        @if ($hoursForThisCrew > 0)
                                            <span class="badge badge-gray" style="font-size: 10px; padding: 4px 10px;">POR HORAS</span>
                                            <span>{{ number_format($hoursForThisCrew, 2) }} horas</span>
                                        @endif
                                    </div>
                                    @if ($staffDeptCosts->count() > 0)
                                        <div style="margin-top: 8px; font-size: 12px; color: #6b7280;">
                                            @foreach ($staffDeptCosts as $deptCost)
                                                @php
                                                    $dept = $departments->get($deptCost->department_id);
                                                    $deptAssignments = $staffAssignmentsByDept->get($deptCost->department_id, collect());
                                                    $deptHours = $deptAssignments->sum('hours');
                                                @endphp
                                                @if ($dept && ($deptCost->is_roster || $deptHours > 0))
                                                    <div style="display: inline-block; background: #e5e7eb; padding: 2px 8px; border-radius: 4px; margin-right: 4px; margin-bottom: 4px;">
                                                        {{ $dept->name }}:
                                                        @if ($deptCost->is_roster)
                                                            ${{ number_format($deptCost->cost, 2) }}/día
                                                        @else
                                                            {{ number_format($deptHours, 1) }}h × ${{ number_format($deptCost->cost, 2) }}
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <div style="text-align: right;">
                                        <div style="font-size: 12px; color: #6b7280;">Base</div>
                                        <div style="font-size: 16px; font-weight: 600; color: #1a1a1a;">${{ number_format($baseCost, 2) }}</div>
                                    </div>

                                    @if ($adjustmentsForCrew->count())
                                        <div style="text-align: right;">
                                            <div style="font-size: 12px; color: #6b7280;">Total</div>
                                            <div style="font-size: 16px; font-weight: 700; color: #F57F17;">${{ number_format($totalWithAdjustments, 2) }}</div>
                                        </div>
                                    @endif

                                    <button type="button" class="btn-modern" style="background: white; color: #6b7280; border: 1px solid #e5e7eb; padding: 8px 16px; font-size: 13px;" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                        Ajustes
                                    </button>
                                </div>
                            </div>

                            <div class="collapse mt-3" id="{{ $collapseId }}">
                                <div style="padding: 16px; background: white; border-radius: 8px; border: 1px solid #e5e7eb;">
                                    <form action="{{ route('admin.staff.adjustments.store') }}" method="POST" style="margin-bottom: 16px;" data-password-confirm>
                                        @csrf
                                        <input type="hidden" name="staff_id" value="{{ $staff->id }}">
                                        <input type="hidden" name="year" value="{{ request('year', now()->year) }}">
                                        <input type="hidden" name="month" value="{{ request('month', now()->month) }}">
                                        <input type="hidden" name="period" value="{{ request('period', '8-22') }}">
                                        <input type="hidden" name="crew_id" value="{{ $crewId }}">

                                        <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: stretch;">
                                            <select name="definition_id" class="form-control" style="flex: 1; min-width: 200px;">
                                                @foreach ($adjustmentDefinitions as $definition)
                                                    <option value="{{ $definition->id }}">
                                                        {{ $definition->type === 'perception' ? '🟢' : '🔴' }}
                                                        {{ $definition->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group" style="flex: 1; min-width: 150px;">
                                                <span class="input-group-text" style="background: linear-gradient(135deg, #F57F17 0%, #F9A825 100%); color: white; font-weight: 600; border: none; font-size: 16px;">$</span>
                                                <input type="number" name="amount" class="form-control" step="0.01" placeholder="Importe" required style="font-size: 14px; font-weight: 600;">
                                            </div>
                                            <button type="submit" class="btn-modern btn-primary" style="padding: 10px 24px;">Agregar</button>
                                        </div>
                                    </form>

                                    @if ($adjustmentsForCrew->count())
                                        <div style="border-top: 1px solid #e5e7eb; padding-top: 16px;">
                                            <h4 style="font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 12px;">Ajustes Aplicados</h4>
                                            <ul style="list-style: none; padding: 0; margin: 0;">
                                                @foreach ($adjustmentsForCrew as $adj)
                                                    <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f9fafb; border-radius: 6px; margin-bottom: 8px;">
                                                        <div style="flex: 1;">
                                                            <span style="font-size: 14px; color: #1a1a1a;">
                                                                {{ $adj->adjustmentDefinition->type === 'perception' ? '🟢' : '🔴' }}
                                                                {{ $adj->adjustmentDefinition->name }}
                                                            </span>
                                                        </div>
                                                        <div style="display: flex; align-items: center; gap: 12px;">
                                                            <span style="font-weight: 600; color: {{ $adj->adjustmentDefinition->type === 'perception' ? '#065f46' : '#991b1b' }};">
                                                                {{ $adj->adjustmentDefinition->type === 'deduction' ? '-' : '' }}${{ number_format($adj->amount, 2) }}
                                                            </span>
                                                            <form action="{{ route('admin.staff.adjustments.destroy', $adj->id) }}" method="POST" class="d-inline" data-password-confirm>
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="action-btn action-delete" title="Eliminar" style="width: 32px; height: 32px;">
                                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                        <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach

    <div class="modern-card" style="margin-top: 24px; background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%); border: 2px solid #0369a1;">
        <div style="padding: 24px; text-align: center;">
            <div style="display: flex; justify-content: center; gap: 48px; flex-wrap: wrap; margin-bottom: 16px;">
                <div>
                    <div style="font-size: 14px; color: #0369a1; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Total de Horas</div>
                    <div style="font-size: 32px; font-weight: 700; color: #0c4a6e;">{{ number_format($totalHours, 1) }}</div>
                </div>
                <div>
                    <div style="font-size: 14px; color: #0369a1; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Costo Total de Nómina</div>
                    <div style="font-size: 32px; font-weight: 700; color: #F57F17;">${{ number_format($adjustedTotalCost, 2) }}</div>
                </div>
            </div>
            <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
                <a href="{{ route('admin.rosters.payroll.fee-check', ['year' => request('year', now()->year), 'month' => request('month', now()->month), 'period' => request('period', '8-22'), 'crew' => request('crew')]) }}" target="_blank" class="btn-modern btn-primary" style="min-width: 260px; text-decoration: none;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15M9 5C9 6.10457 9.89543 7 11 7H13C14.1046 7 15 6.10457 15 5M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Cotejador de honorarios
                </a>
                <a href="{{ route('admin.rosters.payroll.report', ['year' => request('year', now()->year), 'month' => request('month', now()->month), 'period' => request('period', '8-22'), 'crew' => request('crew')]) }}" target="_blank" class="btn-modern btn-primary" style="min-width: 260px; text-decoration: none;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 9V2H18V9M6 18H4C3.46957 18 2.96086 17.7893 2.58579 17.4142C2.21071 17.0391 2 16.5304 2 16V11C2 10.4696 2.21071 9.96086 2.58579 9.58579C2.96086 9.21071 3.46957 9 4 9H20C20.5304 9 21.0391 9.21071 21.4142 9.58579C21.7893 9.96086 22 10.4696 22 11V16C22 16.5304 21.7893 17.0391 21.4142 17.4142C21.0391 17.7893 20.5304 18 20 18H18M6 14H18V22H6V14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Reporte de nómina
                </a>
            </div>
        </div>
    </div>

    @include('includes.password-confirm-modal')
@endsection
