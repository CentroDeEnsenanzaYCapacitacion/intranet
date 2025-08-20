@extends('layout.mainLayout')
@section('title','Funciones de administrador')
@section('content')
<div class="container">
    <div class="row d-flex align-items-center justify-content-center text-center mt-content">
        @if (in_array(Auth::user()->role_id, [1,2]))
        <div class="col-md-4 d-flex justify-content-center mb-5">
            <a id="adminUsers" href="{{route('admin.users.show')}}">
                <div class="card align-content-center cc">
                    <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/users.png')}}">
                    <div class="card-body">
                        <h6 class="card-title">Gestión de usuarios</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 d-flex justify-content-center mb-5">
            <a id="adminUsers" href="{{route('admin.catalogues.menu')}}">
                <div class="card align-content-center cc">
                    <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/catalogues.png')}}">
                    <div class="card-body">
                        <h6 class="card-title">Catálogos</h6>
                    </div>
                </div>
            </a>
        </div>
        @endif
        @if (in_array(Auth::user()->role_id, [1, 2]))
        <div class="col-md-4 d-flex justify-content-center mb-5">
            <a id="adminUsers" id="adminUsers" href="{{route('admin.requests.show')}}">
                <div class="card align-content-center cc">
                    <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/request.png')}}">
                    <div class="card-body">
                        <h6 class="card-title">Gestión de solicitudes</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 d-flex justify-content-center mb-5">
            <a id="adminUsers" href="{{route('admin.rosters.menu')}}">
                <div class="card align-content-center cc">
                    <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/roster.png')}}">
                    <div class="card-body">
                        <h6 class="card-title">Gestión de nóminas</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 d-flex justify-content-center mb-5">
            <a href="{{route('admin.stats.menu')}}">
                <div class="card align-content-center cc">
                    <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/graphs.png')}}">
                    <div class="card-body">
                        <h6 class="card-title">Estadísticas</h6>
                    </div>
                </div>
            </a>
        </div>
        @endif
    </div>
    <script>
        document.getElementById('adminUsers').addEventListener('click', function() {
            showLoader(true);
        });
        document.getElementById('cataloguesMenu').addEventListener('click', function() {
            showLoader(true);
        });
    </script>
@endsection
