<?php

namespace App\Services;

use App\Models\Ticket;
use App\Interfaces\TicketInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;


class TicketService implements TicketInterface
{
    public function index(int $perPage = 10, int $page = 1): LengthAwarePaginator
    {
        $cacheKey = "tickets:per_page_{$perPage}:page_{$page}";

        return Cache::remember($cacheKey, 60, function () use ($perPage, $page) {
            return Ticket::query()->with('user')->paginate($perPage, ['*'], 'page', $page);
        });
    }

    public function store(array $data): Ticket
    {
        return Ticket::create([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => Ticket::STATUS_OPEN
        ]);
    }

    public function show(int $id): Ticket
    {
        return Ticket::query()->with('user')->findOrFail($id);
    }

    public function updateStatus(int $id, string $status): bool
    {
        return Ticket::query()->findOrFail($id)->update([
            'status' => $status
        ]);
    }
}
