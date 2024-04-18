@extends('layout.mainLayout')
@section('title', 'Informes')
@section('content')
    <div class="card shadow ccont pb-3">
        <div class="card-body">
            <div class="row d-flex text-center mt-3">
                <div class="col">
                    <h1>Foto de prfil de estudiante</h1>
                </div>
            </div>
            <div class="row d-flex text-center mt-5">
                <div class="col">
                    <form action="{{ route('system.student.upload-profile-image', ['student_id' => $student_id]) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="image">Seleccione imagen:</label>
                        <input type="file" name="image" id="image">
                        <button type="submit">Subir Imagen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        <img src="{{ route('system.student.image', ['student_id' => $student_id]) }}" alt="Profile Image">
    @endif

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
