@extends('layout.mainLayout')
@section('title', 'Definiciones de Percepciones y Deducciones')
@section('content')
    <div class="card shadow ccont">
        <div class="card-body">
            <div class="row d-flex text-center mt-3">
                <div class="col">
                    <h1>Definiciones de percepciones y deducciones</h1>
                </div>
            </div>

            <div class="row d-flex mt-5">
                <div class="col">
                    <h4>Percepciones</h4>
                    <form action="{{ route('admin.catalogues.perceptions.store') }}" method="POST" class="d-flex mb-3">
                        @csrf
                        <input type="hidden" name="type" value="perception">
                        <input type="text" name="name" class="form-control me-2" placeholder="Nueva percepción" required>
                        <button type="submit" class="btn btn-success">Agregar</button>
                    </form>

                    <ul class="list-group">
                        @foreach ($perceptions as $perception)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $perception->name }}
                                <form action="{{ route('admin.catalogues.perceptions.destroy', $perception->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta percepción?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col">
                    <h4>Deducciones</h4>
                    <form action="{{ route('admin.catalogues.perceptions.store') }}" method="POST" class="d-flex mb-3">
                        @csrf
                        <input type="hidden" name="type" value="deduction">
                        <input type="text" name="name" class="form-control me-2" placeholder="Nueva deducción" required>
                        <button type="submit" class="btn btn-success">Agregar</button>
                    </form>

                    <ul class="list-group">
                        @foreach ($deductions as $deduction)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $deduction->name }}
                                <form action="{{ route('admin.catalogues.perceptions.destroy', $deduction->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta deducción?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
