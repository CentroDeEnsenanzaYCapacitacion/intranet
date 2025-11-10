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
    /**
     * Handle the Ticket "created" event.
     * Envía correo a todos los admins (role_id = 1) cuando se crea un ticket.
     */
    public function created(Ticket $ticket): void
    {
        try {
            // Obtener todos los usuarios admin
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

    /**
     * Handle the Ticket "updated" event.
     * Envía correo al creador del ticket cuando cambia el estado.
     */
    public function updated(Ticket $ticket): void
    {
        // Solo enviar correo si cambió el estado
        if ($ticket->isDirty('status')) {
            try {
                $oldStatus = $ticket->getOriginal('status');
                
                // Enviar correo al usuario creador del ticket
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
        //
    }

    public function restored(Ticket $ticket): void
    {
        //
    }

    public function forceDeleted(Ticket $ticket): void
    {
        //
    }
}
