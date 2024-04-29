<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
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
        $pdf->Output('receipt.pdf', 'I');
    }
}
