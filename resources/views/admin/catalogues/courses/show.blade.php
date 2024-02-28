@extends('layout.mainLayout')
@section('title','Cursos registrados')
@section('content')
<div class="row d-flex text-center mt-content">
    <div class="col">
        <h1>Cursos registrados</h1>
    </div>
</div>
<div class="row d-flex text-center mt-5">
    <div class="col">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Plantel</th>
                    <th style="width: 10%;" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course) 
                <tr>
                    <td class="text-uppercase">{{ $course->name }}</td>
                    <td class="text-uppercase">{{ $course->crew->name }}</td>
                    <td class="d-flex justify-content-center">
                        <span class="material-symbols-outlined bg-edit"><a onclick="showLoader(true)" href="{{ route('admin.catalogues.courses.edit',['id' => $course->id]) }}">edit</a></span>
                        <form method="POST" action="{{ route('admin.catalogues.courses.delete',['id' => $course->id]) }}" id="delete-course-{{ $course->id }}">
                            @csrf
                            @method('DELETE')
                            <span class="material-symbols-outlined bg-red"><a onclick="confirmDelete('course',{{ $course->id }})">delete</a></span>
                        </form>
                    </td>     
                </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('admin.catalogues.courses.new') }}"  class="btn bg-orange text-white mt-5 mb-5">Nuevo Curso</a>
    </div>
</div>
@endsection