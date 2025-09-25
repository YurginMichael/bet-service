<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraudLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'action',
        'details',
        'severity',
        'resolved',
    ];

    protected $casts = [
        'details' => 'array',
        'resolved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function logSuspiciousActivity(
        ?int $userId,
        string $action,
        array $details = [],
        string $severity = 'medium',
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): self {
        return self::create([
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'action' => $action,
            'details' => $details,
            'severity' => $severity,
        ]);
    }
}
