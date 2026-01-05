<?php

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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'permission' => App\Http\Middleware\PermissionMiddleware::class,
            'registration.enabled' => App\Http\Middleware\CheckRegistrationEnabled::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                $status = 500;
                $message = 'An unexpected error occurred';

                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                    $status = $e->getStatusCode();
                    $message = $e->getMessage() ?: 'An error occurred';
                }

                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    $status = 422;
                    $message = $e->getMessage();
                }

                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    $status = 401;
                    $message = 'Unauthenticated';
                }

                if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                    $status = 403;
                    $message = 'Unauthorized';
                }

                $response = [
                    'status' => false,
                    'message' => $message
                ];

                if (config('app.debug')) {
                    $response['debug'] = [
                        'message' => $e->getMessage(),
                        'line' => $e->getLine(),
                        'file' => $e->getFile(),
                        'trace' => $e->getTraceAsString()
                    ];
                }

                return response()->json($response, $status);
            }
        });
    })->create();
