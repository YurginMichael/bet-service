<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

class Money
{
    private float $amount;
    private string $currency;

    public function __construct(float $amount, string $currency = 'RUB')
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Money amount cannot be negative');
        }

        $this->amount = round($amount, 2);
        $this->currency = $currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function __toString(): string
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }
}
