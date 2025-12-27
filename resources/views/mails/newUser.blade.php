<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bienvenido a IntraCEC</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 600px; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">

                    <tr>
                        <td style="background-color: #f37021; padding: 40px 30px; text-align: center;">
                            <img src="https://intranet.capacitacioncec.edu.mx/images/logo-cec.png"
                                 alt="IntraCEC"
                                 style="max-width: 180px; height: auto; margin-bottom: 20px;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 26px; font-weight: 600; line-height: 1.3;">
                                ¡Bienvenido a IntraCEC!
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin: 0 0 10px; color: #2c2c2c; font-size: 20px; font-weight: 600;">
                                Hola, {{ $user['name'] }}
                            </h2>
                            <p style="margin: 0 0 24px; color: #666666; font-size: 15px; line-height: 1.6;">
                                Tu cuenta ha sido creada exitosamente. A continuación encontrarás tus credenciales de acceso al sistema.
                            </p>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #fef8f3; border: 1px solid #f5d5c0; border-radius: 6px; margin: 24px 0;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding-bottom: 16px;">
                                                    <p style="margin: 0 0 6px; color: #666666; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Usuario
                                                    </p>
                                                    <p style="margin: 0; color: #2c2c2c; font-size: 16px; font-weight: 600; font-family: 'Courier New', Courier, monospace; background: #ffffff; padding: 12px; border-radius: 4px; border: 1px solid #e0e0e0;">
                                                        {{ $user['username'] }}
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p style="margin: 0 0 6px; color: #666666; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Contraseña
                                                    </p>
                                                    <p style="margin: 0; color: #2c2c2c; font-size: 16px; font-weight: 600; font-family: 'Courier New', Courier, monospace; background: #ffffff; padding: 12px; border-radius: 4px; border: 1px solid #e0e0e0;">
                                                        {{ $password }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 24px 0;">
                                        <a href="https://intranet.capacitacioncec.edu.mx"
                                           style="display: inline-block; background-color: #f37021; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-size: 16px; font-weight: 600;">
                                            Acceder a la Intranet
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #fff9f5; border-left: 4px solid #f37021; border-radius: 4px; margin-top: 24px;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <p style="margin: 0; color: #666666; font-size: 14px; line-height: 1.6;">
                                            <strong style="color: #f37021;">Importante:</strong> Por seguridad, te recomendamos cambiar tu contraseña después de iniciar sesión por primera vez.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #2c2c2c; padding: 30px; text-align: center;">
                            <img src="https://intranet.capacitacioncec.edu.mx/images/footer-cec.png"
                                 alt="CEC"
                                 style="max-width: 140px; height: auto; opacity: 0.6; margin-bottom: 12px;">
                            <p style="margin: 0; color: #999999; font-size: 13px; line-height: 1.5;">
                                &copy; {{ date('Y') }} Centro de Capacitación CEC<br>
                                Sistema de Gestión Académica
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
