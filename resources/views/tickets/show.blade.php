@extends('layout.mainLayout')
@section('title','Tickets de servicio')

@section('content')
<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>Tickets abiertos</h1>
            </div>
        </div>

        <div class="text-end mt-3">
            <a href="{{ route('tickets.form') }}" class="btn btn-success">
                + Nuevo ticket
            </a>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Fecha</th>
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
                                'resuelto' => 'bg-success text-white',
                                'cerrado' => 'bg-secondary text-white',
                                default => 'bg-light text-dark',
                            };
                        @endphp
                        <tr
                            style="cursor: pointer;"
                            onclick="window.location='{{ route('tickets.detail', $ticket->id) }}'">
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket->title }}</td>
                            <td>{{ $ticket->category?->name ?? 'Sin categoría' }}</td>
                            <td>
                                <span class="badge {{ $priorityClass }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No hay tickets registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
