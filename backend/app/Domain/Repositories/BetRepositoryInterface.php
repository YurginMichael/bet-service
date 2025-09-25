<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Bet;
use App\Domain\ValueObjects\BetId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\EventId;
use App\Domain\ValueObjects\BetStatus;
use App\Models\Bet as EloquentBet;
use Illuminate\Pagination\LengthAwarePaginator;

interface BetRepositoryInterface
{
    public function findById(BetId $id): ?Bet;
    public function findByUserId(UserId $userId): array;
    public function findByEventId(EventId $eventId): array;
    public function findByStatus(BetStatus $status): array;
    public function findByIdempotencyKey(string $key): ?EloquentBet;
    public function getUserBets(int $userId): LengthAwarePaginator;
    public function save(Bet $bet): void;
    public function delete(BetId $id): void;
    public function create(array $data): EloquentBet;
}
