<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTO\EventResponseDTO;
use App\Domain\Repositories\EventRepositoryInterface;

class GetEventUseCase
{
    public function __construct(
        private EventRepositoryInterface $eventRepository
    ) {}

    public function execute(int $eventId): array
    {
        $event = $this->eventRepository->findEloquentById($eventId);

        $eventDto = new EventResponseDTO(
            id: $event->id,
            title: $event->title,
            description: $event->description,
            outcomes: $event->outcomes,
            startsAt: $event->starts_at->toISOString(),
            endsAt: $event->ends_at?->toISOString(),
            status: $event->status,
            availableForBetting: $event->isAvailableForBetting()
        );

        return ['event' => $eventDto->toArray()];
    }
}
