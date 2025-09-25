<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'outcome',
        'amount',
        'coefficient',
        'potential_win',
        'status',
        'idempotency_key',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'coefficient' => 'decimal:4',
        'potential_win' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
