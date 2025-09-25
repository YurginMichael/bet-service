<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Http\Resources\BetResource;
use Illuminate\Contracts\Support\Responsable;

class BetListResponse implements Responsable
{
    /**
     * @param iterable|array $bets
     * @param array $pagination
     */
    public function __construct(private $bets, private array $pagination)
    {
    }

    public function toResponse($request)
    {
        return response()->json([
            'bets' => BetResource::collection($this->bets),
            'pagination' => $this->pagination,
        ]);
    }
}
