<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>404</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:700,900" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}" />
	<link type="text/css" rel="stylesheet" href="{{ asset('assets/css/404.css') }}" />

</head>

<body>

	<div id="notfound">
		<div class="notfound-bg"></div>
		<div class="notfound">
			<div class="notfound-404">
				<h1>404</h1>
			</div>
			<h2>Lo sentimos, la página que buscas no existe</h2>
			<a href="{{ route('dashboard') }}" class="home-btn">Dashboard</a>
		</div>
	</div>
</body>
</html>