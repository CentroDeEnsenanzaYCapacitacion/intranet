<?php

namespace App\Observers;

use App\Models\Receipt;
use App\Http\Controllers\PdfController;

class ReceiptObserver
{
    /**
     * Handle the Receipt "created" event.
     */
    public function created(Receipt $receipt)
    {
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
