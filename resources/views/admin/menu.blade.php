@extends('layout.mainLayout')
@section('title','Funciones de administrador')
@section('content')
<div class="container">
    <div class="row d-flex text-center mt-content">
        <div class="col">
            <a id="adminUsers" href=" {{route('admin.users.show') }}"><img class="menu_icon" src="{{asset('img/users.png')}}"></a><br>
            Gestión de usuarios
        </div>
        <div class="col">
            <a id="adminUsers" href=" {{route('admin.catalogues.menu') }}"><img class="menu_icon" src="{{asset('img/catalogues.png')}}"></a><br>
            Catálogos
        </div>
        <div class="col">
            <a href="../admin/statistics.php"><img class="menu_icon" src="{{asset('img/graphs.png')}}"></a><br>
            Estadísticas
        </div>
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
    <script>
        document.getElementById('adminUsers').addEventListener('click', function() {
            showLoader(true);
        });
        document.getElementById('cataloguesMenu').addEventListener('click', function() {
            showLoader(true);
        });
    </script>
@endsection