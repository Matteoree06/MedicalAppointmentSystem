<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole; // ← IMPORTANTE: importar tu middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Registrar middleware para rutas (equivale a $routeMiddleware en Kernel.php)
        $middleware->alias([
            'role' => CheckRole::class,
        ]);

        // Si quisieras usarlo como global:
        // $middleware->append(CheckRole::class);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
