<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\BetId;
use App\Domain\ValueObjects\BetStatus;
use App\Domain\ValueObjects\EventId;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\UserId;
use Carbon\Carbon;

class Bet
{
    private BetId $id;
    private UserId $userId;
    private EventId $eventId;
    private string $outcome;
    private Money $amount;
    private float $coefficient;
    private Money $potentialWin;
    private BetStatus $status;
    private string $idempotencyKey;
    private Carbon $createdAt;

    public function __construct(
        BetId $id,
        UserId $userId,
        EventId $eventId,
        string $outcome,
        Money $amount,
        float $coefficient,
        Money $potentialWin,
        BetStatus $status,
        string $idempotencyKey,
        Carbon $createdAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->eventId = $eventId;
        $this->outcome = $outcome;
        $this->amount = $amount;
        $this->coefficient = $coefficient;
        $this->potentialWin = $potentialWin;
        $this->status = $status;
        $this->idempotencyKey = $idempotencyKey;
        $this->createdAt = $createdAt ?? Carbon::now();
    }

    public function getId(): BetId
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getEventId(): EventId
    {
        return $this->eventId;
    }

    public function getOutcome(): string
    {
        return $this->outcome;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getCoefficient(): float
    {
        return $this->coefficient;
    }

    public function getPotentialWin(): Money
    {
        return $this->potentialWin;
    }

    public function getStatus(): BetStatus
    {
        return $this->status;
    }

    public function getIdempotencyKey(): string
    {
        return $this->idempotencyKey;
    }
}
