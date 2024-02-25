@extends('layout.mainLayout')
@section('title','dashboard')
@section('content')
@if(session('success'))
    <div id="success" class="alert alert-success" style="margin-top: 100px;">
        {{ session('success') }}
    </div>
@endif
<div class="row d-flex text-center mt-content">
    <div class="col">
        <h1>Informes registrados</h1>
    </div>
</div>
@if(session('error'))
    <div id="error" class="alert alert-danger" style="margin-top: 100px;">
        {{ session('error') }}
    </div>
@endif
<div class="row d-flex text-center mt-5">
    <div class="col">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Celular</th>
                    <th>Género</th>
                    <th>Área de interés</th>
                    <th>Conoce CEC por</th>
                    <th>Plantel de interés</th>
                    <th>Preinscribir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($crew_reports as $report)
                    <tr>
                        <td class="text-uppercase">{{ $report->created_at->format('d/m/Y') }}</td>
                        <td class="text-uppercase">{{ $report->name }}</td>
                        <td class="text-uppercase">{{ $report->surnames}}</td>
                        <td class="text-uppercase">{{ $report->email}}</td>
                        <td class="text-uppercase">{{ $report->phone}}</td>
                        <td class="text-uppercase">{{ $report->cel_phone}}</td>
                        <td class="text-uppercase">{{ $report->genre}}</td>
                        <td class="text-uppercase">{{ $report->course->name}}</td>
                        <td class="text-uppercase">{{ $report->marketing->name}}</td>
                        <td class="text-uppercase">{{ $report->crew->name}}</td>
                        <td class="text-center align-items-center">
                            <a href="{{ route('system.reports.signdiscount', ['report_id'=> $report->id ] ) }}" class="clean-button"><span class="material-symbols-outlined bg-edit">resume</span></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('system.report.new') }}"  class="btn bg-orange text-white mt-content">Nuevo Informe</a>
    </div>
</div>
<div class="row d-flex text-center mt-content">
    <div class="col">
        <h1>Solicitudes de descuento de preinscripción</h1>
    </div>
</div>
<div class="row d-flex text-center mt-5">
    <div class="col">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Celular</th>
                    <th>Género</th>
                    <th>Área de interés</th>
                    <th>Conoce CEC por</th>
                    <th>Plantel de interés</th>
                    <th>Fecha de solicitud</th>
                    <th>Estatus</th>
                    <th>Preinscribir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($crew_requests as $request)
                    <tr>
                        <td class="text-uppercase">{{ $request->created_at->format('d/m/Y') }}</td>
                        <td class="text-uppercase">{{ $request->name }}</td>
                        <td class="text-uppercase">{{ $request->surnames}}</td>
                        <td class="text-uppercase">{{ $request->email}}</td>
                        <td class="text-uppercase">{{ $request->phone}}</td>
                        <td class="text-uppercase">{{ $request->cel_phone}}</td>
                        <td class="text-uppercase">{{ $request->genre}}</td>
                        <td class="text-uppercase">{{ $request->course->name}}</td>
                        <td class="text-uppercase">{{ $request->marketing->name}}</td>
                        <td class="text-uppercase">{{ $request->crew->name}}</td>
                        <td class="text-uppercase">{{ $request->request_date->format('d/m/Y')}}</td>
                        <td class="text-uppercase">
                            @switch($request->status)
                                @case('Aprobado')
                                    <span class="text-success">{{ $request->status }}</span>
                                    @break
                        
                                @case('Pendiente')
                                    <span class="text-info">{{ $request->status }}</span>
                                    @break
                        
                                @case('Rechazado')
                                    <span class="text-danger">{{ $request->status }}</span>
                                    @break
                        
                                @default
                                    <span>{{ $request->status }}</span>
                            @endswitch
                        </td>                                                                    
                        <td class="text-center align-items-center">
                            @if($request->status === "Aprobado")
                            <a href="{{ route('system.reports.signdiscount', ['report_id'=> $request->id ] ) }}" class="clean-button"><span class="material-symbols-outlined bg-edit">resume</span></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection