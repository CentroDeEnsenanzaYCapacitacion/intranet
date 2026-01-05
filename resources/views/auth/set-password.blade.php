<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer contraseña - IntraCEC</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <script>
        window.onload = function() {
            var isMobile = /iPhone|iPad|iPod|Android|webOS|BlackBerry|Windows Phone/i.test(navigator.userAgent);
            if (isMobile) {
                document.body.innerHTML = '';
            }
        };
    </script>
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
</head>

<body class="login-body">
    <div class="login-container">
        <div class="login-left">
            <div class="login-brand">
                <div class="brand-icon">
                    <img src="{{ asset('assets/img/IC.png') }}" alt="IntraCEC" width="80" height="80" class="logo-white">
                </div>
                <h1 class="brand-title"><span class="brand-bold">Intra</span>CEC</h1>
                <p class="brand-subtitle">Sistema de Gestión Académica</p>
            </div>
            <a class="login-github login-github-left" href="https://github.com/AjRoBSeYeR" target="_blank" rel="noopener noreferrer" aria-label="GitHub">
                <img src="{{ asset('assets/img/as.png') }}" alt="GitHub" width="40" height="40">
            </a>
            <div class="login-illustration">
                <img src="{{ asset('assets/img/cec.png') }}" alt="CEC" class="login-watermark">
            </div>
        </div>

        <div class="login-right">
            <div class="login-box-modern">
                <div class="login-header">
                    <h2 class="login-title">Establece tu contraseña</h2>
                    <p class="login-subtitle">Crea una contraseña segura para acceder al sistema</p>
                </div>

                @if ($errors->any() && !$errors->has('password'))
                    <div class="alert-modern alert-danger">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M10 6V10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10 14H10.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </span>
                    </div>
                @endif

                <form method="POST" action="{{ route('set-password.post') }}" class="login-form" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group-modern">
                        <label for="password" class="form-label-modern">Nueva contraseña</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="8" width="14" height="11" rx="2" stroke="currentColor" stroke-width="2"/>
                                <path d="M6 8V5C6 2.79086 7.79086 1 10 1V1C12.2091 1 14 2.79086 14 5V8" stroke="currentColor" stroke-width="2"/>
                                <circle cx="10" cy="13" r="1" fill="currentColor"/>
                            </svg>
                            <input type="password"
                                   id="password"
                                   class="form-control-modern @error('password') is-invalid @enderror"
                                   placeholder="Ingresa tu nueva contraseña"
                                   name="password"
                                   required
                                   autocomplete="new-password">
                        </div>
                        @error('password')
                            <span class="form-error" style="display: block;">{{ $message }}</span>
                        @else
                            <small class="text-muted" style="display: block; margin-top: 6px; font-size: 13px; color: #6E5F52;">
                                La contraseña debe tener al menos 8 caracteres.
                            </small>
                        @enderror
                    </div>

                    <div class="form-group-modern">
                        <label for="password_confirmation" class="form-label-modern">Confirmar contraseña</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="8" width="14" height="11" rx="2" stroke="currentColor" stroke-width="2"/>
                                <path d="M6 8V5C6 2.79086 7.79086 1 10 1V1C12.2091 1 14 2.79086 14 5V8" stroke="currentColor" stroke-width="2"/>
                                <circle cx="10" cy="13" r="1" fill="currentColor"/>
                            </svg>
                            <input type="password"
                                   id="password_confirmation"
                                   class="form-control-modern"
                                   placeholder="Confirma tu nueva contraseña"
                                   name="password_confirmation"
                                   required
                                   autocomplete="new-password">
                        </div>
                    </div>

                    <button type="submit" class="btn-modern btn-primary">
                        <span>Restablecer contraseña</span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16L21 8V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17 21V13H7V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7 3V8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <div class="login-footer">
                        <a href="{{ route('login') }}" class="link-modern">
                            Volver al inicio de sesión
                        </a>
                    </div>
                </form>
            </div>

            <div class="login-info">
                <p>&copy; {{ date('Y') }} CEC - Todos los derechos reservados</p>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/all.min.js') }}"></script>
</body>

</html>
