<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

class UserId
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('User ID must be positive');
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public static function fromInt(int $value): self
    {
        return new self($value);
    }
}
