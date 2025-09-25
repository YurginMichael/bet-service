<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\IdempotencyKey;
use App\Models\FraudLog;

class CheckIdempotency
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $idempotencyKey = $request->header('Idempotency-Key');

        if (!$idempotencyKey) {
            FraudLog::logSuspiciousActivity(
                $user->id,
                'missing_idempotency_key',
                [
                    'endpoint' => $request->path(),
                    'method' => $request->method()
                ],
                'medium',
                $request->ip(),
                $request->userAgent()
            );
            return response()->json(['error' => 'Idempotency-Key header is required'], 400);
        }

        $endpoint = $request->path();

        $existingKey = IdempotencyKey::findValidKey($idempotencyKey, $user->id, $endpoint);

        if ($existingKey) {
            if ($existingKey->response_data && $existingKey->status_code) {
                return response()->json($existingKey->response_data, $existingKey->status_code);
            }

            return response()->json(['error' => 'Request is being processed'], 409);
        }

        $newKey = IdempotencyKey::createKey(
            $idempotencyKey,
            $user->id,
            $endpoint,
            $request->all()
        );

        $response = $next($request);

        $newKey->update([
            'response_data' => json_decode($response->getContent(), true),
            'status_code' => $response->getStatusCode()
        ]);

        return $response;
    }
}
