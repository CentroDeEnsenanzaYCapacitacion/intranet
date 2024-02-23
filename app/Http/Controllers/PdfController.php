<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
use Codedge\Fpdf\Fpdf\Fpdf;
use TCPDF;

class PdfController extends Controller
{
    public static function generateReceipt(Receipt $receipt)
    {
        $htmlContent = view('pdf.receipt', compact('receipt'))->render();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(80, 297), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(3, 3, 3);
        $pdf->AddPage();
        $pdf->writeHTML($htmlContent, true, false, true, false, '');
        $pdf->Output('myview.pdf', 'I');
    }
}
