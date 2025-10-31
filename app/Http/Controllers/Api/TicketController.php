<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\TicketInterface;
use App\Http\Resources\TicketResource;
use App\Http\Requests\Ticket\ListRequest;
use App\Http\Requests\Ticket\StoreRequest;
use App\Http\Requests\Ticket\UpdateStatusRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function __construct(private TicketInterface $ticketInterface) {}

    public function index(ListRequest $request): AnonymousResourceCollection
    {
        $data = $request->validated();
        $perPage = $data['per_page'] ?? 10;
        $page = $data['page'] ?? 1;

        $tickets = $this->ticketInterface->index($perPage, $page);

        return TicketResource::collection($tickets);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $ticket = $this->ticketInterface->store($data);

        return response()->json([
            'message' => 'Destek talebi başarıyla oluşturuldu.',
            'data' => new TicketResource($ticket)
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $ticket = $this->ticketInterface->show($id);

        return response()->json([
            'data' => new TicketResource($ticket)
        ]);
    }

    public function updateStatus(UpdateStatusRequest $request, $id): JsonResponse
    {
        $data = $request->validated();
        $this->ticketInterface->updateStatus($id, $data['status']);

        return response()->json([
            'message' => 'Destek talebi başarıyla güncellendi.'
        ]);
    }
}
