@extends('layout.mainLayout')
@section('title','Informes')
@section('content')
@if(session('success'))
    <div id="success" class="alert alert-success mt-content">
        {{ session('success') }}
    </div>
@endif
<div class="card shadow ccont pb-3">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>Buscar estudiantes</h1>
            </div>
        </div>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <form class="form" action="{{ route('system.students.search-post') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input name="data" type="text" class="form-control" placeholder="Buscar por nombre, apellidos y matrícula">
                            <button class="btn btn-outline-secondary" type="submit" onclick="showLoader(true)">
                                <span class="material-symbols-outlined">search</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row d-flex  mt-5">
            <div class="col">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Matrícula</th>
                            <th>Nombre</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($students)
                            @foreach($students as $student)
                                <tr>
                                    <td class="text-uppercase">{{ $student->crew->name[0].'/'.$student->id }}</td>
                                    <td class="text-uppercase">{{ $student->surnames.', '.$student->name }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="material-symbols-outlined" style="color:royalblue;">
                                                <a href="{{ route('system.student.profile',['student_id'=>$student->id]) }}" onclick="showLoader(true)" style="text-decoration: none; @if($student->first_time) color: red; @else color: inherit; @endif">visibility</a>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
