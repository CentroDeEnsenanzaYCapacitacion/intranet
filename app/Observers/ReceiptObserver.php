<?php

namespace App\Observers;

use App\Models\Receipt;

class ReceiptObserver
{
    public function created(Receipt $receipt): void
    {
        
    }

    public function updated(Receipt $receipt): void
    {
        //
    }

    public function deleted(Receipt $receipt): void
    {
        //
    }

    public function restored(Receipt $receipt): void
    {
        //
    }

    public function forceDeleted(Receipt $receipt): void
    {
        //
    }
}
