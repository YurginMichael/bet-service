<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

enum BetStatus: string
{
    case PENDING = 'pending';
    case WON = 'won';
    case LOST = 'lost';
    case CANCELLED = 'cancelled';
}
