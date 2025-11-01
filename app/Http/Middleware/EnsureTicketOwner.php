<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Ticket;

class EnsureTicketOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ticketId = $request->route('id');
        $ticket = Ticket::findOrFail($ticketId);

        if ($ticket->user_id !== $request->user()->id) {
            return response()->json([
                'mesaj' => 'Bu işlemi yapmak için yetkiniz yok.'
            ], 403);
        }

        return $next($request);
    }
}
