<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña</title>
</head>
<body style="font-family: Arial, sans-serif; color:#333; margin:0; padding:0;">
    <div style="background-color:#f37021; padding:20px; text-align:center;">
        <img src="https://intranet.capacitacioncec.edu.mx/images/logo-cec.png"
             alt=""
             style="max-width:200px; height:auto;">
    </div>

    <div style="padding:20px; text-align:center;">
        <h2 style="color:#f37021; margin-bottom:5px;">Restablecer contraseña</h2></br>
        <h3 style="margin-top:5px;">Hola {{ $user['name'] }}!</h3>
        <p style="margin:20px 0;">Recibimos una solicitud para restablecer la contraseña de tu cuenta en IntraCEC.</p>

        <p style="margin:20px 0;">Para restablecer tu contraseña, haz clic en el siguiente botón:</p>

        <p style="margin-top:30px;">
            <a href="{{ $resetUrl }}"
               style="background-color:#f37021; color:white; padding:12px 25px;
                      text-decoration:none; font-size:16px; border-radius:6px; display:inline-block;">
                Restablecer contraseña
            </a>
        </p>

        <p style="margin-top:30px; font-size:14px; color:#666;">
            Si no puedes hacer clic en el botón, copia y pega el siguiente enlace en tu navegador:
        </p>
        <p style="font-size:12px; color:#999; word-break:break-all;">
            {{ $resetUrl }}
        </p>

        <p style="margin-top:30px; font-size:14px; color:#666;">
            <strong>Este enlace expirará en 1 hora.</strong>
        </p>

        <p style="margin-top:20px; font-size:14px; color:#666;">
            Si no solicitaste restablecer tu contraseña, puedes ignorar este correo.
        </p>
    </div>

    <div style="background-color:#f5f5f5; padding:20px; text-align:center;">
        <img src="https://intranet.capacitacioncec.edu.mx/images/footer-cec.png"
             alt=""
             style="max-width:150px; height:auto; opacity:0.8;">
    </div>

</body>
</html>
