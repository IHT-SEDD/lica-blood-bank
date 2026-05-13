<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class PreventBruteForce
{
    public function handle(Request $request, Closure $next, int $maxAttempts = 5)
    {
        $key = 'brute-force:' . $request->ip() . ':' . $request->path();

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => "Too many attempts. Try again in {$seconds} seconds.",
            ], 429);
        }

        $response = $next($request);

        if (in_array($response->getStatusCode(), [401, 422])) {
            RateLimiter::hit($key, 60);
        } else {
            RateLimiter::clear($key);
        }

        return $response;
    }
}
