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
            <p><strong>Creado por:</strong> {{ $ticket->user->name ?? 'Desconocido' }}</p>

            @php
                $priorityClass = match($ticket->priority) {
                    'baja' => 'text-secondary bg-transparent border border-secondary',
                    'media' => 'text-primary bg-transparent border border-primary',
                    'alta' => 'text-danger bg-transparent border border-danger',
                    'critica' => 'text-white bg-danger border border-danger',
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

            <p><strong>Prioridad:</strong>
                <span class="badge {{ $priorityClass }}">
                    {{ ucfirst($ticket->priority) }}
                </span>
            </p>

            <p><strong>Categoría:</strong> {{ $ticket->category->name ?? 'Sin categoría' }}</p>
            <p><strong>Estado:</strong>
                <span class="badge {{ $statusClass }}">
                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                </span>
            </p>
            <p><strong>Fecha de creación:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
        </div>

        @if($ticket->images && $ticket->images->count())
            <hr>
            <h5>Imágenes adjuntas</h5>
            <div class="row">
                @foreach($ticket->images as $image)
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('tickets.image', $image->path) }}" target="_blank">
                            <img src="{{ route('tickets.image', $image->path) }}"
                                 class="img-fluid rounded border"
                                 alt="{{ $image->original_name }}"
                                 style="cursor: pointer; object-fit: cover; height: 200px; width: 100%;">
                        </a>
                        <small class="text-muted d-block mt-1">{{ $image->original_name }}</small>
                    </div>
                @endforeach
            </div>
        @endif

        @php

            $adminStatusOptions = [
                'abierto' => 'Abierto',
                'en progreso' => 'En progreso',
                'esperando respuesta' => 'Esperando respuesta',
                'resuelto' => 'Resuelto',
                'cerrado' => 'Cerrado'
            ];

            $userStatusOptions = [
                'abierto' => 'Abierto',
                'resuelto' => 'Resuelto'
            ];

            $statusOptions = Auth::user()->role_id == 1 ? $adminStatusOptions : $userStatusOptions;
        @endphp

        <hr class="my-4">
        <h5>Cambiar estado del ticket</h5>
        <form action="{{ route('tickets.updateStatus', $ticket->id) }}" method="POST" class="row g-2 mb-4" onsubmit="showLoader(true)">
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

        <hr class="my-5">
        <h5>Conversación</h5>

        <div class="mb-4">
            @forelse($ticket->messages as $msg)
                <div class="border rounded p-3 mb-3">
                    <strong>{{ $msg->user->role_id == 1 ? 'SysAdmin' : $msg->user->name }}</strong>
                    <small class="text-muted">· {{ $msg->created_at->diffForHumans() }}</small>
                    <p class="mb-0">{{ $msg->message }}</p>

                    @if($msg->attachments && $msg->attachments->count())
                        <div class="mt-3">
                            <small class="text-muted"><strong>Adjuntos:</strong></small>
                            <div class="row mt-2">
                                @foreach($msg->attachments as $attachment)
                                    @php
                                        $isImage = str_starts_with($attachment->mime_type, 'image/');
                                        $isVideo = str_starts_with($attachment->mime_type, 'video/');
                                    @endphp

                                    <div class="col-md-3 mb-2">
                                        @if($isImage)
                                            <a href="{{ route('tickets.attachment', $attachment->path) }}" target="_blank">
                                                <img src="{{ route('tickets.attachment', $attachment->path) }}"
                                                     class="img-fluid rounded border"
                                                     alt="{{ $attachment->original_name }}"
                                                     style="cursor: pointer; object-fit: cover; height: 150px; width: 100%;">
                                            </a>
                                        @elseif($isVideo)
                                            <video controls class="w-100 rounded border" style="height: 150px;">
                                                <source src="{{ route('tickets.attachment', $attachment->path) }}" type="{{ $attachment->mime_type }}">
                                                Tu navegador no soporta videos.
                                            </video>
                                        @endif
                                        <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">{{ $attachment->original_name }}</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <p>No hay mensajes aún.</p>
            @endforelse
        </div>

        @if(Auth::user()->role_id == 1 || !in_array($ticket->status, ['cerrado', 'resuelto']))
            <form action="{{ route('tickets.message', $ticket->id) }}" method="POST" enctype="multipart/form-data" onsubmit="showLoader(true)">
                @csrf
                <div class="mb-3">
                    <label for="message" class="form-label">Nuevo mensaje</label>
                    <textarea name="message" id="message" class="form-control" rows="3" required>{{ old('message') }}</textarea>
                    @error('message')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Adjuntar imágenes o videos (máx. 5 archivos, 20MB cada uno)</label>
                    <input type="file" name="attachments[]" id="attachments" class="form-control" multiple accept="image/*,video/*">
                    <small class="text-muted">Formatos: JPG, PNG, GIF, WEBP, MP4, MOV, AVI, WMV</small>
                    @error('attachments')
                        <small class="text-danger d-block">{{ $message }}</small>
                    @enderror
                    @error('attachments.*')
                        <small class="text-danger d-block">{{ $message }}</small>
                    @enderror
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        @else
            <div class="alert alert-info">
                Este ticket está {{ $ticket->status }}. No se pueden añadir más mensajes.
            </div>
        @endif

        <div class="mt-4 text-end">
            <a href="{{ route('tickets.list') }}" class="btn btn-secondary">Volver al listado</a>
        </div>
    </div>
</div>
@endsection
