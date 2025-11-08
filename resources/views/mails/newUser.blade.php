<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo usuario</title>
</head>
<body style="font-family: Arial, sans-serif; color:#333; margin:0; padding:0;">
    <div style="background-color:#f37021; padding:20px; text-align:center;">
        <img src="https://intranet.capacitacioncec.edu.mx/images/logo-cec.png"
             alt=""
             style="max-width:200px; height:auto;">
    </div>

    <div style="padding:20px; text-align:center;">
        <h2 style="color:#f37021; margin-bottom:5px;">Nuevo usuario intranet</h2></br>
        <h3 style="margin-top:5px;">Bienvenido a CEC, {{ $user['name'] }}!!</h3>
        <p style="margin:20px 0;">A continuación te proporcionamos tus datos de acceso a la intranet:</p>

        <p>
            <strong style="color:#f37021;">Usuario:</strong>
            <span style="font-weight:bold; color:#000;">{{ $user['username'] }}</span>
        </p>
        <p>
            <strong style="color:#f37021;">Contraseña:</strong>
            <span style="font-weight:bold; color:#000;">{{ $password }}</span>
        </p>

        <p style="margin-top:30px;">
            <a href="https://intranet.capacitacioncec.edu.mx"
               style="background-color:#f37021; color:white; padding:12px 25px;
                      text-decoration:none; font-size:16px; border-radius:6px; display:inline-block;">
                Acceder a la Intranet
            </a>
        </p>
    </div>

    <div style="background-color:#f5f5f5; padding:20px; text-align:center;">
        <img src="https://intranet.capacitacioncec.edu.mx/images/footer-cec.png"
             alt=""
             style="max-width:150px; height:auto; opacity:0.8;">
    </div>

</body>
</html>
