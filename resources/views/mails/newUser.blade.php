<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bienvenido a IntraCEC</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f8f9fa;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f8f9fa;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 600px; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);">

                    <tr>
                        <td style="background: linear-gradient(135deg, #F57F17 0%, #F9A825 100%); padding: 40px 40px 30px; text-align: center;">
                            <img src="https://intranet.capacitacioncec.edu.mx/images/logo-cec.png"
                                 alt="IntraCEC"
                                 style="max-width: 180px; height: auto; margin-bottom: 20px;">
                            <h1 style="margin: 0; color: white; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">
                                ¡Bienvenido a IntraCEC!
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding-bottom: 24px;">
                                        <h2 style="margin: 0 0 8px; color: #3B2F2F; font-size: 20px; font-weight: 600;">
                                            Hola, {{ $user['name'] }}
                                        </h2>
                                        <p style="margin: 0; color: #6E5F52; font-size: 15px; line-height: 1.6;">
                                            Tu cuenta ha sido creada exitosamente. A continuación encontrarás tus credenciales de acceso al sistema.
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 24px 0;">
                                        <div style="background: #FFF8F0; border: 1px solid #CFAF94; border-radius: 12px; padding: 24px;">
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                <tr>
                                                    <td style="padding-bottom: 16px;">
                                                        <p style="margin: 0 0 4px; color: #8B6B5A; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                                            Usuario
                                                        </p>
                                                        <p style="margin: 0; color: #3B2F2F; font-size: 16px; font-weight: 600; font-family: 'Courier New', monospace; background: white; padding: 12px; border-radius: 8px; border: 1px solid #CFAF94;">
                                                            {{ $user['username'] }}
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p style="margin: 0 0 4px; color: #8B6B5A; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                                            Contraseña
                                                        </p>
                                                        <p style="margin: 0; color: #3B2F2F; font-size: 16px; font-weight: 600; font-family: 'Courier New', monospace; background: white; padding: 12px; border-radius: 8px; border: 1px solid #CFAF94;">
                                                            {{ $password }}
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 24px 0; text-align: center;">
                                        <a href="https://intranet.capacitacioncec.edu.mx"
                                           style="display: inline-block; background: linear-gradient(135deg, #F57F17 0%, #F9A825 100%); color: white; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-size: 16px; font-weight: 600; box-shadow: 0 4px 12px rgba(245, 127, 23, 0.3);">
                                            Acceder a la Intranet
                                        </a>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-top: 24px; border-top: 1px solid #E5E7EB;">
                                        <div style="background: #FFF8F0; border-left: 4px solid #F57F17; padding: 16px; border-radius: 8px;">
                                            <p style="margin: 0; color: #6E5F52; font-size: 14px; line-height: 1.6;">
                                                <strong style="color: #F57F17;">💡 Importante:</strong> Por seguridad, te recomendamos cambiar tu contraseña después de iniciar sesión por primera vez.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="background: #3B2F2F; padding: 30px 40px; text-align: center;">
                            <img src="https://intranet.capacitacioncec.edu.mx/images/footer-cec.png"
                                 alt="CEC"
                                 style="max-width: 140px; height: auto; opacity: 0.6; margin-bottom: 16px;">
                            <p style="margin: 8px 0 0; color: #9CA3AF; font-size: 13px; line-height: 1.5;">
                                © {{ date('Y') }} Centro de Capacitación CEC<br>
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
