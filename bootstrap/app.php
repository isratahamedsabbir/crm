<?php

use App\Http\Middleware\ApiUserMiddleware;
use App\Http\Middleware\CustomRedirectMiddleware;
use App\Http\Middleware\WebAdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'web-admin'             => WebAdminMiddleware::class,
            'api-user'              => ApiUserMiddleware::class,
            'auth-check'       => CustomRedirectMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
