@extends('layout.mainLayout')
@section('title', 'Detalle del ticket de servicio')

@section('content')
<div class="dashboard-welcome">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="dashboard-title">Ticket #{{ $ticket->id }}</h1>
            <p class="dashboard-subtitle">{{ $ticket->title }}</p>
        </div>
        <a href="{{ route('tickets.list') }}" class="btn-modern btn-secondary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Volver al listado
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-left: 4px solid #10b981; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #ef4444; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
        {{ session('error') }}
    </div>
@endif

<div class="modern-card" style="margin-bottom: 24px;">
    <div class="card-header-modern">
        <div class="header-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V9L13 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13 2V9H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h2>Información del Ticket</h2>
        </div>
    </div>

    <div style="padding: 24px;">
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

        <div class="row">
            <div class="col-md-6 mb-3">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Descripción</label>
                <p style="color: #6b7280; margin: 0;">{{ $ticket->description ?? 'Sin descripción' }}</p>
            </div>

            <div class="col-md-6 mb-3">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Creado por</label>
                <p style="color: #6b7280; margin: 0;">{{ $ticket->user->name ?? 'Desconocido' }}</p>
            </div>

            <div class="col-md-3 mb-3">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Prioridad</label>
                <span class="badge {{ $priorityClass }}">{{ ucfirst($ticket->priority) }}</span>
            </div>

            <div class="col-md-3 mb-3">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Categoría</label>
                <p style="color: #6b7280; margin: 0;">{{ $ticket->category->name ?? 'Sin categoría' }}</p>
            </div>

            <div class="col-md-3 mb-3">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Estado</label>
                <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
            </div>

            <div class="col-md-3 mb-3">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Fecha de creación</label>
                <p style="color: #6b7280; margin: 0;">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        @if($ticket->images && $ticket->images->count())
            <hr style="margin: 24px 0; border: 0; border-top: 1px solid #e5e7eb;">
            <h5 style="font-weight: 600; color: #374151; margin-bottom: 16px;">Imágenes adjuntas</h5>
            <div class="row">
                @foreach($ticket->images as $image)
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('tickets.image', $image->path) }}" target="_blank">
                            <img src="{{ route('tickets.image', $image->path) }}"
                                 class="img-fluid"
                                 alt="{{ $image->original_name }}"
                                 style="cursor: pointer; object-fit: cover; height: 200px; width: 100%; border: 2px solid #e5e7eb; border-radius: 12px; transition: all 0.2s ease;">
                        </a>
                        <small class="text-muted d-block mt-1">{{ $image->original_name }}</small>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

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

