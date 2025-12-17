@extends('layout.mainLayout')
@section('title','Solicitudes')
@section('content')
<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>{{ $request->requestType->name }}</h1>
            </div>
        </div>

        @if($request->request_type_id == 3)
            {{-- Cambio de colegiatura --}}
            <div class="row mt-4">
                <div class="col-md-6 offset-md-3">
                    <div class="mb-3">
                        <label class="form-label"><b>Estudiante:</b></label>
                        <p class="form-control-plaintext">
                            <a href="{{ route('system.student.profile', ['student_id' => $request->student_id]) }}">
                                {{ $request->student->surnames }}, {{ $request->student->name }}
                            </a>
                            <br><small class="text-muted">Matrícula: {{ $request->student->crew->name[0] }}/{{ $request->student->id }}</small>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><b>Colegiatura actual:</b></label>
                        <p class="form-control-plaintext">${{ number_format($request->student->tuition ?? 0, 2) }}</p>
                    </div>
                    @php
                        preg_match('/Nueva colegiatura: \$([\d,\.]+)/', $request->description, $matches);
                        $newTuition = isset($matches[1]) ? str_replace(',', '', $matches[1]) : '';
                        $reason = preg_replace('/Nueva colegiatura: \$[\d,\.]+ - /', '', $request->description);
                    @endphp
                    <form id="myForm" action="{{ route('admin.requests.changeTuition', ['request_id' => $request->id]) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="new_tuition" class="form-label"><b>Nueva colegiatura:</b></label>
                            <input type="number" class="form-control" id="new_tuition" name="new_tuition" step="0.01" min="0.01" value="{{ $newTuition }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><b>Motivo:</b></label>
                            <p class="form-control-plaintext">{{ $reason }}</p>
                        </div>
                        <div class="text-center mt-4">
                            <button onclick="showLoader(true)" class="btn bg-orange text-white" type="submit">Guardar y aprobar</button>
                            <a href="{{ route('admin.requests.show') }}" class="btn btn-outline-secondary ms-2">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        @else
            {{-- Otros tipos de solicitudes --}}
            <div class="row mt-4">
                <div class="col-md-6 offset-md-3">
                    <div class="mb-3">
                        <label class="form-label"><b>Descripción:</b></label>
                        <p class="form-control-plaintext">{{ $request->description }}</p>
                    </div>
                    <div class="text-center mt-4">
                        <p class="text-muted">Esta solicitud no requiere edición.</p>
                        <a href="{{ route('admin.requests.show') }}" class="btn btn-outline-secondary">Volver</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
