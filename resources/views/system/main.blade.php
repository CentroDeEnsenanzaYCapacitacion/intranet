@extends('layout.mainLayout')
@section('title','dashboard')
@section('content')
<div class="row d-flex align-items-center justify-content-center text-center mt-content">
    <div class="col-md-4 d-flex justify-content-center">
        <a id='reports' href="{{route('system.reports.show')}}">
            <div class="card align-content-center cc">
                <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/preins.png')}}">
                <div class="card-body">
                    <h6 class="card-title">Informes</h6>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4 d-flex justify-content-center">
        <a id='profiles' href="{{route('system.students.search')}}">
            <div class="card align-content-center cc">
                <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/ins.png')}}">
                <div class="card-body">
                    <h6 class="card-title">Expedientes de estudiantes</h6>
                </div>
            </div>
        </a>
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
    document.getElementById('profiles').addEventListener('click', function() {
        showLoader(true);
    });
</script>
@endsection