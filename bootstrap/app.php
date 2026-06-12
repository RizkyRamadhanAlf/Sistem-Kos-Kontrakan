<?php

use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Spatie\Permission\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // file route admin
            Route::middleware(['web', 'auth', 'role:admin'])
                ->prefix('admin')// agr semua route admin dimulai dgn /admin/...
                ->group(base_path('routes/admin.php'));

            // file route owner
            Route::middleware(['web', 'auth', 'role:owner'])
                ->prefix('owner')// agr semua route owner dimulai dgn /owner/...
                ->group(base_path('routes/owner.php'));

            // file route tenant
            Route::middleware(['web', 'auth', 'role:tenant'])
                ->prefix('tenant')// agr semua route tenant dimulai dgn /tenant/...
                ->group(base_path('routes/tenant.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'payments/webhook',
            'midtrans/notification',
        ]);

        $middleware->alias([
            'auth.basic' => AuthenticateWithBasicAuth::class,
            'cache.headers' => SetCacheHeaders::class,
            'can' => Authorize::class,
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
