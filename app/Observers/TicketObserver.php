<?php

namespace App\Observers;

use App\Mail\NewTicket;
use App\Mail\TicketStatusChanged;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TicketObserver
{

    public function created(Ticket $ticket): void
    {
        try {

            $admins = User::where('role_id', 1)->where('is_active', true)->get();

            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new NewTicket($ticket));
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de nuevo ticket', [
                'error' => $e->getMessage(),
                'ticket_id' => $ticket->id,
            ]);
        }
    }

    public function updated(Ticket $ticket): void
    {

        if ($ticket->isDirty('status')) {
            try {
                $oldStatus = $ticket->getOriginal('status');

                Mail::to($ticket->user->email)->send(new TicketStatusChanged($ticket, $oldStatus));
            } catch (\Exception $e) {
                Log::error('Error al enviar correo de cambio de estado', [
                    'error' => $e->getMessage(),
                    'ticket_id' => $ticket->id,
                ]);
            }
        }
    }

    public function deleted(Ticket $ticket): void
    {

    }

    public function restored(Ticket $ticket): void
    {

    }

    public function forceDeleted(Ticket $ticket): void
    {

    }
}
