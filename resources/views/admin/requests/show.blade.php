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
                            <th>Referencia</th>
                            <th>Descripción</th>
                            <th>Solicitado por</th>
                            <th>Plantel</th>
                            @if(Auth::user()->role_id === 1)
                            <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td class="text-uppercase">{{ $request->created_at->format('d/m/Y') }}</td>
                                <td class="text-uppercase">{{ $request->requestType->name}}</td>
                                <td class="text-uppercase">
                                    @if($request->student_id)
                                        <a href="{{ route('system.student.profile', ['student_id' => $request->student_id]) }}">
                                            {{ $request->student->surnames }}, {{ $request->student->name }}
                                        </a>
                                        <br><small class="text-muted">Matrícula: {{ $request->student->crew->name[0] }}/{{ $request->student->id }}</small>
                                    @elseif($request->report_id)
                                        {{ $request->report->surnames }}, {{ $request->report->name }}
                                        <br><small class="text-muted">Informe #{{ $request->report_id }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-uppercase">{{ $request->description}}</td>
                                <td class="text-uppercase">{{ $request->user->name}}</td>
                                <td class="text-uppercase">{{ $request->user->crew->name}}</td>
                                @if(Auth::user()->role_id === 1)
                                <td class="text-center">
                                    @if($request->request_type_id == 3)

                                        <span class="material-symbols-outlined bg-done"><a onclick="showLoader(true)" href="{{ route('admin.request.update',['request_id' => $request->id,'action'=>'approve']) }}">done</a></span>
                                        <span class="material-symbols-outlined bg-edit"><a onclick="showLoader(true)" href="{{ route('admin.requests.edit',['request_id' => $request->id]) }}">edit</a></span>
                                        <span class="material-symbols-outlined bg-red"><a onclick="confirmDelete('request',{{ $request->id }})">close</a></span>
                                    @else

                                        <span class="material-symbols-outlined bg-done"><a onclick="showLoader(true)" href="{{ route('admin.request.update',['request_id' => $request->id,'action'=>'approve']) }}">done</a></span>
                                        <span class="material-symbols-outlined bg-edit"><a onclick="showLoader(true)" href="{{ route('admin.requests.edit',['request_id' => $request->id]) }}">edit</a></span>
                                        <span class="material-symbols-outlined bg-red"><a onclick="confirmDelete('request',{{ $request->id }})">close</a></span>
                                    @endif
                                </td>
                                @endif
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
                            <th>Referencia</th>
                            <th>Descripción</th>
                            <th>Solicitado por</th>
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
                                <td class="text-uppercase">
                                    @if($old_request->student_id)
                                        <a href="{{ route('system.student.profile', ['student_id' => $old_request->student_id]) }}">
                                            {{ $old_request->student->surnames }}, {{ $old_request->student->name }}
                                        </a>
                                        <br><small class="text-muted">Matrícula: {{ $old_request->student->crew->name[0] }}/{{ $old_request->student->id }}</small>
                                    @elseif($old_request->report_id)
                                        {{ $old_request->report->surnames }}, {{ $old_request->report->name }}
                                        <br><small class="text-muted">Informe #{{ $old_request->report_id }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
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
