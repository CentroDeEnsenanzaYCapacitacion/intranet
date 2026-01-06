<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitación a IntraCEC</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif; background-color: #f3f4f6;">
    <table cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 600px; background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); overflow: hidden;">

                    <tr>
                        <td style="background-color: #F57F17; background: linear-gradient(135deg, #F57F17 0%, #F9A825 100%); padding: 50px 40px; text-align: center;">
                            <table cellspacing="0" cellpadding="0" border="0" width="80" align="center" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="background-color: rgba(255, 255, 255, 0.2); width: 80px; height: 80px; border-radius: 50%; text-align: center; vertical-align: middle;">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle;">
                                            <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <circle cx="12" cy="7" r="4" stroke="#ffffff" stroke-width="2"/>
                                        </svg>
                                    </td>
                                </tr>
                            </table>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">¡Bienvenido a IntraCEC!</h1>
                            <p style="margin: 12px 0 0; color: #ffffff; opacity: 0.9; font-size: 15px;">Tu cuenta ha sido creada exitosamente</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 48px 40px;">
                            <p style="margin: 0 0 8px; color: #1a1a1a; font-size: 18px; font-weight: 600;">Hola, {{ $user->name }}</p>
                            <p style="margin: 0 0 32px; color: #6b7280; font-size: 15px; line-height: 1.6;">Para acceder al sistema, primero debes establecer tu contraseña personal.</p>

                            <table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 32px;">
                                <tr>
                                    <td style="background-color: #fef3c7; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 50%); padding: 24px; border-radius: 12px;">
                                        <p style="margin: 0 0 8px; color: #92400e; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Tu nombre de usuario</p>
                                        <p style="margin: 0; color: #78350f; font-size: 20px; font-weight: 700; font-family: 'SF Mono', 'Monaco', 'Courier New', monospace;">{{ $user->username }}</p>
                                    </td>
                                </tr>
                            </table>

                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 40px 0;">
                                        <a href="{{ $invitationUrl }}" style="display: inline-block; background-color: #F57F17; background: linear-gradient(135deg, #F57F17 0%, #F9A825 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 10px; font-size: 16px; font-weight: 600; box-shadow: 0 4px 12px rgba(245, 127, 23, 0.3);">Establecer mi contraseña</a>
                                    </td>
                                </tr>
                            </table>

                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="background: #f9fafb; padding: 20px; border-radius: 10px; border-left: 4px solid #F57F17;">
                                        <p style="margin: 0 0 12px; color: #374151; font-size: 14px; line-height: 1.6;"><strong style="color: #F57F17;">Importante:</strong> Este enlace es válido por 7 días.</p>
                                        <p style="margin: 0; color: #9ca3af; font-size: 12px; line-height: 1.5;">Si no puedes hacer clic en el botón, copia y pega este enlace en tu navegador: <span style="color: #6b7280; word-break: break-all;">{{ $invitationUrl }}</span></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="background: #1a1a1a; padding: 32px 40px; text-align: center;">
                            <p style="margin: 0; color: #9ca3af; font-size: 13px; line-height: 1.6;">&copy; {{ date('Y') }} Centro de Capacitación CEC<br><span style="color: #6b7280;">Sistema de Gestión Académica</span></p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
