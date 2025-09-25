<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Bet;
use App\Domain\Repositories\BetRepositoryInterface;
use App\Domain\ValueObjects\BetId;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\EventId;
use App\Domain\ValueObjects\BetStatus;
use App\Models\Bet as EloquentBet;
use Illuminate\Pagination\LengthAwarePaginator;

class BetRepository implements BetRepositoryInterface
{
    public function findById(BetId $betId): ?Bet
    {
        $eloquentBet = EloquentBet::find($betId->value());

        if (!$eloquentBet) {
            return null;
        }

        return $this->toDomainEntity($eloquentBet);
    }

    public function findByUserId(UserId $userId): array
    {
        $eloquentBets = EloquentBet::where('user_id', $userId->value())
            ->orderBy('created_at', 'desc')
            ->get();

        return $eloquentBets->map(function ($eloquentBet) {
            return $this->toDomainEntity($eloquentBet);
        })->toArray();
    }

    public function findByEventId(EventId $eventId): array
    {
        $eloquentBets = EloquentBet::where('event_id', $eventId->value())
            ->orderBy('created_at', 'desc')
            ->get();

        return $eloquentBets->map(function ($eloquentBet) {
            return $this->toDomainEntity($eloquentBet);
        })->toArray();
    }

    public function save(Bet $bet): void
    {
        $eloquentBet = EloquentBet::find($bet->getId()->value());

        if (!$eloquentBet) {
            $eloquentBet = new EloquentBet();
        }

        $eloquentBet->user_id = $bet->getUserId()->value();
        $eloquentBet->event_id = $bet->getEventId()->value();
        $eloquentBet->outcome = $bet->getOutcome();
        $eloquentBet->amount = $bet->getAmount()->getAmount();
        $eloquentBet->coefficient = $bet->getCoefficient();
        $eloquentBet->potential_win = $bet->getPotentialWin()->getAmount();
        $eloquentBet->status = $bet->getStatus()->value;
        $eloquentBet->idempotency_key = $bet->getIdempotencyKey();

        $eloquentBet->save();
    }

    public function findByStatus(BetStatus $status): array
    {
        $eloquentBets = EloquentBet::where('status', $status->value)
            ->orderBy('created_at', 'desc')
            ->get();

        return $eloquentBets->map(function ($eloquentBet) {
            return $this->toDomainEntity($eloquentBet);
        })->toArray();
    }

    public function findByIdempotencyKey(string $key): ?EloquentBet
    {
        return EloquentBet::where('idempotency_key', $key)->first();
    }

    public function getUserBets(int $userId): LengthAwarePaginator
    {
        return EloquentBet::where('user_id', $userId)
            ->with(['event'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function delete(BetId $id): void
    {
        EloquentBet::where('id', $id->value())->delete();
    }

    public function create(array $data): EloquentBet
    {
        return EloquentBet::create($data);
    }

    private function toDomainEntity(EloquentBet $eloquentBet): Bet
    {
        return new Bet(
            BetId::fromInt($eloquentBet->id),
            UserId::fromInt($eloquentBet->user_id),
            EventId::fromInt($eloquentBet->event_id),
            $eloquentBet->outcome,
            new Money((float) $eloquentBet->amount),
            (float) $eloquentBet->coefficient,
            new Money((float) $eloquentBet->potential_win),
            BetStatus::from($eloquentBet->status),
            $eloquentBet->idempotency_key
        );
    }
}
