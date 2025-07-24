<?php

use App\Http\Middleware\AuditoriaConsultas;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::prefix('')
                ->group(base_path('routes/web.php'));
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
            Route::prefix('api')
                ->group(base_path('routes/docente.php'));
            Route::prefix('api')
                ->group(base_path('routes/seguridad.php'));
            Route::prefix('api')
                ->group(base_path('routes/evaluaciones/api.php'));
            Route::prefix('api')
                ->group(base_path('routes/asi/api.php'));
            Route::prefix('api')
                ->group(base_path('routes/ere/api.php'));
            Route::prefix('api')
                ->group(base_path('routes/aula/api.php'));
            Route::prefix('api')
                ->group(base_path('routes/grl/api.php'));
            Route::prefix('api')
                ->group(base_path('routes/general.php'));
            Route::prefix('api')
                ->group(base_path('routes/acad/api.php'));
            Route::prefix('api')
                ->group(base_path('routes/com/api.php'));
            Route::prefix('api')
                ->group(base_path('routes/enc/api.php'));
            Route::prefix('api')
                ->group(base_path('routes/cap/api.php'));
            Route::prefix('api')
                ->group(base_path('routes/seg/api.php'));
            Route::prefix('api')
                ->group(base_path('routes/bienestar/api.php'));
        },
        commands: __DIR__ . '/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->append(AuditoriaConsultas::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 401);
            }
        });
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'La ruta solicitada no existe o está mal escrita.',
                ], 404);
            }
        });
    })->create();
