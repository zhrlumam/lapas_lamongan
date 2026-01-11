<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::define('super', function ($user) {
            return $user->isSuper();
        });

        \Illuminate\Support\Facades\Gate::define('humas', function ($user) {
            return $user->hasRole('Humas');
        });

        \Illuminate\Support\Facades\Gate::define('layanan', function ($user) {
            return $user->isLayanan();
        });

        \Illuminate\Support\Facades\Gate::define('pengaduan', function ($user) {
            return $user->hasRole('Pengaduan');
        });
    }
}
