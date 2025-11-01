<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateTicketLogJob implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Ticket $ticket, protected string $oldStatus, protected string $newStatus) {}
    public function handle(): void
    {
        $this->ticket->logs()->create([
            'action' => TicketLog::STATUS_CHANGED,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ]);
    }
}
