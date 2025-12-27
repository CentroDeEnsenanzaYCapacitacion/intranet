<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar contraseña - IntraCEC</title>
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
                    <h2 class="login-title">Recuperar contraseña</h2>
                    <p class="login-subtitle">Ingresa tu correo electrónico y te enviaremos las instrucciones para restablecer tu contraseña</p>
                </div>

                @if (session('success'))
                    <div class="alert-modern alert-success">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M6 10L9 13L14 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any() && !$errors->has('email'))
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

                <form method="POST" action="{{ route('password.email') }}" class="login-form" novalidate>
                    @csrf

                    <div class="form-group-modern">
                        <label for="email" class="form-label-modern">Correo electrónico</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 4C2 2.89543 2.89543 2 4 2H16C17.1046 2 18 2.89543 18 4V14C18 15.1046 17.1046 16 16 16H4C2.89543 16 2 15.1046 2 14V4Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M2 4L10 10L18 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <input type="email"
                                   id="email"
                                   class="form-control-modern @error('email') is-invalid @enderror"
                                   placeholder="Ingresa tu correo electrónico"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="email">
                        </div>
                        @error('email')
                            <span class="form-error" style="display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-modern btn-primary">
                        <span>Enviar enlace de recuperación</span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 10L2 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 2L18 10L10 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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
