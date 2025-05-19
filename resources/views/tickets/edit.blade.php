@extends('layout.mainLayout')
@section('title', 'Detalle del ticket de servicio')

@section('content')
<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>Detalle del ticket</h1>
            </div>
        </div>

        <div class="mt-4">
            <p><strong>Título:</strong> {{ $ticket->title }}</p>
            <p><strong>Descripción:</strong> {{ $ticket->description ?? 'Sin descripción' }}</p>

            @php
                $priorityClass = match($ticket->priority) {
                    'baja' => 'text-secondary bg-transparent border border-secondary',
                    'media' => 'text-primary bg-transparent border border-primary',
                    'alta' => 'text-danger bg-transparent border border-danger',
                    'critica' => 'text-white bg-danger border border-danger',
                    default => 'text-dark bg-transparent border',
                };
            @endphp

            <p><strong>Prioridad:</strong>
                <span class="badge {{ $priorityClass }}">
                    {{ ucfirst($ticket->priority) }}
                </span>
            </p>

            <p><strong>Categoría:</strong> {{ $ticket->category->name ?? 'Sin categoría' }}</p>
            <p><strong>Estado:</strong> {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</p>
            <p><strong>Fecha de creación:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
        </div>

        @if($ticket->evidences && $ticket->evidences->count())
            <hr>
            <h5>Evidencias gráficas</h5>
            <div class="row">
                @foreach($ticket->evidences as $evidence)
                    <div class="col-md-3 mb-3">
                        <img src="{{ asset('storage/' . $evidence->path) }}" class="img-fluid rounded border" alt="Evidencia">
                    </div>
                @endforeach
            </div>
        @endif

        @php
            $statusOptions = [
                'abierto' => 'Abierto',
                'en progreso' => 'En progreso',
                'resuelto' => 'Resuelto',
                'cerrado' => 'Cerrado'
            ];
        @endphp

        @if (in_array(Auth::user()->role_id, [1]))
            <hr class="my-4">
            <h5>Cambiar estado del ticket</h5>
            <form action="{{ route('tickets.updateStatus', $ticket->id) }}" method="POST" class="row g-2 mb-4">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <select name="status" class="form-select" required>
                        @foreach($statusOptions as $key => $label)
                            <option value="{{ $key }}" {{ $ticket->status === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 text-end">
                    <button type="submit" class="btn btn-warning">Actualizar estado</button>
                </div>
            </form>
        @endif

        {{-- Conversación --}}
        <hr class="my-5">
        <h5>Conversación</h5>

        <div class="mb-4">
            @forelse($ticket->messages as $msg)
                <div class="border rounded p-3 mb-3">
                    <strong>{{ $msg->user->name }}</strong>
                    <small class="text-muted">· {{ $msg->created_at->diffForHumans() }}</small>
                    <p class="mb-0">{{ $msg->message }}</p>
                </div>
            @empty
                <p>No hay mensajes aún.</p>
            @endforelse
        </div>

        <form action="{{ route('tickets.message', $ticket->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="message" class="form-label">Nuevo mensaje</label>
                <textarea name="message" id="message" class="form-control" rows="3" required>{{ old('message') }}</textarea>
                @error('message')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
        </form>

        <div class="mt-4 text-end">
            <a href="{{ route('tickets.list') }}" class="btn btn-secondary">Volver al listado</a>
        </div>
    </div>
</div>
@endsection
