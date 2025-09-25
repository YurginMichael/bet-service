<?php

declare(strict_types=1);

namespace App\Application\DTO;

class EventResponseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly array $outcomes,
        public readonly string $startsAt,
        public readonly ?string $endsAt,
        public readonly string $status,
        public readonly bool $availableForBetting
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'outcomes' => $this->outcomes,
            'starts_at' => $this->startsAt,
            'ends_at' => $this->endsAt,
            'status' => $this->status,
            'available_for_betting' => $this->availableForBetting,
        ];
    }
}
