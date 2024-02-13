<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    @vite(['resources/css/app.css',
        'resources/css/bootstrap.min.css',
        'resources/js/bootstrap.min.js',
        'resources/js/loader.js',
        'resources/js/confirmAlert.js'
    ])
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    <title>@yield('title')</title>
</head>
<body>
    @include('includes.header')
    <div class="container">
        <div id="loader-container">
            <div id="loading"></div>
        </div>
        @yield('content')
    </div>
    {{-- <script src="{{asset('assets/js/heartBeat.js')}}"></script> --}}
</body>
</html>