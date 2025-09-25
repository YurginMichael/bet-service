<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Http\Resources\UserResource;
use Illuminate\Contracts\Support\Responsable;

class AuthResponse implements Responsable
{
    public function __construct(private array $auth)
    {
    }

    public function toResponse($request)
    {
        return response()->json([
            'user' => UserResource::make($this->auth['user'] ?? []),
            'token' => $this->auth['token'] ?? '',
            'token_type' => $this->auth['token_type'] ?? 'Bearer',
        ]);
    }
}
