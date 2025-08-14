<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware for all web requests
        $middleware->web(append: [
            \App\Http\Middleware\SecureSession::class,
        ]);

        // Named middleware
        $middleware->alias([
            'secure.session' => \App\Http\Middleware\SecureSession::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'api.security' => \App\Http\Middleware\ApiSecurityMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
