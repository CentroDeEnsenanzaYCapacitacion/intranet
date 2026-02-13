<?php

namespace App\Services;

use App\Models\Ticket;
use Carbon\Carbon;

class TicketAutoResolveService
{
    public function resolveStaleTickets(int $days = 15): int
    {
        $cutoffDate = Carbon::now()->subDays($days);

        $tickets = Ticket::where('status', 'esperando respuesta')
            ->where('updated_at', '<', $cutoffDate)
            ->get();

        $count = 0;

        foreach ($tickets as $ticket) {
            $ticket->status = 'resuelto';
            $ticket->closed_at = now();
            $ticket->save();
            $count++;
        }

        return $count;
    }
}
