<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IntraCEC - Acceso</title>
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
                    <h2 class="login-title">Iniciar Sesión</h2>
                    <p class="login-subtitle">Bienvenido de nuevo, ingresa tus credenciales</p>
                </div>

                @if (session('error'))
                    <div class="alert-modern alert-danger">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M10 6V10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10 14H10.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert-modern alert-success">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M6 10L9 13L14 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" class="login-form" novalidate action="{{ route('attemptLogin') }}">
                    @csrf

                    <div class="form-group-modern">
                        <label for="username" class="form-label-modern">Usuario</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 10C12.7614 10 15 7.76142 15 5C15 2.23858 12.7614 0 10 0C7.23858 0 5 2.23858 5 5C5 7.76142 7.23858 10 10 10Z" fill="currentColor"/>
                                <path d="M10 12C4.47715 12 0 14.4772 0 17.5V20H20V17.5C20 14.4772 15.5228 12 10 12Z" fill="currentColor"/>
                            </svg>
                            <input type="text" id="username" class="form-control-modern" placeholder="Ingresa tu usuario"
                                   name="username" onchange="validateInput(event,'text')" required autocomplete="username">
                        </div>
                        <span class="form-error">El usuario es requerido</span>
                    </div>

                    <div class="form-group-modern">
                        <label for="password" class="form-label-modern">Contraseña</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="8" width="14" height="11" rx="2" stroke="currentColor" stroke-width="2"/>
                                <path d="M6 8V5C6 2.79086 7.79086 1 10 1V1C12.2091 1 14 2.79086 14 5V8" stroke="currentColor" stroke-width="2"/>
                                <circle cx="10" cy="13" r="1" fill="currentColor"/>
                            </svg>
                            <input type="password" id="password" class="form-control-modern" placeholder="Ingresa tu contraseña"
                                   name="password" required autocomplete="current-password">
                        </div>
                        <span class="form-error">La contraseña es requerida</span>
                    </div>

                    <button type="submit" class="btn-modern btn-primary">
                        <span>Acceder</span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 10H16M16 10L12 6M16 10L12 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <div class="login-footer">
                        <a href="{{ route('password.request') }}" class="link-modern">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </form>
            </div>

            <div class="login-info">
                <p>&copy; {{ date('Y') }} CEC - Todos los derechos reservados</p>
            </div>
        </div>
    </div>

    <div id="login-loader" class="login-loader" aria-hidden="true">
        <div class="login-loader-spinner" role="status" aria-label="Cargando">
            <img src="{{ asset('assets/img/cec.png') }}" alt="" aria-hidden="true">
        </div>
    </div>

    <script src="{{ asset('assets/js/all.min.js') }}"></script>
    <script>
        (function() {
            const form = document.querySelector('.login-form');
            const loader = document.getElementById('login-loader');
            const showDelayMs = 150;
            const minDisplayMs = 1000;
            let loaderTimer;

            if (form && loader) {
                form.addEventListener('submit', (event) => {
                    if (form.dataset.submitting) {
                        return;
                    }

                    event.preventDefault();
                    form.dataset.submitting = 'true';

                    loaderTimer = setTimeout(() => {
                        loader.classList.add('is-visible');
                    }, showDelayMs);

                    setTimeout(() => {
                        form.submit();
                    }, minDisplayMs);
                });

                window.addEventListener('pageshow', () => {
                    clearTimeout(loaderTimer);
                    loader.classList.remove('is-visible');
                    delete form.dataset.submitting;
                });
            }
        })();
    </script>
</body>

</html>
