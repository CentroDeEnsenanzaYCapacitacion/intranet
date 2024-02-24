<?php

namespace App\Observers;

use App\Models\SysRequest;
use App\Http\Controllers\ReportController;

class SysRequestObserver
{
    public function created(SysRequest $sysRequest): void
    {
        ReportController::updateReport($sysRequest->report_id,"signed");
    }

    /**
     * Handle the SysRequest "updated" event.
     */
    public function updated(SysRequest $sysRequest): void
    {
        //
    }

    /**
     * Handle the SysRequest "deleted" event.
     */
    public function deleted(SysRequest $sysRequest): void
    {
        //
    }

    /**
     * Handle the SysRequest "restored" event.
     */
    public function restored(SysRequest $sysRequest): void
    {
        //
    }

    /**
     * Handle the SysRequest "force deleted" event.
     */
    public function forceDeleted(SysRequest $sysRequest): void
    {
        //
    }
}
