<?php

declare(strict_types=1);

namespace App\Http\Api\Actions\Events;

use App\Application\UseCases\GetEventsUseCase;
use App\Http\Responses\EventListResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetList
{
    public function __construct(private GetEventsUseCase $getEventsUseCase)
    {
    }

    public function __invoke(Request $request): JsonResponse|EventListResponse
    {
        $result = $this->getEventsUseCase->execute();
        return new EventListResponse($result['events'] ?? []);
    }
}
