@extends('layout.mainLayout')
@section('title','dashboard')
@section('content')
<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>Emisión de vales</h1>
            </div>
        </div>
        <div class="row d-flex text-center mt-5">
            <div class="col">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Folio</th>
                            <th>Concepto</th>
                            <th>Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paybills as $paybill)
                            <tr>
                                <td class="text-uppercase">{{ $paybill->created_at->format('d/m/Y') }}</td>
                                <td class="text-uppercase">{{ $paybill->id }}</td>
                                <td class="text-uppercase">{{ $paybill->concept}}</td>
                                <td class="text-uppercase">{{ $paybill->amount}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="{{ route('system.collection.newpaybill') }}"  class="btn bg-orange text-white mt-5">Nuevo Vale</a>
            </div>
        </div>
    </div>
</div>
@endsection
