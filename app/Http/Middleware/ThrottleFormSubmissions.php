<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;

class ThrottleFormSubmissions
{
    /**
     * Handle an incoming request.
     * Mencegah spam form submission dengan rate limiting
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 5, $decayMinutes = 1): Response
    {
        $key = $this->resolveRequestSignature($request);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            return back()->withErrors([
                'throttle' => "Terlalu banyak percobaan. Silakan coba lagi dalam {$seconds} detik."
            ])->withInput();
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        return $next($request);
    }

    /**
     * Resolve request signature untuk rate limiting
     */
    protected function resolveRequestSignature(Request $request): string
    {
        // Kombinasi IP + Route untuk mencegah abuse
        return sha1(
            $request->ip() . '|' . $request->path()
        );
    }
}
