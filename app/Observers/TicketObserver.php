<?php

namespace App\Observers;

use App\Events\TicketStatusUpdated;
use App\Models\Ticket;
use Illuminate\Support\Facades\Redis;

class TicketObserver
{
    public function created(Ticket $ticket): void
    {
        $this->removeTicketCaches();
    }

    public function updated(Ticket $ticket): void
    {
        $this->removeTicketCaches();

        if ($ticket->isDirty('status')) {
            event(new TicketStatusUpdated($ticket, $ticket->getOriginal('status'), $ticket->status));
        }
    }

    public function deleted(Ticket $ticket): void
    {
        $this->removeTicketCaches();
    }

    private function removeTicketCaches(): void
    {
        $keys = Redis::connection('cache')->keys('tickets:*');
        foreach ($keys as $key) {
            Redis::connection('cache')->del($key);
        }
    }
}
