<?php

declare(strict_types=1);

namespace App\Identity\Domain\ValueObject;

use InvalidArgumentException;

final class Username
{
    public function __construct(
        private readonly string $value
    ) {
        if (trim($value) === '') {
            throw new InvalidArgumentException('Podana nazwa nie może być pusta');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(Username $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
