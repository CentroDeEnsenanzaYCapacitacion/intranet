<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo mensaje en ticket</title>
</head>
<body style="font-family: Arial, sans-serif; color:#333; margin:0; padding:0;">
    <div style="background-color:#f37021; padding:20px; text-align:center;">
        <img src="https://intranet.capacitacioncec.edu.mx/images/logo-cec.png"
             alt=""
             style="max-width:200px; height:auto;">
    </div>

    <div style="padding:20px;">
        <h2 style="color:#f37021; margin-bottom:5px;">Nuevo mensaje en ticket</h2>
        
        <p style="margin:20px 0;">Se ha añadido un nuevo mensaje a tu ticket:</p>

        <div style="background-color:#f5f5f5; padding:15px; border-left:4px solid #f37021; margin:20px 0;">
            <p style="margin:5px 0;">
                <strong style="color:#f37021;">Ticket:</strong>
                {{ $ticket->title }}
            </p>
            <p style="margin:5px 0;">
                <strong style="color:#f37021;">De:</strong>
                {{ $sender->role_id == 1 ? 'SysAdmin' : $sender->name }}
            </p>
            <p style="margin:15px 0 5px 0;">
                <strong style="color:#f37021;">Mensaje:</strong>
            </p>
            <p style="margin:5px 0; padding:10px; background-color:#fff; border-radius:4px;">
                {{ $message->message }}
            </p>
        </div>

    </div>

    <div style="background-color:#f5f5f5; padding:20px; text-align:center;">
        <img src="https://intranet.capacitacioncec.edu.mx/images/footer-cec.png"
             alt=""
             style="max-width:150px; height:auto; opacity:0.8;">
    </div>

</body>
</html>
