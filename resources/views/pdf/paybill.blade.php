<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paybill</title>
    <style>
        .centered{
            text-align: center;
        }
        .sided{
            text-align: left;
        }
        .title{
            font-size: 8px;
        }
        .title2{
            font-size: 12px;
            font-weight: bold;
        }
        .text-uppercase{
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="centered">
        <img width="150px" src= "https://capacitacioncec.edu.mx/intranet_dev/assets/img/drac_bw.png">
    </div>
    <span class="title centered text-uppercase">
        {{ $paybill->crew->name }}<br>
        <div style="border-top: 1px solid #000; height: 0; margin: 0; padding: 0;"></div>
        <span class="title2">
            VALE DE CAJA
        </span>
    </span>
    <span class="title sided text-uppercase">
        <br><br>
        <b>Fecha:</b> {{ $paybill->created_at->format('d/m/Y H:i') }}<br>
        <b>Folio:</b> V{{ $paybill->crew->name[0] }}/{{ $paybill->id }}/{{ Str::substr($paybill->created_at, 2, 2) }}<br><br>
        <b>Autoriza:</b><br><br><br><br><br><br><br><br>
        <div style="border-top: 1px solid #000; height: 0; margin: 0; padding: 0;"></div>
        <div class="centered" style="margin-top: 5px;">{{ $paybill->user->name .' '. $paybill->user->surnames }}</div><br><br>
        <b>Recibe:</b><br><br><br><br><br><br><br><br>
        <div style="border-top: 1px solid #000; height: 0; margin: 0; padding: 0;"></div>
        <div class="centered">{{ $paybill->receives}}</div><br><br>
        <b>Concepto:</b> {{ $paybill->concept }}<br>
        <b>Importe:</b> ${{ number_format($paybill->amount, 2, '.', ',') }}<br>

        <span class="centered">
            <div style="border-top: 1px solid #000; height: 0; margin: 0; padding: 0;"></div>
            <table border="0">
                <tr>
                    <td width="80" align="center" style="vertical-align: middle;">
                        <div style="display: flex; align-items: center; justify-content: center;">
                            <img src="https://capacitacioncec.edu.mx/intranet_dev/assets/img/qr.png">
                        </div>
                    </td>
                    <td width="130">
                        <br><br>
                        <img src="https://capacitacioncec.edu.mx/intranet_dev/assets/img/logo.png">
                    </td>
                </tr>
            </table>
            <div style="border-top: 1px solid #000; height: 0; margin: 0; padding: 0;"></div>
            {{ $paybill->crew->adress }}<br>
            {{ $paybill->crew->phone }}<br>
            {{ $paybill->crew->mail }}<br>
            www.capacitacioncec.edu.mx
        </span>
    </span>
</body>
</html>
