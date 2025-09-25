<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Event;
use App\Domain\ValueObjects\EventId;
use App\Domain\ValueObjects\EventStatus;
use Illuminate\Database\Eloquent\Collection;

interface EventRepositoryInterface
{
    public function findById(EventId $id): ?Event;
    public function findEloquentById(int $id): ?\App\Models\Event;
    public function findActiveEvents(): array;
    public function getActiveEvents(): Collection;
    public function findEventsByStatus(EventStatus $status): array;
    public function save(Event $event): void;
    public function delete(EventId $id): void;
}
