<?php

namespace App\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Ticket;

interface TicketInterface
{
    public function index(int $perPage = 10, int $page = 1): LengthAwarePaginator;
    public function store(array $data): Ticket;
    public function show(int $id): Ticket;
    public function updateStatus(int $id, string $status): bool;
}
