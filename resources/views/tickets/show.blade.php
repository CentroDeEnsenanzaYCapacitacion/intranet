@extends('layout.mainLayout')
@section('title','Tickets de servicio')

@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Tickets abiertos</h1>
        <p class="dashboard-subtitle">Gestiona tus solicitudes de servicio</p>
    </div>

    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 8L21 8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M5 8V5C5 3.89543 5.89543 3 7 3H17C18.1046 3 19 3.89543 19 5V8" stroke="currentColor" stroke-width="2"/>
                    <path d="M5 8V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V8" stroke="currentColor" stroke-width="2"/>
                    <path d="M9 12H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M9 16H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <h2>Listado de tickets</h2>
            </div>
            <a href="{{ route('tickets.form') }}" class="btn-modern btn-primary" onclick="showLoader(true)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Nuevo ticket
            </a>
        </div>

        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Plantel</th>
                        <th class="text-center">Prioridad</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        @php
                            $priorityClass = match($ticket->priority) {
                                'baja' => 'text-secondary bg-transparent border border-secondary',
                                'media' => 'text-primary bg-transparent border border-primary',
                                'alta' => 'text-danger bg-transparent border border-danger',
                                'crítica' => 'text-white bg-danger border border-danger',
                                default => 'text-dark bg-transparent border',
                            };

                            $statusClass = match($ticket->status) {
                                'abierto' => 'bg-info text-white',
                                'en progreso' => 'bg-warning text-dark',
                                'esperando respuesta' => 'bg-primary text-white',
                                'resuelto' => 'bg-success text-white',
                                'cerrado' => 'bg-secondary text-white',
                                default => 'bg-light text-dark',
                            };
                        @endphp
                        <tr style="cursor: pointer;" onclick="window.location='{{ route('tickets.detail', $ticket->id) }}'">
                            <td class="text-center">{{ $ticket->id }}</td>
                            <td>{{ $ticket->title }}</td>
                            <td>{{ $ticket->category?->name ?? 'Sin categoría' }}</td>
                            <td>
                                @if($ticket->user?->crew_id == 1)
                                    General
                                @else
                                    {{ $ticket->user?->crew?->name ?? 'Sin plantel' }}
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $priorityClass }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td class="text-center">{{ $ticket->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 0;">
                                <div style="text-align: center; padding: 80px 40px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 50%); border-radius: 12px; margin: 20px;">
                                    <div style="background: rgba(255, 255, 255, 0.5); width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 24px; display: flex; align-items: center; justify-content: center;">
                                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #92400e;">
                                            <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                    </div>
                                    <h3 style="margin: 0 0 12px; color: #78350f; font-size: 24px; font-weight: 700;">No hay tickets abiertos</h3>
                                    <p style="margin: 0 0 32px; color: #92400e; font-size: 16px; max-width: 400px; margin-left: auto; margin-right: auto;">¡Excelente! No tienes solicitudes pendientes en este momento.</p>
                                    <a href="{{ route('tickets.form') }}" class="btn-modern btn-primary" onclick="showLoader(true)" style="display: inline-flex; align-items: center; gap: 8px; background: #F57F17; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                        Crear primer ticket
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

