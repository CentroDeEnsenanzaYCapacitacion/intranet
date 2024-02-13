@extends('layout.mainLayout')
@section('title','Nuevo curso')
@section('content')
<div class="row d-flex text-center mt-content">
    <div class="col">
        <h1>Modificar Curso</h1>
    </div>
</div>
<form action="{{ route('admin.catalogues.courses.update',['id'=>$course->id]) }}" method="post">
    @csrf
    @method('PUT')
    <div class="row d-flex text-center mt-5">
        <div class="col">
            <div class="form-group">
                <label for="namer"><b>Nombre(s):</b></label>
                <input type="text" class="form-control text-uppercase" placeholder="Nombre(s)" name="name" value='{{ $course->name }}'>
                <span class="error-message" id="name-error"></span>
            </div>
        </div>
        <div class="form-group">
            <label for="crew_id"><b>Plantel</b></label>
            <select class="form-control text-uppercase" name="crew_id" id="crew_id">
                @foreach($crews as $crew)
                    <option value="{{$crew->id}}" @if($course->crew_id == $crew->id) selected @endif>{{ $crew->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row d-flex text-center mt-5">
        <div class="col">
            <button id="saveCourse" type="submit" class="btn bg-orange text-white w-25">Modificar Curso</button><br><br>
            <a href="{{ route('admin.catalogues.courses.show') }}"><button type="button" class="btn btn-outline-orange text-white w-25">Volver</button></a>
        </div>
    </div>
</form><br>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection