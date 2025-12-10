<?php

namespace App\Observers;

use App\Models\Receipt;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Helpers\Utils;
use App\Models\Documentation;
use App\Models\StudentDocument;
use Illuminate\Support\Facades\Hash;

class ReceiptObserver
{
    public function created(Receipt $receipt)
    {
        if($receipt->report_id != null) {
            ReportController::updateReport($receipt->report_id);
        }
        Utils::generateQR(Hash::make($receipt->id));
        PdfController::generateReceipt($receipt);
    }

    public function updated(Receipt $receipt): void
    {

    }

    public function deleted(Receipt $receipt): void
    {

    }

    public function restored(Receipt $receipt): void
    {

    }

    public function forceDeleted(Receipt $receipt): void
    {

    }
}
