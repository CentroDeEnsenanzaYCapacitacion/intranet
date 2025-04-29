@extends('layout.mainLayout')
@section('title','Menú catálogos')
@section('content')
<div class="row d-flex align-items-center justify-content-center text-center mt-content">
    @if (in_array(Auth::user()->role_id, [1, 2]))
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
        <a id='adminAmounts' href="{{route('admin.catalogues.amounts.show')}}">
            <div class="card align-content-center cc">
                <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/prices.png')}}">
                <div class="card-body">
                    <h6 class="card-title">Costos</h6>
                </div>
            </div>
        </a>
    </div>
    @endif
    @if (in_array(Auth::user()->role_id, [1]))
    <div class="col-md-4 d-flex justify-content-center">
        <a id='adminAmounts' href="{{route('admin.catalogues.perceptions.show')}}">
            <div class="card align-content-center cc">
                <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/plusminus.png')}}">
                <div class="card-body">
                    <h6 class="card-title">Percepciones y deducciones</h6>
                </div>
            </div>
        </a>
    </div>
    @endif
</div>
<script>
    document.getElementById('adminCourses').addEventListener('click', function() {
        showLoader(true);
    });
    document.getElementById('adminAmounts').addEventListener('click', function() {
        showLoader(true);
    });
</script>
