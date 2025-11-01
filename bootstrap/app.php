<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureTicketOwner;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'ticket.owner' => EnsureTicketOwner::class
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            throw new AuthenticationException();
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ValidationException $e): JsonResponse {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'message' => "Giriş yapılmamış!"
            ], 401);
        });

        $exceptions->render(function (ThrottleRequestsException $e): JsonResponse {
            return response()->json([
                'message' => 'Çok fazla istek yaptınız. Lütfen biraz bekleyin.',
                'retry_after' => $e->getHeaders()['Retry-After'] ?? null
            ], 429);
        });
    })->create();
