<?php

use App\Http\Middleware\CustomAuthMiddleware;
use App\Http\Middleware\SetRequestHeaderMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(SetRequestHeaderMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            return response()->json([
                'responseMessage' => 'Access denied! You do not have the permission to do.',
                'responseStatus'  => 403,
            ], 403);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'responseMessage' => 'Please login first.',
                'responseStatus'  => 401,
            ], 401);
        });
    })->create();

