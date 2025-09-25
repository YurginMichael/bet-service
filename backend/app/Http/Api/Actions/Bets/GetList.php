<?php

declare(strict_types=1);

namespace App\Http\Api\Actions\Bets;

use App\Application\UseCases\GetUserBetsUseCase;
use App\Http\Responses\BetListResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetList
{
    public function __construct(private GetUserBetsUseCase $getUserBetsUseCase)
    {
    }

    public function __invoke(Request $request): JsonResponse|BetListResponse
    {
        $result = $this->getUserBetsUseCase->execute($request->user()->id);

        return new BetListResponse(
            bets: $result['bets'] ?? [],
            pagination: $result['pagination'] ?? []
        );
    }
}
