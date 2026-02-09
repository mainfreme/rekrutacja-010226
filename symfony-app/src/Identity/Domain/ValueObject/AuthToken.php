<?php

declare(strict_types=1);

namespace App\Identity\Domain\ValueObject;

use InvalidArgumentException;

final class AuthToken
{
    public function __construct(
        private readonly string $value
    ) {
        if (trim($value) === '') {
            throw new InvalidArgumentException('Podany token nie może być pusty');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(AuthToken $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
