@extends('layout.mainLayout')
@section('title', 'Sistema de gestión')
@section('content')
    <div class="row d-flex align-items-center justify-content-center text-center mt-content">
        @if (in_array(Auth::user()->role_id, [1, 4, 6]))
            <div class="col-md-4 d-flex justify-content-center">
                <a id='reports' href="{{ route('system.reports.show') }}">
                    <div class="card align-content-center cc">
                        <img class="menu_icon  d-block mx-auto" src="{{ asset('assets/img/preins.png') }}">
                        <div class="card-body">
                            <h6 class="card-title">Informes</h6>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if (in_array(Auth::user()->role_id, [1, 2, 3]))
            <div class="col-md-4 d-flex justify-content-center">
                <a id='profiles' href="{{ route('system.students.search') }}">
                    <div class="card align-content-center cc">
                        <img class="menu_icon  d-block mx-auto" src="{{ asset('assets/img/ins.png') }}">
                        <div class="card-body">
                            <h6 class="card-title">Expedientes de estudiantes</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 d-flex justify-content-center">
                <a id='profiles' href="{{ route('system.collection.menu') }}">
                    <div class="card align-content-center cc">
                        <img class="menu_icon  d-block mx-auto" src="{{ asset('assets/img/pay.webp') }}">
                        <div class="card-body">
                            <h6 class="card-title">Cobranza</h6>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if (in_array(Auth::user()->role_id, [1, 2]))
            <div class="col-md-4 mt-5 d-flex justify-content-center">
                <a id='profiles' href="{{ route('admin.schedule.calendar') }}">
                    <div class="card align-content-center cc">
                        <img class="menu_icon  d-block mx-auto" src="{{ asset('assets/img/calendar.png') }}">
                        <div class="card-body">
                            <h6 class="card-title">Calendarios</h6>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    </div>
    <script>
        document.getElementById('reports').addEventListener('click', function() {
            showLoader(true);
        });
        document.getElementById('profiles').addEventListener('click', function() {
            showLoader(true);
        });
    </script>
@endsection
