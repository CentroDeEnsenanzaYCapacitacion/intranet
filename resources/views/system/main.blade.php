@extends('layout.mainLayout')
@section('title','dashboard')
@section('content')
<div class="row d-flex text-center mt-content">
    <div class="col">
        <a id="reports" href="{{ route('system.reports.show') }}"><img class="menu_icon" src="{{ asset('assets/img/preins.png') }}"></a><br>
        Informes
    </div>
    <div class="col">
        <a id="sign" href="inscription.php"><img class="menu_icon" src="{{ asset('assets/img/ins.png') }}"></a><br>
        Generar matrícula
    </div>
    <!-- <div class="col">
        <a><img class="menu_icon" src="../../assets/img/avatar.png"></a><br>
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

<script>
    document.getElementById('reports').addEventListener('click', function() {
        showLoader(true);
    });
</script>
@endsection