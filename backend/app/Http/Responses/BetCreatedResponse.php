<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Http\Resources\BetResource;
use Illuminate\Contracts\Support\Responsable;

class BetCreatedResponse implements Responsable
{
    public function __construct(private array $bet, private float $balance)
    {
    }

    public function toResponse($request)
    {
        return response()->json([
            'bet' => BetResource::make($this->bet),
            'balance' => $this->balance,
        ], 201);
    }
}
