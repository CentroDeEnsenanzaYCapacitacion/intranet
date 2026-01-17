@extends('layout.mainLayout')
@section('title','Solicitudes')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Solicitudes</h1>
        <p class="dashboard-subtitle">Gestión de solicitudes pendientes y atendidas</p>
    </div>

    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 8V12L15 15M21 12C21 13.1819 20.7672 14.3522 20.3149 15.4442C19.8626 16.5361 19.1997 17.5282 18.364 18.364C17.5282 19.1997 16.5361 19.8626 15.4442 20.3149C14.3522 20.7672 13.1819 21 12 21C10.8181 21 9.64778 20.7672 8.55585 20.3149C7.46392 19.8626 6.47177 19.1997 5.63604 18.364C4.80031 17.5282 4.13738 16.5361 3.68508 15.4442C3.23279 14.3522 3 13.1819 3 12C3 9.61305 3.94821 7.32387 5.63604 5.63604C7.32387 3.94821 9.61305 3 12 3C14.3869 3 16.6761 3.94821 18.364 5.63604C20.0518 7.32387 21 9.61305 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Solicitudes Pendientes</h2>
            </div>
            <span class="badge badge-gray">{{ count($requests) }}</span>
        </div>

        <div class="table-responsive">
            <table class="modern-table">
                <thead>
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
                    @forelse($requests as $request)
                        <tr>
                            <td>{{ $request->created_at->format('d/m/Y') }}</td>
                            <td class="text-uppercase">{{ $request->requestType->name}}</td>
                            <td>
                                @if($request->student_id)
                                    <a href="{{ route('system.student.profile', ['student_id' => $request->student_id]) }}" onclick="showLoader(true)" class="text-uppercase" style="color: #0369a1; text-decoration: none; font-weight: 500;">
                                        {{ $request->student->surnames }}, {{ $request->student->name }}
                                    </a>
                                    <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Matrícula: {{ $request->student->crew->name[0] }}/{{ $request->student->id }}</div>
                                @elseif($request->report_id)
                                    <span class="text-uppercase">{{ $request->report->surnames }}, {{ $request->report->name }}</span>
                                    <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Informe #{{ $request->report_id }}</div>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-uppercase">{{ $request->description}}</td>
                            <td class="text-uppercase">{{ $request->user->name}}</td>
                            <td class="text-uppercase">{{ $request->user->crew->name}}</td>
                            @if(Auth::user()->role_id === 1)
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.request.update',['request_id' => $request->id,'action'=>'approve']) }}" class="action-btn action-approve" title="Aprobar" onclick="showLoader(true)">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 13L9 17L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.requests.edit',['request_id' => $request->id]) }}" class="action-btn action-edit" title="Editar" onclick="showLoader(true)">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M18.5 2.50001C18.8978 2.10219 19.4374 1.87869 20 1.87869C20.5626 1.87869 21.1022 2.10219 21.5 2.50001C21.8978 2.89784 22.1213 3.4374 22.1213 4.00001C22.1213 4.56262 21.8978 5.10219 21.5 5.50001L12 15L8 16L9 12L18.5 2.50001Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                    <button class="action-btn action-delete" title="Rechazar" onclick="confirmDelete('request',{{ $request->id }})">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role_id === 1 ? '7' : '6' }}" style="text-align: center; padding: 40px; color: #6b7280;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 16px; opacity: 0.3;">
                                    <path d="M9 12L11 14L15 10M21 12C21 13.1819 20.7672 14.3522 20.3149 15.4442C19.8626 16.5361 19.1997 17.5282 18.364 18.364C17.5282 19.1997 16.5361 19.8626 15.4442 20.3149C14.3522 20.7672 13.1819 21 12 21C10.8181 21 9.64778 20.7672 8.55585 20.3149C7.46392 19.8626 6.47177 19.1997 5.63604 18.364C4.80031 17.5282 4.13738 16.5361 3.68508 15.4442C3.23279 14.3522 3 13.1819 3 12C3 9.61305 3.94821 7.32387 5.63604 5.63604C7.32387 3.94821 9.61305 3 12 3C14.3869 3 16.6761 3.94821 18.364 5.63604C20.0518 7.32387 21 9.61305 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div style="font-size: 16px; font-weight: 500;">No hay solicitudes pendientes</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modern-card" style="margin-bottom: 24px;">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12L11 14L15 10M21 12C21 13.1819 20.7672 14.3522 20.3149 15.4442C19.8626 16.5361 19.1997 17.5282 18.364 18.364C17.5282 19.1997 16.5361 19.8626 15.4442 20.3149C14.3522 20.7672 13.1819 21 12 21C10.8181 21 9.64778 20.7672 8.55585 20.3149C7.46392 19.8626 6.47177 19.1997 5.63604 18.364C4.80031 17.5282 4.13738 16.5361 3.68508 15.4442C3.23279 14.3522 3 13.1819 3 12C3 9.61305 3.94821 7.32387 5.63604 5.63604C7.32387 3.94821 9.61305 3 12 3C14.3869 3 16.6761 3.94821 18.364 5.63604C20.0518 7.32387 21 9.61305 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Solicitudes Atendidas</h2>
            </div>
            <span class="badge badge-gray">{{ count($old_requests) }}</span>
        </div>

        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Fecha Solicitud</th>
                        <th>Fecha Atención</th>
                        <th>Tipo</th>
                        <th>Referencia</th>
                        <th>Descripción</th>
                        <th>Solicitado por</th>
                        <th>Plantel</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($old_requests as $old_request)
                        <tr>
                            <td>{{ $old_request->created_at->format('d/m/Y') }}</td>
                            <td>{{ $old_request->updated_at->format('d/m/Y') }}</td>
                            <td class="text-uppercase">{{ $old_request->requestType->name}}</td>
                            <td>
                                @if($old_request->student_id)
                                    <a href="{{ route('system.student.profile', ['student_id' => $old_request->student_id]) }}" onclick="showLoader(true)" class="text-uppercase" style="color: #0369a1; text-decoration: none; font-weight: 500;">
                                        {{ $old_request->student->surnames }}, {{ $old_request->student->name }}
                                    </a>
                                    <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Matrícula: {{ $old_request->student->crew->name[0] }}/{{ $old_request->student->id }}</div>
                                @elseif($old_request->report_id)
                                    <span class="text-uppercase">{{ $old_request->report->surnames }}, {{ $old_request->report->name }}</span>
                                    <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Informe #{{ $old_request->report_id }}</div>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-uppercase">{{ $old_request->description}}</td>
                            <td class="text-uppercase">{{ $old_request->user->name}}</td>
                            <td class="text-uppercase">{{ $old_request->user->crew->name}}</td>
                            <td>
                                @if($old_request->approved)
                                    <span class="badge badge-success">Aprobada</span>
                                @else
                                    <span class="badge badge-danger">Rechazada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: #6b7280;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 16px; opacity: 0.3;">
                                    <path d="M9 12L11 14L15 10M21 12C21 13.1819 20.7672 14.3522 20.3149 15.4442C19.8626 16.5361 19.1997 17.5282 18.364 18.364C17.5282 19.1997 16.5361 19.8626 15.4442 20.3149C14.3522 20.7672 13.1819 21 12 21C10.8181 21 9.64778 20.7672 8.55585 20.3149C7.46392 19.8626 6.47177 19.1997 5.63604 18.364C4.80031 17.5282 4.13738 16.5361 3.68508 15.4442C3.23279 14.3522 3 13.1819 3 12C3 9.61305 3.94821 7.32387 5.63604 5.63604C7.32387 3.94821 9.61305 3 12 3C14.3869 3 16.6761 3.94821 18.364 5.63604C20.0518 7.32387 21 9.61305 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div style="font-size: 16px; font-weight: 500;">No hay solicitudes atendidas</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
