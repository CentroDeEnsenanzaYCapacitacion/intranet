@extends('layout.mainLayout')
@section('title','dashboard')
@section('content')
<div class="row d-flex text-center mt-content">
    <div class="col">
        <a  href="{{route('adminFunctions')}}"><img class="menu_icon" src="{{asset('assets/img/administrator.png')}}"></a><br>
        Funciones de administrador
    </div>
    <div class="col">
        <a href="../system/pral.php"><img class="menu_icon" src="{{asset('assets/img/school.webp')}}"></a><br>
        Sistema de gestión escolar
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
@endsection