<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PdfController extends Controller
{
    public static function generateReceipt(Receipt $receipt)
    {
        $printerName = "EPSON TM-T20II Receipt5";
        $connector = new WindowsPrintConnector($printerName);
        $printer = new Printer($connector);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $nubarel = EscposImage::load(public_path('assets/img/drac_bw.png'), false);
        $printer->graphics($nubarel);
        $printer->text("CENTRO DE ENSEÑANZA Y CAPACITACIÓN\n");
        $printer->text("VICTOR RAUL BAENA TOLEDO\n");
        $printer->text("RFC: BATV850516E15\n");
        $printer->text("JUAREZ SUR 301, COL. TEXCOCO CENTRO\n");
        $printer->text("C.P.: 56150  TEXCOCO EDO. DE MEX.\n");
        $printer->text("CURP: BATV850516HMCNLC09\n");
        $printer->text("RÉGIMEN DE INCORPORACION FISCAL\n");
        $printer->text(str_repeat("_", 48) . "\n\n");
        $printer->text("NOMBRE DE PLANTEL\n");
        $printer->text("DIRECCIóN PLANTEL\n");
        $printer->text("TELÉFONO PLANTEL\n");
        $printer->text(str_repeat("_", 48) . "\n\n");
        $printer->setEmphasis(true);
        $printer->text("COMPROBANTE DE PAGO\n");
        $printer->setEmphasis(false);
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setFont(Printer::FONT_B);
        $printer->text("ticket\n");
        $printer->text("desde\n");
        $printer->text("Laravel\n");
        $printer->text("https://parzibyte.me");
        $printer->feed(5);
        $printer->cut();
        $printer->close();
    }
}
