<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\UserId;

class User
{
    private UserId $id;
    private string $name;
    private string $email;
    private Money $balance;

    public function __construct(UserId $id, string $name, string $email, Money $balance)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->balance = $balance;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getBalance(): Money
    {
        return $this->balance;
    }
}
