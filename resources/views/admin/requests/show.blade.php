@extends('layout.mainLayout')
@section('title','Solicitudes')
@section('content')
<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>Solicitudes pendientes</h1>
            </div>
        </div>
        <div class="row d-flex text-center mt-5">
            <div class="col">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Solicitador por</th>
                            <th>Plantel</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td class="text-uppercase">{{ $request->created_at->format('d/m/Y') }}</td>
                                <td class="text-uppercase">{{ $request->requestType->name}}</td>
                                <td class="text-uppercase">{{ $request->description}}</td>
                                <td class="text-uppercase">{{ $request->user->name}}</td>
                                <td class="text-uppercase">{{ $request->user->crew->name}}</td>
                                <td class="text-center">
                                    <span class="material-symbols-outlined bg-done"><a onclick="showLoader(true)" href="{{ route('admin.request.update',['request_id' => $request->id,'action'=>'approve']) }}">done</a></span>
                                    <span class="material-symbols-outlined bg-edit"><a onclick="showLoader(true)" href="{{ route('admin.requests.edit',['request_id' => $request->id]) }}">edit</a></span>
                                    <span class="material-symbols-outlined bg-red"><a onclick="confirmDelete('request',{{ $request->id }})">close</a></span>
                                </td> 
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row d-flex text-center mt-content">
            <div class="col">
                <h1>Solicitudes atendidas</h1>
            </div>
        </div>
        <div class="row d-flex text-center mt-5">
            <div class="col">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Fecha solicitud</th>
                            <th>Fecha atención</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Solicitador por</th>
                            <th>Plantel</th>
                            <th>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($old_requests as $old_request)
                            <tr>
                                <td class="text-uppercase">{{ $old_request->created_at->format('d/m/Y') }}</td>
                                <td class="text-uppercase">{{ $old_request->updated_at->format('d/m/Y') }}</td>
                                <td class="text-uppercase">{{ $old_request->requestType->name}}</td>
                                <td class="text-uppercase">{{ $old_request->description}}</td>
                                <td class="text-uppercase">{{ $old_request->user->name}}</td>
                                <td class="text-uppercase">{{ $old_request->user->crew->name}}</td>
                                <td class="text-uppercase">
                                    @if($old_request->approved)
                                        <span class="text-success">Aprobada</span>
                                    @else
                                        <span class="text-danger">Rechazada</span>
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