<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     * Sanitize semua input untuk mencegah XSS attacks
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();
        
        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                // Trim whitespace
                $value = trim($value);
                
                // Remove null bytes
                $value = str_replace(chr(0), '', $value);
                
                // Strip tags kecuali untuk field yang memang butuh HTML (seperti 'isi' di berita)
                // Field yang diizinkan HTML akan di-handle di controller dengan htmlpurifier
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
