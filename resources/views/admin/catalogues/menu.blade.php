@extends('layout.mainLayout')
@section('title','Menú catálogos')
@section('content')
<div class="row d-flex text-center mt-content">
    <div class="col">
        <a id='adminCourses' href="{{ route('admin.catalogues.courses.show') }}"><img class="menu_icon" src="{{ asset('assets/img/courses.png') }}"></a><br>
        Cursos
    </div>
    <div class="col">
        <a id='adminAmounts' href="../admin/adminAmounts.php"><img class="menu_icon" src="{{ asset('assets/img/prices.png') }}"></a><br>
        Costos
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