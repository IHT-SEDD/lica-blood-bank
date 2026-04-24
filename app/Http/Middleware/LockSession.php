<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LockSession
{
    public function handle(Request $request, Closure $next)
    {
        if (
            Auth::check() &&
            session('locked') &&
            !$request->routeIs('lock.screen') &&
            !$request->routeIs('unlock') &&
            !$request->routeIs('lock')
        ) {
            return redirect()->route('lock.screen');
        }

        return $next($request);
    }
}
