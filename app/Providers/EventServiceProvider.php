<?php

namespace App\Providers;

use App\Events\CreateReceiptEvent;
use App\Listeners\GenerateReceiptListener;
use App\Models\Paybill;
use App\Models\Receipt;
use App\Models\Staff;
use App\Models\Student;
use App\Models\SysRequest;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Observers\PaybillObserver;
use App\Observers\ReceiptObserver;
use App\Observers\StaffObserver;
use App\Observers\StudentObserver;
use App\Observers\TicketObserver;
use App\Observers\TicketMessageObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    public function boot(): void
    {
        Receipt::observe(ReceiptObserver::class);
        Student::observe(StudentObserver::class);
        Paybill::observe(PaybillObserver::class);
        Staff::observe(StaffObserver::class);
        Ticket::observe(TicketObserver::class);
        TicketMessage::observe(TicketMessageObserver::class);
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
