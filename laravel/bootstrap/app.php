<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->group(base_path('routes/api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\CorsMiddleware::class);
        $middleware->validateCsrfTokens(except: [
            'users/*',
            'system/*',
            'items/*',
            'shows/*',
            'startup/*',
            'quickconnect/*',
            'branding/*',
            'displaypreferences/*',
            'notifications/*',
            'localization/*',
            'scheduledtasks',
            'plugins/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
