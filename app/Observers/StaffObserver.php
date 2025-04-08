<?php

namespace App\Observers;

use App\Models\MailRequest;
use App\Models\Staff;

class StaffObserver
{
    public function created(Staff $staff): void
    {
        if ($staff->isRoster) {
            MailRequest::create([
                'staff_id' => $staff->id,
            ]);
        }
    }

    public function updated(Staff $staff): void
    {
        //
    }

    public function deleted(Staff $staff): void
    {
        //
    }

    public function restored(Staff $staff): void
    {
        //
    }

    public function forceDeleted(Staff $staff): void
    {
        //
    }
}
