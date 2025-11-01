<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['prefix' => 'tickets', 'middleware' => 'auth:sanctum'], function () {
    Route::middleware('throttle:60,1')->get('/', [TicketController::class, 'index']);
    Route::middleware('throttle:60,1')->post('/', [TicketController::class, 'store']);
    Route::get('/{id}', [TicketController::class, 'show']);
    Route::middleware('ticket.owner')->patch('/{id}/status', [TicketController::class, 'updateStatus']);
});
