<?php

declare(strict_types=1);

namespace App\Http\Api\Actions\Bets;

use App\Application\DTO\CreateBetDTO;
use App\Application\UseCases\CreateBetUseCase;
use App\Http\Requests\Bets\CreateBetRequest;
use App\Http\Responses\BetCreatedResponse;
use Illuminate\Http\JsonResponse;

class Create
{
    public function __construct(private CreateBetUseCase $createBetUseCase)
    {
    }

    public function __invoke(CreateBetRequest $request): JsonResponse|BetCreatedResponse
    {
        $dto = CreateBetDTO::fromRequest(
            $request->all(),
            $request->user()->id,
            $request->header('Idempotency-Key', 'default_key')
        );

        $result = $this->createBetUseCase->execute($dto);

        return new BetCreatedResponse(
            bet: $result['bet'] ?? [],
            balance: (float) ($result['balance'] ?? 0.0)
        );
    }
}
