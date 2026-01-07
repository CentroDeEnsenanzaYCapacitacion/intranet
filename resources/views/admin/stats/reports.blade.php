@extends('layout.mainLayout')
@section('title','Estadísticas de informes')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/stats.css') }}">
@endpush

@section('content')

<div class="dashboard-welcome">
    <h1 class="dashboard-title">Estadísticas de Informes</h1>
    <p class="dashboard-subtitle">Análisis de informes e inscripciones por período</p>
</div>

<div class="modern-card" style="margin-bottom: 24px;">
    <div class="card-header-modern">
        <div class="header-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 17V7M15 17V13M21 21H3M21 3V21M3 3V21M7 3H17C17.5304 3 18.0391 3.21071 18.4142 3.58579C18.7893 3.96086 19 4.46957 19 5V19C19 19.5304 18.7893 20.0391 18.4142 20.4142C18.0391 20.7893 17.5304 21 17 21H7C6.46957 21 5.96086 20.7893 5.58579 20.4142C5.21071 20.0391 5 19.5304 5 19V5C5 4.46957 5.21071 3.96086 5.58579 3.58579C5.96086 3.21071 6.46957 3 7 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h2>Seleccionar Período</h2>
        </div>
    </div>

    <div style="padding: 24px;">
        <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; margin-bottom: 20px;">
            <button onclick="setPeriod('mensual')" data-period-btn="mensual" class="btn-modern @if ($period == 'mensual') btn-primary @else btn-secondary @endif">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 2V5M16 2V5M3.5 9.09H20.5M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Mensual
            </button>

            <button onclick="setPeriod('semestral')" data-period-btn="semestral" class="btn-modern @if ($period == 'semestral') btn-primary @else btn-secondary @endif">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 2V5M16 2V5M3.5 9.09H20.5M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Semestral
            </button>

            <button onclick="setPeriod('anual')" data-period-btn="anual" class="btn-modern @if ($period == 'anual') btn-primary @else btn-secondary @endif">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 2V5M16 2V5M3.5 9.09H20.5M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Anual
            </button>

            <button onclick="setPeriod('custom')" data-period-btn="custom" class="btn-modern @if (!empty($startDate) && !empty($endDate)) btn-primary @else btn-secondary @endif">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 2V5M16 2V5M3.5 9.09H20.5M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Rango personalizado
            </button>
        </div>

        <div id="filterInputs" style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; align-items: end;">
            <div id="yearGroup" style="display: flex; flex-direction: column; gap: 6px;">
                <label style="font-weight: 600; color: #374151; font-size: 14px;">Año</label>
                <select class="form-control" id="yearSelector" style="min-width: 120px; padding: 10px 16px; font-weight: 600; border: 2px solid #e5e7eb; border-radius: 8px;">
                    @foreach($availableYears as $availableYear)
                        <option value="{{ $availableYear }}" {{ $availableYear == $year ? 'selected' : '' }}>
                            {{ $availableYear }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="monthGroup" style="display: {{ $period == 'mensual' ? 'flex' : 'none' }}; flex-direction: column; gap: 6px;">
                <label style="font-weight: 600; color: #374151; font-size: 14px;">Mes</label>
                <select class="form-control" id="monthSelector" style="min-width: 150px; padding: 10px 16px; font-weight: 600; border: 2px solid #e5e7eb; border-radius: 8px;">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ ($month == $m || (!$month && $m == Carbon\Carbon::now()->month)) ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create()->month($m)->locale('es')->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div id="semesterGroup" style="display: {{ $period == 'semestral' ? 'flex' : 'none' }}; flex-direction: column; gap: 6px;">
                <label style="font-weight: 600; color: #374151; font-size: 14px;">Semestre</label>
                <select class="form-control" id="semesterSelector" style="min-width: 150px; padding: 10px 16px; font-weight: 600; border: 2px solid #e5e7eb; border-radius: 8px;">
                    <option value="1" {{ (!$month || $month == 1) ? 'selected' : '' }}>Ene - Jun</option>
                    <option value="7" {{ $month == 7 ? 'selected' : '' }}>Jul - Dic</option>
                </select>
            </div>

            <div id="customRangeGroup" style="display: {{ !empty($startDate) && !empty($endDate) ? 'flex' : 'none' }}; gap: 12px; align-items: end;">
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-weight: 600; color: #374151; font-size: 14px;">Fecha inicio</label>
                    <input type="date" id="startDate" class="form-control" value="{{ $startDate }}" style="padding: 10px 16px; border: 2px solid #e5e7eb; border-radius: 8px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-weight: 600; color: #374151; font-size: 14px;">Fecha fin</label>
                    <input type="date" id="endDate" class="form-control" value="{{ $endDate }}" style="padding: 10px 16px; border: 2px solid #e5e7eb; border-radius: 8px;">
                </div>
            </div>

            <button type="button" class="btn-modern btn-primary" onclick="applyFilters()" style="white-space: nowrap; align-self: end;">
                Aplicar
            </button>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 24px;">
    <div class="modern-card" style="background: linear-gradient(135deg, #F57F17 0%, #F9A825 100%);">
        <div style="padding: 32px; text-align: center;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: rgba(255,255,255,0.9); margin-bottom: 16px;">
                <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15M9 5C9 6.10457 9.89543 7 11 7H13C14.1046 7 15 6.10457 15 5M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5M12 12H15M12 16H15M9 12H9.01M9 16H9.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3 style="margin: 0 0 8px; color: white; font-size: 16px; font-weight: 600; opacity: 0.9;">Total de Informes</h3>
            <p style="margin: 0; color: white; font-size: 40px; font-weight: 700;">{{ number_format($totalReports) }}</p>
            @if($reportsDiff != 0 && !$startDate && !$endDate)
                <div style="margin-top: 12px; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(255,255,255,0.2); border-radius: 20px;">
                    @if($reportsDiff > 0)
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: white;">
                            <path d="M12 19V5M5 12L12 5L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @else
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: white;">
                            <path d="M12 5V19M5 12L12 19L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @endif
                    <span style="color: white; font-size: 13px; font-weight: 600;">{{ abs($reportsDiff) }}% vs {{ $year - 1 }}</span>
                </div>
            @endif
        </div>
    </div>

    <div class="modern-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
        <div style="padding: 32px; text-align: center;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: rgba(255,255,255,0.9); margin-bottom: 16px;">
                <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3 style="margin: 0 0 8px; color: white; font-size: 16px; font-weight: 600; opacity: 0.9;">Inscritos</h3>
            <p style="margin: 0; color: white; font-size: 40px; font-weight: 700;">{{ number_format($totalEnrolled) }}</p>
            @if($enrolledDiff != 0 && !$startDate && !$endDate)
                <div style="margin-top: 12px; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(255,255,255,0.2); border-radius: 20px;">
                    @if($enrolledDiff > 0)
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: white;">
                            <path d="M12 19V5M5 12L12 5L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @else
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: white;">
                            <path d="M12 5V19M5 12L12 19L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @endif
                    <span style="color: white; font-size: 13px; font-weight: 600;">{{ abs($enrolledDiff) }}% vs {{ $year - 1 }}</span>
                </div>
            @endif
        </div>
    </div>

    <div class="modern-card" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
        <div style="padding: 32px; text-align: center;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: rgba(255,255,255,0.9); margin-bottom: 16px;">
                <path d="M16 8V16M12 11V16M8 14V16M6 20H18C18.5304 20 19.0391 19.7893 19.4142 19.4142C19.7893 19.0391 20 18.5304 20 18V6C20 5.46957 19.7893 4.96086 19.4142 4.58579C19.0391 4.21071 18.5304 4 18 4H6C5.46957 4 4.96086 4.21071 4.58579 4.58579C4.21071 4.96086 4 5.46957 4 6V18C4 18.5304 4.21071 19.0391 4.58579 19.4142C4.96086 19.7893 5.46957 20 6 20Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3 style="margin: 0 0 8px; color: white; font-size: 16px; font-weight: 600; opacity: 0.9;">Tasa de Conversión</h3>
            <p style="margin: 0; color: white; font-size: 40px; font-weight: 700;">{{ $overallConversion }}%</p>
            @if(isset($compareStats['conversion']) && $compareStats['conversion'] != $overallConversion && !$startDate && !$endDate)
                @php
                    $conversionDiff = round($overallConversion - $compareStats['conversion'], 2);
                @endphp
                <div style="margin-top: 12px; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(255,255,255,0.2); border-radius: 20px;">
                    @if($conversionDiff > 0)
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: white;">
                            <path d="M12 19V5M5 12L12 5L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @else
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: white;">
                            <path d="M12 5V19M5 12L12 19L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @endif
                    <span style="color: white; font-size: 13px; font-weight: 600;">{{ abs($conversionDiff) }}pp vs {{ $year - 1 }}</span>
                </div>
            @endif
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 24px;">
    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 3V16C3 17.1046 3.89543 18 5 18H21M7 14L12 9L16 13L21 8M21 8V12M21 8H17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Evolución de Informes e Inscripciones</h2>
            </div>
        </div>

        <div style="padding: 24px;">
            <canvas id="reportsChart" style="max-height: 400px;"></canvas>
        </div>
    </div>

    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M12 6V12L16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <h2>Tasa de Conversión</h2>
            </div>
        </div>

        <div style="padding: 24px;">
            <canvas id="conversionChart" style="max-height: 400px;"></canvas>
        </div>
    </div>
</div>

<div class="modern-card">
    <div class="card-header-modern">
        <div class="header-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21M23 21V19C22.9993 18.1137 22.7044 17.2528 22.1614 16.5523C21.6184 15.8519 20.8581 15.3516 20 15.13M16 3.13C16.8604 3.35031 17.623 3.85071 18.1676 4.55232C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89318 18.7122 8.75608 18.1676 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88M13 7C13 9.20914 11.2091 11 9 11C6.79086 11 5 9.20914 5 7C5 4.79086 6.79086 3 9 3C11.2091 3 13 4.79086 13 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h2>Top 10 Usuarios por Informes</h2>
        </div>
    </div>

    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th class="text-center">Posición</th>
                    <th>Usuario</th>
                    <th class="text-center">Total Informes</th>
                    <th class="text-center">Inscritos</th>
                    <th class="text-center">Tasa de Conversión</th>
                    <th class="text-center">Desempeño</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($userStats as $index => $user)
                    @php
                        $userConversion = $user->reports_count > 0 ? round(($user->enrolled_reports_count / $user->reports_count) * 100, 2) : 0;
                        $performanceClass = $userConversion >= 70 ? 'bg-success text-white' : ($userConversion >= 50 ? 'bg-warning text-dark' : 'bg-danger text-white');
                    @endphp
                    <tr>
                        <td class="text-center">
                            @if($index == 0)
                                <span style="font-size: 28px;">🥇</span>
                            @elseif($index == 1)
                                <span style="font-size: 28px;">🥈</span>
                            @elseif($index == 2)
                                <span style="font-size: 28px;">🥉</span>
                            @else
                                <strong>{{ $index + 1 }}</strong>
                            @endif
                        </td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td class="text-center">{{ number_format($user->reports_count) }}</td>
                        <td class="text-center">{{ number_format($user->enrolled_reports_count) }}</td>
                        <td class="text-center"><strong>{{ $userConversion }}%</strong></td>
                        <td class="text-center">
                            <span class="badge {{ $performanceClass }}">
                                @if($userConversion >= 70) Excelente
                                @elseif($userConversion >= 50) Bueno
                                @else Mejorable
                                @endif
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 0;">
                            <div style="text-align: center; padding: 60px 40px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 50%); border-radius: 12px; margin: 20px;">
                                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #92400e; margin-bottom: 16px;">
                                    <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                                </svg>
                                <h3 style="margin: 0 0 8px; color: #78350f; font-size: 20px; font-weight: 700;">No hay datos disponibles</h3>
                                <p style="margin: 0; color: #92400e; font-size: 14px;">No se encontraron informes registrados para este período.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
window.statsReportsConfig = {
    baseUrl: '{!! route('admin.stats.reports', ['period' => $period, 'year' => $year]) !!}',
    baseRoute: '{!! url('/admin/stats/reports') !!}',
    period: '{{ $period }}',
    year: {{ $year }},
    month: {{ $month ?? 'null' }},
    startDate: {{ $startDate ? "'".$startDate."'" : 'null' }},
    endDate: {{ $endDate ? "'".$endDate."'" : 'null' }},
    stats: @json($stats),
    compareStats: @json($compareStats ?? []),
    totalReports: {{ $totalReports }},
    totalEnrolled: {{ $totalEnrolled }}
};
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/js/stats_reports.js') }}"></script>

@endsection
