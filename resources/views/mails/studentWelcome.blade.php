<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>Bienvenido a CEC</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif; background-color: #f3f4f6;">
    <table cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 600px; background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); overflow: hidden;">
                    <tr>
                        <td style="background-color: #F57F17; background: linear-gradient(135deg, #F57F17 0%, #F9A825 100%); padding: 50px 40px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">Bienvenido a CEC</h1>
                            <p style="margin: 12px 0 0; color: #ffffff; opacity: 0.9; font-size: 15px;">Tu cuenta ha sido creada</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px;">
                            <p style="margin: 0 0 12px; color: #1a1a1a; font-size: 18px; font-weight: 600;">Hola, {{ $student->name }}</p>
                            <p style="margin: 0 0 24px; color: #6b7280; font-size: 15px; line-height: 1.6;">
                                Usa la siguiente contrasena para acceder a la plataforma.
                            </p>
                            <table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="background-color: #fef3c7; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 50%); padding: 24px; border-radius: 12px;">
                                        <p style="margin: 0 0 8px; color: #92400e; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Contrasena temporal</p>
                                        <p style="margin: 0; color: #78350f; font-size: 20px; font-weight: 700; font-family: 'SF Mono', 'Monaco', 'Courier New', monospace;">{{ $plainPassword }}</p>
                                    </td>
                                </tr>
                            </table>
                            <table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="background: #f9fafb; padding: 20px; border-radius: 10px; border-left: 4px solid #F57F17;">
                                        <p style="margin: 0 0 8px; color: #374151; font-size: 14px; line-height: 1.6;"><strong style="color: #F57F17;">Usuario:</strong> {{ $student->email }}</p>
                                        <p style="margin: 0; color: #6b7280; font-size: 14px; line-height: 1.6;">Te recomendamos cambiar la contrasena despues de iniciar sesion.</p>
                                    </td>
                                </tr>
                            </table>
                            <table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center">
                                        <a href="https://capacitacioncec.edu.mx/student/login" style="display: inline-block; background-color: #F57F17; background: linear-gradient(135deg, #F57F17 0%, #F9A825 100%); color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-size: 14px; font-weight: 600; box-shadow: 0 4px 12px rgba(245, 127, 23, 0.3);">Entrar a la plataforma</a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin: 0 0 16px; color: #9ca3af; font-size: 12px; line-height: 1.6;">
                                Si no puedes hacer clic en el boton, copia y pega este enlace en tu navegador:
                                <span style="color: #6b7280; word-break: break-all;">https://capacitacioncec.edu.mx/student/login</span>
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px; line-height: 1.6;">Si no reconoces este correo, puedes ignorarlo.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background: #1a1a1a; padding: 24px 40px; text-align: center;">
                            <p style="margin: 0; color: #9ca3af; font-size: 12px; line-height: 1.6;">(c) {{ date('Y') }} Centro de Capacitacion CEC</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
