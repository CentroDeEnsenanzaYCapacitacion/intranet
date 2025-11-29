@extends('layout.mainLayout')
@section('title', 'dashboard')
@section('content')
    <div class="card shadow ccont">
        <div class="card-body">
            @if (session('success'))
                <div id="success" class="alert alert-success" style="margin-top: 100px;">
                    {{ session('success') }}
                </div>
            @endif
            <div class="row d-flex text-center mt-3">
                <div class="col">
                    <h1>Búqueda de estudiante</h1>
                </div>
            </div>
            <div id="searchForm" class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8">
                        <form class="form" action="{{ route('system.collection.tuitions.search-post') }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input name="data" type="text" class="form-control"
                                    placeholder="Buscar por nombre, apellidos y matrícula" value="{{ old('data') }}">
                                <button class="btn btn-outline-secondary" type="submit" onclick="showLoader(true)">
                                    <span class="material-symbols-outlined">search</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="studentsTable" class="container mt-5">
                @if (count($students) > 0)
                    <div class="col">
                        <table class="table table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Matrícula</th>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                <tr onclick="redirectToStudent('{{ route('system.collection.student.tuitions', $student->id) }}')" style="cursor: pointer;">
                                    <td class="text-uppercase">{{ $student->crew->name[0].'/'.$student->id }}</td>
                                    <td class="text-uppercase">{{ $student->surnames.', '.$student->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>No hay resultados para mostrar.</p>
                @endif
            </div>
        </div>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger" style="margin-top: 20px;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    <script>
        function redirectToStudent(url) {
            window.location.href = url;
        }
    </script>
@endsection
