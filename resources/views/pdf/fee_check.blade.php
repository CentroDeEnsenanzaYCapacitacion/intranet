@php
    $months = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
    $periodLabel = $report['period'] === '8-22' ? 'Del 8 al 22' : 'Del 23 al 7';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotejador de Honorarios</title>
    <style>
        body {
            font-family: helvetica, sans-serif;
            font-size: 9px;
            color: #1a1a1a;
        }
        .header-table {
            width: 100%;
            margin-bottom: 8px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .company-name {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .company-info {
            font-size: 8px;
            color: #555;
        }
        .report-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            padding: 6px 0;
            border-top: 2px solid #1a1a1a;
            border-bottom: 2px solid #1a1a1a;
            margin: 8px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .period-info {
            text-align: center;
            font-size: 10px;
            margin-bottom: 12px;
            color: #333;
        }
        .crew-title {
            font-size: 11px;
            font-weight: bold;
            color: #7c2d12;
            padding: 5px 8px;
            background-color: #fed7aa;
            margin: 10px 0 4px 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .data-table th {
            background-color: #c2410c;
            color: #ffffff;
            padding: 4px 6px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
        }
        .data-table th.num {
            text-align: right;
        }
        .data-table td {
            padding: 3px 6px;
            border-bottom: 1px solid #fdba74;
            font-size: 8px;
        }
        .data-table td.num {
            text-align: right;
        }
        .data-table tr.subtotal td {
            border-top: 2px solid #c2410c;
            font-weight: bold;
            font-size: 9px;
            background-color: #fff7ed;
            padding: 5px 6px;
        }
        .grand-total {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        .grand-total td {
            padding: 8px 10px;
            font-size: 11px;
            font-weight: bold;
        }
        .grand-total .label {
            text-align: right;
            width: 70%;
            border-top: 3px solid #1a1a1a;
        }
        .grand-total .value {
            text-align: right;
            width: 30%;
            border-top: 3px solid #1a1a1a;
        }
        .footer {
            text-align: center;
            font-size: 7px;
            color: #999;
            margin-top: 16px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
        }
        .signature-area {
            margin-top: 60px;
            width: 100%;
        }
        .signature-area td {
            width: 33%;
            text-align: center;
            padding-top: 60px;
            font-size: 9px;
        }
        .signature-line {
            border-top: 1px solid #1a1a1a;
            width: 150px;
            margin: 0 auto;
            padding-top: 3px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="60" valign="top">
                <img src="{{ $dracBase64 }}" width="55">
            </td>
            <td valign="top" style="padding-top: 8px;">
                <span class="company-name">Centro de Enseñanza y Capacitación</span><br>
                <span class="company-info">Victor Raúl Baena Toledo &bull; RFC: BATV850516E15</span>
            </td>
        </tr>
    </table>

    <div class="report-title">Cotejador de Honorarios</div>
    <div class="period-info">
        {{ $periodLabel }} de {{ $months[(int)$report['month']] }} {{ $report['year'] }}
        &bull; {{ $report['periodDays'] }} días
    </div>

    @foreach ($report['crewsReport'] as $crewReport)
        <div class="crew-title">{{ $crewReport['crew']->name }}</div>
        <table class="data-table">
            <tr>
                <th style="width: 25%;">Proveedor</th>
                <th style="width: 12%;">Nivel</th>
                <th class="num" style="width: 10%;">Horas</th>
                <th class="num" style="width: 14%;">Base</th>
                <th class="num" style="width: 14%;">Percepciones</th>
                <th class="num" style="width: 13%;">Deducciones</th>
                <th class="num" style="width: 12%;">Neto</th>
            </tr>
            @php
                $subHours = 0;
                $subBase = 0;
                $subPerceptions = 0;
                $subDeductions = 0;
                $subNet = 0;
            @endphp
            @foreach ($crewReport['rows'] as $row)
                @php
                    $subHours += $row['hours'];
                    $subBase += $row['baseCost'];
                    $subPerceptions += $row['perceptions'];
                    $subDeductions += $row['deductions'];
                    $subNet += $row['netPay'];
                @endphp
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['position'] }}</td>
                    <td class="num">{{ $row['hours'] > 0 ? number_format($row['hours'], 1) : '-' }}</td>
                    <td class="num">${{ number_format($row['baseCost'], 2) }}</td>
                    <td class="num">{{ $row['perceptions'] > 0 ? '$' . number_format($row['perceptions'], 2) : '-' }}</td>
                    <td class="num">{{ $row['deductions'] > 0 ? '$' . number_format($row['deductions'], 2) : '-' }}</td>
                    <td class="num">${{ number_format($row['netPay'], 2) }}</td>
                </tr>
            @endforeach
            <tr class="subtotal">
                <td colspan="2" style="text-align: right;">Subtotal {{ $crewReport['crew']->name }}</td>
                <td class="num">{{ $subHours > 0 ? number_format($subHours, 1) : '-' }}</td>
                <td class="num">${{ number_format($subBase, 2) }}</td>
                <td class="num">{{ $subPerceptions > 0 ? '$' . number_format($subPerceptions, 2) : '-' }}</td>
                <td class="num">{{ $subDeductions > 0 ? '$' . number_format($subDeductions, 2) : '-' }}</td>
                <td class="num">${{ number_format($subNet, 2) }}</td>
            </tr>
        </table>
    @endforeach

    <table class="grand-total">
        <tr>
            <td class="label">TOTAL HORAS:</td>
            <td class="value">{{ number_format($report['totalHours'], 1) }}</td>
        </tr>
        <tr>
            <td class="label">TOTAL NÓMINA:</td>
            <td class="value">${{ number_format($report['totalCost'], 2) }}</td>
        </tr>
    </table>

    <br><br><br><br><br><br>
    <table class="signature-area">
        <tr>
            <td>
                <div class="signature-line">Elaboró</div>
            </td>
            <td>
                <div class="signature-line">Revisó</div>
            </td>
            <td>
                <div class="signature-line">Autorizó</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }} &bull; www.capacitacioncec.edu.mx
    </div>
</body>
</html>
