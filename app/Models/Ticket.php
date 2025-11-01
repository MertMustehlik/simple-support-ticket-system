<?php

namespace App\Models;

use App\Models\TicketLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Ticket extends Model
{
    public const
        OPEN = "open",
        IN_PROGRESS = "in_progress",
        CLOSED = "closed";

    protected $fillable = ['user_id', 'title', 'description', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
