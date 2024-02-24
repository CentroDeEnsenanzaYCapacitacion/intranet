<?php

namespace App\Observers;

use App\Models\Receipt;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ReportController;
use App\Helpers\Utils;
use Illuminate\Support\Facades\Hash;

class ReceiptObserver
{
    /**
     * Handle the Receipt "created" event.
     */
    public function created(Receipt $receipt)
    {
        ReportController::updateReport($receipt->report_id);
        Utils::generateQR(Hash::make($receipt->id));
        PdfController::generateReceipt($receipt);
    }

    /**
     * Handle the Receipt "updated" event.
     */
    public function updated(Receipt $receipt): void
    {
        //
    }

    /**
     * Handle the Receipt "deleted" event.
     */
    public function deleted(Receipt $receipt): void
    {
        //
    }

    /**
     * Handle the Receipt "restored" event.
     */
    public function restored(Receipt $receipt): void
    {
        //
    }

    /**
     * Handle the Receipt "force deleted" event.
     */
    public function forceDeleted(Receipt $receipt): void
    {
        //
    }
}
