<?php

use App\Exceptions\KesalahanPos;
use App\Http\Middleware\CatatRequest;
use App\Http\Middleware\JamOperasional;
use App\Http\Middleware\KunciApiKasir;
use App\Http\Middleware\PeranKasir;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(append: [
            CatatRequest::class,
        ]);

        $middleware->alias([
            'kasir' => KunciApiKasir::class,
            'peran' => PeranKasir::class,
            'jam.buka' => JamOperasional::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (KesalahanPos $e, Request $request) {
            if (! $request->expectsJson()) {
                return null;
            }

            return response()->json(array_merge([
                'kesalahan' => $e->kodeKesalahan(),
                'pesan' => $e->getMessage(),
            ], $e->konteks()), $e->kodeHttp());
        });
    })->create();