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
<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <div class="text-center">
                    <div class="btn-group" role="group" aria-label="button group">
                        <button onclick="window.location.href='{{ route('admin.stats.reports', ['period' => 'mensual']) }}'" class="btn @if ($period == 'mensual') btn-outline-orange-selected @else btn-outline-orange @endif">Mensual</button>

                        <button onclick="window.location.href='{{ route('admin.stats.reports', ['period' => 'semestral']) }}'" class="btn @if ($period == 'semestral') btn-outline-orange-selected @else btn-outline-orange @endif">Semestral</button>

                        <button onclick="window.location.href='{{ route('admin.stats.reports', ['period' => 'anual']) }}'" class="btn @if ($period == 'anual') btn-outline-orange-selected @else btn-outline-orange @endif">Anual</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="myChart" style="width:100%; height:500px;"></div>
    </div>
</div>
@endsection