<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'outcomes',
        'starts_at',
        'ends_at',
        'status',
    ];

    protected $casts = [
        'outcomes' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }

    public function getCoefficientForOutcome(string $outcome): ?float
    {
        $outcomes = $this->outcomes ?? [];
        return $outcomes[$outcome] ?? null;
    }

    public function isAvailableForBetting(): bool
    {
        return $this->status === 'active' &&
               $this->starts_at->isFuture() &&
               ($this->ends_at === null || $this->ends_at->isFuture());
    }
}
