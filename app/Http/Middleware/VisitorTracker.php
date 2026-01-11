<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\VisitorLog;
use Symfony\Component\HttpFoundation\Response;

class VisitorTracker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya log request GET untuk halaman utama/front-end
        if ($request->isMethod('GET') && !$request->expectsJson()) {
            try {
                VisitorLog::create([
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'visited_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Biarkan website tetap jalan walaupun DB visitor bermasalah (Skala Nasional)
                \Log::error("Visitor tracking failed: " . $e->getMessage());
            }
        }

        return $next($request);
    }
}
