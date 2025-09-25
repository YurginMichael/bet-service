<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class DomainValidationException extends BaseDomainException
{
    public function __construct(string $message = 'Validation failed', int $code = 422)
    {
        parent::__construct($message, $code);
    }
}


