<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
        ];
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function idempotencyKeys()
    {
        return $this->hasMany(IdempotencyKey::class);
    }

    public function fraudLogs()
    {
        return $this->hasMany(FraudLog::class);
    }
}
