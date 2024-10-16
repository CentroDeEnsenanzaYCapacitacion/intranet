<?php

namespace App\Http\Controllers;

use App\Models\Paybill;
use App\Models\Receipt;
use TCPDF;

class PdfController extends Controller
{
    public static function generateReceipt(Receipt $receipt)
    {
        $environment = env('APP_ENV');
        switch ($environment) {
            case 'local':
                $imageUrl = public_path().'/assets/img/';
                break;
            case 'development':
            case 'production':
            default:
                $imageUrl = substr(public_path(), 0, -19).env('PUBLIC_PATH').'/assets/img/';
                break;
        }
        $htmlContent = view('pdf.receipt', compact('receipt','imageUrl'))->render();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(80, 297), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(3, 3, 3);
        $pdf->AddPage();
        $pdf->writeHTML($htmlContent, true, false, true, false, '');
        $pdf->Output('receipt.pdf', 'I');
    }

    public static function generatePaybillReceipt(Paybill $paybill)
    {
        $htmlContent = view('pdf.paybill', compact('paybill'))->render();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(80, 297), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(3, 3, 3);
        $pdf->AddPage();
        $pdf->writeHTML($htmlContent, true, false, true, false, '');
        $pdf->Output('paybill.pdf', 'I');
    }
}
