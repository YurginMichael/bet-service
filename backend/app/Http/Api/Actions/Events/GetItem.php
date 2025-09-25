<?php

declare(strict_types=1);

namespace App\Http\Api\Actions\Events;

use App\Application\UseCases\GetEventUseCase;
use App\Http\Responses\EventItemResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetItem
{
    public function __construct(private GetEventUseCase $getEventUseCase)
    {
    }

    public function __invoke(Request $request, int $id): JsonResponse|EventItemResponse
    {
        $result = $this->getEventUseCase->execute($id);
        return new EventItemResponse($result['event'] ?? []);
    }
}
