<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \App\Providers\ViewServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Garanti POS 3D Secure callback - bank POSTs back, no CSRF token
        $middleware->validateCsrfTokens(except: [
            'payment/callback-3d',
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SetCurrency::class,
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\PageMaintenanceMode::class,
            \App\Http\Middleware\BrowserCache::class,
            \App\Http\Middleware\CompressResponse::class,
        ]);
        
        // Rate limiting alias'ı zaten aşağıda tanımlanıyor
        
        // Alias middleware
        $middleware->alias([
            'uye.auth' => \App\Http\Middleware\UyeAuthMiddleware::class,
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'rol' => \App\Http\Middleware\RolKontrol::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
