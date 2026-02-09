<?php

declare(strict_types=1);

namespace App\Identity\Application\Query;

use App\Identity\Domain\ValueObject\AuthToken;
use App\Identity\Domain\ValueObject\Username;

final class LoginQuery
{
    public function __construct(
        public readonly Username  $username,
        public readonly AuthToken $token,
    ){}
}
