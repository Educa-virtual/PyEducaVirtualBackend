<?php

namespace App\Providers;

use App\Models\acad\BuzonSugerencia;
use App\Models\User;
use App\Policies\BuzonSugerenciaPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('tiene-perfil', function (User $user, array $perfiles) {
            return $user->validarPersonaCredencialPerfil(request()->header('icredentperfid'), $perfiles);
        });
    }
}
