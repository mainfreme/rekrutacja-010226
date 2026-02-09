<?php

declare(strict_types=1);

namespace App\Identity\Application\Command;

use App\Identity\Domain\ValueObject\AuthToken;

class CheckAuthTokenCommand
{
    public function __construct(
        public readonly AuthToken $token
    ) {}
}