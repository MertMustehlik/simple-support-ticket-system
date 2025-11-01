<?php

namespace App\Models;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketLog extends Model
{
    public const
        CREATED = 'created',
        UPDATED = 'updated',
        STATUS_CHANGED = 'status_changed',
        DELETED = 'deleted';

    protected $fillable = ['ticket_id', 'action', 'old_data', 'new_data'];

    protected function casts(): array
    {
        return [
            'old_data' => 'json',
            'new_data' => 'json',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
