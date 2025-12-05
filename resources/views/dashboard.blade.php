@extends('layout.mainLayout')
@section('title', 'dashboard')
@section('content')
    <div class="container">
        <div class="row d-flex align-items-center justify-content-center text-center mt-content">
            @if (in_array(Auth::user()->role_id, [1, 2, 6]))
                <div class="col-md-4 d-flex justify-content-center mb-5">
                    <a href="{{ route('adminFunctions') }}">
                        <div class="card align-content-center cc">
                            <img class="menu_icon  d-block mx-auto" src="{{ asset('assets/img/administrator.png') }}">
                            <div class="card-body">
                                <h6 class="card-title">Funciones de administrador</h6>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
            <div class="col-md-4 d-flex justify-content-center mb-5">
                <a href="{{ route('system.main') }}">
                    <div class="card align-content-center cc">
                        <img class="menu_icon  d-block mx-auto" src="{{ asset('assets/img/school.webp') }}">
                        <div class="card-body">
                            <h6 class="card-title">Sistema de gestión escolar</h6>
                        </div>
                    </div>
                </a>
            </div>
            @if (in_array(Auth::user()->role_id, [1, 6]))
            <div class="col-md-4 d-flex justify-content-center mb-5">
                <a href="{{ route('web.menu') }}">
                    <div class="card align-content-center cc">
                        <img class="menu_icon  d-block mx-auto" src="{{ asset('assets/img/web.png') }}">
                        <div class="card-body">
                            <h6 class="card-title">Gestión datos web</h6>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            <div class="col-md-4 d-flex justify-content-center mb-5">
                <a href="{{ route('tickets.list') }}">
                    <div class="card align-content-center cc">
                        <img class="menu_icon  d-block mx-auto" src="{{ asset('assets/img/ticket.png') }}">
                        <div class="card-body">
                            <h6 class="card-title">Tickets de servicio</h6>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
