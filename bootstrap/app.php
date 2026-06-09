<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'payments/webhook',
        ]);

        $middleware->alias([
            'auth.basic' => Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'cache.headers' => Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => Illuminate\Auth\Middleware\Authorize::class,
            'role' => App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
