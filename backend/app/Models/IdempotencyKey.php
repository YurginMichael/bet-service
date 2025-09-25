<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class IdempotencyKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'user_id',
        'endpoint',
        'request_data',
        'response_data',
        'status_code',
        'expires_at',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public static function createKey(
        string $key,
        int $userId,
        string $endpoint,
        ?array $requestData = null,
        int $expirationHours = 24
    ): self {
        return self::create([
            'key' => $key,
            'user_id' => $userId,
            'endpoint' => $endpoint,
            'request_data' => $requestData,
            'expires_at' => Carbon::now()->addHours($expirationHours),
        ]);
    }

    public static function findValidKey(string $key, int $userId, string $endpoint): ?self
    {
        return self::where('key', $key)
            ->where('user_id', $userId)
            ->where('endpoint', $endpoint)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    }
}
