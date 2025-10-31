<?php

namespace App\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface TicketInterface
{
    public function index(int $perPage = 10, int $page = 1): LengthAwarePaginator;
    public function store(array $data);
    public function show(int $id);
    public function updateStatus(array $data);
}
