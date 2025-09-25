<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Models\Transaction;

interface TransactionRepositoryInterface
{
    public function create(array $data): Transaction;
    public function findByUserId(int $userId): array;
    public function findByBetId(int $betId): array;
}
