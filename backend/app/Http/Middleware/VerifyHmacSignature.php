<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Models\FraudLog;

class VerifyHmacSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('X-Signature');

        if (!$signature) {
            FraudLog::logSuspiciousActivity(
                $request->user()?->id,
                'missing_hmac_signature',
                [
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ],
                'high',
                $request->ip(),
                $request->userAgent()
            );
            return response()->json(['error' => 'Missing X-Signature header'], 401);
        }

        $secret = config('app.signing_secret', env('SIGNING_SECRET'));

        if (!$secret) {
            Log::error('SIGNING_SECRET not configured');
            return response()->json(['error' => 'Server configuration error'], 500);
        }

        $body = $request->getContent();
        $expectedSignature = 'sha256=' . hash_hmac('sha256', $body, $secret);

        if (!hash_equals($expectedSignature, $signature)) {
            FraudLog::logSuspiciousActivity(
                $request->user()?->id,
                'invalid_hmac_signature',
                [
                    'expected' => $expectedSignature,
                    'received' => $signature,
                    'body_length' => strlen($body),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ],
                'high',
                $request->ip(),
                $request->userAgent()
            );
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        return $next($request);
    }
}
