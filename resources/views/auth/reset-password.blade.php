<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer contraseña - IntraCEC</title>
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
                    <p class="login-box-msg">Restablecer contraseña</p>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="input-group mb-3">
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Nueva contraseña"
                                   name="password"
                                   required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <input type="password"
                                   class="form-control"
                                   placeholder="Confirmar contraseña"
                                   name="password_confirmation"
                                   required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>

                        <p class="text-muted text-center mb-3" style="font-size: 13px;">
                            La contraseña debe tener al menos 8 caracteres.
                        </p>

                        <div class="row">
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn bg-orange btn-block"
                                    style="color:white !important">Restablecer contraseña</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if ($errors->any() && !$errors->has('password'))
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
