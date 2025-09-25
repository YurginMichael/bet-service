<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Event;
use App\Domain\Repositories\EventRepositoryInterface;
use App\Domain\ValueObjects\EventId;
use App\Domain\ValueObjects\EventStatus;
use App\Models\Event as EloquentEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class EventRepository implements EventRepositoryInterface
{
    public function findById(EventId $eventId): ?Event
    {
        $eloquentEvent = EloquentEvent::find($eventId->value());

        if (!$eloquentEvent) {
            return null;
        }

        return $this->toDomainEntity($eloquentEvent);
    }

    public function findActiveEvents(): array
    {
        $eloquentEvents = EloquentEvent::where('status', EventStatus::ACTIVE->value)
            ->where('starts_at', '>', now())
            ->orderBy('starts_at', 'asc')
            ->get();

        return $eloquentEvents->map(function ($eloquentEvent) {
            return $this->toDomainEntity($eloquentEvent);
        })->toArray();
    }

    public function save(Event $event): void
    {
        $eloquentEvent = EloquentEvent::find($event->getId()->value());

        if (!$eloquentEvent) {
            $eloquentEvent = new EloquentEvent();
        }

        $eloquentEvent->title = $event->getTitle();
        $eloquentEvent->description = $event->getDescription();
        $eloquentEvent->outcomes = $event->getOutcomes();
        $eloquentEvent->starts_at = $event->getStartsAt();
        $eloquentEvent->ends_at = $event->getEndsAt();
        $eloquentEvent->status = $event->getStatus()->value;

        $eloquentEvent->save();
    }

    public function findEloquentById(int $id): ?EloquentEvent
    {
        return EloquentEvent::findOrFail($id);
    }

    public function getActiveEvents(): Collection
    {
        return EloquentEvent::where('status', 'active')
            ->where('starts_at', '>', now())
            ->orderBy('starts_at', 'asc')
            ->get();
    }

    public function findEventsByStatus(EventStatus $status): array
    {
        $eloquentEvents = EloquentEvent::where('status', $status->value)
            ->orderBy('starts_at', 'asc')
            ->get();

        return $eloquentEvents->map(function ($eloquentEvent) {
            return $this->toDomainEntity($eloquentEvent);
        })->toArray();
    }

    public function delete(EventId $id): void
    {
        EloquentEvent::where('id', $id->value())->delete();
    }

    private function toDomainEntity(EloquentEvent $eloquentEvent): Event
    {
        return new Event(
            EventId::fromInt($eloquentEvent->id),
            $eloquentEvent->title,
            $eloquentEvent->description,
            $eloquentEvent->outcomes,
            Carbon::parse($eloquentEvent->starts_at),
            $eloquentEvent->ends_at ? Carbon::parse($eloquentEvent->ends_at) : null,
            EventStatus::from($eloquentEvent->status)
        );
    }
}
