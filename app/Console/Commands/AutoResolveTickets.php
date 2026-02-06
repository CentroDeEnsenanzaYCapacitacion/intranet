<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoResolveTickets extends Command
{
    protected $signature = 'tickets:auto-resolve';

    protected $description = 'Resuelve automáticamente tickets en estado "esperando respuesta" con más de 15 días sin actividad';

    public function handle()
    {
        $cutoffDate = Carbon::now()->subDays(15);

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

        $this->info("Se han resuelto automáticamente {$count} tickets.");

        return 0;
    }
}
