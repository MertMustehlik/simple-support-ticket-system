<?php

namespace App\Models;

use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(TicketObserver::class)]
class Ticket extends Model
{
    public const
        STATUS_OPEN = "open",
        STATUS_IN_PROGRESS = "in_progress",
        STATUS_CLOSED = "closed";

    protected $fillable = ['user_id', 'title', 'description', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_OPEN,
            self::STATUS_IN_PROGRESS,
            self::STATUS_CLOSED,
        ];
    }
}
