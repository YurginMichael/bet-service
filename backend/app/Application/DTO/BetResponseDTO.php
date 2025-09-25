<?php

declare(strict_types=1);

namespace App\Application\DTO;

class BetResponseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $eventTitle,
        public readonly string $outcome,
        public readonly float $amount,
        public readonly float $coefficient,
        public readonly float $potentialWin,
        public readonly string $status,
        public readonly string $createdAt
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'event_title' => $this->eventTitle,
            'outcome' => $this->outcome,
            'amount' => $this->amount,
            'coefficient' => $this->coefficient,
            'potential_win' => $this->potentialWin,
            'status' => $this->status,
            'created_at' => $this->createdAt,
        ];
    }
}
