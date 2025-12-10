<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar contraseña - IntraCEC</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="/" class="h1"><b>Intra</b>CEC</a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Recuperar contraseña</p>
                    <p class="text-muted text-center mb-3" style="font-size: 14px;">
                        Ingresa tu correo electrónico y te enviaremos las instrucciones para restablecer tu contraseña.
                    </p>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="Correo electrónico"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn bg-orange btn-block"
                                    style="color:white !important">Enviar enlace de recuperación</button>
                            </div>
                        </div>
                    </form>

                    <p class="mt-3 mb-1 text-center">
                        <a href="{{ route('login') }}">Volver al inicio de sesión</a>
                    </p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success text-center mt-2">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any() && !$errors->has('email'))
                <div class="alert alert-danger text-center mt-2">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <script src="{{ asset('assets/js/all.min.js') }}"></script>
</body>

</html>
