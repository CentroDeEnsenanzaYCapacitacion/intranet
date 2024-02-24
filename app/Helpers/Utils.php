<?php

namespace App\Helpers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\PngWriter;

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

        dd(substr(public_path(),0,-6).env('PUBLIC_PATH'));

        $url = substr(public_path(),0,-6);

        $result->saveToFile($url.'assets/img/qr.png');
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
