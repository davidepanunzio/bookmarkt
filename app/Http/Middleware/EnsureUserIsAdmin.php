<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Blocca l'accesso (403) a chi non è un amministratore.
        // Il middleware 'auth' va sempre usato prima di questo, così siamo certi che l'utente esista.
        abort_unless($request->user()?->isAdmin(), 403);

        return $next($request);
    }
}
