<?php

namespace App\Console\Commands;

use App\Services\TicketAutoResolveService;
use Illuminate\Console\Command;

class AutoResolveTickets extends Command
{
    protected $signature = 'tickets:auto-resolve';

    protected $description = 'Resuelve automáticamente tickets en estado "esperando respuesta" con más de 15 días sin actividad';

    public function handle(TicketAutoResolveService $ticketAutoResolveService)
    {
        $count = $ticketAutoResolveService->resolveStaleTickets();

        $this->info("Se han resuelto automáticamente {$count} tickets.");

        return 0;
    }
}
