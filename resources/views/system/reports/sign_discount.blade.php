@extends('layout.mainLayout')
@section('title','Inscripción')
@section('content')
<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>Inscripción de nuevo alumno</h1>
            </div>
        </div>
        <form id="inscriptionForm" action="{{ route('system.report.receiptorrequest') }}" method="POST">
            @csrf
            <input type="hidden" value="{{ $report_id }}" name="report_id">
            <input type="hidden" value="{{ $report->course->name ?? '' }}" id="courseName">
            <input type="hidden" value="{{ $report->course_id ?? '' }}" id="courseId">

            @php
                $isBachilleratoExamen = stripos($report->course->name ?? '', 'BACHILLERATO EN UN EXAMEN') !== false;
            @endphp

            @if(!$isBachilleratoExamen)
                <div class="row d-flex text-center mt-5">
                    <div class="col">
                        <div class="text-center">
                            <label for="amount" class="form-label h5">Importe de inscripción</label>
                            <div class="input-group mb-3 mx-auto" style="max-width: 300px;">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" id="amount" name="amount" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row d-flex text-center mt-3">
                    <div class="col">
                        <div class="text-center">
                            <input class="form-check-input" name="card_payment" type="checkbox" value="card" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Pago con tarjeta
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row d-flex text-center mt-3" id="explanationContainer" style="display: none;">
                    <div class="col">
                        <div class="text-center">
                            <label for="price_explanation" class="form-label h5 text-danger">El importe es diferente al registrado. Por favor, explica la razón:</label>
                            <textarea class="form-control mx-auto" style="max-width: 500px;" id="price_explanation" name="price_explanation" rows="3" placeholder="Escribe aquí la razón de la diferencia de precio..."></textarea>
                        </div>
                    </div>
                </div>
            @else
                <input type="hidden" name="amount" value="0">
                <div class="row d-flex text-center mt-5">
                    <div class="col">
                        <div class="alert alert-info">
                            <strong>Curso: {{ $report->course->name }}</strong><br>
                            Este curso no requiere importe de inscripción.
                        </div>
                    </div>
                </div>
            @endif

            <div class="row d-flex text-center mt-5">
                <div class="col">
                    <div class="text-center">
                        <button onclick="showLoader(true)" class="btn bg-orange text-white w-25" type="submit">Inscribir</button><br><br>
                        <a class="btn btn-outline-orange text-white w-25" href="{{ route('system.reports.show') }}">Cancelar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<br>
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="alert alert-danger" style="{{ $errors->any() ? '' : 'display: none;' }}" id="error-container">
    <ul id="error-list">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        @endif
    </ul>
</div>

@endsection
@push('scripts')
<script src="{{ asset('assets/js/no_discount_redirection.js') }}"></script>
@endpush
