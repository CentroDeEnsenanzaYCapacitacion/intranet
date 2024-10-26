<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo usuario</title>
</head>
<body>
    <h1>Bienvenido a CEC, {{ $user['name'] }}!!</h1>
    <h3>A continuación te proporcionamos tus datos de acceso a la intranet:</h3>
    <p><strong>usuario:</strong>{{ $user['username'] }} </p>
    <p><strong>Contraseña:</strong> {{ $password }}</p>
</body>
</html>
