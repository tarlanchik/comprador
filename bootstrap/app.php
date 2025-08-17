<?php

use Artesaos\SEOTools\Providers\SEOToolsServiceProvider;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Application;
use App\Providers\ViewServiceProvider;
use App\Http\Middleware\SetLocale;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        SEOToolsServiceProvider::class,
        ViewServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register the alias
        $middleware->alias([
            'set.locale' => SetLocale::class,
        ]);

        // Apply to all web routes using the class directly
        $middleware->web([
            SetLocale::class, // Add directly to web middleware stack
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
