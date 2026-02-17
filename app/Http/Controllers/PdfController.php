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

        $qrBase64 = 'data:image/png;base64,' . base64_encode(Storage::get('qr/qr.png'));

        $logoPath = str_replace('/intranet/public/', '/public_html/intranet/', public_path('/assets/img/logo.png'));
        $logoPath = str_replace('/intranet_dev/public/', '/public_html/intranet_dev/', $logoPath);
        $dracPath = str_replace('/intranet/public/', '/public_html/intranet/', public_path('/assets/img/drac_bw.png'));
        $dracPath = str_replace('/intranet_dev/public/', '/public_html/intranet_dev/', $dracPath);

        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        $dracBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($dracPath));

        $htmlContent = view('pdf.receipt', compact('receipt', 'qrBase64', 'logoBase64', 'dracBase64'))->render();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(80, 297), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(3, 3, 3);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();
        $pdf->writeHTML($htmlContent, true, false, true, false, '');

        $pdf->lastPage();
        $contentHeight = $pdf->getY() + 3;
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

        $qrBase64 = 'data:image/png;base64,' . base64_encode(Storage::get('qr/qr.png'));

        $logoPath = str_replace('/intranet/public/', '/public_html/intranet/', public_path('/assets/img/logo.png'));
        $logoPath = str_replace('/intranet_dev/public/', '/public_html/intranet_dev/', $logoPath);
        $dracPath = str_replace('/intranet/public/', '/public_html/intranet/', public_path('/assets/img/drac_bw.png'));
        $dracPath = str_replace('/intranet_dev/public/', '/public_html/intranet_dev/', $dracPath);

        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        $dracBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($dracPath));

        $htmlContent = view('pdf.paybill', compact('paybill', 'qrBase64', 'logoBase64', 'dracBase64'))->render();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(80, 297), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(3, 3, 3);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();
        $pdf->writeHTML($htmlContent, true, false, true, false, '');

        $pdf->lastPage();
        $contentHeight = $pdf->getY() + 3;
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

    public static function generatePayrollReport(array $report)
    {
        $logoPath = str_replace('/intranet/public/', '/public_html/intranet/', public_path('/assets/img/logo.png'));
        $logoPath = str_replace('/intranet_dev/public/', '/public_html/intranet_dev/', $logoPath);
        $dracPath = str_replace('/intranet/public/', '/public_html/intranet/', public_path('/assets/img/drac_bw.png'));
        $dracPath = str_replace('/intranet_dev/public/', '/public_html/intranet_dev/', $dracPath);

        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        $dracBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($dracPath));

        $htmlContent = view('pdf.payroll_report', compact('report', 'logoBase64', 'dracBase64'))->render();

        $pdf = new TCPDF('L', 'mm', 'LETTER', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();
        $pdf->writeHTML($htmlContent, true, false, true, false, '');

        $pdf->Output('reporte_nomina.pdf', 'I');
    }

    public static function generateFeeCheck(array $report)
    {
        $logoPath = str_replace('/intranet/public/', '/public_html/intranet/', public_path('/assets/img/logo.png'));
        $logoPath = str_replace('/intranet_dev/public/', '/public_html/intranet_dev/', $logoPath);
        $dracPath = str_replace('/intranet/public/', '/public_html/intranet/', public_path('/assets/img/drac_bw.png'));
        $dracPath = str_replace('/intranet_dev/public/', '/public_html/intranet_dev/', $dracPath);

        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        $dracBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($dracPath));

        $htmlContent = view('pdf.fee_check', compact('report', 'logoBase64', 'dracBase64'))->render();

        $pdf = new TCPDF('L', 'mm', 'LETTER', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();
        $pdf->writeHTML($htmlContent, true, false, true, false, '');

        $pdf->Output('cotejador_honorarios.pdf', 'I');
    }
}
