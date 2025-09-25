<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use App\Models\FraudLog;

class RateLimitBets
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $maxAttempts = 10;
        $decayMinutes = 1;

        $key = "rate_limit_bets_user_{$user->id}";

        $attempts = Cache::get($key, 0);

        if ($attempts >= $maxAttempts) {
            FraudLog::logSuspiciousActivity(
                $user->id,
                'rate_limit_exceeded',
                [
                    'attempts' => $attempts,
                    'max_attempts' => $maxAttempts,
                    'endpoint' => $request->path(),
                    'method' => $request->method()
                ],
                'medium',
                $request->ip(),
                $request->userAgent()
            );

            return response()->json([
                'error' => 'Too many betting requests. Please wait before making another bet.',
                'retry_after' => $decayMinutes * 60
            ], 429);
        }

        Cache::put($key, $attempts + 1, now()->addMinutes($decayMinutes));

        return $next($request);
    }
}
