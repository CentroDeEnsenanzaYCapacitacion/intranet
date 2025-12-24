@extends('layout.mainLayout')

@section('title', 'Cambiar contraseña')

@section('content')
    <div style="max-width: 1500px; margin: 0 auto; width: 100%;">
        <div class="dashboard-welcome">
            <h1 class="dashboard-title">Cambiar contraseña</h1>
            <p class="dashboard-subtitle">Actualiza tu contraseña de acceso.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success" style="background: #d1fae5; border: 1px solid #065f46; color: #065f46; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" style="background: #fee2e2; border: 1px solid #991b1b; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.change.update') }}" id="changePasswordForm" onsubmit="showLoader(true)">
            @csrf

            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="11" width="16" height="9" rx="2" stroke="currentColor" stroke-width="2"/>
                            <path d="M8 11V8C8 5.79086 9.79086 4 12 4C14.2091 4 16 5.79086 16 8V11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="12" cy="15.5" r="1.5" fill="currentColor"/>
                        </svg>
                        <h2>Seguridad de la cuenta</h2>
                    </div>
                </div>

                <div style="padding: 24px;">
                    <div class="row">
                        <div class="col-12 col-lg-8 mx-auto">
                            <div class="modern-field">
                                <label for="current_password">Contraseña actual</label>
                                <input type="password"
                                       class="form-control modern-input"
                                       id="current_password"
                                       name="current_password"
                                       placeholder="Contraseña actual"
                                       required>
                            </div>
                            @error('current_password')
                                <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row" style="margin-top: 16px;">
                        <div class="col-12 col-lg-8 mx-auto">
                            <div class="modern-field">
                                <label for="password">Nueva contraseña</label>
                                <input type="password"
                                       class="form-control modern-input"
                                       id="password"
                                       name="password"
                                       placeholder="Nueva contraseña"
                                       required>
                                <small class="text-muted" style="display: block; margin-top: 6px;">
                                    Debe tener al menos 8 caracteres y ser diferente a la actual.
                                </small>
                            </div>
                            @error('password')
                                <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row" style="margin-top: 16px;">
                        <div class="col-12 col-lg-8 mx-auto">
                            <div class="modern-field">
                                <label for="password_confirmation">Confirmar nueva contraseña</label>
                                <input type="password"
                                       class="form-control modern-input"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="Confirmar nueva contraseña"
                                       required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: center; gap: 12px; margin-top: 32px; margin-bottom: 60px;">
                <button type="submit" class="btn-modern btn-primary" style="min-width: 200px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16L21 8V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17 21V13H7V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 3V8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Cambiar contraseña
                </button>
                <a href="{{ route('dashboard') }}" class="btn-modern" style="min-width: 200px; background: white; color: #6b7280; border: 1px solid #e5e7eb;" onclick="showLoader(true)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Volver
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password_confirmation').value;

        if (password !== confirmation) {
            e.preventDefault();
            alert('Las contrase\u00f1as no coinciden.');
            return false;
        }

        if (password.length < 8) {
            e.preventDefault();
            alert('La contrase\u00f1a debe tener al menos 8 caracteres.');
            return false;
        }
    });
</script>
@endpush