<div class="modern-card" style="margin-bottom: 24px;">
    <div class="card-header-modern">
        <div class="header-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h2>Cambiar estado del ticket</h2>
        </div>
    </div>

    <div style="padding: 24px;">
        <form action="{{ route('tickets.updateStatus', $ticket->id) }}" method="POST" onsubmit="showLoader(true)">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8 mb-3">
                    <div class="modern-field">
                        <label for="status">Nuevo estado</label>
                        <select name="status" id="status" class="form-select modern-input" required>
                            @foreach($statusOptions as $key => $label)
                                <option value="{{ $key }}" {{ $ticket->status === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4 mb-3" style="display: flex; align-items: flex-end;">
                    <button type="submit" class="btn-modern btn-primary" style="width: 100%;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 13L9 17L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Actualizar estado
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modern-card" style="margin-bottom: 24px;">
    <div class="card-header-modern">
        <div class="header-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 10H8.01M12 10H12.01M16 10H16.01M21 12C21 16.4183 16.9706 20 12 20C10.4607 20 9.01172 19.6565 7.74467 19.0511L3 20L4.39499 16.28C3.51156 15.0423 3 13.5743 3 12C3 7.58172 7.02944 4 12 4C16.9706 4 21 7.58172 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h2>Conversación</h2>
        </div>
    </div>

    <div style="padding: 24px;">
        @forelse($ticket->messages as $msg)
            <div style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 16px; margin-bottom: 16px; background: #f9fafb;">
                <div style="margin-bottom: 8px;">
                    <strong style="color: #374151;">{{ $msg->user->role_id == 1 ? 'SysAdmin' : $msg->user->name }}</strong>
                    <small class="text-muted">· {{ $msg->created_at->diffForHumans() }}</small>
                </div>
                <p style="margin: 0; color: #6b7280;">{{ $msg->message }}</p>

                @if($msg->attachments && $msg->attachments->count())
                    <div style="margin-top: 16px;">
                        <small class="text-muted" style="font-weight: 600;">Adjuntos:</small>
                        <div class="row" style="margin-top: 12px;">
                            @foreach($msg->attachments as $attachment)
                                @php
                                    $isImage = str_starts_with($attachment->mime_type, 'image/');
                                    $isVideo = str_starts_with($attachment->mime_type, 'video/');
                                @endphp

                                <div class="col-md-3 mb-2">
                                    @if($isImage)
                                        <a href="{{ route('tickets.attachment', $attachment->path) }}" target="_blank">
                                            <img src="{{ route('tickets.attachment', $attachment->path) }}"
                                                 class="img-fluid"
                                                 alt="{{ $attachment->original_name }}"
                                                 style="cursor: pointer; object-fit: cover; height: 150px; width: 100%; border: 2px solid #e5e7eb; border-radius: 12px;">
                                        </a>
                                    @elseif($isVideo)
                                        <video controls class="w-100" style="height: 150px; border: 2px solid #e5e7eb; border-radius: 12px;">
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
            <p style="color: #6b7280; text-align: center; padding: 24px;">No hay mensajes aún.</p>
        @endforelse
    </div>
</div>

@if(Auth::user()->role_id == 1 || !in_array($ticket->status, ['cerrado', 'resuelto']))
    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4V20M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <h2>Añadir mensaje</h2>
            </div>
        </div>

        <div style="padding: 24px;">
            <form action="{{ route('tickets.message', $ticket->id) }}" method="POST" enctype="multipart/form-data" onsubmit="showLoader(true)">
                @csrf
                <div class="mb-3">
                    <div class="modern-field">
                        <label for="message">Nuevo mensaje</label>
                        <textarea name="message" id="message" rows="4" class="form-control modern-textarea" required>{{ old('message') }}</textarea>
                    </div>
                    @error('message')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="modern-field">
                        <label for="attachments">Adjuntar imágenes o videos</label>
                        <div class="file-input">
                            <input type="file" name="attachments[]" id="attachments" class="file-input-native" accept="image/*,video/*" multiple>
                            <span class="file-input-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 16V6M12 6L8 10M12 6L16 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <span class="file-input-text" data-default="Sin archivos seleccionados">Sin archivos seleccionados</span>
                        </div>
                        <small class="text-muted" style="display: block; margin-top: 6px;">Máx. 5 archivos, 20MB cada uno. Formatos: JPG, PNG, GIF, WEBP, MP4, MOV, AVI, WMV</small>
                    </div>
                    @error('attachments')
                        <small class="text-danger d-block">{{ $message }}</small>
                    @enderror
                    @error('attachments.*')
                        <small class="text-danger d-block">{{ $message }}</small>
                    @enderror
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-modern btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 2L11 13M22 2L15 22L11 13M22 2L2 9L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Enviar mensaje
                    </button>
                </div>
            </form>
        </div>
    </div>
@else
    <div class="alert alert-info" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-left: 4px solid #3b82f6; border-radius: 12px; padding: 16px;">
        Este ticket está {{ $ticket->status }}. No se pueden añadir más mensajes.
    </div>
@endif
@endsection

@push('scripts')
<script src="{{ asset('assets/js/file_input.js') }}"></script>
@endpush
