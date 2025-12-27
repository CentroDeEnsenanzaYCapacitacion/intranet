@extends('layout.mainLayout')
@section('title', 'Foto de Perfil')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/calendar-modal.css') }}">
@endpush
@section('content')

    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Foto de Perfil</h1>
        <p class="dashboard-subtitle">Actualiza la fotografía del estudiante</p>
    </div>

    @if (session('success'))
        <div id="success" class="alert alert-success" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-left: 4px solid #10b981; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #ef4444; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="modern-card" style="margin-bottom: 24px;">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23 19C23 19.5304 22.7893 20.0391 22.4142 20.4142C22.0391 20.7893 21.5304 21 21 21H3C2.46957 21 1.96086 20.7893 1.58579 20.4142C1.21071 20.0391 1 19.5304 1 19V8C1 7.46957 1.21071 6.96086 1.58579 6.58579C1.96086 6.21071 2.46957 6 3 6H7L9 3H15L17 6H21C21.5304 6 22.0391 6.21071 22.4142 6.58579C22.7893 6.96086 23 7.46957 23 8V19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 17C14.2091 17 16 15.2091 16 13C16 10.7909 14.2091 9 12 9C9.79086 9 8 10.7909 8 13C8 15.2091 9.79086 17 12 17Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Subir Fotografía</h2>
            </div>
        </div>

        <div style="padding: 40px; text-align: center;">
            <form action="{{ route('system.student.upload-profile-image', ['student_id' => $student_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="max-width: 400px; margin: 0 auto;">
                    <div style="width: 200px; height: 200px; margin: 0 auto 24px; border-radius: 50%; overflow: hidden; border: 4px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <img id="imagePreview" src="{{ route('system.student.image', ['student_id' => $student_id]) }}" alt="Vista previa" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>

                    <div class="modern-field" style="margin-bottom: 24px;">
                        <label for="image">SELECCIONA LA IMAGEN</label>
                        <div class="file-input">
                            <input class="file-input-native" type="file" name="image" id="image" accept="image/*">
                            <label for="image" class="file-input-label">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 2.58579 20.4142C2.21071 20.0391 2 19.5304 2 19V15M17 8L12 3M12 3L7 8M12 3V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span id="fileName">Seleccionar archivo</span>
                            </label>
                        </div>
                    </div>

                    <button class="btn-modern btn-primary" type="submit" style="width: 100%; padding: 14px;" onclick="showLoader(true)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 2.58579 20.4142C2.21071 20.0391 2 19.5304 2 19V15M7 10L12 15M12 15L17 10M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Subir Fotografía
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="modern-card" style="margin-bottom: 24px;">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>Imagen Actual</h2>
                </div>
            </div>

            <div style="padding: 40px; text-align: center;">
                <div style="width: 300px; height: 300px; margin: 0 auto; border-radius: 16px; overflow: hidden; border: 4px solid #10b981; box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);">
                    <img src="{{ route('system.student.image', ['student_id' => $student_id]) }}" alt="Imagen de perfil" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
<script src="{{ asset('assets/js/profile_image_preview.js') }}"></script>
@endpush
