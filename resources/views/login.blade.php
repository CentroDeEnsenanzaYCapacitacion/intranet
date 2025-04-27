<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IntraCEC</title>
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
                    <p class="login-box-msg">Bienvenid@</p>
                    <form method="POST" class="needs-validation" novalidate action="{{ route('attemptLogin') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Usuario" name="username"
                                onchange="validateInput(event,'text')" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                            <div class="valid-feedback"></div>
                            <div class="invalid-feedback">Campo obligatorio.</div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Contraseña" name="password"
                                required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <div class="valid-feedback"></div>
                            <div class="invalid-feedback">Campo obligatorio.</div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                            </div>
                            <div class="col-8 mt-2">
                                <button type="submit" class="btn bg-orange btn-block"
                                    style="color:white !important">Acceder</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if (session('error'))
                <div class="alert alert-danger text-center  mt-2">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
    <script src="{{ asset('assets/js/all.min.js') }}"></script>
</body>

</html>
