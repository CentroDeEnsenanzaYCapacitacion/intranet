<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
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
        CENTRO DE ENSEÑANZA Y CAPACITACIÓN<br>
        VICTOR RAUL BAENA TOLEDO<br>
        RFC: BATV850516E15<br>
        JUAREZ SUR 301, COL. TEXCOCO CENTRO<br>
        C.P.: 56150  TEXCOCO EDO. DE MEX.<br>
        CURP: BATV850516HMCNLC09<br>
        RÉGIMEN DE INCORPORACION FISCAL<br>
        <div style="border-top: 1px solid #000; height: 0; margin: 0; padding: 0;"></div>
        {{ $receipt->crew->name }}<br>
        {{ $receipt->crew->adress }}<br>
        {{ $receipt->crew->phone }}<br>
        <div style="border-top: 1px solid #000; height: 0; margin: 0; padding: 0;"></div>
        <span class="title2">
            COMPROBANTE DE PAGO
        </span>
    </span>
    <span class="title sided text-uppercase">
        <br><br>
        Fecha: {{ $receipt->created_at->format('d/m/Y H:i') }}<br>
        Folio: {{ $receipt->crew->name[0] }}/{{ $receipt->id }}/{{ Str::substr($receipt->created_at, 2, 2) }}<br>
        @if($receipt->student_id) Matrícula:{{ $receipt->student_id }}<br>@endif
        {{ $receipt->student_id ? "Alumno:" : "Nombre:" }} {{ $receipt->student_id ? $receipt->student->name .' '. $receipt->student->surnames : $receipt->report->name.' '. $receipt->report->surnames }}<br>
        Concepto: {{ $receipt->concept }}<br>
        Importe: ${{ number_format($receipt->amount, 2, '.', ',') }}<br>
        {{ strtoupper(\App\Helpers\Utils::numberToText($receipt->amount)) }} PESOS 00/100 MN<br>
        PAGO {{ $receipt->payment_type_id==1?"EN EFECTIVO":"CON TARJETA" }}<br>
        Le atendió: {{ $receipt->user->name.' '.$receipt->user->surnames }}<br>
        <span class="centered">
            <div style="border-top: 1px solid #000; height: 0; margin: 0; padding: 0;"></div>
            Recuerda que debes conservar todos tus tickets para cualquier aclaración.<br>
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
            {{ $receipt->crew->mail }}<br>
            www.capacitacioncec.edu.mx
        </span>
    </span>
</body>
</html>
