@extends('layout.mainLayout')
@section('title', 'costos registrados')
@section('content')
    @if (session('success'))
        <div id="success" class="alert alert-success mt-content">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow ccont">
        <div class="card-body">
            <div class="row d-flex text-center mt-3">
                <div class="col">
                    <h1>Costos registrados</h1>
                </div>
                <div>
                    <a href="{{ route('admin.catalogues.amounts.generate') }}" onclick="showLoader(true)"
                        class="btn bg-orange text-white mt-2 mb-2">Generar inscripciones de cursos</a>
                    @if($amounts->where('receipt_type_id', '!=', 1)->where('crew_id', '!=', 1)->count() > 0)
                        <a href="{{ route('admin.catalogues.amounts.clean') }}" onclick="return confirm('¿Estás seguro de eliminar todos los costos que no sean inscripciones?') && showLoader(true)"
                            class="btn btn-danger text-white mt-2 mb-2 ms-2">Limpiar colegiaturas</a>
                    @endif
                </div>
                {{-- <div>
                <a href="{{ route('admin.users.new') }}"  class="btn bg-orange text-white mb-2">Nuevo costo</a>
            </div> --}}
            </div>
            <div class="row d-flex text-center mt-5">
                <div class="col">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th>Plantel</th>
                                <th>Tipo</th>
                                <th>Curso</th>
                                <th>Monto</th>
                                <th style="width: 10%;" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($amounts as $amount)
                                <tr>
                                    <td class="text-uppercase">{{ $amount->crew->name }}</td>
                                    <td class="text-uppercase">{{ $amount->receiptType->name }}</td>
                                    <td class="text-uppercase">
                                        @isset($amount->course)
                                            {{ $amount->course->name }}
                                        @endisset
                                    </td>
                                    <td class="text-uppercase">${{ number_format($amount->amount, 2, '.', ',') }}</td>
                                    <td class="justify-content-center">
                                        @if (Auth::user()->role_id !== 1)
                                            @if ($amount->id > 133)
                                                <span class="material-symbols-outlined bg-edit"><a
                                                        onclick="showLoader(true)"
                                                        href="{{ route('admin.catalogues.amount.edit', ['id' => $amount->id]) }}">edit</a></span>
                                            @endif
                                            @if ($amount->id <= 133)
                                                <span class="material-symbols-outlined bg-inactive">edit</span>
                                            @endif
                                        @else
                                            <span class="material-symbols-outlined bg-edit"><a onclick="showLoader(true)"
                                                    href="{{ route('admin.catalogues.amount.edit', ['id' => $amount->id]) }}">edit</a></span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
