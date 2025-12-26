@extends('layout.mainLayout')
@section('title','Estadísticas de informes')
@section('content')
@php
    if ($period=='mensual') {
        $datos = [
            ['Month', 'Informes', 'Inscripciones'],
            ['Ene.',  1000,      400],
            ['Feb.',  1170,      460],
            ['Mar.',  660,       1120],
            ['Abr.',  1030,      540],
            ['May.',  1030,      540],
            ['Jun.',  1030,      540],
            ['Jul.',  1030,      540],
            ['Ago.',  1030,      540],
            ['Sep.',  1030,      540],
            ['Oct.',  1030,      540],
            ['Nov.',  1030,      540],
            ['Dic.',  1030,      540]
        ];
    }
    elseif($period=='semestral') {
        $datos = [
            ['Period', 'Informes', 'Inscripciones'],
            ['Ene-Jun',  1000,      400],
            ['Jul-Dic',  1170,      460]
        ];
    } elseif($period=='anual') {
        $datos = [
            ['Year', 'Informes', 'Inscripciones'],
            ['2004',  1000,      400],
            ['2005',  1170,      460],
            ['2006',  660,       1120],
            ['2007',  1030,      540]
        ];
    }
@endphp

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($datos); ?>);

    var options = {
      title: 'Informes e inscripciones',
      curveType: 'function',
      legend: { position: 'bottom' }
    };

    var chart = new google.visualization.LineChart(document.getElementById('myChart'));
    chart.draw(data, options);
  }
</script>

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
        <div style="display: flex; justify-content: center; gap: 12px;">
            <button onclick="window.location.href='{{ route('admin.stats.reports', ['period' => 'mensual']) }}'" class="btn-modern @if ($period == 'mensual') btn-primary @else btn-secondary @endif">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 2V5M16 2V5M3.5 9.09H20.5M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Mensual
            </button>

            <button onclick="window.location.href='{{ route('admin.stats.reports', ['period' => 'semestral']) }}'" class="btn-modern @if ($period == 'semestral') btn-primary @else btn-secondary @endif">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 2V5M16 2V5M3.5 9.09H20.5M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Semestral
            </button>

            <button onclick="window.location.href='{{ route('admin.stats.reports', ['period' => 'anual']) }}'" class="btn-modern @if ($period == 'anual') btn-primary @else btn-secondary @endif">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 2V5M16 2V5M3.5 9.09H20.5M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Anual
            </button>
        </div>
    </div>
</div>

<div class="modern-card">
    <div class="card-header-modern">
        <div class="header-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 3V16C3 17.1046 3.89543 18 5 18H21M7 14L12 9L16 13L21 8M21 8V12M21 8H17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h2>Gráfica de Informes e Inscripciones</h2>
        </div>
    </div>

    <div style="padding: 24px;">
        <div id="myChart" style="width:100%; height:500px;"></div>
    </div>
</div>

@endsection