@extends('layout.mainLayout')
@section('title', 'Informes')
@section('content')
    <div class="card shadow ccont pb-3">
        <div class="card-body">
            <div class="row d-flex text-center mt-3">
                <div class="col">
                    <h1>Foto de perfil de estudiante</h1>
                </div>
            </div>

            <div class="row d-flex text-center mt-5">
                <div class="col">
                    <form action="{{ route('system.student.upload-profile-image', ['student_id' => $student_id]) }}"
                        method="POST" enctype="multipart/form-data" class="d-flex flex-column align-items-center">
                        @csrf
                        <label for="image" class="form-label">Seleccione imagen:</label>
                        <input class="form-control w-50 mb-2" type="file" name="image" id="image">
                        <img id="imagePreview" style="width: 300px; margin-top: 10px; display: none;">
                        <button class="btn bg-orange text-white mt-3" type="submit">Subir Imagen</button>
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
@push('scripts')
<script src="{{ asset('assets/js/image_preview.js') }}"></script>
@endpush
