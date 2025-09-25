<?php

declare(strict_types=1);

namespace App\Application\DTO;

class CreateBetDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly int $eventId,
        public readonly string $outcome,
        public readonly float $amount,
        public readonly string $idempotencyKey
    ) {}

    public static function fromRequest(array $data, int $userId, string $idempotencyKey): self
    {
        return new self(
            userId: $userId,
            eventId: $data['event_id'],
            outcome: $data['outcome'],
            amount: (float) $data['amount'],
            idempotencyKey: $idempotencyKey
        );
    }
}
