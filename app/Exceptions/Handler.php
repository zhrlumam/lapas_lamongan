<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Redirect 403 (Unauthorized) in admin to Login Page
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            if ($request->is('admin/*') || $request->is('admin')) {
                return redirect()->route('admin.login')->with('error', 'Anda tidak memiliki hak akses untuk halaman tersebut.');
            }
        });

        // Handle CSRF Token Mismatch (419 Page Expired)
        $this->renderable(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            // Jika request dari halaman integrasi
            if ($request->is('integrasi/*') || $request->is('integrasi')) {
                return redirect()->route('integrasi.login')
                    ->with('error', 'Sesi Anda telah kedaluwarsa. Silakan login kembali.')
                    ->withInput($request->except(['_token', 'password']));
            }
            
            // Untuk halaman lain, tampilkan pesan umum
            return back()->with('error', 'Sesi Anda telah kedaluwarsa. Silakan muat ulang halaman dan coba lagi.');
        });
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpKernel\Exception\HttpException|\Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }

        // Jika request dari bagian integrasi, lempar ke login integrasi
        if ($request->is('integrasi/*') || $request->is('integrasi')) {
            return redirect()->guest(route('integrasi.login'));
        }

        // Default (untuk admin dan lainnya) lempar ke admin login
        return redirect()->guest(route('admin.login'));
    }
}
