<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet"></link>
    <link href="{{asset('assets/css/styles.css')}}" rel="stylesheet"></link>
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="{{asset('assets/img/IC.png')}}" type="image/png">
    <link rel="icon" href="{{asset('assets/img/IC.png')}}" media="(prefers-color-scheme: light)">
    <link rel="icon" href="{{asset('assets/img/IC-White.png')}}" media="(prefers-color-scheme: dark)">
    <title>@yield('title')</title>
    @stack('styles')
</head>
<body class="modern-layout">
    @include('includes.header')
    <main class="main-content">
        <div class="container custom-container">
        <div id="loader-container" aria-hidden="true">
            <div class="app-loader-spinner" role="status" aria-label="Cargando">
                <img src="{{ asset('assets/img/cec.png') }}" alt="" aria-hidden="true">
            </div>
        </div>
            @yield('content')
        </div>
    </main>
    @include('includes.footer')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/loader.js')}}"></script>
    <script src="{{asset('assets/js/confirmAlert.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        const appEnv = @json(env('APP_ENV'));
        flatpickr("#datePicker", {
          enableTime: false,
          dateFormat: "d/m/Y",
        });
      </script>
    @stack('scripts')
</body>
</html>
