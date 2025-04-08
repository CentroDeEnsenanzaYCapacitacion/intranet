@extends('layout.mainLayout')
@section('title','Solicitudes')
@section('content')
<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>Personal activo</h1>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col text-end">
                <a href="{{ route('admin.staff.create') }}" class="btn bg-orange text-white">Nuevo empleado</a>
            </div>
        </div>
        @if (session('success'))
            <div id="success-alert" class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="row mt-3">
            <div class="col">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Correo</th>
                            <th>Departamento</th>
                            <th>Puesto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staff as $person)
                        <tr>
                            <td>{{ $person->name }}</td>
                            <td>{{ $person->surnames }}</td>
                            <td>{{ $person->cec_mail }}</td>
                            <td>{{ $person->department }}</td>
                            <td>{{ $person->position }}</td>
                            <td>
                                <span class="material-symbols-outlined bg-edit">
                                    <a onclick="showLoader(true)" href="{{ route('admin.staff.edit', $person->id) }}">edit</a>
                                </span>
                                <form action="{{ route('admin.staff.deactivate', $person->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="border-0 bg-transparent p-0" onclick="return confirm('¿Estás seguro de desactivar este empleado?');">
                                        <span class="material-symbols-outlined text-danger">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                        @if($staff->where('isActive', true)->count() === 0)
                        <tr>
                            <td colspan="6" class="text-center">No hay empleados activos.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/alerts.js') }}"></script>
@endpush

