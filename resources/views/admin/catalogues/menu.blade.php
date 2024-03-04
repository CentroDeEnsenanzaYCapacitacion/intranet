@extends('layout.mainLayout')
@section('title','Menú catálogos')
@section('content')
<div class="row d-flex align-items-center justify-content-center text-center mt-content">
    <div class="col-md-4 d-flex justify-content-center">
        <a id='adminCourses' href="{{route('admin.catalogues.courses.show')}}">
            <div class="card align-content-center cc">
                <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/courses.png')}}">
                <div class="card-body">
                    <h6 class="card-title">Cursos</h6>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4 d-flex justify-content-center">
        <a id='adminAmounts' href="#">
            <div class="card align-content-center cc">
                <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/prices.png')}}">
                <div class="card-body">
                    <h6 class="card-title">Costos</h6>
                </div>
            </div>
        </a>
    </div>
    <!--<div class="col">
        <a><img src="/assets/img/avatar.png"></a><br>
        Gestión de datos académicos
    </div>
    <div class="col">
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
<script>
    document.getElementById('adminCourses').addEventListener('click', function() {
        showLoader(true);
    });
    document.getElementById('adminAmounts').addEventListener('click', function() {
        showLoader(true);
    });
</script>