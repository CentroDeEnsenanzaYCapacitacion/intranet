<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Nuevo ticket de soporte</title>
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
                                Nuevo Ticket de Soporte
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 24px; color: #666666; font-size: 15px; line-height: 1.6;">
                                Se ha creado un nuevo ticket de soporte en el sistema.
                            </p>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #fef8f3; border: 1px solid #f5d5c0; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding-bottom: 16px;">
                                                    <p style="margin: 0 0 6px; color: #666666; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Título
                                                    </p>
                                                    <p style="margin: 0; color: #2c2c2c; font-size: 16px; font-weight: 600; line-height: 1.4;">
                                                        {{ $ticket->title }}
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: 1px solid #f0e5dc; padding-top: 16px; padding-bottom: 16px;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <tr>
                                                            <td width="50%" style="padding-right: 8px;">
                                                                <p style="margin: 0 0 6px; color: #666666; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                                                    Prioridad
                                                                </p>
                                                                <p style="margin: 0; color: #2c2c2c; font-size: 14px; font-weight: 500; text-transform: capitalize;">
                                                                    {{ $ticket->priority }}
                                                                </p>
                                                            </td>
                                                            <td width="50%" style="padding-left: 8px;">
                                                                <p style="margin: 0 0 6px; color: #666666; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                                                    Categoría
                                                                </p>
                                                                <p style="margin: 0; color: #2c2c2c; font-size: 14px; font-weight: 500;">
                                                                    {{ $ticket->category->name ?? 'Sin categoría' }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: 1px solid #f0e5dc; padding-top: 16px;">
                                                    <p style="margin: 0 0 6px; color: #666666; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Creado por
                                                    </p>
                                                    <p style="margin: 0; color: #2c2c2c; font-size: 14px; font-weight: 500;">
                                                        {{ $ticket->user->name }}
                                                    </p>
                                                </td>
                                            </tr>
                                            @if($ticket->description)
                                            <tr>
                                                <td style="border-top: 1px solid #f0e5dc; padding-top: 16px;">
                                                    <p style="margin: 0 0 8px; color: #666666; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Descripción
                                                    </p>
                                                    <p style="margin: 0; color: #2c2c2c; font-size: 14px; line-height: 1.6; background: #ffffff; padding: 12px; border-radius: 4px; border: 1px solid #e0e0e0;">
                                                        {{ $ticket->description }}
                                                    </p>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
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
