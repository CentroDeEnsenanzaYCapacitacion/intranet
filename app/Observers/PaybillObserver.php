<?php

namespace App\Observers;

use App\Helpers\Utils;
use App\Http\Controllers\PdfController;
use App\Models\Paybill;
use Illuminate\Support\Facades\Hash;

class PaybillObserver
{

    public function created(Paybill $paybill): void
    {
        Utils::generateQR(Hash::make($paybill->id));
        PdfController::generatePaybillReceipt($paybill);
    }

    public function updated(Paybill $paybill): void
    {

    }

    public function deleted(Paybill $paybill): void
    {

    }

    public function restored(Paybill $paybill): void
    {

    }

    public function forceDeleted(Paybill $paybill): void
    {

    }
}
