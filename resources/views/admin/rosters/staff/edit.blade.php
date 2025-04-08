@extends('layout.mainLayout')
@section('title','Editar Empleado')
@section('content')

<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col mb-3">
                <h1>Editar empleado</h1>
            </div>
        </div>

        <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $staff->name) }}" readonly>
                </div>
                <div class="col">
                    <label class="form-label">Apellidos</label>
                    <input type="text" name="surnames" class="form-control" value="{{ old('surnames', $staff->surnames) }}" readonly>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Dirección</label>
                <input type="text" name="Address" class="form-control" value="{{ old('Address', $staff->Address) }}">
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Colonia</label>
                    <input type="text" name="colony" class="form-control" value="{{ old('colony', $staff->colony) }}">
                </div>
                <div class="col">
                    <label class="form-label">Municipio</label>
                    <input type="text" name="municipalty" class="form-control" value="{{ old('municipalty', $staff->municipalty) }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $staff->phone) }}">
                </div>
                <div class="col">
                    <label class="form-label">Celular</label>
                    <input type="text" name="cel" class="form-control" value="{{ old('cel', $staff->cel) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">RFC</label>
                <input type="text" name="rfc" class="form-control" value="{{ old('rfc', $staff->rfc) }}">
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Departamento</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department', $staff->department) }}">
                </div>
                <div class="col">
                    <label class="form-label">Puesto</label>
                    <input type="text" name="position" class="form-control" value="{{ old('position', $staff->position) }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Correo personal</label>
                    <input type="email" name="personal_mail" class="form-control" value="{{ old('personal_mail', $staff->personal_mail) }}">
                </div>
                <div class="col">
                    <label class="form-label">Correo CEC</label>
                    <input type="email" name="cec_mail" class="form-control" value="{{ old('cec_mail', $staff->cec_mail) }}" readonly>
                </div>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="isRoster" value="1" {{ $staff->isRoster ? 'checked' : '' }}>
                <label class="form-check-label">Incluir en nómina</label>
            </div>

            <button type="submit" class="btn bg-orange text-white">Actualizar empleado</button>
            <a href="{{ route('admin.staff.show') }}" class="btn btn-secondary ms-2">Cancelar</a>
        </form>
    </div>
</div>
@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@endsection
