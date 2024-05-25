<?php

namespace App\Observers;

use App\Helpers\Utils;
use App\Http\Controllers\PdfController;
use App\Models\Paybill;
use Illuminate\Support\Facades\Hash;

class PaybillObserver
{
    /**
     * Handle the Paybill "created" event.
     */
    public function created(Paybill $paybill): void
    {
        Utils::generateQR(Hash::make($paybill->id));
        PdfController::generatePaybillReceipt($paybill);
    }

    /**
     * Handle the Paybill "updated" event.
     */
    public function updated(Paybill $paybill): void
    {
        //
    }

    /**
     * Handle the Paybill "deleted" event.
     */
    public function deleted(Paybill $paybill): void
    {
        //
    }

    /**
     * Handle the Paybill "restored" event.
     */
    public function restored(Paybill $paybill): void
    {
        //
    }

    /**
     * Handle the Paybill "force deleted" event.
     */
    public function forceDeleted(Paybill $paybill): void
    {
        //
    }
}
