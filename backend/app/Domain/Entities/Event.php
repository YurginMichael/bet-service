<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\EventId;
use Carbon\Carbon;

class Event
{
    private EventId $id;
    private string $title;
    private string $description;
    private array $outcomes;
    private Carbon $startsAt;
    private ?Carbon $endsAt;
    private EventStatus $status;

    public function __construct(
        EventId $id,
        string $title,
        string $description,
        array $outcomes,
        Carbon $startsAt,
        ?Carbon $endsAt = null,
        EventStatus $status = EventStatus::PENDING
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->outcomes = $outcomes;
        $this->startsAt = $startsAt;
        $this->endsAt = $endsAt;
        $this->status = $status;
    }

    public function getId(): EventId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getOutcomes(): array
    {
        return $this->outcomes;
    }

    public function getStartsAt(): Carbon
    {
        return $this->startsAt;
    }

    public function getEndsAt(): ?Carbon
    {
        return $this->endsAt;
    }

    public function getStatus(): EventStatus
    {
        return $this->status;
    }
}
