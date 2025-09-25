<?php

declare(strict_types=1);

use Illuminate\Auth\AuthenticationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Middleware\VerifyHmacSignature;
use App\Http\Middleware\RateLimitBets;
use App\Http\Middleware\CheckIdempotency;
use App\Domain\Exceptions\BusinessRuleException;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Exceptions\DomainValidationException;
use InvalidArgumentException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $middleware->web(append: [
            EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'hmac.verify' => VerifyHmacSignature::class,
            'rate.limit.bets' => RateLimitBets::class,
            'idempotency' => CheckIdempotency::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'error' => $e->getMessage() ?: 'Unauthenticated'
            ], 401);
        });

        $exceptions->render(function (BusinessRuleException $e, $request) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        });

        $exceptions->render(function (EntityNotFoundException $e, $request) {
            return response()->json([
                'error' => $e->getMessage() ?: 'Not found'
            ], 404);
        });

        $exceptions->render(function (DomainValidationException $e, $request) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        });

        $exceptions->render(function (InvalidArgumentException $e, $request) {
            $message = (string) $e->getMessage();
            $lower = strtolower($message);
            $status = str_contains($lower, 'not found') ? 404 : 422;
            return response()->json([
                'error' => $message
            ], $status);
        });

        $exceptions->render(function (ValidationException $e, $request) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json([
                'error' => 'Method Not Allowed'
            ], 405);
        });

        $exceptions->render(function (ModelNotFoundException $e, $request) {
            return response()->json([
                'error' => 'Not found'
            ], 404);
        });

        $exceptions->render(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'error' => 'Not Found'
            ], 404);
        });
    })->create();
