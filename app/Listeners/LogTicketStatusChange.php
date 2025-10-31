<?php

namespace App\Listeners;

use App\Events\TicketStatusUpdated;
use App\Jobs\CreateTicketLogJob;

class LogTicketStatusChange
{
    public function handle(TicketStatusUpdated $event): void
    {
        CreateTicketLogJob::dispatch($event->ticket, $event->oldStatus, $event->newStatus);
    }
}
