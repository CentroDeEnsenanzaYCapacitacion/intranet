@extends('layout.mainLayout')
@section('title','Nuevo Empleado')
@section('content')

<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col mb-3">
                <h1>Nuevo empleado</h1>
            </div>
        </div>

        <form action="{{ route('admin.staff.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col">
                    <label for="name" class="form-label">Nombre *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col">
                    <label for="surnames" class="form-label">Apellidos</label>
                    <input type="text" name="surnames" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label for="Address" class="form-label">Dirección</label>
                <input type="text" name="Address" class="form-control">
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="colony" class="form-label">Colonia</label>
                    <input type="text" name="colony" class="form-control">
                </div>
                <div class="col">
                    <label for="municipalty" class="form-label">Municipio</label>
                    <input type="text" name="municipalty" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="phone" class="form-label">Teléfono</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="col">
                    <label for="cel" class="form-label">Celular</label>
                    <input type="text" name="cel" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label for="rfc" class="form-label">RFC</label>
                <input type="text" name="rfc" class="form-control">
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="department" class="form-label">Departamento</label>
                    <input type="text" name="department" class="form-control">
                </div>
                <div class="col">
                    <label for="position" class="form-label">Puesto</label>
                    <input type="text" name="position" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="personal_mail" class="form-label">Correo personal</label>
                    <input type="email" name="personal_mail" class="form-control">
                </div>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="requiresMail" value="1" checked>
                <label class="form-check-label">Requiere correo</label>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="isRoster" value="1" checked>
                <label class="form-check-label">Incluir en nómina</label>
            </div>

            <button type="submit" class="btn bg-orange text-white">Guardar empleado</button>
            <a href="{{ route('admin.staff.show') }}" class="btn btn-secondary ms-2">Cancelar</a>
        </form>
    </div>
</div>

@endsection
