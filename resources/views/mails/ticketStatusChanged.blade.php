<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>Ticket actualizado</title>
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
                                            <path d="M22 11.08V12C21.9988 14.1564 21.3005 16.2547 20.0093 17.9818C18.7182 19.709 16.9033 20.9725 14.8354 21.5839C12.7674 22.1953 10.5573 22.1219 8.53447 21.3746C6.51168 20.6273 4.78465 19.2461 3.61096 17.4371C2.43727 15.628 1.87979 13.4881 2.02168 11.3363C2.16356 9.18455 2.99721 7.13631 4.39828 5.49706C5.79935 3.85781 7.69279 2.71537 9.79619 2.24013C11.8996 1.7649 14.1003 1.98232 16.07 2.85999" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M22 4L12 14.01L9 11.01" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </td>
                                </tr>
                            </table>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">Ticket Actualizado</h1>
                            <p style="margin: 12px 0 0; color: #ffffff; opacity: 0.9; font-size: 15px;">El estado ha cambiado</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 48px 40px;">
                            <p style="margin: 0 0 32px; color: #6b7280; font-size: 15px; line-height: 1.6;">El estado de tu ticket de soporte ha sido actualizado.</p>

                            <table cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #fef3c7; border-radius: 12px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <p style="margin: 0 0 8px; color: #92400e; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Título</p>
                                        <p style="margin: 0 0 16px; color: #78350f; font-size: 18px; font-weight: 600; line-height: 1.4;">{{ $ticket->title }}</p>

                                        <p style="margin: 0 0 6px; color: #92400e; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Estado anterior</p>
                                        <p style="margin: 0 0 16px; color: #78350f; font-size: 14px; font-weight: 500; text-transform: capitalize;">{{ str_replace('_', ' ', $oldStatus) }}</p>

                                        <p style="margin: 0 0 8px; color: #92400e; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Estado actual</p>
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="background: #F57F17; padding: 12px 24px; border-radius: 8px;">
                                                    <p style="margin: 0; color: #ffffff; font-size: 15px; font-weight: 600; text-transform: capitalize;">{{ str_replace('_', ' ', $ticket->status) }}</p>
                                                </td>
                                            </tr>
                                        </table>
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
