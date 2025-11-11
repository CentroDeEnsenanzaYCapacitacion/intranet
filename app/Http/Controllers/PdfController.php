<?php

namespace App\Http\Controllers;

use App\Models\Paybill;
use App\Models\Receipt;
use TCPDF;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public static function generateReceipt(Receipt $receipt)
    {
        // Convertir imágenes a base64
        $qrBase64 = 'data:image/png;base64,' . base64_encode(Storage::get('qr/qr.png'));
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('/assets/img/logo.png')));
        $dracBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('/assets/img/drac_bw.png')));
        
        $htmlContent = view('pdf.receipt', compact('receipt', 'qrBase64', 'logoBase64', 'dracBase64'))->render();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(80, 297), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(3, 3, 3);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();
        $pdf->writeHTML($htmlContent, true, false, true, false, '');
        
        // Ajustar altura de la página al contenido
        $pdf->lastPage();
        $contentHeight = $pdf->getY() + 3; // +3 para margen inferior
        $pdf->deletePage(1);
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(80, $contentHeight), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(3, 3, 3);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();
        $pdf->writeHTML($htmlContent, true, false, true, false, '');
        
        $pdf->Output('receipt.pdf', 'I');
    }

    public static function generatePaybillReceipt(Paybill $paybill)
    {
        // Convertir imágenes a base64
        $qrBase64 = 'data:image/png;base64,' . base64_encode(Storage::get('qr/qr.png'));
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('/assets/img/logo.png')));
        $dracBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('/assets/img/drac_bw.png')));
        
        $htmlContent = view('pdf.paybill', compact('paybill', 'qrBase64', 'logoBase64', 'dracBase64'))->render();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(80, 297), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(3, 3, 3);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();
        $pdf->writeHTML($htmlContent, true, false, true, false, '');
        
        // Ajustar altura de la página al contenido
        $pdf->lastPage();
        $contentHeight = $pdf->getY() + 3; // +3 para margen inferior
        $pdf->deletePage(1);
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(80, $contentHeight), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(3, 3, 3);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();
        $pdf->writeHTML($htmlContent, true, false, true, false, '');
        
        $pdf->Output('paybill.pdf', 'I');
    }
}
