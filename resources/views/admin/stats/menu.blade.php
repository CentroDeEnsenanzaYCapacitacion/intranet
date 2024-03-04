@extends('layout.mainLayout')
@section('title','Menú estadísticas')
@section('content')
<div class="row d-flex align-items-center justify-content-center text-center mt-content">
    <div class="col-md-4 d-flex justify-content-center">
        <a href="{{route('admin.stats.reports',['period'=>'mensual'])}}">
            <div class="card align-content-center cc">
                <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/reports_stats.png')}}">
                <div class="card-body">
                    <h6 class="card-title">Informes</h6>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection