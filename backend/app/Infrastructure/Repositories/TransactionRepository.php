<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\TransactionRepositoryInterface;
use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    public function findByUserId(int $userId): array
    {
        return Transaction::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function findByBetId(int $betId): array
    {
        return Transaction::where('bet_id', $betId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }
}
