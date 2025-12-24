@extends('layout.mainLayout')
@section('title', 'dashboard')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Nosotros, Misi&oacute;n, Visi&oacute;n y Valores</h1>
        <p class="dashboard-subtitle">Actualiza el contenido institucional del sitio</p>
    </div>

    @if (session('success'))
        <div id="success" class="alert alert-success" style="margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    <form id="form" action="{{ route('web.mvv.post') }}" method="post" enctype="multipart/form-data">
        @csrf
        @foreach($data as $index => $item)
            <div class="modern-card" style="margin-bottom: 24px;">
                <div class="card-header-modern">
                    <div class="header-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 3H15L20 8V21H6V3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 3V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 13H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M9 17H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <h2>{{ $item->name }}</h2>
                    </div>
                    <div class="header-actions">
                        <span style="font-size: 13px; color: #6b7280;">Caracteres restantes:</span>
                        <input style="border: none; background-color: transparent; width: 48px;" disabled maxlength="3" size="3" value="{{ 355 - Str::length($item->description) }}" id="counter{{ $index + 1 }}">
                    </div>
                </div>
                <div style="padding: 24px;">
                    <textarea oninput="textCounter(this, 'counter{{ $index + 1 }}', 355);" id="description{{ $index + 1 }}" maxlength="355" class="form-control" name="description{{ $index + 1 }}" rows="5" cols="25">{{ old('description' . ($index + 1), $item->description) }}</textarea>
                    @error('description' . ($index + 1))
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endforeach

        <div style="display: flex; justify-content: center;">
            <button class="btn-modern btn-primary" type="submit">Guardar datos</button>
        </div>
    </form>
@endsection


@push('scripts')
<script src="{{ asset('assets/js/text_counter.js') }}"></script>
@endpush
