@extends('layout.mainLayout')
@section('title','Menú estadísticas')
@section('content')
<div class="row d-flex text-center mt-content">
    <div class="col">
        <a href="{{ route('admin.stats.reports',['period'=>'mensual']) }}"><img class="menu_icon" src="{{ asset('assets/img/reports_stats.png') }}"></a><br>
        Informes
    </div>
    <!-- <div class="col">
        <a href="../admin/adminCatalogues.php"><img src="../../assets/img/avatar.png"></a><br>
        Catálogos
    </div>
    <div class="col">
        <a href="../admin/statistics.php"><img src="../../assets/img/avatar.png"></a><br>
        Estadísticas
    </div> -->
    <!--<div class="col">
        <a><img src="/assets/img/avatar.png"></a><br>
        Gestión de biblioteca
    </div> -->
</div>
<!-- <div class="row d-flex text-center mt-5">
    <div class="col">
        <a><img src="/assets/img/avatar.png"></a><br>
        Sistema de gestión escolar
    </div>
    <div class="col">
        <a><img src="/assets/img/avatar.png"></a><br>
        Gestión de contenido web
    </div>
    <div class="col">
        <a><img src="/assets/img/avatar.png"></a><br>
        Gestión de datos académicos
    </div>
    <div class="col">
        <a><img src="/assets/img/avatar.png"></a><br>
        Gestión de biblioteca
    </div>
</div> -->
@endsection