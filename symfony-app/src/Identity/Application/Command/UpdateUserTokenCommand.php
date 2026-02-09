<?php

declare(strict_types=1);

namespace App\Identity\Application\Command;

use App\Identity\Domain\Entity\User;
use App\Identity\Domain\ValueObject\AuthToken as TokenVO;

final class UpdateUserTokenCommand
{
    public function __construct(
        public int $id,
        public TokenVO $token,
    ){}
}
