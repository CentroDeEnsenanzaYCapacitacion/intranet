<?php

namespace App\Helpers;

use App\Models\Amount;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\PngWriter;
use App\Models\Receipt;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Utils
{
    public static function generateQR($qrContent)
    {
        $qrCode = QrCode::create($qrContent)
            ->setEncoding(new Encoding('UTF-8'))
            ->setSize(100)
            ->setMargin(10)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        switch (env('APP_ENV')) {
            case 'local':
                $url = public_path().'/';
                break;
            case 'development':
                $url = str_replace('/intranet_dev/public/', '/public_html/intranet_dev/', public_path().'/');
                break;
            case 'production':
                $url = str_replace('/intranet/public/', '/public_html/intranet/', public_path().'/');
                break;
        }

        // Verificar que el directorio existe
        $qrDir = dirname($url.'assets/img/qr.png');
        if (!file_exists($qrDir)) {
            mkdir($qrDir, 0775, true);
        }

        $result->saveToFile($url.'assets/img/qr.png');
    }

    public static function generateReceipt($crew_id, $receipt_type_id, $card_payment, $student_id, $concept, $amount, $report_id = null, $receipt_attribute_id = null, $voucher = null, $bill = null)
    {
        $finalAmount = $amount;
        if (!isset($report_id)) {
            if ($amount[0] == '$') {
                $numericString = str_replace(['$', ','], '', $amount);
                $formattedNumber = (float)$numericString;
                $finalAmount = number_format($formattedNumber, 2, '.', '');
            }
        }
        Receipt::create([
            'crew_id' => $crew_id,
            'user_id' => Auth::user()->id,
            'receipt_type_id' => $receipt_type_id,
            'payment_type_id' => $card_payment,
            'student_id' => $student_id,
            'report_id' => $report_id,
            'receipt_attribute_id' => $receipt_attribute_id,
            'voucher' => $voucher,
            'bill' => $bill,
            'concept' => $concept,
            'amount' => $finalAmount
        ]);
    }

    public static function validateAmount($id, $type)
    {
        switch ($type) {
            case 'report':
                $report = Report::find($id);
                $amount = Amount::where('crew_id', $report->crew_id)->where('course_id', $report->course_id)->where('receipt_type_id', 1)->first();
                break;
        }

        if (!isset($amount)) {
            return false;
        } else {
            if ($amount->amount == '0.00') {
                return false;
            }
            return true;
        }
    }

    public static function numberToText($number)
    {
        if (!is_numeric($number)) {
            return "No es un número";
        }

        $number = (int) $number;
        if ($number < 0) {
            return "Menos " . self::numberToText(-$number);
        }

        $words = [
            'cero', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve',
            'diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve',
            'veinte', 30 => 'treinta', 40 => 'cuarenta', 50 => 'cincuenta', 60 => 'sesenta', 70 => 'setenta', 80 => 'ochenta', 90 => 'noventa',
            100 => 'ciento', 200 => 'doscientos', 300 => 'trescientos', 400 => 'cuatrocientos', 500 => 'quinientos', 600 => 'seiscientos', 700 => 'setecientos', 800 => 'ochocientos', 900 => 'novecientos'
        ];

        if ($number <= 20) {
            return $words[$number];
        }
        if ($number < 100) {
            $tens = (int) ($number / 10) * 10;
            $units = $number % 10;
            return $words[$tens] . ($units ? " y " . self::numberToText($units) : "");
        }
        if ($number < 1000) {
            $hundreds = (int) ($number / 100) * 100;
            $remainder = $number % 100;
            if ($number == 100) {
                return "cien";
            }
            return $words[$hundreds] . ($remainder ? " " . self::numberToText($remainder) : "");
        }
        if ($number < 1000000) {
            $thousands = (int) ($number / 1000);
            $remainder = $number % 1000;
            $thousandsText = ($thousands > 1 ? self::numberToText($thousands) . " " : "") . "mil";
            return $thousandsText . ($remainder ? " " . self::numberToText($remainder) : "");
        }

        return "Número fuera de rango";
    }
}
