<?php

namespace App\Observers;

use App\Mail\NewTicketMessage;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TicketMessageObserver
{
    /**
     * Handle the TicketMessage "created" event.
     * Envía correo al creador del ticket y a los admins cuando se añade un mensaje.
     */
    public function created(TicketMessage $message): void
    {
        try {
            $ticket = $message->ticket;
            $recipients = collect();
            
            // 1. Siempre agregar al creador del ticket (si no es quien escribió el mensaje)
            if ($ticket->user_id !== $message->user_id) {
                $recipients->push($ticket->user);
            }
            
            // 2. Si el mensaje lo escribió un usuario normal (no admin), notificar a TODOS los admins
            if ($message->user->role_id !== 1) {
                $admins = User::where('role_id', 1)->where('is_active', true)->get();
                $recipients = $recipients->merge($admins);
            }
            // 3. Si el mensaje lo escribió un admin, notificar a otros admins (excepto quien escribió)
            else {
                $admins = User::where('role_id', 1)
                    ->where('is_active', true)
                    ->where('id', '!=', $message->user_id)
                    ->get();
                $recipients = $recipients->merge($admins);
            }
            
            // Eliminar duplicados por email
            $recipients = $recipients->unique('email');
            
            // Enviar correos
            foreach ($recipients as $recipient) {
                Mail::to($recipient->email)->send(new NewTicketMessage($ticket, $message));
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de nuevo mensaje', [
                'error' => $e->getMessage(),
                'message_id' => $message->id,
            ]);
        }
    }

    public function updated(TicketMessage $ticketMessage): void
    {
        //
    }

    public function deleted(TicketMessage $ticketMessage): void
    {
        //
    }

    public function restored(TicketMessage $ticketMessage): void
    {
        //
    }

    public function forceDeleted(TicketMessage $ticketMessage): void
    {
        //
    }
}
