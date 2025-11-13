@extends('layout.mainLayout')
@section('title','Historial de recibos')
@section('content')
<div class="card shadow ccont">
    <div class="card-body">
        @if(session('success'))
            <div id="success" class="alert alert-success" style="margin-top: 100px;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div id="error" class="alert alert-danger" style="margin-top: 100px;">
                {{ session('error') }}
            </div>
        @endif
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>Cobro a estudiante</h1>
            </div>
        </div>
        <div class="row d-flex mt-3">
            <div class="col">
                {{ $student->crew->name[0].'/'.$student->id }} {{ $student->surnames }}, {{ $student->name }}<br>
                {{ $student->course->name }} {{ $student->generation }} {{ $student->modality->name }} de {{ $student->schedule->name }}<br>
                {{ $student->paymentPeriodicity->name }}, ${{ number_format($amount->amount, 2, '.', ',') }}<br>
            </div>
        </div>
        <div class="row d-flex mt-5">
            <div class="col">
                <a href="{{ route('system.collection.student.newtuition',['student_id'=>$student->id]) }}"><button class="btn bg-orange text-white">Nuevo cobro</button></a>
            </div>
        </div>
        <div class="row d-flex mt-5">
            <div class="col">
                <h4>Historial de cobros</h4>
            </div>
        </div>
        <div class="row d-flex mt-2">
            <div class="col">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th>Importe</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $pay)
                            <tr>
                                <td class="text-uppercase">{{ $pay->id }}</td>
                                <td class="text-uppercase">{{ $pay->created_at->format('d/m/Y') }}</td>
                                <td class="text-uppercase">{{ $pay->concept}}</td>
                                <td class="text-uppercase">${{ number_format($pay->amount, 2, '.', ',') }}</td>
                                <td class="text-center">
                                    <span class="material-symbols-outlined bg-edit">
                                        <a href="{{ route('system.collection.receipt.reprint', ['receipt_id' => $pay->id]) }}" 
                                           target="_blank" 
                                           title="Reimprimir recibo">print</a>
                                    </span>
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
