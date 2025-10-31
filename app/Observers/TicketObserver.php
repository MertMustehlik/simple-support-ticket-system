<?php

namespace App\Observers;

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
    }

    private function removeTicketCaches(): void
    {
        $keys = Redis::connection('cache')->keys('tickets:*');
        foreach ($keys as $key) {
            Redis::connection('cache')->del($key);
        }
    }
}
