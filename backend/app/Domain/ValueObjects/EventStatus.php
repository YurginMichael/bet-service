<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

enum EventStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case FINISHED = 'finished';
    case CANCELLED = 'cancelled';
}
