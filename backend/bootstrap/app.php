<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        )
    ->withMiddleware(function (Middleware $middleware): void {

    // Grupo web (normalmente ya viene por defecto)
    $middleware->web();

    // Grupo api
    $middleware->api(prepend: [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ]);

    // Tus aliases personalizados
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'chofer' => \App\Http\Middleware\ChoferMiddleware::class,
    ]);

})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
