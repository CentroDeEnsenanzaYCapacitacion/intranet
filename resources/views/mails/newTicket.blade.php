<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>Nuevo ticket de soporte</title>
    <style type="text/css">
        :root {
            color-scheme: light only;
            supported-color-schemes: light;
        }
    </style>
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
                                            <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M14 2V8H20" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M12 18V12" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M9 15H15" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </td>
                                </tr>
                            </table>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">Nuevo Ticket de Soporte</h1>
                            <p style="margin: 12px 0 0; color: #ffffff; opacity: 0.9; font-size: 15px;">Se ha creado un nuevo ticket</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 48px 40px;">
                            <p style="margin: 0 0 32px; color: #6b7280; font-size: 15px; line-height: 1.6;">Se ha creado un nuevo ticket de soporte en el sistema.</p>

                            <table cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #fef3c7; border-radius: 12px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <p style="margin: 0 0 8px; color: #92400e; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Título</p>
                                        <p style="margin: 0 0 16px; color: #78350f; font-size: 18px; font-weight: 600; line-height: 1.4;">{{ $ticket->title }}</p>

                                        <table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 16px;">
                                            <tr>
                                                <td width="50%" style="padding-right: 8px;">
                                                    <p style="margin: 0 0 6px; color: #92400e; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Prioridad</p>
                                                    <p style="margin: 0; color: #78350f; font-size: 14px; font-weight: 500; text-transform: capitalize;">{{ $ticket->priority }}</p>
                                                </td>
                                                <td width="50%" style="padding-left: 8px;">
                                                    <p style="margin: 0 0 6px; color: #92400e; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Categoría</p>
                                                    <p style="margin: 0; color: #78350f; font-size: 14px; font-weight: 500;">{{ $ticket->category->name ?? 'Sin categoría' }}</p>
                                                </td>
                                            </tr>
                                        </table>

                                        <p style="margin: 0 0 6px; color: #92400e; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Creado por</p>
                                        <p style="margin: 0; color: #78350f; font-size: 14px; font-weight: 500;">{{ $ticket->user->name }}</p>

                                        @if($ticket->description)
                                        <p style="margin: 16px 0 6px; color: #92400e; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Descripción</p>
                                        <p style="margin: 0; color: #78350f; font-size: 14px; line-height: 1.6; background: #ffffff; padding: 12px; border-radius: 8px;">{{ $ticket->description }}</p>
                                        @endif
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
