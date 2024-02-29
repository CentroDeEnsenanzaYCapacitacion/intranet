@extends('layout.mainLayout')
@section('title','Informes')
@section('content')
<div class="row d-flex text-center mt-content">
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
                    <button class="btn btn-outline-secondary" type="submit">
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
                </tr>
            </thead>
            <tbody>
                @if($students)
                    @foreach($students as $student)
                        <tr>
                            <td class="text-uppercase">{{ $student->crew->name[0].'/'.$student->id }}</td>
                            <td class="text-uppercase">{{ $student->surnames.', '.$student->name }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection