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
                            <td colspan="7">No hay tickets registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

